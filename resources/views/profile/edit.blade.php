@extends('layouts.app')

@section('title', 'Edit Profile - SIP PKL')

@section('content')
<div class="space-y-4 md:space-y-6">
    <!-- Success/Error Messages handled in layout -->

    @if($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-3 md:p-4 rounded-r-lg shadow-sm" role="alert">
        <div class="flex items-center mb-2">
            <i class="fas fa-exclamation-circle mr-2 md:mr-3 text-lg md:text-xl"></i>
            <p class="font-medium text-sm md:text-base">Terdapat kesalahan:</p>
        </div>
        <ul class="list-disc list-inside ml-6 md:ml-8">
            @foreach($errors->all() as $error)
                <li class="text-xs md:text-sm">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Header with Minimalist Style -->
    <div class="bg-white shadow-sm rounded-xl md:rounded-2xl p-4 md:p-8 border border-gray-200">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <div class="flex items-center">
                <div class="w-12 h-12 md:w-16 md:h-16 bg-green-50 rounded-lg md:rounded-xl flex items-center justify-center mr-3 md:mr-4">
                    <i class="fas fa-user-edit text-2xl md:text-4xl text-green-600"></i>
                </div>
                <div>
                    <h1 class="text-xl md:text-3xl font-bold text-gray-900">Edit Profile</h1>
                    <p class="text-gray-500 mt-0.5 md:mt-1 text-sm md:text-base">Perbarui informasi Anda</p>
                </div>
            </div>
            <a href="{{ route('profile.index') }}" class="bg-white text-gray-700 px-4 md:px-6 py-2 md:py-2.5 rounded-lg md:rounded-xl transition-all duration-200 shadow-sm hover:shadow-md font-medium border border-gray-300 hover:bg-gray-50 text-center text-sm md:text-base">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Edit Form -->
    <form id="profileForm" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Profile Photo Section -->
        <div class="bg-white shadow-sm rounded-xl md:rounded-2xl p-4 md:p-6 border border-gray-200">
            <div class="flex items-center mb-4 md:mb-6">
                <div class="w-8 h-8 md:w-10 md:h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-camera text-gray-600 text-sm md:text-lg"></i>
                </div>
                <h3 class="text-base md:text-lg font-bold text-gray-900 ml-2 md:ml-3">Foto Profil</h3>
            </div>

            <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
                <div class="relative">
                    <img id="profilePhotoPreview" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}"
                         class="w-32 h-32 rounded-full object-cover border-4 border-gray-100 shadow-sm">
                    <button type="button" onclick="document.getElementById('profilePhotoInput').click()"
                            class="absolute bottom-0 right-0 w-10 h-10 bg-white hover:bg-gray-50 rounded-full shadow-md flex items-center justify-center transition-all duration-200 border border-gray-200">
                        <i class="fas fa-camera text-gray-600"></i>
                    </button>
                </div>

                <div class="flex-1 text-center md:text-left">
                    <input type="file" name="profile_photo" id="profilePhotoInput" accept="image/jpeg,image/png,image/jpg" class="hidden">

                    <div class="space-y-3">
                        <button type="button" onclick="document.getElementById('profilePhotoInput').click()"
                                class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200 font-medium shadow-sm">
                            <i class="fas fa-upload mr-2"></i>Pilih Foto Baru
                        </button>

                        @if($user->photo && !$user->google_linked)
                        <button type="button" onclick="deleteProfilePhoto()"
                                class="ml-3 px-6 py-2.5 bg-red-50 text-red-600 border border-red-100 rounded-lg hover:bg-red-100 transition-all duration-200 font-medium">
                            <i class="fas fa-trash mr-2"></i>Hapus Foto
                        </button>
                        @endif
                    </div>

                    <div class="mt-4 text-sm text-gray-500">
                        <p><i class="fas fa-info-circle mr-2 text-blue-500"></i>Ukuran maksimal: 8 MB</p>
                        <p><i class="fas fa-info-circle mr-2 text-blue-500"></i>Format: JPEG, PNG, JPG</p>
                        @if($user->google_linked)
                        <p class="mt-2 text-xs text-orange-600 bg-orange-50 p-2 rounded border border-orange-100">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Akun terhubung dengan Google. Upload foto manual akan menggantikan foto Google.
                        </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Basic Information -->
        <div class="bg-white shadow-sm rounded-xl md:rounded-2xl p-4 md:p-6 border border-gray-200">
            <div class="flex items-center mb-4 md:mb-6">
                <div class="w-8 h-8 md:w-10 md:h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-id-card text-gray-600 text-sm md:text-lg"></i>
                </div>
                <h3 class="text-base md:text-lg font-bold text-gray-900 ml-2 md:ml-3">Informasi Dasar</h3>
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
        <div class="bg-white shadow-sm rounded-xl md:rounded-2xl p-4 md:p-6 border border-gray-200">
            <div class="flex items-center mb-4 md:mb-6">
                <div class="w-8 h-8 md:w-10 md:h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chalkboard-teacher text-gray-600 text-sm md:text-lg"></i>
                </div>
                <h3 class="text-base md:text-lg font-bold text-gray-900 ml-2 md:ml-3">Biodata Dosen</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nip" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-id-badge text-gray-400 mr-2"></i>NIP
                    </label>
                    <input type="text" id="nip" name="nip" value="{{ old('nip', $dospem->nip ?? '') }}"
                           class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                           placeholder="Masukkan NIP">
                    @error('nip')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        @endif

        @if($user->role === 'mahasiswa')
        <!-- Biodata Information -->
        <div class="bg-white shadow-sm rounded-xl md:rounded-2xl p-4 md:p-6 border border-gray-200">
            <div class="flex items-center mb-4 md:mb-6">
                <div class="w-8 h-8 md:w-10 md:h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-graduate text-gray-600 text-sm md:text-lg"></i>
                </div>
                <h3 class="text-base md:text-lg font-bold text-gray-900 ml-2 md:ml-3">Biodata Mahasiswa</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nim" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-id-card text-gray-400 mr-2"></i>NIM
                    </label>
                    <input type="text" id="nim" name="nim" value="{{ old('nim', $profil->nim ?? '') }}"
                           class="block w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent cursor-not-allowed"
                           readonly>
                    <p class="mt-2 text-xs text-gray-500 flex items-center">
                        <i class="fas fa-lock text-gray-400 mr-1"></i>
                        NIM tidak dapat diubah
                    </p>
                    @error('nim')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="prodi" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-graduation-cap text-gray-400 mr-2"></i>Program Studi
                    </label>
                    <input type="text" id="prodi" name="prodi" value="{{ old('prodi', $profil->prodi ?? '') }}" required
                           class="block w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent cursor-not-allowed"
                           placeholder="Contoh: Teknik Informatika" readonly>
                    <p class="mt-2 text-xs text-gray-500 flex items-center">
                        <i class="fas fa-lock text-gray-400 mr-1"></i>
                        Program Studi tidak dapat diubah
                    </p>
                    @error('prodi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="semester" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-calendar-alt text-gray-400 mr-2"></i>Semester
                    </label>
                    <input type="number" id="semester" name="semester" value="{{ old('semester', $profil->semester ?? 5) }}" required
                           min="1" max="14"
                           class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                    @error('semester')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="jenis_kelamin" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-venus-mars text-gray-400 mr-2"></i>Jenis Kelamin
                    </label>
                    <select id="jenis_kelamin" name="jenis_kelamin"
                            class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="L" {{ old('jenis_kelamin', $profil->jenis_kelamin ?? '') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin', $profil->jenis_kelamin ?? '') === 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
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
                               class="block w-full pl-14 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
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
                           class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                           placeholder="Contoh: 3.50">
                    <p class="mt-2 text-xs text-gray-500">IPK harus antara 0.00 - 4.00</p>
                    @error('ipk')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
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

        <!-- Requirement Notification -->
        <div id="requirement-notification" class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-r-lg shadow-sm transition-all duration-300 hidden">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-yellow-400 text-xl animate-pulse"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-yellow-700">
                        Silahkan mencentang persyaratan berikut untuk dapat melakukan Pemberkasan
                    </p>
                </div>
            </div>
        </div>

        <!-- Checkboxes -->
        <div id="konfirmasi-persyaratan" class="bg-white shadow-sm rounded-xl md:rounded-2xl p-4 md:p-6 border border-gray-200">
            <div class="flex items-center mb-4 md:mb-6">
                <div class="w-8 h-8 md:w-10 md:h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-double text-gray-600 text-sm md:text-lg"></i>
                </div>
                <h3 class="text-base md:text-lg font-bold text-gray-900 ml-2 md:ml-3">Konfirmasi Persyaratan</h3>
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
                            Telah menempuh minimal 4 semester (D-3) <span class="text-red-500">*</span>
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
        <div class="flex flex-col-reverse md:flex-row justify-end gap-2 md:gap-3">
            <a href="{{ route('profile.index') }}" class="px-4 md:px-6 py-2.5 md:py-3 bg-white border border-gray-300 text-gray-700 rounded-lg md:rounded-xl hover:bg-gray-50 transition-all duration-200 font-semibold shadow-sm text-center text-sm md:text-base">
                <i class="fas fa-times mr-2"></i>Batal
            </a>
            <button type="button" onclick="confirmSaveChanges()" class="px-4 md:px-6 py-2.5 md:py-3 bg-blue-600 text-white rounded-lg md:rounded-xl hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 shadow-md hover:shadow-lg transition-all duration-200 font-semibold text-sm md:text-base">
                <i class="fas fa-save mr-2"></i>Simpan
            </button>
        </div>
    </form>
</div>

<!-- Delete Photo Form (hidden) -->
<form id="deletePhotoForm" action="{{ route('profile.photo.delete') }}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

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
// Profile photo preview
document.addEventListener('DOMContentLoaded', function() {
    const profilePhotoInput = document.getElementById('profilePhotoInput');
    const profilePhotoPreview = document.getElementById('profilePhotoPreview');

    if (profilePhotoInput) {
        profilePhotoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file size (max 8MB)
                if (file.size > 8388608) {
                    alert('Ukuran file terlalu besar! Maksimal 8MB.');
                    this.value = '';
                    return;
                }

                // Validate file type
                if (!['image/jpeg', 'image/png', 'image/jpg'].includes(file.type)) {
                    alert('Format file harus JPEG, PNG, atau JPG!');
                    this.value = '';
                    return;
                }

                // Show preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    profilePhotoPreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }
});

// Delete profile photo
function deleteProfilePhoto() {
    if (confirm('Yakin ingin menghapus foto profil?')) {
        document.getElementById('deletePhotoForm').submit();
    }
}

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

    // Requirement notification logic
    const checkboxes = document.querySelectorAll('#konfirmasi-persyaratan input[type="checkbox"]');
    const notification = document.getElementById('requirement-notification');

    function checkRequirements() {
        if (!notification) return;
        
        const allChecked = Array.from(checkboxes).every(cb => cb.checked);
        if (allChecked) {
            notification.classList.add('hidden');
        } else {
            notification.classList.remove('hidden');
        }
    }

    if (checkboxes.length > 0) {
        checkboxes.forEach(cb => {
            cb.addEventListener('change', checkRequirements);
        });
        
        // Initial check
        checkRequirements();
    }
});
</script>
@endsection
