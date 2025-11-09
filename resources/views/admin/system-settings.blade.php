@extends('layouts.app')

@section('title', 'Menu Sistem')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <!-- Header with Gradient -->
        <div class="relative overflow-hidden bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 shadow-2xl rounded-2xl p-8">
            <div class="absolute inset-0 bg-black opacity-10"></div>
            <div class="relative z-10">
                <h1 class="text-3xl font-bold text-white mb-2">Menu Sistem</h1>
                <p class="text-purple-100">Kelola pengaturan sistem untuk mengontrol fitur-fitur yang tersedia</p>
            </div>
            <!-- Decorative circles -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mr-32 -mt-32"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full -ml-24 -mb-24"></div>
        </div>

        <!-- Settings Form -->
        <div class="bg-white shadow-lg rounded-2xl border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-5 border-b border-gray-200">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-sliders-h text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Pengaturan Fitur</h2>
                        <p class="text-xs text-gray-600">Aktifkan atau nonaktifkan fitur-fitur yang tersedia</p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.system-settings.update') }}" class="p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Tab Pemberkasan Akhir Toggle -->
                    <div class="group bg-gradient-to-br from-blue-50 to-white border-2 border-blue-100 rounded-xl p-5 hover:shadow-lg transition-all duration-300 hover:border-blue-300">
                        <div class="flex items-start justify-between mb-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-file-alt text-white"></i>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="laporan_pkl_enabled" value="1" class="sr-only peer" {{ $laporanPklEnabled ? 'checked' : '' }}>
                                <div class="w-14 h-7 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-blue-500 peer-checked:to-blue-600"></div>
                            </label>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-1">Tab Pemberkasan Akhir</h3>
                        <p class="text-sm text-gray-600">Mengontrol akses Tab Pemberkasan Akhir mahasiswa</p>
                        <div class="mt-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $laporanPklEnabled ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $laporanPklEnabled ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                    </div>

                    <!-- Penilaian Toggle -->
                    <div class="group bg-gradient-to-br from-green-50 to-white border-2 border-green-100 rounded-xl p-5 hover:shadow-lg transition-all duration-300 hover:border-green-300">
                        <div class="flex items-start justify-between mb-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-star text-white"></i>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="penilaian_enabled" value="1" class="sr-only peer" {{ $penilaianEnabled ? 'checked' : '' }}>
                                <div class="w-14 h-7 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-green-500 peer-checked:to-green-600"></div>
                            </label>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-1">Penilaian</h3>
                        <p class="text-sm text-gray-600">Kontrol penilaian dosen pembimbing</p>
                        <div class="mt-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $penilaianEnabled ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $penilaianEnabled ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                    </div>

                    <!-- Jadwal Seminar Toggle -->
                    <div class="group bg-gradient-to-br from-purple-50 to-white border-2 border-purple-100 rounded-xl p-5 hover:shadow-lg transition-all duration-300 hover:border-purple-300">
                        <div class="flex items-start justify-between mb-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-calendar-alt text-white"></i>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="jadwal_seminar_enabled" value="1" class="sr-only peer" {{ $jadwalSeminarEnabled ? 'checked' : '' }}>
                                <div class="w-14 h-7 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-purple-500 peer-checked:to-purple-600"></div>
                            </label>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-1">Jadwal Seminar</h3>
                        <p class="text-sm text-gray-600">Kontrol fitur Jadwal Seminar</p>
                        <div class="mt-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $jadwalSeminarEnabled ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $jadwalSeminarEnabled ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                    </div>

                    <!-- Instansi Mitra Toggle -->
                    <div class="group bg-gradient-to-br from-orange-50 to-white border-2 border-orange-100 rounded-xl p-5 hover:shadow-lg transition-all duration-300 hover:border-orange-300">
                        <div class="flex items-start justify-between mb-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-building text-white"></i>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="instansi_mitra_enabled" value="1" class="sr-only peer" {{ $instansiMitraEnabled ? 'checked' : '' }}>
                                <div class="w-14 h-7 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-orange-500 peer-checked:to-orange-600"></div>
                            </label>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-1">Instansi Mitra</h3>
                        <p class="text-sm text-gray-600">Kontrol fitur Instansi Mitra</p>
                        <div class="mt-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $instansiMitraEnabled ? 'bg-orange-100 text-orange-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $instansiMitraEnabled ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                    </div>

                    <!-- Tab Pemberkasan Instansi Mitra Toggle -->
                    <div class="group bg-gradient-to-br from-indigo-50 to-white border-2 border-indigo-100 rounded-xl p-5 hover:shadow-lg transition-all duration-300 hover:border-indigo-300">
                        <div class="flex items-start justify-between mb-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-folder-open text-white"></i>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="dokumen_pemberkasan_enabled" value="1" class="sr-only peer" {{ $dokumenPemberkasanEnabled ? 'checked' : '' }}>
                                <div class="w-14 h-7 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-indigo-500 peer-checked:to-indigo-600"></div>
                            </label>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-1">Tab Pemberkasan Instansi Mitra</h3>
                        <p class="text-sm text-gray-600">Mengontrol akses Tab Pemberkasan Instansi Mitra mahasiswa</p>
                        <div class="mt-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $dokumenPemberkasanEnabled ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $dokumenPemberkasanEnabled ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                    </div>

                    <!-- Registration Toggle -->
                    <div class="group bg-gradient-to-br from-red-50 to-white border-2 border-red-100 rounded-xl p-5 hover:shadow-lg transition-all duration-300 hover:border-red-300">
                        <div class="flex items-start justify-between mb-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-user-lock text-white"></i>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="registration_enabled" value="1" class="sr-only peer" {{ $registrationEnabled ? 'checked' : '' }}>
                                <div class="w-14 h-7 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-red-500 peer-checked:to-red-600"></div>
                            </label>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-1">Tutup Pendaftaran</h3>
                        <p class="text-sm text-gray-600">Kontrol pendaftaran akun baru</p>
                        <div class="mt-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $registrationEnabled ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $registrationEnabled ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end mt-6 pt-6 border-t border-gray-200">
                    <button type="submit" class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-8 py-3 rounded-xl hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 font-semibold">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>

        <!-- Login Background Image Upload -->
        <div class="bg-white shadow-lg rounded-2xl border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-5 border-b border-gray-200">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-image text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Gambar Background Login</h2>
                        <p class="text-xs text-gray-600">Upload gambar background untuk halaman login</p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.system-settings.upload-login-bg') }}" enctype="multipart/form-data" class="px-6 py-4">
                @csrf

                <div class="mb-6">
                    <!-- Current Image Preview -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Preview Gambar Saat Ini</label>
                        @if(file_exists(public_path('images/auth/bg_login.jpg')))
                            <img src="{{ asset('images/auth/bg_login.jpg') }}?v={{ time() }}"
                                 alt="Current Login Background"
                                 class="w-full h-64 object-cover rounded-lg border border-gray-300">
                        @else
                            <div class="w-full h-64 bg-gray-100 rounded-lg border border-gray-300 flex items-center justify-center">
                                <p class="text-gray-500">Belum ada gambar background</p>
                            </div>
                        @endif
                    </div>

                    <!-- File Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Upload Gambar Baru</label>
                        <div class="relative">
                            <input type="file"
                                   name="login_bg_image"
                                   id="login_bg_image"
                                   accept="image/jpeg,image/jpg,image/png"
                                   class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-l-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 file:cursor-pointer"
                                   onchange="previewImage(event)">
                        </div>
                        <p class="mt-2 text-xs text-gray-500">
                            Format yang didukung: JPG, JPEG, PNG. Maksimal ukuran file: 5MB. Resolusi rekomendasi: 1920x1080px
                        </p>
                        <p id="fileName" class="mt-2 text-sm font-medium text-green-600 hidden"></p>
                    </div>

                    <!-- Preview New Image -->
                    <div id="imagePreview" class="mt-4 hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Preview Gambar Baru</label>
                        <img id="previewImg" src="" alt="Preview" class="w-full h-64 object-cover rounded-lg border border-gray-300">
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-gradient-to-r from-green-600 to-green-700 text-white px-8 py-3 rounded-xl hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 font-semibold">
                        <i class="fas fa-upload mr-2"></i>
                        Upload Gambar
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

