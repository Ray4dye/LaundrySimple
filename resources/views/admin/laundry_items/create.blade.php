<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Layanan Baru</h2>
            <a href="{{ route('laundry_items.index') }}" class="text-sm text-gray-600 hover:text-gray-900 font-medium">
                &larr; Kembali ke Daftar Layanan
            </a>
        </div>
    </x-slot>

    <div class="py-12 max-w-2xl mx-auto sm:px-6 lg:px-8">
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 p-4 rounded-lg mb-6 shadow-sm text-sm">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('laundry_items.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            @csrf
            <div class="mb-4">
                <label class="block font-medium text-sm text-gray-700 mb-1">Nama Layanan</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Cuci Baju Reguler" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border" required>
            </div>
            <div class="mb-4">
                <label class="block font-medium text-sm text-gray-700 mb-1">Kategori</label>
                <select name="category" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border" required>
                    <option value="Cuci" {{ old('category') == 'Cuci' ? 'selected' : '' }}>Cuci</option>
                    <option value="Setrika" {{ old('category') == 'Setrika' ? 'selected' : '' }}>Setrika</option>
                    <option value="Paket" {{ old('category') == 'Paket' ? 'selected' : '' }}>Paket</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block font-medium text-sm text-gray-700 mb-1">Harga (Rp)</label>
                <input type="number" name="price" value="{{ old('price') }}" placeholder="Contoh: 25000" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border" required min="0">
            </div>
            <div class="mb-4">
                <label class="block font-medium text-sm text-gray-700 mb-1">Deskripsi</label>
                <textarea name="description" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border" rows="3" placeholder="Jelaskan detail layanan..." required>{{ old('description') }}</textarea>
            </div>
            <div class="mb-6">
                <label class="block font-medium text-sm text-gray-700 mb-1">Gambar Layanan (Opsional)</label>
                <input type="file" name="image" accept="image/*" class="w-full border border-gray-300 rounded-md p-2 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>
            <div class="flex justify-end gap-3">
                <a href="{{ route('laundry_items.index') }}" class="px-5 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-md font-semibold text-sm transition">
                    Batal
                </a>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-md font-semibold text-sm shadow transition">
                    Simpan Data Layanan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
