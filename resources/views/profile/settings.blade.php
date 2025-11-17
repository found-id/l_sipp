@extends('layouts.app')

@section('title', 'Pengaturan - SIPP PKL')

@section('content')
<div class="space-y-6">
    <!-- Header with Gradient -->
    <div class="relative overflow-hidden bg-gradient-to-br from-gray-700 via-slate-700 to-zinc-700 shadow-2xl rounded-2xl p-8">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="relative z-10">
            <div class="flex items-center">
                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-cog text-4xl text-white"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-white">Pengaturan</h1>
                    <p class="text-gray-200 mt-1">Kelola akun dan keamanan Anda</p>
                </div>
            </div>
        </div>
        <!-- Decorative circles -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mr-32 -mt-32"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full -ml-24 -mb-24"></div>
    </div>

    <!-- Account Information -->
    <div class="bg-white shadow-lg rounded-2xl p-6 border border-gray-100">
        <div class="flex items-center mb-6">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-user-circle text-white text-xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 ml-3">Informasi Akun</h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @if($user)
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-5 rounded-xl border border-blue-100">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-user text-blue-500 mr-2"></i>Nama Lengkap
                    </label>
                    <p class="text-base text-gray-900 font-medium">{{ $user->name }}</p>
                </div>

                <div class="bg-gradient-to-br from-purple-50 to-pink-50 p-5 rounded-xl border border-purple-100">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-envelope text-purple-500 mr-2"></i>Email
                    </label>
                    <p class="text-base text-gray-900 font-medium break-all">{{ $user->email }}</p>
                </div>

                <div class="bg-gradient-to-br from-green-50 to-emerald-50 p-5 rounded-xl border border-green-100">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-shield-alt text-green-500 mr-2"></i>Role
                    </label>
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold
                        @if($user->role === 'admin') bg-red-500 text-white
                        @elseif($user->role === 'dospem') bg-purple-500 text-white
                        @else bg-green-500 text-white @endif shadow-md">
                        <i class="fas
                            @if($user->role === 'admin') fa-shield-alt
                            @elseif($user->role === 'dospem') fa-chalkboard-teacher
                            @else fa-user-graduate @endif mr-2"></i>
                        {{ ucfirst($user->role) }}
                    </span>
                </div>

                <div class="bg-gradient-to-br from-orange-50 to-amber-50 p-5 rounded-xl border border-orange-100">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-calendar text-orange-500 mr-2"></i>Bergabung Sejak
                    </label>
                    <p class="text-base text-gray-900 font-medium">{{ $user->created_at->format('d M Y') }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $user->created_at->diffForHumans() }}</p>
                </div>
            @else
                <div class="col-span-2 bg-yellow-50 border border-yellow-200 rounded-xl p-6 text-center">
                    <i class="fas fa-exclamation-triangle text-yellow-600 text-3xl mb-3"></i>
                    <p class="text-gray-700 font-medium">Data pengguna tidak ditemukan</p>
                    <p class="text-gray-500 text-sm mt-1">Silakan coba login kembali</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Security Actions -->
    <div class="bg-white shadow-lg rounded-2xl p-6 border border-gray-100">
        <div class="flex items-center mb-6">
            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-shield-alt text-gray-600 text-lg"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 ml-3">Keamanan</h3>
        </div>
        <div class="space-y-4">
            <div class="bg-gray-50 p-5 rounded-xl border border-gray-200 hover:border-gray-300 transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-key text-gray-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Ubah Password</h4>
                            <p class="text-sm text-gray-500 mt-0.5">Perbarui password untuk keamanan akun</p>
                        </div>
                    </div>
                    <button onclick="openPasswordModal()" class="bg-gray-600 hover:bg-gray-700 text-white px-5 py-2.5 rounded-lg transition-all duration-200 font-medium text-sm">
                        <i class="fas fa-key mr-2"></i>Ubah
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Account Actions -->
    <div class="bg-white shadow-lg rounded-2xl p-6 border border-gray-100">
        <div class="flex items-center mb-6">
            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-cog text-gray-600 text-lg"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 ml-3">Aksi Akun</h3>
        </div>
        <div class="space-y-4">
            <div class="bg-gray-50 p-5 rounded-xl border border-gray-200 hover:border-gray-300 transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-sign-out-alt text-gray-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Logout dari Akun</h4>
                            <p class="text-sm text-gray-500 mt-0.5">Keluar dari akun Anda dengan aman</p>
                        </div>
                    </div>
                    <form id="logoutForm" action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="button" onclick="confirmLogout()" class="bg-gray-600 hover:bg-gray-700 text-white px-5 py-2.5 rounded-lg transition-all duration-200 font-medium text-sm">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div id="passwordModal" class="fixed inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full hidden z-50 backdrop-blur-sm">
    <div class="relative top-20 mx-auto p-0 border-0 w-full max-w-md shadow-2xl rounded-2xl bg-white">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-700 p-6 rounded-t-2xl">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-key text-white text-lg"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white">Ubah Password</h3>
                </div>
                <button onclick="closePasswordModal()" class="text-white hover:text-gray-200 transition-colors">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <form method="POST" action="{{ route('profile.password') }}" class="p-6">
            @csrf
            @method('PUT')
            <div class="space-y-5">
                <div>
                    <label for="current_password" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-lock text-gray-400 mr-2"></i>Password Lama
                    </label>
                    <input type="password" id="current_password" name="current_password" required
                           class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                           placeholder="Masukkan password lama">
                </div>

                <div>
                    <label for="new_password" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-key text-gray-400 mr-2"></i>Password Baru
                    </label>
                    <input type="password" id="new_password" name="password" required
                           class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                           placeholder="Masukkan password baru">
                    <p class="mt-2 text-xs text-gray-500">Minimal 8 karakter</p>
                </div>

                <div>
                    <label for="new_password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-check-double text-gray-400 mr-2"></i>Konfirmasi Password Baru
                    </label>
                    <input type="password" id="new_password_confirmation" name="password_confirmation" required
                           class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                           placeholder="Konfirmasi password baru">
                </div>
            </div>

            <div class="flex justify-end space-x-3 mt-8">
                <button type="button" onclick="closePasswordModal()"
                        class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-all duration-200 font-semibold">
                    <i class="fas fa-times mr-2"></i>Batal
                </button>
                <button type="submit"
                        class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-700 text-white rounded-xl hover:from-indigo-700 hover:to-purple-800 transition-all duration-200 shadow-md hover:shadow-lg font-semibold">
                    <i class="fas fa-save mr-2"></i>Ubah Password
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openPasswordModal() {
    document.getElementById('passwordModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closePasswordModal() {
    document.getElementById('passwordModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    // Reset form
    document.getElementById('current_password').value = '';
    document.getElementById('new_password').value = '';
    document.getElementById('new_password_confirmation').value = '';
}

// Close modal when clicking outside
document.getElementById('passwordModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePasswordModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePasswordModal();
        closeLogoutModal();
    }
});

