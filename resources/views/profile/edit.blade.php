@extends('layouts.app')

@section('title', 'Edit Profile - SIP PKL')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Profil Saya</h1>
        <p class="text-gray-600 mt-1">Kelola informasi profil Anda untuk mengontrol, melindungi dan mengamankan akun</p>
    </div>

    <!-- Main Form -->
    <form id="profileForm" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-sm border border-gray-200">
        @csrf
        @method('PUT')

        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - Form Fields -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Nama -->
                    <div class="border-b border-gray-200 pb-4">
                        <label class="block text-sm text-gray-700 mb-2">Nama</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                               class="w-full px-3 py-2 border-0 bg-transparent text-gray-900 focus:outline-none focus:ring-0 border-b-2 border-transparent focus:border-gray-300"
                               placeholder="Masukkan nama lengkap">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="border-b border-gray-200 pb-4">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <label class="block text-sm text-gray-700 mb-2">Email</label>
                                @php
                                    $email = $user->email;
                                    $parts = explode('@', $email);
                                    $localPart = $parts[0];
                                    $domain = $parts[1] ?? '';

                                    if (strlen($localPart) > 2) {
                                        $maskedLocal = $localPart[0] . $localPart[1] . str_repeat('*', strlen($localPart) - 2);
                                    } else {
                                        $maskedLocal = str_repeat('*', strlen($localPart));
                                    }
                                    $maskedEmail = $maskedLocal . '@' . $domain;
                                @endphp
                                <input type="text" value="{{ $maskedEmail }}" readonly
                                       class="w-full px-3 py-2 border-0 bg-transparent text-gray-900 focus:outline-none cursor-default">
                            </div>
                            <button type="button" class="ml-4 text-sm text-blue-600 hover:text-blue-700 whitespace-nowrap">Ubah</button>
                        </div>
                    </div>

                    <!-- Nomor Telepon / WhatsApp -->
                    @if($user->role === 'mahasiswa')
                    <div class="border-b border-gray-200 pb-4">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <label class="block text-sm text-gray-700 mb-2">Nomor Telepon</label>
                                @php
                                    $phone = $profil->no_whatsapp ?? '';
                                    if ($phone && strlen($phone) > 4) {
                                        $maskedPhone = str_repeat('*', strlen($phone) - 2) . substr($phone, -2);
                                    } else {
                                        $maskedPhone = $phone;
                                    }
                                @endphp
                                <div class="relative">
                                    <input type="text" id="displayPhone" value="{{ $maskedPhone ? $maskedPhone : '' }}" readonly
                                           class="w-full px-3 py-2 border-0 bg-transparent text-gray-900 focus:outline-none cursor-default"
                                           placeholder="Belum diisi">
                                    <input type="text" id="no_whatsapp" name="no_whatsapp" value="{{ old('no_whatsapp', $profil->no_whatsapp ?? '') }}"
                                           class="hidden w-full px-3 py-2 border-0 bg-transparent text-gray-900 focus:outline-none focus:ring-0 border-b-2 border-transparent focus:border-gray-300"
                                           placeholder="8xxxxxxxxxx"
                                           oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                           maxlength="13">
                                </div>
                            </div>
                            <button type="button" onclick="togglePhoneEdit()" id="phoneEditBtn" class="ml-4 text-sm text-blue-600 hover:text-blue-700 whitespace-nowrap">Ubah</button>
                        </div>
                        <p class="mt-2 text-xs text-gray-500">Format: 8xxxxxxxxxx</p>
                        @error('no_whatsapp')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- NIM (for Mahasiswa) -->
                    <div class="border-b border-gray-200 pb-4">
                        <label class="block text-sm text-gray-700 mb-2">NIM</label>
                        <input type="text" name="nim" value="{{ old('nim', $profil->nim ?? '') }}"
                               class="w-full px-3 py-2 border-0 bg-transparent text-gray-900 focus:outline-none focus:ring-0 border-b-2 border-transparent focus:border-gray-300"
                               placeholder="Masukkan NIM">
                        @error('nim')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    @endif

                    @if($user->role === 'dospem')
                    <!-- NIP (for Dospem) -->
                    <div class="border-b border-gray-200 pb-4">
                        <label class="block text-sm text-gray-700 mb-2">NIP</label>
                        <input type="text" name="nip" value="{{ old('nip', $dospem->nip ?? '') }}"
                               class="w-full px-3 py-2 border-0 bg-transparent text-gray-900 focus:outline-none focus:ring-0 border-b-2 border-transparent focus:border-gray-300"
                               placeholder="Masukkan NIP">
                        @error('nip')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    @endif

                    <!-- Jenis Kelamin -->
                    @if($user->role === 'mahasiswa')
                    <div class="border-b border-gray-200 pb-4">
                        <label class="block text-sm text-gray-700 mb-3">Jenis Kelamin</label>
                        <div class="flex items-center gap-6">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="jenis_kelamin" value="L"
                                       {{ old('jenis_kelamin', $profil->jenis_kelamin ?? '') === 'L' ? 'checked' : '' }}
                                       class="w-4 h-4 text-gray-900 border-gray-300 focus:ring-gray-500">
                                <span class="ml-2 text-sm text-gray-700">Laki-laki</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="jenis_kelamin" value="P"
                                       {{ old('jenis_kelamin', $profil->jenis_kelamin ?? '') === 'P' ? 'checked' : '' }}
                                       class="w-4 h-4 text-gray-900 border-gray-300 focus:ring-gray-500">
                                <span class="ml-2 text-sm text-gray-700">Perempuan</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="jenis_kelamin" value=""
                                       {{ old('jenis_kelamin', $profil->jenis_kelamin ?? '') === '' ? 'checked' : '' }}
                                       class="w-4 h-4 text-gray-900 border-gray-300 focus:ring-gray-500">
                                <span class="ml-2 text-sm text-gray-700">Lainnya</span>
                            </label>
                        </div>
                    </div>

                    <!-- Program Studi -->
                    <div class="border-b border-gray-200 pb-4">
                        <label class="block text-sm text-gray-700 mb-2">Program Studi</label>
                        <input type="text" name="prodi" value="Teknologi Informasi" required readonly
                               class="w-full px-3 py-2 border-0 bg-gray-50 text-gray-500 focus:outline-none cursor-not-allowed">
                        <p class="mt-2 text-xs text-gray-500">
                            <i class="fas fa-lock text-gray-400 mr-1"></i>
                            Program Studi tidak dapat diubah
                        </p>
                    </div>

                    <!-- Semester -->
                    <div class="border-b border-gray-200 pb-4">
                        <label class="block text-sm text-gray-700 mb-2">Semester</label>
                        <input type="number" name="semester" value="{{ old('semester', $profil->semester ?? 5) }}" required
                               min="1" max="14"
                               class="w-full px-3 py-2 border-0 bg-transparent text-gray-900 focus:outline-none focus:ring-0 border-b-2 border-transparent focus:border-gray-300">
                    </div>

                    <!-- IPK -->
                    <div class="border-b border-gray-200 pb-4">
                        <label class="block text-sm text-gray-700 mb-2">IPK</label>
                        <input type="text" name="ipk" value="{{ old('ipk', $profil->ipk ?? '') }}"
                               oninput="validateIPK(this)"
                               class="w-full px-3 py-2 border-0 bg-transparent text-gray-900 focus:outline-none focus:ring-0 border-b-2 border-transparent focus:border-gray-300"
                               placeholder="Contoh: 3,50 atau 3.50">
                        <p class="mt-2 text-xs text-gray-500">
                            IPK harus antara 0,00 - 4,00
                        </p>
                    </div>

                    <!-- Dosen Pembimbing -->
                    <div class="border-b border-gray-200 pb-4">
                        <label class="block text-sm text-gray-700 mb-2">Dosen Pembimbing</label>
                        @php
                            $currentDospem = $dosenPembimbingList->firstWhere('id', $profil->id_dospem ?? null);
                        @endphp
                        <input type="text" value="{{ $currentDospem ? $currentDospem->name : 'Belum ditentukan' }}" readonly
                               class="w-full px-3 py-2 border-0 bg-gray-50 text-gray-500 focus:outline-none cursor-not-allowed">
                        <input type="hidden" name="id_dospem" value="{{ $profil->id_dospem ?? '' }}">
                        <p class="mt-2 text-xs text-gray-500">
                            <i class="fas fa-lock text-gray-400 mr-1"></i>
                            Dosen Pembimbing tidak dapat diubah
                        </p>
                    </div>

                    <!-- Checkboxes -->
                    <div class="space-y-3 pt-4">
                        <div class="flex items-start">
                            <input type="checkbox" id="cek_min_semester" name="cek_min_semester" value="1"
                                   {{ old('cek_min_semester', $profil->cek_min_semester ?? false) ? 'checked' : '' }}
                                   class="mt-1 h-4 w-4 text-gray-900 border-gray-300 rounded focus:ring-gray-500">
                            <label for="cek_min_semester" class="ml-3 text-sm text-gray-700">
                                Telah menempuh minimal 4 semester (D-3)
                            </label>
                        </div>

                        <div class="flex items-start">
                            <input type="checkbox" id="cek_ipk_nilaisks" name="cek_ipk_nilaisks" value="1"
                                   {{ old('cek_ipk_nilaisks', $profil->cek_ipk_nilaisks ?? false) ? 'checked' : '' }}
                                   class="mt-1 h-4 w-4 text-gray-900 border-gray-300 rounded focus:ring-gray-500">
                            <label for="cek_ipk_nilaisks" class="ml-3 text-sm text-gray-700">
                                IPK tidak di bawah 2,50, tanpa nilai E, nilai D maksimal 9 SKS
                            </label>
                        </div>

                        <div class="flex items-start">
                            <input type="checkbox" id="cek_valid_biodata" name="cek_valid_biodata" value="1"
                                   {{ old('cek_valid_biodata', $profil->cek_valid_biodata ?? false) ? 'checked' : '' }}
                                   class="mt-1 h-4 w-4 text-gray-900 border-gray-300 rounded focus:ring-gray-500">
                            <label for="cek_valid_biodata" class="ml-3 text-sm text-gray-700">
                                Kamu sudah melakukan verifikasi KYC sehingga tidak dapat mengubah tanggal lahir.
                            </label>
                        </div>
                    </div>
                    @endif

                </div>

                <!-- Right Column - Profile Photo -->
                <div class="lg:col-span-1">
                    <div class="flex flex-col items-center">
                        <div class="relative mb-4">
                            <div class="w-32 h-32 rounded-full overflow-hidden bg-gray-100 border border-gray-200 cursor-pointer hover:opacity-80 transition-opacity" onclick="document.getElementById('profilePhotoInput').click()">
                                <img id="profilePhotoPreview" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                            </div>
                        </div>

                        <input type="file" name="profile_photo" id="profilePhotoInput" accept="image/jpeg,image/png" class="hidden">

                        <button type="button" onclick="document.getElementById('profilePhotoInput').click()" class="mb-3 px-6 py-2 border border-gray-300 text-gray-700 rounded hover:bg-gray-50 transition-colors">
                            Pilih Gambar
                        </button>

                        <div class="text-center text-xs text-gray-500">
                            <p>Ukuran gambar: maks. 1 MB</p>
                            <p>Format gambar: .JPEG, .PNG</p>
                        </div>

                        @if($user->profile_photo)
                        <button type="button" onclick="deleteProfilePhoto()" class="mt-4 text-sm text-red-600 hover:text-red-700">
                            Hapus Foto
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="border-t border-gray-200 px-6 py-4 bg-gray-50 rounded-b-lg">
            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors">
                    <b>Simpan</b>
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Delete Photo Form (hidden) -->
<form id="deletePhotoForm" action="{{ route('profile.photo.delete') }}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

