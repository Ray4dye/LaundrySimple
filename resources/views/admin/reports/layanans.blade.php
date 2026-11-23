<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-gray-800 leading-tight">
                    {{ __('Rekap Layanan & Laporan Omzet') }}
                </h2>
                <p class="text-xs text-gray-500 mt-1">Laporan rekap pesanan selesai dan rincian omzet harian</p>
            </div>
            <div class="flex gap-2">
                <button type="button" onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 shadow-sm transition">
                    🖨️ Cetak / Print Laporan
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Filter Tanggal Periode -->
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-xs">
                <form method="GET" action="{{ route('admin.reports.layanans') }}" class="flex flex-wrap items-end gap-4 text-sm">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Dari Tanggal Pesanan</label>
                        <input type="date" name="start_date" value="{{ $startDate }}" required class="border-gray-300 rounded-lg p-2 text-sm border focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Sampai Tanggal</label>
                        <input type="date" name="end_date" value="{{ $endDate }}" required class="border-gray-300 rounded-lg p-2 text-sm border focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-5 py-2.5 rounded-lg text-sm transition shadow-sm">
                        Tampilkan Rekap
                    </button>
                </form>

                @if($errors->any())
                    <p class="text-xs text-red-600 mt-3 font-semibold">{{ $errors->first() }}</p>
                @endif
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-emerald-50 border border-emerald-200 p-6 rounded-xl">
                    <p class="text-xs font-bold text-emerald-800 uppercase tracking-wider">Total Omzet Pesanan Selesai</p>
                    <p class="text-3xl font-black text-emerald-700 mt-2">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                    <span class="text-xs text-emerald-600 mt-1 block">Periode: {{ $startDate }} s/d {{ $endDate }}</span>
                </div>
                <div class="bg-blue-50 border border-blue-200 p-6 rounded-xl">
                    <p class="text-xs font-bold text-blue-800 uppercase tracking-wider">Total Pesanan Selesai Dilayani</p>
                    <p class="text-3xl font-black text-blue-700 mt-2">{{ $totalOrders }} Pesanan</p>
                    <span class="text-xs text-blue-600 mt-1 block">Status: Completed / Selesai</span>
                </div>
            </div>

            <!-- TABEL 1: Rekap Jumlah Tiap Layanan yang Diorder -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="font-bold text-lg text-gray-800">1. Rekap Porsi/Jumlah Tiap Layanan Diorder</h3>
                    <p class="text-xs text-gray-500">Berdasarkan data SUM(order_details.quantity) pada pesanan selesai</p>
                </div>
                <div class="p-6 overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                            <tr>
                                <th class="p-3 border-b">#</th>
                                <th class="p-3 border-b">Nama Layanan</th>
                                <th class="p-3 border-b">Kategori</th>
                                <th class="p-3 border-b">Harga Satuan</th>
                                <th class="p-3 border-b text-center">Total Dipesan (Porsi)</th>
                                <th class="p-3 border-b text-right">Total Pendapatan Layanan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y text-sm">
                            @forelse($layanans as $index => $layanan)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="p-3 text-gray-500">{{ $index + 1 }}</td>
                                    <td class="p-3 font-semibold text-gray-800">{{ $layanan->name }}</td>
                                    <td class="p-3">
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-700">
                                            {{ $layanan->category }}
                                        </span>
                                    </td>
                                    <td class="p-3 text-gray-600">Rp {{ number_format($layanan->price, 0, ',', '.') }}</td>
                                    <td class="p-3 text-center font-bold text-blue-600 text-base">{{ $layanan->total_quantity }}</td>
                                    <td class="p-3 text-right font-bold text-emerald-600">Rp {{ number_format($layanan->total_revenue, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-6 text-center text-gray-500">Tidak ada pesanan selesai pada periode tanggal ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TABEL 2: Rekap Omzet Harian -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="font-bold text-lg text-gray-800">2. Rekap Omzet Harian</h3>
                    <p class="text-xs text-gray-500">Dihitung langsung dari SUM(orders.total_price) per tanggal</p>
                </div>
                <div class="p-6 overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                            <tr>
                                <th class="p-3 border-b">Tanggal</th>
                                <th class="p-3 border-b text-center">Jumlah Pesanan Selesai</th>
                                <th class="p-3 border-b text-right">Omzet Harian</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y text-sm">
                            @forelse($dailyReports as $daily)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="p-3 font-semibold text-gray-800">{{ date('d F Y', strtotime($daily->tanggal)) }}</td>
                                    <td class="p-3 text-center font-bold text-blue-600">{{ $daily->jumlah_pesanan }}</td>
                                    <td class="p-3 text-right font-black text-emerald-600">Rp {{ number_format($daily->omzet, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="p-6 text-center text-gray-500">Tidak ada data omzet pada periode tanggal ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