// Logout confirmation modal functions
function confirmLogout() {
    document.getElementById('logoutModal').classList.remove('hidden');
    document.getElementById('logoutModal').classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeLogoutModal() {
    document.getElementById('logoutModal').classList.add('hidden');
    document.getElementById('logoutModal').classList.remove('flex');
    document.body.style.overflow = 'auto';
}

function proceedLogout() {
    document.getElementById('logoutForm').submit();
}

// Close logout modal when clicking outside
document.getElementById('logoutModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeLogoutModal();
    }
});
</script>

<!-- Logout Confirmation Modal -->
<div id="logoutModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-2xl p-8 max-w-md w-full mx-4">
        <div class="text-center">
            <i class="fas fa-sign-out-alt text-5xl text-gray-500 mb-4"></i>
            <h3 class="text-xl font-bold text-gray-900 mb-3">Konfirmasi Logout</h3>
            <p class="text-gray-600 mb-6">Apakah Anda yakin ingin keluar dari akun?</p>
            <div class="flex justify-center space-x-3">
                <button onclick="closeLogoutModal()" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2.5 px-6 rounded-lg transition-all duration-200">
                    Batal
                </button>
                <button onclick="proceedLogout()" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2.5 px-6 rounded-lg transition-all duration-200">
                    Ya, Logout
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
