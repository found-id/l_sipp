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
            <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-shield-alt text-white text-xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 ml-3">Keamanan</h3>
        </div>
        <div class="space-y-4">
            <div class="group bg-gradient-to-br from-indigo-50 to-purple-50 p-6 rounded-xl border-2 border-indigo-100 hover:border-indigo-300 transition-all duration-300 hover:shadow-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-key text-white text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-lg">Ubah Password</h4>
                            <p class="text-sm text-gray-600 mt-1">Perbarui password Anda untuk meningkatkan keamanan akun</p>
                        </div>
                    </div>
                    <button onclick="openPasswordModal()" class="ml-4 bg-gradient-to-r from-indigo-600 to-purple-700 text-white px-6 py-3 rounded-xl hover:from-indigo-700 hover:to-purple-800 transition-all duration-200 shadow-md hover:shadow-lg font-semibold">
                        <i class="fas fa-key mr-2"></i>Ubah Password
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Account Actions -->
    <div class="bg-white shadow-lg rounded-2xl p-6 border border-gray-100">
        <div class="flex items-center mb-6">
            <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-pink-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-white text-xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 ml-3">Aksi Akun</h3>
        </div>
        <div class="space-y-4">
            <div class="group bg-gradient-to-br from-red-50 to-pink-50 p-6 rounded-xl border-2 border-red-200 hover:border-red-300 transition-all duration-300 hover:shadow-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-pink-600 rounded-lg flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-sign-out-alt text-white text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-red-900 text-lg">Logout dari Akun</h4>
                            <p class="text-sm text-red-600 mt-1">Keluar dari akun Anda dengan aman</p>
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="inline ml-4">
                        @csrf
                        <button type="submit" class="bg-gradient-to-r from-red-600 to-pink-700 text-white px-6 py-3 rounded-xl hover:from-red-700 hover:to-pink-800 transition-all duration-200 shadow-md hover:shadow-lg font-semibold">
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
    }
});
</script>
@endsection
