<?php

namespace App\Http\Controllers;

use App\Models\LaundryItem;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Halaman Utama / Katalog Layanan Pelanggan (dengan filter kategori & periode)
     */
    public function index(Request $request)
    {
        $filters = $request->validate([
            'category' => 'nullable|in:Cuci,Setrika,Paket',
            'start_date' => 'nullable|required_with:end_date|date_format:Y-m-d',
            'end_date' => 'nullable|required_with:start_date|date_format:Y-m-d|after_or_equal:start_date',
        ]);

        $laundry_items = LaundryItem::query()
            ->when($filters['category'] ?? null, fn ($query, $category) => $query->where('category', $category))
            ->when($filters['start_date'] ?? null, fn ($query, $date) => $query->whereDate('created_at', '>=', $date))
            ->when($filters['end_date'] ?? null, fn ($query, $date) => $query->whereDate('created_at', '<=', $date))
            ->get();

        return view('customer.index', compact('laundry_items'));
    }

    /**
     * Proses Checkout Pesanan Pelanggan
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'queue_number' => 'required|integer|min:1',
            'items' => 'required|array',
            'items.*' => 'nullable|integer|min:0',
        ]);

        $orderedItems = array_filter($request->items, fn ($qty) => $qty > 0);

        if (empty($orderedItems)) {
            return back()->with('error', 'Pilih minimal satu layanan dengan jumlah lebih dari 0!');
        }

        DB::beginTransaction();
        try {
            $order = Order::create([
                'customer_name' => $request->customer_name,
                'queue_number' => str_pad((string) $request->queue_number, 2, '0', STR_PAD_LEFT),
                'total_price' => 0,
                'status' => 'pending',
            ]);

            $totalPrice = 0;

            foreach ($orderedItems as $laundryItemId => $quantity) {
                $laundryItem = LaundryItem::findOrFail($laundryItemId);
                $subtotal = $laundryItem->price * $quantity;
                $totalPrice += $subtotal;

                OrderDetail::create([
                    'order_id' => $order->id,
                    'laundry_item_id' => $laundryItem->id,
                    'quantity' => $quantity,
                    'price' => $laundryItem->price,
                    'subtotal' => $subtotal,
                ]);
            }

            $order->update(['total_price' => $totalPrice]);
            DB::commit();

            return redirect()->route('customer.index')->with('success', 'Pesanan berhasil dibuat! Nomor Antrean Anda: '.$order->queue_number);
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal memproses pesanan: '.$e->getMessage());
        }
    }

    /**
     * Admin Dashboard: Melihat Semua Pesanan Masuk
     */
    public function adminDashboard()
    {
        $orders = Order::with('orderDetails.laundryItem')->latest()->get();

        return view('dashboard', compact('orders'));
    }

    /**
     * Update Status Pesanan (Pending, Selesai/Completed, Batal/Cancelled)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|string|in:pending,completed,cancelled,selesai,batal']);
        $order = Order::findOrFail($id);
        $order->update(['status' => strtolower($request->status)]);

        return back()->with('success', 'Status pesanan #'.$order->id.' ('.$order->customer_name.') berhasil diperbarui!');
    }

    /**
     * Laporan Rekap Layanan yang Diorder & Omzet Harian
     */
    public function report(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if (! $startDate && ! $endDate) {
            // Default 30 hari terakhir
            $startDate = now()->subDays(30)->format('Y-m-d');
            $endDate = now()->format('Y-m-d');
        } else {
            $dates = $request->validate([
                'start_date' => 'required|date_format:Y-m-d',
                'end_date' => 'required|date_format:Y-m-d|after_or_equal:start_date',
            ]);
            $startDate = $dates['start_date'];
            $endDate = $dates['end_date'];
        }

        // 1. Rekap Jumlah Tiap Layanan yang Diorder (Status Completed / Selesai)
        $layanans = DB::table('order_details as od')
            ->join('orders as o', 'o.id', '=', 'od.order_id')
            ->join('laundry_items as f', 'f.id', '=', 'od.laundry_item_id')
            ->whereRaw('LOWER(o.status) IN (?, ?)', ['completed', 'selesai'])
            ->whereDate('o.created_at', '>=', $startDate)
            ->whereDate('o.created_at', '<=', $endDate)
            ->select('f.id', 'f.name', 'f.category', 'f.price')
            ->selectRaw('SUM(od.quantity) AS total_quantity')
            ->selectRaw('SUM(od.subtotal) AS total_revenue')
            ->groupBy('f.id', 'f.name', 'f.category', 'f.price')
            ->orderByDesc('total_quantity')
            ->orderBy('f.id')
            ->get();

        // 2. Rekap Jumlah Pesanan & Omzet Harian
        $dailyReports = DB::table('orders')
            ->whereRaw('LOWER(status) IN (?, ?)', ['completed', 'selesai'])
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->selectRaw('DATE(created_at) AS tanggal')
            ->selectRaw('COUNT(*) AS jumlah_pesanan')
            ->selectRaw('SUM(total_price) AS omzet')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('tanggal', 'desc')
            ->get();

        $totalRevenue = $dailyReports->sum('omzet');
        $totalOrders = $dailyReports->sum('jumlah_pesanan');

        return view('admin.reports.layanans', compact('layanans', 'dailyReports', 'startDate', 'endDate', 'totalRevenue', 'totalOrders'));
    }
}
