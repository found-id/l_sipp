@extends('layouts.app')

@section('title', 'Menu Sistem')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <!-- Header with Minimalist Style -->
        <div class="relative overflow-hidden bg-slate-800 shadow-lg rounded-2xl p-8 border border-slate-700">
            <div class="absolute inset-0 bg-gradient-to-br from-slate-700 to-slate-900 opacity-50"></div>
            <div class="relative z-10">
                <h1 class="text-3xl font-bold text-white mb-2 tracking-tight">Menu Sistem</h1>
                <p class="text-slate-300 text-lg">Kelola pengaturan sistem untuk mengontrol fitur-fitur yang tersedia</p>
            </div>
            <!-- Decorative circles -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full -ml-24 -mb-24 blur-2xl"></div>
        </div>

        <!-- Settings Form -->
        <div class="bg-white shadow-sm rounded-2xl border border-slate-200 overflow-hidden">
            <div class="bg-slate-50 px-6 py-5 border-b border-slate-200">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-slate-800 rounded-lg flex items-center justify-center mr-3 shadow-sm">
                        <i class="fas fa-sliders-h text-slate-200"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">Pengaturan Fitur</h2>
                        <p class="text-sm text-slate-500">Aktifkan atau nonaktifkan fitur-fitur yang tersedia</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.system-settings.update') }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <!-- Tampilan Section -->
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-slate-800 mb-4 flex items-center">
                        <i class="fas fa-paint-brush mr-2 text-indigo-600"></i>
                        Tampilan
                    </h2>
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                        <div class="max-w-xl">
                            <label for="system_font" class="block text-sm font-medium text-slate-700 mb-2">Jenis Font Sistem</label>
                            <select id="system_font" name="system_font" class="w-full rounded-lg bg-white border-slate-300 text-slate-800 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors">
                                <option value="default" {{ $systemFont === 'default' ? 'selected' : '' }}>Default (System UI)</option>
                                <option value="poppins" {{ $systemFont === 'poppins' ? 'selected' : '' }}>Poppins</option>
                                <option value="inter" {{ $systemFont === 'inter' ? 'selected' : '' }}>Inter</option>
                                <option value="ibm_plex_sans" {{ $systemFont === 'ibm_plex_sans' ? 'selected' : '' }}>IBM Plex Sans</option>
                                <option value="archivo" {{ $systemFont === 'archivo' ? 'selected' : '' }}>Archivo</option>
                                <option value="space_grotesk" {{ $systemFont === 'space_grotesk' ? 'selected' : '' }}>Space Grotesk</option>
                                <option value="bricolage_grotesque" {{ $systemFont === 'bricolage_grotesque' ? 'selected' : '' }}>Bricolage Grotesque</option>
                            </select>
                            <p class="mt-2 text-sm text-slate-500">Pilih font yang akan digunakan pada seluruh halaman aplikasi.</p>
                        </div>
                    </div>
                </div>

                <!-- Feature Toggles Section -->
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-slate-800 mb-4 flex items-center">
                        <i class="fas fa-toggle-on mr-2 text-indigo-600"></i>
                        Pengaturan Fitur
                    </h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- WhatsApp Notification Toggle -->
                    <div class="group bg-white border border-slate-200 rounded-xl p-5 hover:border-slate-400 hover:shadow-md transition-all duration-300">
                        <div class="flex items-start justify-between mb-3">
                            <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center group-hover:bg-slate-200 transition-colors">
                                <i class="fab fa-whatsapp text-slate-700 text-xl"></i>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="whatsapp_notification_enabled" value="1" class="sr-only peer" {{ $whatsappNotificationEnabled ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-slate-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-slate-700"></div>
                            </label>
                        </div>
                        <h3 class="text-base font-bold text-slate-800 mb-1">Notifikasi WhatsApp</h3>
                        <p class="text-sm text-slate-500 mb-3">Kirim notifikasi WA via Fonnte</p>
                        <div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $whatsappNotificationEnabled ? 'bg-slate-800 text-slate-100' : 'bg-slate-100 text-slate-500' }}">
                                {{ $whatsappNotificationEnabled ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                    </div>

                    <!-- Tab Pemberkasan Akhir Toggle -->
                    <div class="group bg-white border border-slate-200 rounded-xl p-5 hover:border-slate-400 hover:shadow-md transition-all duration-300">
                        <div class="flex items-start justify-between mb-3">
                            <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center group-hover:bg-slate-200 transition-colors">
                                <i class="fas fa-file-alt text-slate-700"></i>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="laporan_pkl_enabled" value="1" class="sr-only peer" {{ $laporanPklEnabled ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-slate-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-slate-700"></div>
                            </label>
                        </div>
                        <h3 class="text-base font-bold text-slate-800 mb-1">Tab Pemberkasan Akhir</h3>
                        <p class="text-sm text-slate-500 mb-3">Akses Tab Pemberkasan Akhir</p>
                        <div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $laporanPklEnabled ? 'bg-slate-800 text-slate-100' : 'bg-slate-100 text-slate-500' }}">
                                {{ $laporanPklEnabled ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                    </div>

                    <!-- Penilaian Toggle -->
                    <div class="group bg-white border border-slate-200 rounded-xl p-5 hover:border-slate-400 hover:shadow-md transition-all duration-300">
                        <div class="flex items-start justify-between mb-3">
                            <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center group-hover:bg-slate-200 transition-colors">
                                <i class="fas fa-star text-slate-700"></i>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="penilaian_enabled" value="1" class="sr-only peer" {{ $penilaianEnabled ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-slate-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-slate-700"></div>
                            </label>
                        </div>
                        <h3 class="text-base font-bold text-slate-800 mb-1">Penilaian</h3>
                        <p class="text-sm text-slate-500 mb-3">Penilaian dosen pembimbing</p>
                        <div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $penilaianEnabled ? 'bg-slate-800 text-slate-100' : 'bg-slate-100 text-slate-500' }}">
                                {{ $penilaianEnabled ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                    </div>

                    <!-- Jadwal Seminar Toggle -->
                    <div class="group bg-white border border-slate-200 rounded-xl p-5 hover:border-slate-400 hover:shadow-md transition-all duration-300">
                        <div class="flex items-start justify-between mb-3">
                            <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center group-hover:bg-slate-200 transition-colors">
                                <i class="fas fa-calendar-alt text-slate-700"></i>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="jadwal_seminar_enabled" value="1" class="sr-only peer" {{ $jadwalSeminarEnabled ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-slate-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-slate-700"></div>
                            </label>
                        </div>
                        <h3 class="text-base font-bold text-slate-800 mb-1">Jadwal Seminar</h3>
                        <p class="text-sm text-slate-500 mb-3">Fitur Jadwal Seminar</p>
                        <div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $jadwalSeminarEnabled ? 'bg-slate-800 text-slate-100' : 'bg-slate-100 text-slate-500' }}">
                                {{ $jadwalSeminarEnabled ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                    </div>

                    <!-- Instansi Mitra Toggle -->
                    <div class="group bg-white border border-slate-200 rounded-xl p-5 hover:border-slate-400 hover:shadow-md transition-all duration-300">
                        <div class="flex items-start justify-between mb-3">
                            <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center group-hover:bg-slate-200 transition-colors">
                                <i class="fas fa-building text-slate-700"></i>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="instansi_mitra_enabled" value="1" class="sr-only peer" {{ $instansiMitraEnabled ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-slate-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-slate-700"></div>
                            </label>
                        </div>
                        <h3 class="text-base font-bold text-slate-800 mb-1">Instansi Mitra</h3>
                        <p class="text-sm text-slate-500 mb-3">Fitur Instansi Mitra</p>
                        <div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $instansiMitraEnabled ? 'bg-slate-800 text-slate-100' : 'bg-slate-100 text-slate-500' }}">
                                {{ $instansiMitraEnabled ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                    </div>

                    <!-- Tab Pemberkasan Instansi Mitra Toggle -->
                    <div class="group bg-white border border-slate-200 rounded-xl p-5 hover:border-slate-400 hover:shadow-md transition-all duration-300">
                        <div class="flex items-start justify-between mb-3">
                            <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center group-hover:bg-slate-200 transition-colors">
                                <i class="fas fa-folder-open text-slate-700"></i>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="dokumen_pemberkasan_enabled" value="1" class="sr-only peer" {{ $dokumenPemberkasanEnabled ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-slate-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-slate-700"></div>
                            </label>
                        </div>
                        <h3 class="text-base font-bold text-slate-800 mb-1">Tab Pemberkasan Mitra</h3>
                        <p class="text-sm text-slate-500 mb-3">Akses Tab Pemberkasan Mitra</p>
                        <div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $dokumenPemberkasanEnabled ? 'bg-slate-800 text-slate-100' : 'bg-slate-100 text-slate-500' }}">
                                {{ $dokumenPemberkasanEnabled ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                    </div>

                    <!-- Registration Toggle -->
                    <div class="group bg-white border border-slate-200 rounded-xl p-5 hover:border-slate-400 hover:shadow-md transition-all duration-300">
                        <div class="flex items-start justify-between mb-3">
                            <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center group-hover:bg-slate-200 transition-colors">
                                <i class="fas fa-user-lock text-slate-700"></i>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="registration_enabled" value="1" class="sr-only peer" {{ $registrationEnabled ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-slate-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-slate-700"></div>
                            </label>
                        </div>
                        <h3 class="text-base font-bold text-slate-800 mb-1">Tutup Pendaftaran</h3>
                        <p class="text-sm text-slate-500 mb-3">Kontrol pendaftaran akun baru</p>
                        <div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $registrationEnabled ? 'bg-slate-800 text-slate-100' : 'bg-slate-100 text-slate-500' }}">
                                {{ $registrationEnabled ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end mt-6 pt-6 border-t border-slate-200">
                    <button type="submit" class="bg-slate-800 text-white px-6 py-2.5 rounded-xl hover:bg-slate-900 focus:outline-none focus:ring-4 focus:ring-slate-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 font-semibold text-sm flex items-center">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>

        <!-- Login Background Image Upload -->
        <div class="bg-white shadow-sm rounded-2xl border border-slate-200 overflow-hidden">
            <div class="bg-slate-50 px-6 py-5 border-b border-slate-200">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-slate-800 rounded-lg flex items-center justify-center mr-3 shadow-sm">
                        <i class="fas fa-image text-slate-200"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">Gambar Background Login</h2>
                        <p class="text-sm text-slate-500">Upload gambar background untuk halaman login</p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.system-settings.upload-login-bg') }}" enctype="multipart/form-data" class="px-6 py-4">
                @csrf

                <div class="mb-6">
                    <!-- Current Image Preview -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Preview Gambar Saat Ini</label>
                        @if(file_exists(public_path('images/auth/bg_login.jpg')))
                            <img src="{{ asset('images/auth/bg_login.jpg') }}?v={{ time() }}"
                                 alt="Current Login Background"
                                 class="w-full h-64 object-cover rounded-lg border border-slate-300 shadow-sm">
                        @else
                            <div class="w-full h-64 bg-slate-50 rounded-lg border border-slate-300 flex items-center justify-center border-dashed">
                                <div class="text-center">
                                    <i class="fas fa-image text-slate-300 text-4xl mb-2"></i>
                                    <p class="text-slate-500">Belum ada gambar background</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- File Upload -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Upload Gambar Baru</label>
                        <div class="relative">
                            <input type="file"
                                   name="login_bg_image"
                                   id="login_bg_image"
                                   accept="image/jpeg,image/jpg,image/png"
                                   class="block w-full text-sm text-slate-500
                                          file:mr-4 file:py-2.5 file:px-4
                                          file:rounded-lg file:border-0
                                          file:text-sm file:font-semibold
                                          file:bg-slate-100 file:text-slate-700
                                          hover:file:bg-slate-200
                                          cursor-pointer border border-slate-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-slate-500 focus:border-slate-500"
                                   onchange="previewImage(event)">
                        </div>
                        <p class="mt-2 text-xs text-slate-500">
                            Format yang didukung: JPG, JPEG, PNG. Maksimal ukuran file: 5MB. Resolusi rekomendasi: 1920x1080px
                        </p>
                        <p id="fileName" class="mt-2 text-sm font-medium text-green-600 hidden"></p>
                    </div>

                    <!-- Preview New Image -->
                    <div id="imagePreview" class="mt-4 hidden">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Preview Gambar Baru</label>
                        <img id="previewImg" src="" alt="Preview" class="w-full h-64 object-cover rounded-lg border border-slate-300 shadow-sm">
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-slate-800 text-white px-6 py-2.5 rounded-xl hover:bg-slate-900 focus:outline-none focus:ring-4 focus:ring-slate-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 font-semibold text-sm flex items-center">
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
document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const span = this.nextElementSibling.nextElementSibling;
        if (span) {
            span.textContent = this.checked ? 'Aktif' : 'Nonaktif';
            if (this.checked) {
                span.classList.remove('bg-slate-100', 'text-slate-500');
                span.classList.add('bg-slate-800', 'text-slate-100');
            } else {
                span.classList.remove('bg-slate-800', 'text-slate-100');
                span.classList.add('bg-slate-100', 'text-slate-500');
            }
        }
    });
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
