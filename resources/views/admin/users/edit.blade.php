<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit User Latihan</h2>
            <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-600 hover:text-gray-900 font-medium">
                &larr; Kembali ke Daftar User
            </a>
        </div>
    </x-slot>

    <div class="py-12 max-w-xl mx-auto sm:px-6 lg:px-8">
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 p-4 rounded-lg mb-6 shadow-sm text-sm">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block font-medium text-sm text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border" required>
            </div>
            <div class="mb-4">
                <label class="block font-medium text-sm text-gray-700 mb-1">Alamat Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border" required>
            </div>
            <div class="mb-4">
                <label class="block font-medium text-sm text-gray-700 mb-1">Password Baru (Boleh Kosong)</label>
                <input type="password" name="password" minlength="8" placeholder="Biarkan kosong bila tidak diubah" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border">
            </div>
            <div class="mb-6">
                <label class="block font-medium text-sm text-gray-700 mb-1">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" minlength="8" placeholder="Ulangi password baru" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border">
            </div>
            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-md font-semibold text-sm transition">
                    Batal
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-md font-semibold text-sm shadow transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
