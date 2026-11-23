<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Master Data Layanan</h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 shadow-sm transition">
                    Dashboard Pesanan
                </a>
                <a href="{{ route('laundry_items.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 shadow-sm transition">
                    + Tambah Layanan
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 p-4 rounded-lg mb-6 shadow-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden border border-gray-200">
            <table class="w-full bg-white border-collapse text-left">
                <thead>
                    <tr class="bg-gray-100 border-b text-gray-600 uppercase text-xs">
                        <th class="p-3 text-center">Gambar</th>
                        <th class="p-3">Nama Layanan</th>
                        <th class="p-3">Kategori</th>
                        <th class="p-3">Harga</th>
                        <th class="p-3">Deskripsi</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y text-sm">
                    @forelse($laundry_items as $laundryItem)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-3 text-center">
                            @if($laundryItem->image)
                                <img src="{{ asset('storage/' . $laundryItem->image) }}" class="w-16 h-16 object-cover mx-auto rounded shadow-sm">
                            @else
                                <span class="text-gray-400 text-xs italic bg-gray-100 px-2 py-1 rounded">No Image</span>
                            @endif
                        </td>
                        <td class="p-3 font-semibold text-gray-800">{{ $laundryItem->name }}</td>
                        <td class="p-3">
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full 
                                {{ $laundryItem->category === 'Cuci' ? 'bg-blue-100 text-blue-800' : ($laundryItem->category === 'Setrika' ? 'bg-amber-100 text-amber-800' : 'bg-purple-100 text-purple-800') }}">
                                {{ $laundryItem->category }}
                            </span>
                        </td>
                        <td class="p-3 font-bold text-green-600">Rp {{ number_format($laundryItem->price, 0, ',', '.') }}</td>
                        <td class="p-3 text-xs text-gray-500 max-w-xs truncate">{{ $laundryItem->description }}</td>
                        <td class="p-3 text-center whitespace-nowrap">
                            <a href="{{ route('laundry_items.edit', $laundryItem->id) }}" class="inline-block bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1.5 rounded font-semibold text-xs mr-2 transition">
                                Edit
                            </a>
                            <form action="{{ route('laundry_items.destroy', $laundryItem->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus layanan ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1.5 rounded font-semibold text-xs transition">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-6 text-center text-gray-500">Belum ada layanan laundry.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $laundry_items->links() }}</div>
    </div>
</x-app-layout>
