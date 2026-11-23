<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Layanan Laundry - Pemesanan Online</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen">
    <!-- NAVBAR -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center text-white font-black text-xl shadow-md shadow-blue-500/20">
                    🧺
                </div>
                <div>
                    <a href="{{ route('customer.index') }}" class="font-extrabold text-xl text-slate-900 tracking-tight">Laundry<span class="text-blue-600">Simple</span></a>
                    <p class="text-[11px] text-slate-600 leading-none">Bersih, Cepat & Terpercaya</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                @auth
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('dashboard') }}" class="px-4 py-2 text-xs font-bold rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition shadow-sm">
                            Dashboard Admin
                        </a>
                    @endif
                    <span class="text-xs text-slate-600 font-medium hidden sm:inline">Halo, <strong class="text-slate-800">{{ auth()->user()->name }}</strong></span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 text-xs font-semibold rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-100 transition">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 text-xs font-bold rounded-lg text-slate-700 hover:text-blue-600 transition">
                        Masuk (Login)
                    </a>
                    <a href="{{ route('register') }}" class="px-4 py-2 text-xs font-bold rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition shadow-sm shadow-blue-500/20">
                        Daftar Akun
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- HERO BANNER -->
        <div class="bg-gradient-to-r from-blue-700 via-blue-600 to-indigo-700 rounded-3xl p-8 mb-8 text-white shadow-xl shadow-blue-600/10">
            <div class="max-w-2xl">
                <span class="inline-block px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-semibold tracking-wide uppercase mb-3">
                    ✨ Layanan Cuci & Setrika Profesional
                </span>
                <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Layanan Laundry Praktis</h1>
                <p class="text-blue-100 text-sm sm:text-base mt-2">
                    Pilih paket layanan yang Anda inginkan, tentukan jumlahnya, dan dapatkan nomor antrean secara instan.
                </p>
            </div>
        </div>

        <!-- NOTIFIKASI SUCCESS / ERROR / VALIDASI -->
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-300 text-emerald-800 px-5 py-4 rounded-2xl mb-8 flex items-center gap-3 shadow-xs">
                <span class="text-2xl">✅</span>
                <div>
                    <h4 class="font-bold text-sm">Pesanan Berhasil!</h4>
                    <p class="text-xs text-emerald-700">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-rose-50 border border-rose-300 text-rose-800 px-5 py-4 rounded-2xl mb-8 flex items-center gap-3 shadow-xs">
                <span class="text-2xl">⚠️</span>
                <div>
                    <h4 class="font-bold text-sm">Terjadi Kesalahan</h4>
                    <p class="text-xs text-rose-700">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-rose-50 border border-rose-300 text-rose-800 px-5 py-4 rounded-2xl mb-8 shadow-xs">
                <h4 class="font-bold text-sm mb-1">Periksa kembali data Anda:</h4>
                <ul class="list-disc list-inside text-xs space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- FILTER KATEGORI & PERIODE (GET FORM) -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 mb-8 shadow-xs">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <!-- Client-Side Quick Buttons -->
                <div>
                    <p class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Filter Kategori Cepat</p>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" onclick="filterCategory('all', this)" class="btn-category px-4 py-2 rounded-xl font-bold text-xs transition bg-blue-600 text-white shadow-sm shadow-blue-500/20">
                            Semua Layanan
                        </button>
                        <button type="button" onclick="filterCategory('Cuci', this)" class="btn-category px-4 py-2 rounded-xl font-bold text-xs transition bg-slate-100 text-slate-700 hover:bg-slate-200">
                            Cuci
                        </button>
                        <button type="button" onclick="filterCategory('Setrika', this)" class="btn-category px-4 py-2 rounded-xl font-bold text-xs transition bg-slate-100 text-slate-700 hover:bg-slate-200">
                            Setrika
                        </button>
                        <button type="button" onclick="filterCategory('Paket', this)" class="btn-category px-4 py-2 rounded-xl font-bold text-xs transition bg-slate-100 text-slate-700 hover:bg-slate-200">
                            Paket Cuci & Setrika
                        </button>
                    </div>
                </div>

                <!-- Server-Side Date/Category Filter (Materi Bonus) -->
                <form method="GET" action="{{ route('customer.index') }}" class="flex flex-wrap items-end gap-2 text-xs">
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Kategori Server</label>
                        <select name="category" class="border border-slate-300 rounded-lg px-2.5 py-1.5 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="">Semua</option>
                            <option value="Cuci" @selected(request('category') === 'Cuci')>Cuci</option>
                            <option value="Setrika" @selected(request('category') === 'Setrika')>Setrika</option>
                            <option value="Paket" @selected(request('category') === 'Paket')>Paket</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Dari Tgl Layanan</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="border border-slate-300 rounded-lg px-2 py-1.5 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Sampai Tgl</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="border border-slate-300 rounded-lg px-2 py-1.5 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white font-bold px-3 py-1.5 rounded-lg transition">
                        Terapkan
                    </button>
                    @if(request()->hasAny(['category', 'start_date', 'end_date']))
                        <a href="{{ route('customer.index') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold px-3 py-1.5 rounded-lg transition">
                            Reset
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <!-- FORM CHECKOUT PESANAN -->
        <form id="orderForm" action="{{ route('customer.checkout') }}" method="POST">
            @csrf

            <!-- 1. INFORMASI PEMESAN -->
            <div class="bg-white p-6 rounded-2xl shadow-xs border border-slate-200 mb-8">
                <div class="flex items-center gap-2 mb-4 pb-3 border-b border-slate-100">
                    <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-700 font-bold text-xs flex items-center justify-center">1</span>
                    <h2 class="text-base font-bold text-slate-800">Informasi Pemesan</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap</label>
                        <input type="text" id="customer_name" name="customer_name" value="{{ auth()->check() ? auth()->user()->name : old('customer_name') }}" required placeholder="Contoh: Budi Santoso" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Nomor Antrean (Angka)</label>
                        <input type="number" id="queue_number" name="queue_number" value="{{ old('queue_number', rand(1, 30)) }}" min="1" required placeholder="Contoh: 05" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm transition">
                    </div>
                </div>
            </div>

            <!-- 2. PILIH LAYANAN -->
            <div class="mb-4 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-700 font-bold text-xs flex items-center justify-center">2</span>
                    <h2 class="text-base font-bold text-slate-800">Pilih Layanan Laundry</h2>
                </div>
                <span class="text-xs text-slate-600 font-medium">Tersedia {{ count($laundry_items) }} Layanan</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                @forelse($laundry_items as $laundryItem)
                    <div class="laundryItem-card bg-white rounded-2xl shadow-xs border border-slate-200 overflow-hidden flex flex-col justify-between hover:shadow-md hover:border-slate-300 transition duration-200" data-category="{{ $laundryItem->category ?? 'Cuci' }}">
                        <div>
                            @if($laundryItem->image)
                                <img src="{{ asset('storage/' . $laundryItem->image) }}" alt="{{ $laundryItem->name }}" class="w-full h-44 object-cover">
                            @else
                                <div class="bg-gradient-to-br from-slate-100 to-slate-200 h-44 flex flex-col items-center justify-center text-slate-400 font-medium gap-2">
                                    <span class="text-4xl">🧺</span>
                                    <span class="text-xs uppercase tracking-wider font-semibold">Foto Layanan</span>
                                </div>
                            @endif

                            <div class="p-5">
                                <div class="flex justify-between items-center mb-2.5">
                                    <span class="text-[11px] font-bold px-2.5 py-1 rounded-full 
                                        {{ $laundryItem->category === 'Cuci' ? 'bg-blue-100 text-blue-800' : ($laundryItem->category === 'Setrika' ? 'bg-amber-100 text-amber-800' : 'bg-purple-100 text-purple-800') }}">
                                        {{ $laundryItem->category ?? 'Cuci' }}
                                    </span>
                                    <span class="font-extrabold text-blue-600 text-base">
                                        Rp {{ number_format($laundryItem->price, 0, ',', '.') }}
                                    </span>
                                </div>
                                <h3 class="font-extrabold text-slate-900 text-base item-name leading-snug">{{ $laundryItem->name }}</h3>
                                <p class="text-xs text-slate-500 mt-2 line-clamp-2 leading-relaxed">{{ $laundryItem->description }}</p>
                            </div>
                        </div>

                        <div class="p-4 bg-slate-50 border-t border-slate-100">
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Jumlah Porsi / Satuan</label>
                            <div class="flex items-center gap-2">
                                <button type="button" onclick="adjustQty('qty_{{ $laundryItem->id }}', -1)" class="w-9 h-9 rounded-lg bg-white border border-slate-300 hover:bg-slate-100 text-slate-700 font-bold text-base flex items-center justify-center transition">-</button>
                                <input type="number" id="qty_{{ $laundryItem->id }}" name="items[{{ $laundryItem->id }}]" min="0" value="0" data-name="{{ $laundryItem->name }}" data-price="{{ $laundryItem->price }}" class="item-qty flex-1 border border-slate-300 rounded-lg px-3 py-2 text-center font-extrabold text-slate-800 focus:ring-2 focus:ring-blue-500 outline-none text-sm bg-white">
                                <button type="button" onclick="adjustQty('qty_{{ $laundryItem->id }}', 1)" class="w-9 h-9 rounded-lg bg-white border border-slate-300 hover:bg-slate-100 text-slate-700 font-bold text-base flex items-center justify-center transition">+</button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 bg-white p-12 text-center rounded-2xl border border-slate-200">
                        <span class="text-5xl block mb-3">🔍</span>
                        <h3 class="text-base font-bold text-slate-800">Tidak ada layanan yang sesuai</h3>
                        <p class="text-xs text-slate-500 mt-1">Silakan reset filter atau pilih kategori lain.</p>
                        <a href="{{ route('customer.index') }}" class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded-xl text-xs font-bold hover:bg-blue-700 transition">
                            Tampilkan Semua Layanan
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- TOMBOL PESAN SEKARANG -->
            <div class="sticky bottom-6 z-30">
                <div class="bg-white/90 backdrop-blur-md border border-slate-200 p-4 rounded-2xl shadow-xl flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div>
                        <p class="text-xs text-slate-500">Estimasi Total Belanja:</p>
                        <p id="liveTotal" class="text-2xl font-black text-slate-900">Rp 0</p>
                    </div>
                    <button type="button" onclick="showConfirmationModal()" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-extrabold px-8 py-3.5 rounded-xl shadow-lg shadow-blue-600/30 transition transform hover:-translate-y-0.5">
                        Pesan Sekarang &rarr;
                    </button>
                </div>
            </div>

            <!-- MODAL KONFIRMASI PESANAN -->
            <div id="confirmModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs hidden items-center justify-center z-50 p-4">
                <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl border border-slate-100 transform transition-all">
                    <div class="text-center mb-6">
                        <span class="w-12 h-12 rounded-full bg-blue-100 text-blue-700 text-2xl flex items-center justify-center mx-auto mb-3">📋</span>
                        <h3 class="text-xl font-extrabold text-slate-900">Konfirmasi Pesanan Laundry</h3>
                        <p class="text-xs text-slate-500 mt-1">Pastikan rincian pesanan Anda sudah benar</p>
                    </div>

                    <div class="bg-slate-50 p-4 rounded-xl space-y-2 text-xs text-slate-700 mb-4 border border-slate-200">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Nama Pemesan:</span>
                            <span id="modalName" class="text-slate-900 font-bold"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Nomor Antrean:</span>
                            <span id="modalTable" class="text-blue-700 font-black"></span>
                        </div>
                    </div>

                    <div class="border-t border-b border-slate-200 py-3 mb-4 max-h-52 overflow-y-auto">
                        <p class="font-bold text-[11px] text-slate-400 uppercase tracking-wider mb-2">Rincian Item Layanan</p>
                        <ul id="modalItemList" class="space-y-2 text-xs divide-y divide-slate-100"></ul>
                    </div>

                    <div class="flex justify-between items-center text-sm font-bold text-slate-800 mb-6 bg-emerald-50 p-3 rounded-xl border border-emerald-200">
                        <span class="text-emerald-900">Total Pembayaran:</span>
                        <span id="modalTotalPrice" class="text-emerald-700 text-lg font-black">Rp 0</span>
                    </div>

                    <div class="flex gap-3">
                        <button type="button" onclick="closeConfirmationModal()" class="w-1/2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 rounded-xl transition text-xs">
                            Batal
                        </button>
                        <button type="submit" class="w-1/2 bg-blue-600 hover:bg-blue-700 text-white font-extrabold py-3 rounded-xl shadow-md shadow-blue-600/20 transition text-xs">
                            Ya, Kirim Pesanan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </main>

    <footer class="border-t border-slate-200 bg-white py-8 text-center text-xs text-slate-600 mt-16">
        <p class="font-semibold">&copy; {{ date('Y') }} LaundrySimple. Ujian Sertifikasi Kompetensi (Serkom) Rekayasa Perangkat Lunak.</p>
    </footer>

    <script>
        function filterCategory(category, element) {
            document.querySelectorAll('.btn-category').forEach(btn => {
                btn.className = "btn-category px-4 py-2 rounded-xl font-bold text-xs transition bg-slate-100 text-slate-700 hover:bg-slate-200";
            });
            element.className = "btn-category px-4 py-2 rounded-xl font-bold text-xs transition bg-blue-600 text-white shadow-sm shadow-blue-500/20";
            
            const cards = document.querySelectorAll('.laundryItem-card');
            cards.forEach(card => {
                const cardCategory = card.getAttribute('data-category');
                card.style.display = (category === 'all' || cardCategory === category) ? 'flex' : 'none';
            });
        }

        function adjustQty(inputId, delta) {
            const input = document.getElementById(inputId);
            let val = parseInt(input.value) || 0;
            val = Math.max(0, val + delta);
            input.value = val;
            updateLiveTotal();
        }

        function updateLiveTotal() {
            let total = 0;
            document.querySelectorAll('.item-qty').forEach(input => {
                const qty = parseInt(input.value) || 0;
                const price = parseFloat(input.getAttribute('data-price')) || 0;
                total += qty * price;
            });
            document.getElementById('liveTotal').textContent = 'Rp ' + total.toLocaleString('id-ID');
        }

        document.querySelectorAll('.item-qty').forEach(input => {
            input.addEventListener('input', updateLiveTotal);
        });

        function showConfirmationModal() {
            const name = document.getElementById('customer_name').value.trim();
            const table = document.getElementById('queue_number').value.trim();
            if (!name || !table) {
                alert('Silakan isi Nama Lengkap dan Nomor Antrean terlebih dahulu!');
                return;
            }

            const items = document.querySelectorAll('.item-qty');
            let itemListHtml = '';
            let grandTotal = 0;
            let hasOrder = false;

            items.forEach(input => {
                const qty = parseInt(input.value) || 0;
                if (qty > 0) {
                    hasOrder = true;
                    const itemName = input.getAttribute('data-name');
                    const price = parseFloat(input.getAttribute('data-price'));
                    const subtotal = qty * price;
                    grandTotal += subtotal;
                    itemListHtml += `
                        <li class="pt-2 flex justify-between items-center">
                            <div>
                                <span class="font-bold text-slate-800">${itemName}</span>
                                <span class="text-[11px] text-slate-400 block">${qty}x @ Rp ${price.toLocaleString('id-ID')}</span>
                            </div>
                            <span class="font-bold text-slate-700">Rp ${subtotal.toLocaleString('id-ID')}</span>
                        </li>`;
                }
            });

            if (!hasOrder) {
                alert('Pilih minimal 1 layanan dengan jumlah lebih dari 0!');
                return;
            }

            document.getElementById('modalName').textContent = name;
            document.getElementById('modalTable').textContent = table.padStart(2, '0');
            document.getElementById('modalItemList').innerHTML = itemListHtml;
            document.getElementById('modalTotalPrice').textContent = 'Rp ' + grandTotal.toLocaleString('id-ID');

            const modal = document.getElementById('confirmModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeConfirmationModal() {
            const modal = document.getElementById('confirmModal');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }
    </script>
</body>
</html>