<script>
// Validate IPK input (max 4.0) - accepts both comma and dot
function validateIPK(input) {
    // Allow only numbers, comma, and dot
    input.value = input.value.replace(/[^0-9.,]/g, '');

    // Replace multiple commas/dots with single one
    input.value = input.value.replace(/[.,]+/g, function(match) {
        return match[0];
    });

    // Prevent multiple decimal separators
    const separatorCount = (input.value.match(/[.,]/g) || []).length;
    if (separatorCount > 1) {
        // Keep only the first separator
        let firstSeparatorFound = false;
        input.value = input.value.split('').filter(char => {
            if (char === ',' || char === '.') {
                if (firstSeparatorFound) return false;
                firstSeparatorFound = true;
            }
            return true;
        }).join('');
    }

    // Convert comma to dot for validation
    let valueForValidation = input.value.replace(',', '.');
    let value = parseFloat(valueForValidation);

    // Check if value exceeds 4.0
    if (value > 4.0) {
        input.value = input.value.includes(',') ? '4,0' : '4.0';
        alert('IPK maksimal adalah 4,00');
    }

    // Check if value is negative
    if (value < 0) {
        input.value = '0';
        alert('IPK tidak boleh negatif');
    }

    // Limit to 2 decimal places
    if (input.value.includes(',') || input.value.includes('.')) {
        const separator = input.value.includes(',') ? ',' : '.';
        const parts = input.value.split(separator);
        if (parts[1] && parts[1].length > 2) {
            input.value = parts[0] + separator + parts[1].substring(0, 2);
        }
    }
}