<script>
// Update toggle text when changed
document.querySelector('input[name="laporan_pkl_enabled"]').addEventListener('change', function() {
    const span = this.nextElementSibling.nextElementSibling;
    span.textContent = this.checked ? 'Aktif' : 'Nonaktif';
});

// Preview image before upload
function previewImage(event) {
    const file = event.target.files[0];
    const fileNameDisplay = document.getElementById('fileName');

    if (file) {
        // Check file size (max 5MB)
        if (file.size > 5 * 1024 * 1024) {
            alert('Ukuran file terlalu besar! Maksimal 5MB.');
            event.target.value = '';
            fileNameDisplay.classList.add('hidden');
            return;
        }

        // Check file type
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!validTypes.includes(file.type)) {
            alert('Format file tidak didukung! Gunakan JPG, JPEG, atau PNG.');
            event.target.value = '';
            fileNameDisplay.classList.add('hidden');
            return;
        }

        // Display file name
        fileNameDisplay.innerHTML = '<i class="fas fa-check-circle text-green-600 mr-2"></i><span class="font-medium">File dipilih:</span> ' + file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';
        fileNameDisplay.classList.remove('hidden');

        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imagePreview').classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    } else {
        fileNameDisplay.classList.add('hidden');
    }
}
</script>
@endsection
