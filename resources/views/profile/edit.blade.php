@extends('layouts.app')

@section('title', 'Edit Profile - SIPP PKL')

@section('content')
<div class="space-y-6">
    <!-- Header with Gradient -->
    <div class="relative overflow-hidden bg-gradient-to-br from-green-600 via-emerald-600 to-teal-600 shadow-2xl rounded-2xl p-8">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="relative z-10">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center mr-4">
                        <i class="fas fa-user-edit text-4xl text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white">Edit Profile</h1>
                        <p class="text-green-100 mt-1">Lengkapi dan perbarui informasi pribadi Anda</p>
                    </div>
                </div>
                <a href="{{ route('profile.index') }}" class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white px-6 py-3 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl font-semibold border border-white/30">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>
        <!-- Decorative circles -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mr-32 -mt-32"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full -ml-24 -mb-24"></div>
    </div>

    <!-- Edit Form -->
    <form id="profileForm" action="{{ route('profile.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Basic Information -->
        <div class="bg-white shadow-lg rounded-2xl p-6 border border-gray-100">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-id-card text-white text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 ml-3">Informasi Dasar</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-user text-gray-400 mr-2"></i>Nama Lengkap
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                           class="block w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent cursor-not-allowed"
                           readonly>
                    <p class="mt-2 text-xs text-gray-500 flex items-center">
                        <i class="fas fa-lock text-gray-400 mr-1"></i>
                        Nama tidak dapat diubah
                    </p>
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-envelope text-gray-400 mr-2"></i>Email
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="block w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent cursor-not-allowed"
                           readonly>
                    <p class="mt-2 text-xs text-gray-500 flex items-center">
                        <i class="fas fa-lock text-gray-400 mr-1"></i>
                        Email tidak dapat diubah
                    </p>
                </div>
            </div>
        </div>

        @if($user->role === 'dospem')
        <!-- Biodata Dosen -->
        <div class="bg-white shadow-lg rounded-2xl p-6 border border-gray-100">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chalkboard-teacher text-white text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 ml-3">Biodata Dosen</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nip" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-id-badge text-gray-400 mr-2"></i>NIP
                    </label>
                    <input type="text" id="nip" name="nip" value="{{ old('nip', $dospem->nip ?? '') }}"
                           class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                           placeholder="Masukkan NIP">
                </div>
            </div>
        </div>
        @endif

        @if($user->role === 'mahasiswa')
        <!-- Biodata Information -->
        <div class="bg-white shadow-lg rounded-2xl p-6 border border-gray-100">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-user-graduate text-white text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 ml-3">Biodata Mahasiswa</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nim" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-id-card text-gray-400 mr-2"></i>NIM
                    </label>
                    <input type="text" id="nim" name="nim" value="{{ old('nim', $profil->nim ?? '') }}"
                           class="block w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent cursor-not-allowed"
                           readonly>
                    <p class="mt-2 text-xs text-gray-500 flex items-center">
                        <i class="fas fa-lock text-gray-400 mr-1"></i>
                        NIM tidak dapat diubah
                    </p>
                </div>

                <div>
                    <label for="prodi" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-graduation-cap text-gray-400 mr-2"></i>Program Studi
                    </label>
                    <input type="text" id="prodi" name="prodi" value="{{ old('prodi', $profil->prodi ?? '') }}" required
                           class="block w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent cursor-not-allowed"
                           placeholder="Contoh: Teknik Informatika" readonly>
                    <p class="mt-2 text-xs text-gray-500 flex items-center">
                        <i class="fas fa-lock text-gray-400 mr-1"></i>
                        Program Studi tidak dapat diubah
                    </p>
                </div>

                <div>
                    <label for="semester" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-calendar-alt text-gray-400 mr-2"></i>Semester
                    </label>
                    <input type="number" id="semester" name="semester" value="{{ old('semester', $profil->semester ?? 5) }}" required
                           min="1" max="14"
                           class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200">
                </div>

                <div>
                    <label for="jenis_kelamin" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-venus-mars text-gray-400 mr-2"></i>Jenis Kelamin
                    </label>
                    <select id="jenis_kelamin" name="jenis_kelamin"
                            class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200">
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="L" {{ old('jenis_kelamin', $profil->jenis_kelamin ?? '') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin', $profil->jenis_kelamin ?? '') === 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <div>
                    <label for="no_whatsapp" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fab fa-whatsapp text-gray-400 mr-2"></i>Nomor WhatsApp
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-gray-700 font-medium">+62</span>
                        </div>
                        <input type="text" id="no_whatsapp" name="no_whatsapp"
                               value="{{ old('no_whatsapp', $profil->no_whatsapp ?? '') }}"
                               class="block w-full pl-14 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                               placeholder="8xxxxxxxxxx"
                               oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                               maxlength="13">
                    </div>
                    <p class="mt-2 text-xs text-gray-500">Format: +62 8xxxxxxxxxx (tanpa 0 di depan, maksimal 13 digit)</p>
                    @error('no_whatsapp')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="ipk" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-chart-line text-gray-400 mr-2"></i>IPK
                    </label>
                    <input type="number" id="ipk" name="ipk" value="{{ old('ipk', $profil->ipk ?? '') }}"
                           step="0.01" min="0" max="4.0"
                           class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                           placeholder="Contoh: 3.50">
                </div>

                <div>
                    <label for="id_dospem" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-chalkboard-teacher text-gray-400 mr-2"></i>Dosen Pembimbing
                    </label>
                    @php
                        $currentDospem = $dosenPembimbingList->firstWhere('id', $profil->id_dospem ?? null);
                    @endphp
                    <input type="text" value="{{ $currentDospem ? $currentDospem->name : 'Belum ditentukan' }}"
                           class="block w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 focus:outline-none cursor-not-allowed"
                           readonly>
                    <input type="hidden" name="id_dospem" value="{{ $profil->id_dospem ?? '' }}">
                    <p class="mt-2 text-xs text-gray-500 flex items-center">
                        <i class="fas fa-lock text-gray-400 mr-1"></i>
                        Dosen Pembimbing ditentukan oleh Admin
                    </p>
                </div>
            </div>
        </div>

        <!-- Checkboxes -->
        <div id="konfirmasi-persyaratan" class="bg-white shadow-lg rounded-2xl p-6 border border-gray-100">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-double text-white text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 ml-3">Konfirmasi Persyaratan</h3>
            </div>
            <div class="space-y-4">
                <div class="bg-gray-50 p-5 rounded-xl border border-gray-200">
                    <div class="flex items-start">
                        <div class="flex items-center h-6">
                            <input type="checkbox" id="cek_min_semester" name="cek_min_semester" value="1"
                                   {{ old('cek_min_semester', $profil->cek_min_semester ?? false) ? 'checked' : '' }}
                                   class="h-5 w-5 text-green-600 focus:ring-green-500 border-gray-300 rounded" required>
                        </div>
                        <label for="cek_min_semester" class="ml-4 text-sm text-gray-700 font-medium">
                            Telah menempuh minimal 4 semester (D-3) atau 5 semester (D-4) <span class="text-red-500">*</span>
                        </label>
                    </div>
                </div>

                <div class="bg-gray-50 p-5 rounded-xl border border-gray-200">
                    <div class="flex items-start">
                        <div class="flex items-center h-6">
                            <input type="checkbox" id="cek_ipk_nilaisks" name="cek_ipk_nilaisks" value="1"
                                   {{ old('cek_ipk_nilaisks', $profil->cek_ipk_nilaisks ?? false) ? 'checked' : '' }}
                                   class="h-5 w-5 text-green-600 focus:ring-green-500 border-gray-300 rounded" required>
                        </div>
                        <label for="cek_ipk_nilaisks" class="ml-4 text-sm text-gray-700 font-medium">
                            IPK tidak di bawah 2,50, tanpa nilai E, nilai D maksimal 9 SKS <span class="text-red-500">*</span>
                        </label>
                    </div>
                </div>

                <div class="bg-gray-50 p-5 rounded-xl border border-gray-200">
                    <div class="flex items-start">
                        <div class="flex items-center h-6">
                            <input type="checkbox" id="cek_valid_biodata" name="cek_valid_biodata" value="1"
                                   {{ old('cek_valid_biodata', $profil->cek_valid_biodata ?? false) ? 'checked' : '' }}
                                   class="h-5 w-5 text-green-600 focus:ring-green-500 border-gray-300 rounded" required>
                        </div>
                        <label for="cek_valid_biodata" class="ml-4 text-sm text-gray-700 font-medium">
                            Biodata yang saya masukkan valid dan dapat dipertanggungjawabkan <span class="text-red-500">*</span>
                        </label>
                    </div>
                </div>
            </div>
            <p class="mt-3 text-xs text-gray-500"><span class="text-red-500">*</span> Wajib dicentang</p>
        </div>
        @endif

        <!-- Submit Button -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('profile.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-all duration-200 font-semibold">
                <i class="fas fa-times mr-2"></i>Batal
            </a>
            <button type="button" onclick="confirmSaveChanges()" class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-700 text-white rounded-xl hover:from-green-700 hover:to-emerald-800 focus:outline-none focus:ring-4 focus:ring-green-300 shadow-lg hover:shadow-xl transition-all duration-200 font-semibold">
                <i class="fas fa-save mr-2"></i>Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<!-- Confirmation Modal -->
<div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-2xl p-8 max-w-md w-full mx-4 transform transition-all">
        <div class="text-center">
            <i class="fas fa-question-circle text-5xl text-blue-500 mb-4"></i>
            <h3 class="text-xl font-bold text-gray-900 mb-3">Konfirmasi Perubahan</h3>
            <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menyimpan perubahan profil?</p>
            <div class="flex justify-center space-x-3">
                <button onclick="closeConfirmModal()" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2.5 px-6 rounded-lg transition-all duration-200">
                    Batal
                </button>
                <button onclick="submitForm()" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 px-6 rounded-lg transition-all duration-200">
                    Ya, Simpan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function confirmSaveChanges() {
    // Validate form first
    const form = document.getElementById('profileForm');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    // Show confirmation modal
    const modal = document.getElementById('confirmModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeConfirmModal() {
    const modal = document.getElementById('confirmModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function submitForm() {
    closeConfirmModal();
    document.getElementById('profileForm').submit();
}

// Scroll to section if hash is present
document.addEventListener('DOMContentLoaded', function() {
    if (window.location.hash === '#konfirmasi-persyaratan') {
        const element = document.getElementById('konfirmasi-persyaratan');
        if (element) {
            setTimeout(() => {
                element.scrollIntoView({ behavior: 'smooth', block: 'center' });
                // Highlight the section
                element.classList.add('ring-4', 'ring-orange-300');
                setTimeout(() => {
                    element.classList.remove('ring-4', 'ring-orange-300');
                }, 3000);
            }, 500);
        }
    }
});
</script>
@endsection