// Toggle phone edit mode
function togglePhoneEdit() {
    const displayPhone = document.getElementById('displayPhone');
    const editPhone = document.getElementById('no_whatsapp');
    const editBtn = document.getElementById('phoneEditBtn');

    if (displayPhone.classList.contains('hidden')) {
        // Switch to display mode
        displayPhone.classList.remove('hidden');
        editPhone.classList.add('hidden');
        editBtn.textContent = 'Ubah';
    } else {
        // Switch to edit mode
        displayPhone.classList.add('hidden');
        editPhone.classList.remove('hidden');
        editPhone.focus();
        editBtn.textContent = 'Simpan';
    }
}

// Profile photo preview
document.addEventListener('DOMContentLoaded', function() {
    const profilePhotoInput = document.getElementById('profilePhotoInput');
    const profilePhotoPreview = document.getElementById('profilePhotoPreview');

    if (profilePhotoInput) {
        profilePhotoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file size (max 1MB)
                if (file.size > 1048576) {
                    alert('Ukuran file terlalu besar! Maksimal 1MB.');
                    this.value = '';
                    return;
                }

                // Validate file type
                if (!['image/jpeg', 'image/png'].includes(file.type)) {
                    alert('Format file harus JPEG atau PNG!');
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

// Before submit, convert IPK comma to dot
document.getElementById('profileForm').addEventListener('submit', function(e) {
    const ipkInput = document.querySelector('input[name="ipk"]');
    if (ipkInput && ipkInput.value.includes(',')) {
        ipkInput.value = ipkInput.value.replace(',', '.');
    }
});
</script>
@endsection
