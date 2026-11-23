<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manajemen Akun User</h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 shadow-sm transition">
                    Dashboard Pesanan
                </a>
                <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 shadow-sm transition">
                    + Tambah User
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

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 p-4 rounded-lg mb-6 shadow-sm font-medium">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden border border-gray-200">
            <table class="w-full bg-white border-collapse text-left">
                <thead>
                    <tr class="bg-gray-100 border-b text-gray-600 uppercase text-xs">
                        <th class="p-3">Nama</th>
                        <th class="p-3">Email</th>
                        <th class="p-3">Role</th>
                        <th class="p-3">Terdaftar Sejak</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y text-sm">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-3 font-semibold text-gray-800">{{ $user->name }}</td>
                        <td class="p-3 text-gray-600">{{ $user->email }}</td>
                        <td class="p-3">
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                {{ strtoupper($user->role) }}
                            </span>
                        </td>
                        <td class="p-3 text-xs text-gray-500">{{ $user->created_at ? $user->created_at->format('d M Y H:i') : '-' }}</td>
                        <td class="p-3 text-center whitespace-nowrap">
                            @if($user->role !== 'admin')
                                <a href="{{ route('admin.users.edit', $user) }}" class="inline-block bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1.5 rounded font-semibold text-xs mr-2 transition">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline" onsubmit="return confirm('Yakin ingin menghapus user latihan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1.5 rounded font-semibold text-xs transition">
                                        Hapus
                                    </button>
                                </form>
                            @else
                                <span class="text-xs text-gray-400 italic">Akun Utama (Terkunci)</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-6 text-center text-gray-500">Belum ada user.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $users->links() }}</div>
    </div>
</x-app-layout>
