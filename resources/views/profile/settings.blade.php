@extends('layouts.app')

@section('title', 'Pengaturan - SIP PKL')

@section('content')
<div class="space-y-4 md:space-y-6">
    <!-- Header -->
    <div class="bg-white shadow-sm rounded-xl p-4 md:p-6 border border-gray-100">
        <div class="flex items-center">
            <div class="w-10 h-10 md:w-12 md:h-12 bg-gray-50 rounded-lg flex items-center justify-center mr-3 md:mr-4 border border-gray-100">
                <i class="fas fa-cog text-xl md:text-2xl text-gray-600"></i>
            </div>
            <div>
                <h1 class="text-xl md:text-2xl font-bold text-gray-900">Pengaturan</h1>
                <p class="text-gray-500 mt-0.5 md:mt-1 text-sm">Kelola akun Anda</p>
            </div>
        </div>
    </div>

    <!-- Account Information -->
    <div class="bg-white shadow-sm rounded-xl p-4 md:p-6 border border-gray-100">
        <div class="flex items-center mb-4 md:mb-6">
            <div class="w-8 h-8 md:w-10 md:h-10 bg-blue-50 rounded-lg flex items-center justify-center border border-blue-100">
                <i class="fas fa-user-circle text-blue-600 text-sm md:text-lg"></i>
            </div>
            <h3 class="text-base md:text-lg font-bold text-gray-900 ml-2 md:ml-3">Informasi Akun</h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @if($user)
                <div class="p-4 rounded-xl border border-gray-200 hover:border-gray-300 transition-colors">
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">
                        Nama Lengkap
                    </label>
                    <div class="flex items-center">
                        <i class="fas fa-user text-gray-400 mr-2"></i>
                        <p class="text-base text-gray-900 font-medium">{{ $user->name }}</p>
                    </div>
                </div>

                <div class="p-4 rounded-xl border border-gray-200 hover:border-gray-300 transition-colors">
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">
                        Email
                    </label>
                    <div class="flex items-center">
                        <i class="fas fa-envelope text-gray-400 mr-2"></i>
                        <p class="text-base text-gray-900 font-medium break-all">{{ $user->email }}</p>
                    </div>
                </div>

                <div class="p-4 rounded-xl border border-gray-200 hover:border-gray-300 transition-colors">
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">
                        Role
                    </label>
                    <div class="flex items-center mt-1">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($user->role === 'admin') bg-red-50 text-red-700 border border-red-100
                            @elseif($user->role === 'dospem') bg-purple-50 text-purple-700 border border-purple-100
                            @else bg-green-50 text-green-700 border border-green-100 @endif">
                            <i class="fas
                                @if($user->role === 'admin') fa-shield-alt
                                @elseif($user->role === 'dospem') fa-chalkboard-teacher
                                @else fa-user-graduate @endif mr-2 text-xs"></i>
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>
                </div>

                <div class="p-4 rounded-xl border border-gray-200 hover:border-gray-300 transition-colors">
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">
                        Bergabung Sejak
                    </label>
                    <div class="flex items-center">
                        <i class="fas fa-calendar text-gray-400 mr-2"></i>
                        <div>
                            <p class="text-base text-gray-900 font-medium">{{ $user->created_at->format('d M Y') }}</p>
                            <p class="text-xs text-gray-400">{{ $user->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
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
    <div class="bg-white shadow-sm rounded-xl p-4 md:p-6 border border-gray-100">
        <div class="flex items-center mb-4 md:mb-6">
            <div class="w-8 h-8 md:w-10 md:h-10 bg-gray-50 rounded-lg flex items-center justify-center border border-gray-100">
                <i class="fas fa-shield-alt text-gray-600 text-sm md:text-lg"></i>
            </div>
            <h3 class="text-base md:text-lg font-bold text-gray-900 ml-2 md:ml-3">Keamanan</h3>
        </div>
        <div class="space-y-3 md:space-y-4">
            <div class="p-3 md:p-5 rounded-xl border border-gray-200 hover:border-gray-300 transition-all duration-200">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <div class="flex items-center">
                        <div class="w-8 h-8 md:w-10 md:h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-3 md:mr-4 text-gray-500">
                            <i class="fas fa-key text-sm md:text-base"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 text-sm md:text-base">Ubah Password</h4>
                            <p class="text-xs md:text-sm text-gray-500 mt-0.5">Perbarui password akun</p>
                        </div>
                    </div>
                    <button onclick="openPasswordModal()" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-lg transition-all duration-200 font-medium text-sm shadow-sm w-full md:w-auto">
                        <i class="fas fa-pen mr-2 text-gray-400"></i>Ubah
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Pindah Akun Section -->
    @if(\App\Models\SystemSetting::isEnabled('switch_account_enabled', true))
    <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
        <div class="p-4 md:p-6 cursor-pointer hover:bg-gray-50 transition-colors duration-200" onclick="toggleAccounts()">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-8 h-8 md:w-10 md:h-10 bg-gray-50 rounded-lg flex items-center justify-center border border-gray-100">
                        <i class="fas fa-exchange-alt text-gray-600 text-sm md:text-lg"></i>
                    </div>
                    <h3 class="text-base md:text-lg font-bold text-gray-900 ml-2 md:ml-3">Pindah Akun</h3>
                </div>
                <div class="flex items-center">
                    <span class="text-xs md:text-sm text-gray-500 mr-2 md:mr-3">{{ count($linkedAccounts) + 1 }} Akun</span>
                    <i id="accountChevron" class="fas fa-chevron-down text-gray-400 transition-transform duration-300"></i>
                </div>
            </div>
        </div>

        <div id="accountList" class="hidden border-t border-gray-100 bg-gray-50/50 p-6 space-y-4">
            <div class="flex justify-end mb-4">
                <a href="{{ route('profile.accounts.add-login') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-all duration-200 font-medium text-sm shadow-sm flex items-center">
                    <i class="fas fa-plus mr-2"></i>Tambah Akun
                </a>
            </div>

            <!-- Current Account -->
            <div class="bg-white p-4 rounded-xl border border-blue-200 ring-1 ring-blue-100 flex items-center justify-between">
                <div class="flex items-center">
                    <div class="relative">
                        <img src="{{ $user->profile_photo_url }}" 
                             class="w-10 h-10 rounded-full object-cover border border-gray-200"
                             alt="{{ $user->name }}">
                        <div class="absolute -bottom-1 -right-1 bg-green-500 w-3 h-3 rounded-full border-2 border-white"></div>
                    </div>
                    <div class="ml-3">
                        <h4 class="font-bold text-gray-900">{{ $user->name }}</h4>
                        <p class="text-xs text-gray-500">{{ $user->email }}</p>
                    </div>
                </div>
                <span class="px-3 py-1 bg-blue-50 text-blue-700 text-xs font-semibold rounded-full border border-blue-100">Sedang Aktif</span>
            </div>

            <!-- Linked Accounts -->
            @forelse($linkedAccounts as $account)
                <div class="bg-white p-4 rounded-xl border border-gray-200 hover:border-gray-300 transition-all duration-200 flex items-center justify-between group">
                    <div class="flex items-center">
                    <div class="relative">
                        <img src="{{ $account->profile_photo_url }}" 
                             class="w-10 h-10 rounded-full object-cover border border-gray-200 grayscale group-hover:grayscale-0 transition-all"
                             alt="{{ $account->name }}">
                    </div>
                        <div class="ml-3">
                            <h4 class="font-bold text-gray-700 group-hover:text-gray-900 transition-colors">{{ $account->name }}</h4>
                            <p class="text-xs text-gray-500">{{ $account->email }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <form action="{{ route('profile.accounts.switch', $account->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-3 py-1.5 rounded-lg transition-all duration-200 text-xs font-medium shadow-sm">
                                <i class="fas fa-exchange-alt mr-1"></i>Switch
                            </button>
                        </form>
                        <form action="{{ route('profile.accounts.remove', $account->id) }}" method="POST" onsubmit="return confirm('Hapus akun ini dari daftar?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-white border border-gray-200 hover:bg-red-50 hover:border-red-200 text-gray-400 hover:text-red-600 p-1.5 rounded-lg transition-all duration-200" title="Hapus Akun">
                                <i class="fas fa-trash-alt text-sm"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center py-4 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                    <p class="text-sm text-gray-500">Belum ada akun lain yang terhubung</p>
                </div>
            @endforelse
        </div>
    </div>
    @endif

    <!-- Account Actions -->
    <div class="bg-white shadow-sm rounded-xl p-4 md:p-6 border border-gray-100">
        <div class="flex items-center mb-4 md:mb-6">
            <div class="w-8 h-8 md:w-10 md:h-10 bg-gray-50 rounded-lg flex items-center justify-center border border-gray-100">
                <i class="fas fa-cog text-gray-600 text-sm md:text-lg"></i>
            </div>
            <h3 class="text-base md:text-lg font-bold text-gray-900 ml-2 md:ml-3">Aksi Akun</h3>
        </div>
        <div class="space-y-3 md:space-y-4">
            <div class="p-3 md:p-5 rounded-xl border border-gray-200 hover:border-gray-300 transition-all duration-200">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <div class="flex items-center">
                        <div class="w-8 h-8 md:w-10 md:h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-3 md:mr-4 text-gray-500">
                            <i class="fas fa-sign-out-alt text-sm md:text-base"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 text-sm md:text-base">Logout</h4>
                            <p class="text-xs md:text-sm text-gray-500 mt-0.5">Keluar dari akun</p>
                        </div>
                    </div>
                    <form id="logoutForm" action="{{ route('logout') }}" method="POST" class="w-full md:w-auto">
                        @csrf
                        <button type="button" onclick="confirmLogout()" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-lg transition-all duration-200 font-medium text-sm shadow-sm w-full md:w-auto">
                            <i class="fas fa-sign-out-alt mr-2 text-gray-400"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Change Password Modal - Will be moved to body via JavaScript -->
<div id="passwordModal" class="fixed inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full hidden backdrop-blur-sm flex items-center justify-center p-4" style="z-index: 9999;">
    <div class="relative p-0 border-0 w-full max-w-md shadow-xl rounded-xl md:rounded-2xl bg-white mx-auto">
        <!-- Modal Header -->
        <div class="bg-white p-4 md:p-6 rounded-t-xl md:rounded-t-2xl border-b border-gray-100">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-8 h-8 md:w-10 md:h-10 bg-blue-50 rounded-lg flex items-center justify-center mr-2 md:mr-3">
                        <i class="fas fa-key text-blue-600 text-sm md:text-lg"></i>
                    </div>
                    <h3 class="text-lg md:text-xl font-bold text-gray-900">Ubah Password</h3>
                </div>
                <button onclick="closePasswordModal()" class="text-gray-400 hover:text-gray-500 transition-colors p-1">
                    <i class="fas fa-times text-lg md:text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <form id="passwordForm" method="POST" action="{{ route('profile.password') }}" class="p-4 md:p-6">
            @csrf
            @method('PUT')
            
            <!-- Error Display -->
            @if($errors->any())
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-center text-red-700">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <ul class="text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
            
            <div class="space-y-4 md:space-y-5">
                <div>
                    <label for="current_password" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-lock text-gray-400 mr-2"></i>Password Lama
                    </label>
                    <div class="relative">
                        <input type="password" id="current_password" name="current_password" required
                               class="block w-full px-3 md:px-4 py-2.5 md:py-3 border border-gray-300 rounded-lg md:rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 pr-10 text-sm md:text-base"
                               placeholder="Masukkan password lama">
                        <button type="button" onclick="togglePasswordVisibility('current_password', 'icon_current')" 
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                            <i id="icon_current" class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div>
                    <label for="new_password" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-key text-gray-400 mr-2"></i>Password Baru
                    </label>
                    <div class="relative">
                        <input type="password" id="new_password" name="password" required minlength="8"
                               class="block w-full px-3 md:px-4 py-2.5 md:py-3 border border-gray-300 rounded-lg md:rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 pr-10 text-sm md:text-base"
                               placeholder="Masukkan password baru">
                        <button type="button" onclick="togglePasswordVisibility('new_password', 'icon_new')" 
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                            <i id="icon_new" class="fas fa-eye"></i>
                        </button>
                    </div>
                    <p class="mt-1 md:mt-2 text-xs text-gray-500">Minimal 8 karakter</p>
                </div>

                <div>
                    <label for="new_password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-check-double text-gray-400 mr-2"></i>Konfirmasi
                    </label>
                    <div class="relative">
                        <input type="password" id="new_password_confirmation" name="password_confirmation" required minlength="8"
                               class="block w-full px-3 md:px-4 py-2.5 md:py-3 border border-gray-300 rounded-lg md:rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 pr-10 text-sm md:text-base"
                               placeholder="Konfirmasi password baru">
                        <button type="button" onclick="togglePasswordVisibility('new_password_confirmation', 'icon_confirm')" 
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                            <i id="icon_confirm" class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex flex-col-reverse md:flex-row justify-end gap-2 md:gap-3 mt-6 md:mt-8">
                <button type="button" onclick="closePasswordModal()"
                        class="px-4 md:px-6 py-2.5 md:py-3 bg-white border border-gray-300 text-gray-700 rounded-lg md:rounded-xl hover:bg-gray-50 transition-all duration-200 font-semibold shadow-sm text-sm md:text-base">
                    Batal
                </button>
                <button type="submit"
                        class="px-4 md:px-6 py-2.5 md:py-3 bg-blue-600 text-white rounded-lg md:rounded-xl hover:bg-blue-700 transition-all duration-200 shadow-md hover:shadow-lg font-semibold text-sm md:text-base">
                    <i class="fas fa-save mr-2"></i>Ubah
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
    // Reset visibility
    resetPasswordVisibility('current_password', 'icon_current');
    resetPasswordVisibility('new_password', 'icon_new');
    resetPasswordVisibility('new_password_confirmation', 'icon_confirm');
}

function togglePasswordVisibility(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

function resetPasswordVisibility(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    
    if (input && icon) {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

function toggleAccounts() {
    const list = document.getElementById('accountList');
    const chevron = document.getElementById('accountChevron');
    
    if (list.classList.contains('hidden')) {
        list.classList.remove('hidden');
        chevron.style.transform = 'rotate(180deg)';
    } else {
        list.classList.add('hidden');
        chevron.style.transform = 'rotate(0deg)';
    }
}

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

// Move modals to body to escape transform stacking context
document.addEventListener('DOMContentLoaded', function() {
    const passwordModal = document.getElementById('passwordModal');
    const logoutModal = document.getElementById('logoutModal');
    
    if (passwordModal) {
        document.body.appendChild(passwordModal);
    }
    if (logoutModal) {
        document.body.appendChild(logoutModal);
    }
    
    // Auto-open password modal if there are validation errors
    @if($errors->any())
    setTimeout(function() {
        openPasswordModal();
    }, 100);
    @endif
    
    // Close modal when clicking outside
    if (passwordModal) {
        passwordModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closePasswordModal();
            }
        });
    }
    
    if (logoutModal) {
        logoutModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeLogoutModal();
            }
        });
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePasswordModal();
        closeLogoutModal();
    }
});
</script>

<!-- Logout Confirmation Modal - Will be moved to body via JavaScript -->
<div id="logoutModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center p-4" style="z-index: 9999;">
    <div class="bg-white rounded-xl shadow-2xl p-6 md:p-8 max-w-md w-full mx-auto">
        <div class="text-center">
            <div class="w-14 h-14 md:w-16 md:h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3 md:mb-4">
                <i class="fas fa-sign-out-alt text-xl md:text-2xl text-gray-500"></i>
            </div>
            <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-2">Konfirmasi Logout</h3>
            <p class="text-gray-600 mb-4 md:mb-6 text-sm md:text-base">Yakin ingin keluar?</p>
            <div class="flex flex-col-reverse md:flex-row justify-center gap-2 md:gap-3">
                <button onclick="closeLogoutModal()" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold py-2 md:py-2.5 px-4 md:px-6 rounded-lg transition-all duration-200 shadow-sm text-sm md:text-base">
                    Batal
                </button>
                <button onclick="proceedLogout()" class="bg-gray-900 hover:bg-black text-white font-semibold py-2 md:py-2.5 px-4 md:px-6 rounded-lg transition-all duration-200 shadow-md text-sm md:text-base">
                    Ya, Logout
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
