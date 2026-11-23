<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-gray-800 leading-tight">
                    {{ __('Daftar Pesanan Masuk') }}
                </h2>
                <p class="text-xs text-gray-500 mt-1">Kelola status pesanan antrean pelanggan secara real-time</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('laundry_items.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 shadow-sm transition">
                    Kelola Layanan
                </a>
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 shadow-sm transition">
                    Kelola User
                </a>
                <a href="{{ route('admin.reports.layanans') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 shadow-sm transition">
                    Rekap & Laporan
                </a>
                <a href="{{ route('customer.index') }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 shadow-sm transition">
                    Lihat Katalog &nearr;
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-lg shadow-sm font-medium">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Ringkasan Cepat -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-xs">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Semua Pesanan</p>
                    <p class="text-2xl font-black text-gray-800 mt-1">{{ $orders->count() }}</p>
                </div>
                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-xs">
                    <p class="text-xs font-bold text-amber-500 uppercase tracking-wider">Pesanan Pending</p>
                    <p class="text-2xl font-black text-amber-600 mt-1">{{ $orders->where('status', 'pending')->count() }}</p>
                </div>
                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-xs">
                    <p class="text-xs font-bold text-emerald-500 uppercase tracking-wider">Pesanan Selesai / Lunas</p>
                    <p class="text-2xl font-black text-emerald-600 mt-1">{{ $orders->whereIn('status', ['completed', 'selesai'])->count() }}</p>
                </div>
            </div>

            <!-- Tabel Pesanan -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                            <tr>
                                <th class="p-4 border-b"># ID</th>
                                <th class="p-4 border-b">Pelanggan</th>
                                <th class="p-4 border-b">No. Antrean</th>
                                <th class="p-4 border-b">Rincian Pesanan</th>
                                <th class="p-4 border-b">Total Harga</th>
                                <th class="p-4 border-b">Status</th>
                                <th class="p-4 border-b text-center">Aksi Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y text-sm">
                            @forelse($orders as $order)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="p-4 font-bold text-gray-700">#{{ $order->id }}</td>
                                    <td class="p-4 font-semibold text-gray-900">
                                        {{ $order->customer_name }}
                                        <span class="block text-[11px] text-gray-400 font-normal">{{ $order->created_at->diffForHumans() }}</span>
                                    </td>
                                    <td class="p-4">
                                        <span class="bg-blue-100 text-blue-800 font-bold px-3 py-1 rounded-full text-xs">
                                            Antrean {{ $order->queue_number }}
                                        </span>
                                    </td>
                                    <td class="p-4">
                                        <ul class="list-disc list-inside space-y-1 text-gray-600 text-xs">
                                            @foreach($order->orderDetails as $detail)
                                                <li>
                                                    <strong>{{ $detail->laundryItem->name ?? 'Layanan' }}</strong> 
                                                    x{{ $detail->quantity }} 
                                                    <span class="text-gray-400 font-medium">(Rp {{ number_format($detail->subtotal ?: $detail->price * $detail->quantity, 0, ',', '.') }})</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="p-4 font-bold text-green-600 whitespace-nowrap">
                                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                    </td>
                                    <td class="p-4">
                                        @if(strtolower($order->status) == 'pending')
                                            <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-2.5 py-1 rounded-full">PENDING</span>
                                        @elseif(strtolower($order->status) == 'completed' || strtolower($order->status) == 'selesai')
                                            <span class="bg-green-100 text-green-800 text-xs font-bold px-2.5 py-1 rounded-full">SELESAI</span>
                                        @else
                                            <span class="bg-red-100 text-red-800 text-xs font-bold px-2.5 py-1 rounded-full">{{ strtoupper($order->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-center">
                                        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" onchange="this.form.submit()" class="text-xs border border-gray-300 rounded-lg p-1.5 bg-white shadow-xs font-semibold focus:ring-blue-500 focus:border-blue-500">
                                                <option value="pending" {{ strtolower($order->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="completed" {{ in_array(strtolower($order->status), ['completed', 'selesai']) ? 'selected' : '' }}>Selesai / Lunas</option>
                                                <option value="cancelled" {{ in_array(strtolower($order->status), ['cancelled', 'batal']) ? 'selected' : '' }}>Batalkan</option>
                                            </select>  
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-8 text-center text-gray-500">
                                        Belum ada pesanan masuk.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
