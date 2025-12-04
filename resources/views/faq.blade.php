<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - {{ config('app.name', 'SIP PKL') }}</title>
    <meta name="description" content="Frequently Asked Questions tentang Sistem Informasi Pengelolaan PKL Prodi Teknologi Informasi">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    @if(isset($fontConfig) && $fontConfig['url'])
        <link href="{{ $fontConfig['url'] }}" rel="stylesheet">
    @else
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @endif

    <style>
        * {
            -webkit-text-size-adjust: 100%;
            -moz-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            text-size-adjust: 100%;
        }
        
        body {
            font-family: {!! isset($fontConfig) ? $fontConfig['family'] : "'Inter', sans-serif" !!};
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        .accordion-content {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            max-height: 0;
            opacity: 0;
            overflow: hidden;
        }
        .accordion-content.active {
            opacity: 1;
            padding-top: 1rem;
            padding-bottom: 2rem;
        }
        .accordion-icon {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .accordion-btn[aria-expanded="true"] .accordion-icon {
            transform: rotate(180deg);
        }
        .accordion-btn[aria-expanded="true"] {
            background-color: #f8fafc; /* bg-slate-50 */
            color: #4f46e5; /* text-indigo-600 */
        }
        .accordion-btn:hover {
            background-color: #f8fafc;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased">
    @include('partials.public-header')

    <!-- Main Content -->
    <main class="max-w-5xl mx-auto py-12 px-4 sm:px-6 lg:px-8 pt-28 sm:pt-32">
        <!-- Page Title -->
        <div class="text-center mb-12 sm:mb-20">
            <span class="inline-block py-1 px-3 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold tracking-wide uppercase mb-4 shadow-sm">
                Pusat Bantuan
            </span>
            <h1 class="text-3xl sm:text-5xl font-extrabold text-gray-900 mb-4 sm:mb-6 tracking-tight">
                Frequently Asked Questions
            </h1>
            <p class="text-base sm:text-xl text-gray-500 max-w-2xl mx-auto leading-relaxed">
                Temukan jawaban lengkap seputar prosedur dan penggunaan Sistem Informasi Pengelolaan PKL di Prodi Teknologi Informasi.
            </p>
        </div>

        <!-- FAQ Sections -->
        <div class="space-y-12 sm:space-y-16">
            
            <!-- Section 1: Umum -->
            <section>
                <div class="flex items-center mb-6 sm:mb-8">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-indigo-100 flex items-center justify-center mr-3 sm:mr-4 text-indigo-600">
                        <i class="fas fa-info text-base sm:text-lg"></i>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Informasi Umum</h2>
                </div>
                <div class="space-y-3 sm:space-y-4">
                    <!-- Item -->
                    <div class="bg-white border border-gray-200 rounded-xl sm:rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">
                        <button class="accordion-btn w-full flex justify-between items-center p-4 sm:p-6 text-left focus:outline-none transition-colors duration-200" aria-expanded="false">
                            <span class="font-semibold text-base sm:text-lg">Apa fungsi utama SIP PKL?</span>
                            <i class="fas fa-chevron-down accordion-icon text-gray-400 text-sm sm:text-base"></i>
                        </button>
                        <div class="accordion-content bg-white px-4 sm:px-8 text-gray-600 leading-relaxed text-sm sm:text-lg">
                            SIP PKL berfungsi sebagai platform terpusat untuk memanajemen seluruh siklus Praktik Kerja Lapangan, mulai dari pendaftaran, validasi berkas, monitoring kegiatan harian (logbook), hingga penilaian akhir dan pencetakan sertifikat. Sistem ini mengintegrasikan mahasiswa, dosen pembimbing, mitra industri, dan koordinator PKL.
                        </div>
                    </div>
                    <!-- Item -->
                    <div class="bg-white border border-gray-200 rounded-xl sm:rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">
                        <button class="accordion-btn w-full flex justify-between items-center p-4 sm:p-6 text-left focus:outline-none transition-colors duration-200" aria-expanded="false">
                            <span class="font-semibold text-base sm:text-lg">Kapan periode pendaftaran PKL dibuka?</span>
                            <i class="fas fa-chevron-down accordion-icon text-gray-400 text-sm sm:text-base"></i>
                        </button>
                        <div class="accordion-content bg-white px-4 sm:px-8 text-gray-600 leading-relaxed text-sm sm:text-lg">
                            Periode pendaftaran PKL biasanya dibuka pada awal semester genap (semester 6). Jadwal detail akan diumumkan melalui dashboard sistem dan papan pengumuman jurusan. Pastikan Anda memantau notifikasi di akun SIP PKL Anda secara berkala.
                        </div>
                    </div>
                </div>
            </section>

            <!-- Section 2: Pendaftaran & Administrasi -->
            <section>
                <div class="flex items-center mb-6 sm:mb-8">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-indigo-100 flex items-center justify-center mr-3 sm:mr-4 text-indigo-600">
                        <i class="fas fa-file-alt text-base sm:text-lg"></i>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Pendaftaran & Administrasi</h2>
                </div>
                <div class="space-y-3 sm:space-y-4">
                    <!-- Item -->
                    <div class="bg-white border border-gray-200 rounded-xl sm:rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">
                        <button class="accordion-btn w-full flex justify-between items-center p-4 sm:p-6 text-left focus:outline-none transition-colors duration-200" aria-expanded="false">
                            <span class="font-semibold text-base sm:text-lg">Apa saja syarat dokumen untuk mendaftar?</span>
                            <i class="fas fa-chevron-down accordion-icon text-gray-400 text-sm sm:text-base"></i>
                        </button>
                        <div class="accordion-content bg-white px-4 sm:px-8 text-gray-600 leading-relaxed text-sm sm:text-lg">
                            <ul class="list-disc pl-5 space-y-2">
                                <li>Scan Kartu Hasil Studi (KHS) Semester 1 s.d. 4 (PDF).</li>
                                <li>Bukti Lunas SPP Semester berjalan.</li>
                                <li>Sertifikat kegiatan pendukung (jika ada).</li>
                                <li>Transkrip nilai sementara yang telah divalidasi Dosen Wali.</li>
                            </ul>
                        </div>
                    </div>
                    <!-- Item -->
                    <div class="bg-white border border-gray-200 rounded-xl sm:rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">
                        <button class="accordion-btn w-full flex justify-between items-center p-4 sm:p-6 text-left focus:outline-none transition-colors duration-200" aria-expanded="false">
                            <span class="font-semibold text-base sm:text-lg">Bagaimana jika saya belum mendapatkan tempat PKL?</span>
                            <i class="fas fa-chevron-down accordion-icon text-gray-400 text-sm sm:text-base"></i>
                        </button>
                        <div class="accordion-content bg-white px-4 sm:px-8 text-gray-600 leading-relaxed text-sm sm:text-lg">
                            Anda dapat memanfaatkan fitur <strong>Rekomendasi Mitra</strong> di sistem ini. Sistem menggunakan metode SAW untuk menyarankan mitra yang sesuai dengan profil dan preferensi Anda. Selain itu, Anda juga dapat berkonsultasi dengan Koordinator PKL untuk mendapatkan referensi mitra yang masih membuka lowongan.
                        </div>
                    </div>
                     <!-- Item -->
                     <div class="bg-white border border-gray-200 rounded-xl sm:rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">
                        <button class="accordion-btn w-full flex justify-between items-center p-4 sm:p-6 text-left focus:outline-none transition-colors duration-200" aria-expanded="false">
                            <span class="font-semibold text-base sm:text-lg">Berapa lama proses validasi surat pengantar?</span>
                            <i class="fas fa-chevron-down accordion-icon text-gray-400 text-sm sm:text-base"></i>
                        </button>
                        <div class="accordion-content bg-white px-4 sm:px-8 text-gray-600 leading-relaxed text-sm sm:text-lg">
                            Proses validasi dan penerbitan surat pengantar oleh admin jurusan memakan waktu maksimal <strong>3 hari kerja</strong> setelah dokumen diunggah lengkap. Anda akan menerima notifikasi email jika surat sudah siap diunduh.
                        </div>
                    </div>
                </div>
            </section>

            <!-- Section 3: Pelaksanaan PKL -->
            <section>
                <div class="flex items-center mb-6 sm:mb-8">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-indigo-100 flex items-center justify-center mr-3 sm:mr-4 text-indigo-600">
                        <i class="fas fa-briefcase text-base sm:text-lg"></i>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Pelaksanaan PKL</h2>
                </div>
                <div class="space-y-3 sm:space-y-4">
                    <!-- Item -->
                    <div class="bg-white border border-gray-200 rounded-xl sm:rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">
                        <button class="accordion-btn w-full flex justify-between items-center p-4 sm:p-6 text-left focus:outline-none transition-colors duration-200" aria-expanded="false">
                            <span class="font-semibold text-base sm:text-lg">Bagaimana cara mengisi Logbook Harian?</span>
                            <i class="fas fa-chevron-down accordion-icon text-gray-400 text-sm sm:text-base"></i>
                        </button>
                        <div class="accordion-content bg-white px-4 sm:px-8 text-gray-600 leading-relaxed text-sm sm:text-lg">
                            Login ke akun mahasiswa, pilih menu <strong>Logbook</strong>, lalu klik "Tambah Kegiatan". Isi tanggal, deskripsi kegiatan, dan unggah foto dokumentasi kegiatan. Logbook wajib diisi setiap hari kerja dan akan dipantau oleh Dosen Pembimbing.
                        </div>
                    </div>
                    <!-- Item -->
                    <div class="bg-white border border-gray-200 rounded-xl sm:rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">
                        <button class="accordion-btn w-full flex justify-between items-center p-4 sm:p-6 text-left focus:outline-none transition-colors duration-200" aria-expanded="false">
                            <span class="font-semibold text-base sm:text-lg">Apakah Dosen Pembimbing akan melakukan kunjungan?</span>
                            <i class="fas fa-chevron-down accordion-icon text-gray-400 text-sm sm:text-base"></i>
                        </button>
                        <div class="accordion-content bg-white px-4 sm:px-8 text-gray-600 leading-relaxed text-sm sm:text-lg">
                            Ya, Dosen Pembimbing dijadwalkan melakukan monitoring dan evaluasi (monev) minimal 1 kali selama periode PKL, baik secara langsung (kunjungan ke lokasi) maupun daring (video conference), tergantung lokasi dan kebijakan prodi.
                        </div>
                    </div>
                </div>
            </section>

            <!-- Section 4: Pasca PKL & Penilaian -->
            <section>
                <div class="flex items-center mb-6 sm:mb-8">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-indigo-100 flex items-center justify-center mr-3 sm:mr-4 text-indigo-600">
                        <i class="fas fa-graduation-cap text-base sm:text-lg"></i>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Pasca PKL & Penilaian</h2>
                </div>
                <div class="space-y-3 sm:space-y-4">
                    <!-- Item -->
                    <div class="bg-white border border-gray-200 rounded-xl sm:rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">
                        <button class="accordion-btn w-full flex justify-between items-center p-4 sm:p-6 text-left focus:outline-none transition-colors duration-200" aria-expanded="false">
                            <span class="font-semibold text-base sm:text-lg">Apa saja komponen penilaian PKL?</span>
                            <i class="fas fa-chevron-down accordion-icon text-gray-400 text-sm sm:text-base"></i>
                        </button>
                        <div class="accordion-content bg-white px-4 sm:px-8 text-gray-600 leading-relaxed text-sm sm:text-lg">
                            Nilai akhir PKL terdiri dari gabungan:
                            <ul class="list-disc pl-5 mt-2 space-y-1">
                                <li>Nilai Pembimbing Lapangan (Mitra): 40%</li>
                                <li>Nilai Dosen Pembimbing (Laporan & Bimbingan): 30%</li>
                                <li>Nilai Ujian Seminar PKL: 30%</li>
                            </ul>
                        </div>
                    </div>
                    <!-- Item -->
                    <div class="bg-white border border-gray-200 rounded-xl sm:rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">
                        <button class="accordion-btn w-full flex justify-between items-center p-4 sm:p-6 text-left focus:outline-none transition-colors duration-200" aria-expanded="false">
                            <span class="font-semibold text-base sm:text-lg">Kapan batas akhir pengumpulan Laporan PKL?</span>
                            <i class="fas fa-chevron-down accordion-icon text-gray-400 text-sm sm:text-base"></i>
                        </button>
                        <div class="accordion-content bg-white px-4 sm:px-8 text-gray-600 leading-relaxed text-sm sm:text-lg">
                            Laporan PKL final (yang sudah direvisi setelah seminar) wajib diunggah ke sistem paling lambat <strong>2 minggu</strong> setelah pelaksanaan seminar PKL. Keterlambatan dapat mempengaruhi nilai akhir atau penundaan kelulusan mata kuliah PKL.
                        </div>
                    </div>
                </div>
            </section>

             <!-- Section 5: Masalah Teknis -->
             <section>
                <div class="flex items-center mb-6 sm:mb-8">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-indigo-100 flex items-center justify-center mr-3 sm:mr-4 text-indigo-600">
                        <i class="fas fa-tools text-base sm:text-lg"></i>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Masalah Teknis</h2>
                </div>
                <div class="space-y-3 sm:space-y-4">
                    <!-- Item -->
                    <div class="bg-white border border-gray-200 rounded-xl sm:rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">
                        <button class="accordion-btn w-full flex justify-between items-center p-4 sm:p-6 text-left focus:outline-none transition-colors duration-200" aria-expanded="false">
                            <span class="font-semibold text-base sm:text-lg">Saya tidak bisa login, apa yang harus dilakukan?</span>
                            <i class="fas fa-chevron-down accordion-icon text-gray-400 text-sm sm:text-base"></i>
                        </button>
                        <div class="accordion-content bg-white px-4 sm:px-8 text-gray-600 leading-relaxed text-sm sm:text-lg">
                            Pastikan NIM dan password yang Anda masukkan benar. Jika Anda lupa password, gunakan fitur "Lupa Password" atau hubungi Admin Prodi melalui kontak yang tersedia di bawah. Jika akun terkunci, tunggu 15 menit sebelum mencoba lagi.
                        </div>
                    </div>
                    <!-- Item -->
                    <div class="bg-white border border-gray-200 rounded-xl sm:rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">
                        <button class="accordion-btn w-full flex justify-between items-center p-4 sm:p-6 text-left focus:outline-none transition-colors duration-200" aria-expanded="false">
                            <span class="font-semibold text-base sm:text-lg">Format file apa yang didukung untuk upload dokumen?</span>
                            <i class="fas fa-chevron-down accordion-icon text-gray-400 text-sm sm:text-base"></i>
                        </button>
                        <div class="accordion-content bg-white px-4 sm:px-8 text-gray-600 leading-relaxed text-sm sm:text-lg">
                            Untuk dokumen administrasi (KHS, Surat, Laporan), sistem hanya menerima format <strong>PDF</strong> dengan ukuran maksimal 2MB per file. Untuk foto kegiatan logbook, gunakan format JPG/PNG maksimal 1MB.
                        </div>
                    </div>
                </div>
            </section>

        </div>

        <!-- CTA Section (Only for Guests) -->
        @guest
        <div class="mt-16 sm:mt-20 bg-white border border-gray-200 rounded-2xl sm:rounded-3xl p-8 sm:p-12 text-center shadow-lg">
            <div class="max-w-3xl mx-auto">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-4">Ayo Daftar Sekarang!</h2>
                <p class="text-gray-600 mb-8 text-base sm:text-lg leading-relaxed">
                    Bergabunglah dengan sistem pengelolaan PKL yang modern dan terintegrasi. Permudah proses administrasi dan fokus pada pengalaman belajar Anda.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-semibold rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition-all duration-200 shadow-sm hover:shadow-md">
                        <i class="fas fa-user-plus mr-2"></i> Daftar Akun
                    </a>
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-semibold rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200">
                        <i class="fas fa-sign-in-alt mr-2"></i> Login
                    </a>
                </div>
            </div>
        </div>
        @endguest
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-24 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center">
            <div class="mb-6 md:mb-0 text-center md:text-left">
                <p class="text-base text-gray-500 font-medium">
                    © {{ date('Y') }} SIP PKL
                </p>
                <p class="text-sm text-gray-400 mt-1">
                    Prodi Teknologi Informasi, Politeknik Negeri Tanah Laut
                </p>
            </div>
            <div class="flex space-x-8">
                <a href="{{ url('/') }}" class="text-gray-400 hover:text-indigo-600 transition-colors font-medium">
                    Beranda
                </a>
                <a href="{{ route('login') }}" class="text-gray-400 hover:text-indigo-600 transition-colors font-medium">
                    Login Sistem
                </a>
                <a href="#" class="text-gray-400 hover:text-indigo-600 transition-colors font-medium">
                    Panduan Pengguna
                </a>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const accordions = document.querySelectorAll('.accordion-btn');

            accordions.forEach(acc => {
                acc.addEventListener('click', function() {
                    const content = this.nextElementSibling;
                    const isExpanded = this.getAttribute('aria-expanded') === 'true';

                    // Close all other accordions
                    accordions.forEach(otherAcc => {
                        if (otherAcc !== this) {
                            otherAcc.setAttribute('aria-expanded', 'false');
                            otherAcc.nextElementSibling.style.maxHeight = null;
                            otherAcc.nextElementSibling.classList.remove('active');
                        }
                    });

                    // Toggle current accordion
                    this.setAttribute('aria-expanded', !isExpanded);
                    
                    if (!isExpanded) {
                        content.classList.add('active');
                        content.style.maxHeight = content.scrollHeight + 50 + "px"; // Add extra buffer for padding
                    } else {
                        content.classList.remove('active');
                        content.style.maxHeight = null;
                    }
                });
            });
        });
    </script>
</body>
</html>
