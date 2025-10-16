@extends('layouts.app')

@section('title', 'Pengaturan - SIPP PKL')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg p-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Pengaturan</h1>
            <p class="text-gray-600 mt-2">Kelola akun dan keamanan</p>
        </div>
    </div>

    <!-- Account Information -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Akun</h3>
        <div class="space-y-4">
            @if($user)
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $user->name }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $user->email }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Role</label>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @if($user->role === 'admin') bg-red-100 text-red-800
                        @elseif($user->role === 'dospem') bg-purple-100 text-purple-800
                        @else bg-green-100 text-green-800 @endif">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Bergabung Sejak</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('d M Y H:i') }}</p>
                </div>
            @else
                <p class="text-gray-500">Data pengguna tidak ditemukan. Silakan coba login kembali.</p>
            @endif
        </div>
    </div>

    <!-- Security Actions -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Keamanan</h3>
        <div class="space-y-4">
            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                <div>
                    <h4 class="font-medium text-gray-900">Ubah Password</h4>
                    <p class="text-sm text-gray-600">Perbarui password untuk keamanan akun</p>
                </div>
                <button onclick="openPasswordModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    <i class="fas fa-key mr-2"></i>Ubah Password
                </button>
            </div>
        </div>
    </div>

    <!-- Account Actions -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Aksi Akun</h3>
        <div class="space-y-4">
            <div class="flex items-center justify-between p-4 border border-red-200 rounded-lg bg-red-50">
                <div>
                    <h4 class="font-medium text-red-900">Logout</h4>
                    <p class="text-sm text-red-600">Keluar dari akun Anda</p>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div id="passwordModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Ubah Password</h3>
            <form method="POST" action="{{ route('profile.password') }}">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700">Password Lama</label>
                        <input type="password" id="current_password" name="current_password" required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="new_password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                        <input type="password" id="new_password" name="password" required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                        <input type="password" id="new_password_confirmation" name="password_confirmation" required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closePasswordModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Ubah Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openPasswordModal() {
    document.getElementById('passwordModal').classList.remove('hidden');
}

function closePasswordModal() {
    document.getElementById('passwordModal').classList.add('hidden');
}
</script>
@endsection
