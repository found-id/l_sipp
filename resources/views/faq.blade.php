<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - SIP PKL</title>
    <meta name="description" content="Frequently Asked Questions tentang Sistem Informasi Pengelolaan PKL">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }

        .faq-answer.active {
            max-height: 2000px;
            transition: max-height 0.5s ease-in;
        }

        .faq-icon {
            transition: transform 0.3s ease;
        }

        .faq-icon.active {
            transform: rotate(180deg);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <i class="fas fa-graduation-cap text-2xl text-indigo-600 mr-3"></i>
                    <span class="text-xl font-bold text-gray-900">SIP PKL</span>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-indigo-600 transition-colors">
                        <i class="fas fa-sign-in-alt mr-1"></i>
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition-colors">
                        <i class="fas fa-user-plus mr-1"></i>
                        Daftar
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-5xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <!-- Page Title -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-3">
                Frequently Asked Questions
            </h1>
            <p class="text-lg text-gray-600">
                Temukan jawaban untuk pertanyaan Anda tentang Sistem Informasi Pengelolaan PKL
            </p>
        </div>

        <!-- FAQ Sections -->
        <div class="space-y-10">
            <!-- Pertanyaan Umum -->
            <div>
                <div class="flex items-center mb-5">
                    <div class="bg-indigo-100 rounded-full p-2 mr-3">
                        <i class="fas fa-info-circle text-indigo-600 text-lg"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Pertanyaan Umum</h2>
                </div>
                
                <div class="space-y-3">
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <button class="faq-question w-full text-left p-5 hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-center">
                                <h3 class="font-semibold text-gray-900 pr-4">Apa itu SIP PKL?</h3>
                                <i class="fas fa-chevron-down faq-icon text-indigo-600 flex-shrink-0"></i>
                            </div>
                        </button>
                        <div class="faq-answer bg-gray-50">
                            <div class="p-5 pt-0">
                                <p class="text-gray-700 leading-relaxed">
                                    SIP PKL (Sistem Informasi Pengelolaan Praktik Kerja Lapangan) adalah platform digital yang dirancang untuk memudahkan mahasiswa, dosen pembimbing, dan admin dalam mengelola seluruh proses PKL secara terintegrasi.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <button class="faq-question w-full text-left p-5 hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-center">
                                <h3 class="font-semibold text-gray-900 pr-4">Siapa saja yang dapat menggunakan sistem ini?</h3>
                                <i class="fas fa-chevron-down faq-icon text-indigo-600 flex-shrink-0"></i>
                            </div>
                        </button>
                        <div class="faq-answer bg-gray-50">
                            <div class="p-5 pt-0">
                                <p class="text-gray-700 leading-relaxed mb-3">Sistem ini dapat digunakan oleh:</p>
                                <div class="space-y-2">
                                    <div class="flex items-start">
                                        <i class="fas fa-check-circle text-indigo-600 mt-1 mr-2"></i>
                                        <div>
                                            <strong class="text-gray-900">Mahasiswa:</strong>
                                            <span class="text-gray-700"> Mengelola dokumen PKL dan melihat hasil penilaian</span>
                                        </div>
                                    </div>
                                    <div class="flex items-start">
                                        <i class="fas fa-check-circle text-indigo-600 mt-1 mr-2"></i>
                                        <div>
                                            <strong class="text-gray-900">Dosen Pembimbing:</strong>
                                            <span class="text-gray-700"> Validasi dokumen dan penilaian mahasiswa</span>
                                        </div>
                                    </div>
                                    <div class="flex items-start">
                                        <i class="fas fa-check-circle text-indigo-600 mt-1 mr-2"></i>
                                        <div>
                                            <strong class="text-gray-900">Admin:</strong>
                                            <span class="text-gray-700"> Mengelola data mahasiswa, dosen, dan mitra</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <button class="faq-question w-full text-left p-5 hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-center">
                                <h3 class="font-semibold text-gray-900 pr-4">Bagaimana cara mendaftar akun?</h3>
                                <i class="fas fa-chevron-down faq-icon text-indigo-600 flex-shrink-0"></i>
                            </div>
                        </button>
                        <div class="faq-answer bg-gray-50">
                            <div class="p-5 pt-0">
                                <p class="text-gray-700 leading-relaxed mb-3">Anda dapat mendaftar dengan dua cara:</p>
                                <div class="space-y-2">
                                    <div class="flex items-start">
                                        <span class="bg-indigo-100 text-indigo-700 font-semibold rounded-full w-6 h-6 flex items-center justify-center text-sm mr-2 flex-shrink-0">1</span>
                                        <div>
                                            <strong class="text-gray-900">Registrasi Manual:</strong>
                                            <span class="text-gray-700"> Klik "Daftar", isi formulir, dan lengkapi biodata</span>
                                        </div>
                                    </div>
                                    <div class="flex items-start">
                                        <span class="bg-indigo-100 text-indigo-700 font-semibold rounded-full w-6 h-6 flex items-center justify-center text-sm mr-2 flex-shrink-0">2</span>
                                        <div>
                                            <strong class="text-gray-900">Login dengan Google:</strong>
                                            <span class="text-gray-700"> Gunakan akun Google untuk registrasi cepat</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Untuk Mahasiswa -->
            <div>
                <div class="flex items-center mb-5">
                    <div class="bg-indigo-100 rounded-full p-2 mr-3">
                        <i class="fas fa-user-graduate text-indigo-600 text-lg"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Untuk Mahasiswa</h2>
                </div>
                
                <div class="space-y-3">
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <button class="faq-question w-full text-left p-5 hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-center">
                                <h3 class="font-semibold text-gray-900 pr-4">Apa saja tahapan dalam proses PKL?</h3>
                                <i class="fas fa-chevron-down faq-icon text-indigo-600 flex-shrink-0"></i>
                            </div>
                        </button>
                        <div class="faq-answer bg-gray-50">
                            <div class="p-5 pt-0">
                                <p class="text-gray-700 leading-relaxed mb-3">Proses PKL terdiri dari beberapa tahapan:</p>
                                <div class="space-y-2">
                                    <div class="flex items-start">
                                        <span class="bg-indigo-100 text-indigo-700 font-semibold rounded-full w-6 h-6 flex items-center justify-center text-sm mr-2 flex-shrink-0">1</span>
                                        <div><strong class="text-gray-900">Cek Kelayakan:</strong> <span class="text-gray-700">Upload KHS semester 1-4 dan dokumen pendukung</span></div>
                                    </div>
                                    <div class="flex items-start">
                                        <span class="bg-indigo-100 text-indigo-700 font-semibold rounded-full w-6 h-6 flex items-center justify-center text-sm mr-2 flex-shrink-0">2</span>
                                        <div><strong class="text-gray-900">Pemilihan Mitra:</strong> <span class="text-gray-700">Pilih instansi mitra PKL menggunakan metode SAW</span></div>
                                    </div>
                                    <div class="flex items-start">
                                        <span class="bg-indigo-100 text-indigo-700 font-semibold rounded-full w-6 h-6 flex items-center justify-center text-sm mr-2 flex-shrink-0">3</span>
                                        <div><strong class="text-gray-900">Upload Surat Pengantar:</strong> <span class="text-gray-700">Upload surat pengantar dari kampus</span></div>
                                    </div>
                                    <div class="flex items-start">
                                        <span class="bg-indigo-100 text-indigo-700 font-semibold rounded-full w-6 h-6 flex items-center justify-center text-sm mr-2 flex-shrink-0">4</span>
                                        <div><strong class="text-gray-900">Upload Surat Balasan:</strong> <span class="text-gray-700">Upload surat balasan dari mitra</span></div>
                                    </div>
                                    <div class="flex items-start">
                                        <span class="bg-indigo-100 text-indigo-700 font-semibold rounded-full w-6 h-6 flex items-center justify-center text-sm mr-2 flex-shrink-0">5</span>
                                        <div><strong class="text-gray-900">Pelaksanaan PKL:</strong> <span class="text-gray-700">Lakukan PKL di instansi mitra</span></div>
                                    </div>
                                    <div class="flex items-start">
                                        <span class="bg-indigo-100 text-indigo-700 font-semibold rounded-full w-6 h-6 flex items-center justify-center text-sm mr-2 flex-shrink-0">6</span>
                                        <div><strong class="text-gray-900">Upload Laporan:</strong> <span class="text-gray-700">Upload laporan PKL setelah selesai</span></div>
                                    </div>
                                    <div class="flex items-start">
                                        <span class="bg-indigo-100 text-indigo-700 font-semibold rounded-full w-6 h-6 flex items-center justify-center text-sm mr-2 flex-shrink-0">7</span>
                                        <div><strong class="text-gray-900">Penilaian:</strong> <span class="text-gray-700">Dosen pembimbing melakukan penilaian</span></div>
                                    </div>
                                    <div class="flex items-start">
                                        <span class="bg-indigo-100 text-indigo-700 font-semibold rounded-full w-6 h-6 flex items-center justify-center text-sm mr-2 flex-shrink-0">8</span>
                                        <div><strong class="text-gray-900">Seminar PKL:</strong> <span class="text-gray-700">Mengikuti seminar sesuai jadwal</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <button class="faq-question w-full text-left p-5 hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-center">
                                <h3 class="font-semibold text-gray-900 pr-4">Dokumen apa saja yang perlu diunggah?</h3>
                                <i class="fas fa-chevron-down faq-icon text-indigo-600 flex-shrink-0"></i>
                            </div>
                        </button>
                        <div class="faq-answer bg-gray-50">
                            <div class="p-5 pt-0">
                                <p class="text-gray-700 leading-relaxed mb-3">Dokumen yang perlu diunggah:</p>
                                <div class="space-y-2">
                                    <div class="flex items-start">
                                        <i class="fas fa-file-pdf text-red-500 mt-1 mr-2"></i>
                                        <div><strong class="text-gray-900">KHS:</strong> <span class="text-gray-700">Semester 1-4 dalam format PDF</span></div>
                                    </div>
                                    <div class="flex items-start">
                                        <i class="fas fa-link text-blue-500 mt-1 mr-2"></i>
                                        <div><strong class="text-gray-900">Link PKKMB:</strong> <span class="text-gray-700">Link Google Drive dokumentasi PKKMB</span></div>
                                    </div>
                                    <div class="flex items-start">
                                        <i class="fas fa-link text-blue-500 mt-1 mr-2"></i>
                                        <div><strong class="text-gray-900">Link E-Course:</strong> <span class="text-gray-700">Link Google Drive sertifikat E-Course</span></div>
                                    </div>
                                    <div class="flex items-start">
                                        <i class="fas fa-file-pdf text-red-500 mt-1 mr-2"></i>
                                        <div><strong class="text-gray-900">Surat Pengantar:</strong> <span class="text-gray-700">Surat dari kampus (PDF)</span></div>
                                    </div>
                                    <div class="flex items-start">
                                        <i class="fas fa-file-pdf text-red-500 mt-1 mr-2"></i>
                                        <div><strong class="text-gray-900">Surat Balasan:</strong> <span class="text-gray-700">Surat penerimaan dari mitra (PDF)</span></div>
                                    </div>
                                    <div class="flex items-start">
                                        <i class="fas fa-file-pdf text-red-500 mt-1 mr-2"></i>
                                        <div><strong class="text-gray-900">Laporan PKL:</strong> <span class="text-gray-700">Laporan lengkap PKL (PDF)</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <button class="faq-question w-full text-left p-5 hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-center">
                                <h3 class="font-semibold text-gray-900 pr-4">Bagaimana cara memilih mitra PKL?</h3>
                                <i class="fas fa-chevron-down faq-icon text-indigo-600 flex-shrink-0"></i>
                            </div>
                        </button>
                        <div class="faq-answer bg-gray-50">
                            <div class="p-5 pt-0">
                                <p class="text-gray-700 leading-relaxed mb-3">
                                    Sistem menggunakan metode SAW (Simple Additive Weighting) untuk merekomendasikan mitra terbaik berdasarkan kriteria:
                                </p>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                    <div class="flex items-center">
                                        <i class="fas fa-map-marker-alt text-indigo-600 mr-2"></i>
                                        <span class="text-gray-700">Jarak lokasi</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-money-bill-wave text-indigo-600 mr-2"></i>
                                        <span class="text-gray-700">Ketersediaan honor</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-building text-indigo-600 mr-2"></i>
                                        <span class="text-gray-700">Fasilitas</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-graduation-cap text-indigo-600 mr-2"></i>
                                        <span class="text-gray-700">Kesesuaian jurusan</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-broom text-indigo-600 mr-2"></i>
                                        <span class="text-gray-700">Tingkat kebersihan</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <button class="faq-question w-full text-left p-5 hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-center">
                                <h3 class="font-semibold text-gray-900 pr-4">Bagaimana cara melihat hasil penilaian?</h3>
                                <i class="fas fa-chevron-down faq-icon text-indigo-600 flex-shrink-0"></i>
                            </div>
                        </button>
                        <div class="faq-answer bg-gray-50">
                            <div class="p-5 pt-0">
                                <p class="text-gray-700 leading-relaxed">
                                    Hasil penilaian dapat dilihat melalui menu <strong>"Hasil Penilaian"</strong> di dashboard mahasiswa setelah dosen pembimbing melakukan penilaian.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <button class="faq-question w-full text-left p-5 hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-center">
                                <h3 class="font-semibold text-gray-900 pr-4">Bagaimana cara melihat jadwal seminar PKL?</h3>
                                <i class="fas fa-chevron-down faq-icon text-indigo-600 flex-shrink-0"></i>
                            </div>
                        </button>
                        <div class="faq-answer bg-gray-50">
                            <div class="p-5 pt-0">
                                <p class="text-gray-700 leading-relaxed">
                                    Jadwal seminar dapat dilihat melalui menu <strong>"Jadwal Seminar"</strong> setelah login. Admin akan mengupload jadwal yang berisi informasi waktu, tempat, dan peserta.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Untuk Dosen Pembimbing -->
            <div>
                <div class="flex items-center mb-5">
                    <div class="bg-indigo-100 rounded-full p-2 mr-3">
                        <i class="fas fa-chalkboard-teacher text-indigo-600 text-lg"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Untuk Dosen Pembimbing</h2>
                </div>
                
                <div class="space-y-3">
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <button class="faq-question w-full text-left p-5 hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-center">
                                <h3 class="font-semibold text-gray-900 pr-4">Bagaimana cara melakukan validasi dokumen?</h3>
                                <i class="fas fa-chevron-down faq-icon text-indigo-600 flex-shrink-0"></i>
                            </div>
                        </button>
                        <div class="faq-answer bg-gray-50">
                            <div class="p-5 pt-0">
                                <p class="text-gray-700 leading-relaxed mb-3">Proses validasi dilakukan melalui menu "Validasi":</p>
                                <div class="space-y-2">
                                    <div class="flex items-start">
                                        <span class="bg-indigo-100 text-indigo-700 font-semibold rounded-full w-6 h-6 flex items-center justify-center text-sm mr-2 flex-shrink-0">1</span>
                                        <span class="text-gray-700">Pilih mahasiswa bimbingan</span>
                                    </div>
                                    <div class="flex items-start">
                                        <span class="bg-indigo-100 text-indigo-700 font-semibold rounded-full w-6 h-6 flex items-center justify-center text-sm mr-2 flex-shrink-0">2</span>
                                        <span class="text-gray-700">Lihat detail dokumen yang diupload</span>
                                    </div>
                                    <div class="flex items-start">
                                        <span class="bg-indigo-100 text-indigo-700 font-semibold rounded-full w-6 h-6 flex items-center justify-center text-sm mr-2 flex-shrink-0">3</span>
                                        <span class="text-gray-700">Validasi setiap kategori (Kelayakan, Dokumen Pendukung, Instansi Mitra, Akhir)</span>
                                    </div>
                                    <div class="flex items-start">
                                        <span class="bg-indigo-100 text-indigo-700 font-semibold rounded-full w-6 h-6 flex items-center justify-center text-sm mr-2 flex-shrink-0">4</span>
                                        <span class="text-gray-700">Berikan catatan jika ada revisi</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <button class="faq-question w-full text-left p-5 hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-center">
                                <h3 class="font-semibold text-gray-900 pr-4">Bagaimana cara melakukan penilaian mahasiswa?</h3>
                                <i class="fas fa-chevron-down faq-icon text-indigo-600 flex-shrink-0"></i>
                            </div>
                        </button>
                        <div class="faq-answer bg-gray-50">
                            <div class="p-5 pt-0">
                                <div class="space-y-2">
                                    <div class="flex items-start">
                                        <span class="bg-indigo-100 text-indigo-700 font-semibold rounded-full w-6 h-6 flex items-center justify-center text-sm mr-2 flex-shrink-0">1</span>
                                        <span class="text-gray-700">Login sebagai dosen pembimbing</span>
                                    </div>
                                    <div class="flex items-start">
                                        <span class="bg-indigo-100 text-indigo-700 font-semibold rounded-full w-6 h-6 flex items-center justify-center text-sm mr-2 flex-shrink-0">2</span>
                                        <span class="text-gray-700">Pilih menu "Penilaian"</span>
                                    </div>
                                    <div class="flex items-start">
                                        <span class="bg-indigo-100 text-indigo-700 font-semibold rounded-full w-6 h-6 flex items-center justify-center text-sm mr-2 flex-shrink-0">3</span>
                                        <span class="text-gray-700">Pilih mahasiswa yang akan dinilai</span>
                                    </div>
                                    <div class="flex items-start">
                                        <span class="bg-indigo-100 text-indigo-700 font-semibold rounded-full w-6 h-6 flex items-center justify-center text-sm mr-2 flex-shrink-0">4</span>
                                        <span class="text-gray-700">Isi form penilaian sesuai rubrik</span>
                                    </div>
                                    <div class="flex items-start">
                                        <span class="bg-indigo-100 text-indigo-700 font-semibold rounded-full w-6 h-6 flex items-center justify-center text-sm mr-2 flex-shrink-0">5</span>
                                        <span class="text-gray-700">Submit penilaian</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <button class="faq-question w-full text-left p-5 hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-center">
                                <h3 class="font-semibold text-gray-900 pr-4">Apakah bisa mengubah penilaian yang sudah disubmit?</h3>
                                <i class="fas fa-chevron-down faq-icon text-indigo-600 flex-shrink-0"></i>
                            </div>
                        </button>
                        <div class="faq-answer bg-gray-50">
                            <div class="p-5 pt-0">
                                <p class="text-gray-700 leading-relaxed">
                                    Penilaian yang sudah disubmit tidak dapat diubah langsung. Hubungi admin sistem untuk perubahan penilaian.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Untuk Admin -->
            <div>
                <div class="flex items-center mb-5">
                    <div class="bg-indigo-100 rounded-full p-2 mr-3">
                        <i class="fas fa-user-shield text-indigo-600 text-lg"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Untuk Admin</h2>
                </div>
                
                <div class="space-y-3">
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <button class="faq-question w-full text-left p-5 hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-center">
                                <h3 class="font-semibold text-gray-900 pr-4">Apa saja fitur yang tersedia untuk admin?</h3>
                                <i class="fas fa-chevron-down faq-icon text-indigo-600 flex-shrink-0"></i>
                            </div>
                        </button>
                        <div class="faq-answer bg-gray-50">
                            <div class="p-5 pt-0">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div class="flex items-start">
                                        <i class="fas fa-database text-indigo-600 mt-1 mr-2"></i>
                                        <div><strong class="text-gray-900">Kelola Data</strong><br><span class="text-sm text-gray-600">Mengelola data mahasiswa</span></div>
                                    </div>
                                    <div class="flex items-start">
                                        <i class="fas fa-users text-indigo-600 mt-1 mr-2"></i>
                                        <div><strong class="text-gray-900">Kelola Akun</strong><br><span class="text-sm text-gray-600">CRUD akun pengguna</span></div>
                                    </div>
                                    <div class="flex items-start">
                                        <i class="fas fa-handshake text-indigo-600 mt-1 mr-2"></i>
                                        <div><strong class="text-gray-900">Kelola Mitra</strong><br><span class="text-sm text-gray-600">Mengelola instansi mitra</span></div>
                                    </div>
                                    <div class="flex items-start">
                                        <i class="fas fa-check-circle text-indigo-600 mt-1 mr-2"></i>
                                        <div><strong class="text-gray-900">Validasi</strong><br><span class="text-sm text-gray-600">Validasi dokumen</span></div>
                                    </div>
                                    <div class="flex items-start">
                                        <i class="fas fa-calendar text-indigo-600 mt-1 mr-2"></i>
                                        <div><strong class="text-gray-900">Jadwal Seminar</strong><br><span class="text-sm text-gray-600">Kelola jadwal seminar</span></div>
                                    </div>
                                    <div class="flex items-start">
                                        <i class="fas fa-clipboard-list text-indigo-600 mt-1 mr-2"></i>
                                        <div><strong class="text-gray-900">Rubrik Penilaian</strong><br><span class="text-sm text-gray-600">Buat rubrik penilaian</span></div>
                                    </div>
                                    <div class="flex items-start">
                                        <i class="fas fa-cog text-indigo-600 mt-1 mr-2"></i>
                                        <div><strong class="text-gray-900">System Settings</strong><br><span class="text-sm text-gray-600">Konfigurasi sistem</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <button class="faq-question w-full text-left p-5 hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-center">
                                <h3 class="font-semibold text-gray-900 pr-4">Bagaimana cara menambahkan mitra PKL baru?</h3>
                                <i class="fas fa-chevron-down faq-icon text-indigo-600 flex-shrink-0"></i>
                            </div>
                        </button>
                        <div class="faq-answer bg-gray-50">
                            <div class="p-5 pt-0">
                                <div class="space-y-2">
                                    <div class="flex items-start">
                                        <span class="bg-indigo-100 text-indigo-700 font-semibold rounded-full w-6 h-6 flex items-center justify-center text-sm mr-2 flex-shrink-0">1</span>
                                        <span class="text-gray-700">Login sebagai admin</span>
                                    </div>
                                    <div class="flex items-start">
                                        <span class="bg-indigo-100 text-indigo-700 font-semibold rounded-full w-6 h-6 flex items-center justify-center text-sm mr-2 flex-shrink-0">2</span>
                                        <span class="text-gray-700">Pilih menu "Kelola Mitra"</span>
                                    </div>
                                    <div class="flex items-start">
                                        <span class="bg-indigo-100 text-indigo-700 font-semibold rounded-full w-6 h-6 flex items-center justify-center text-sm mr-2 flex-shrink-0">3</span>
                                        <span class="text-gray-700">Klik "Tambah Mitra"</span>
                                    </div>
                                    <div class="flex items-start">
                                        <span class="bg-indigo-100 text-indigo-700 font-semibold rounded-full w-6 h-6 flex items-center justify-center text-sm mr-2 flex-shrink-0">4</span>
                                        <span class="text-gray-700">Isi data mitra termasuk kriteria SAW</span>
                                    </div>
                                    <div class="flex items-start">
                                        <span class="bg-indigo-100 text-indigo-700 font-semibold rounded-full w-6 h-6 flex items-center justify-center text-sm mr-2 flex-shrink-0">5</span>
                                        <span class="text-gray-700">Simpan data</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <button class="faq-question w-full text-left p-5 hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-center">
                                <h3 class="font-semibold text-gray-900 pr-4">Bagaimana cara membuat rubrik penilaian?</h3>
                                <i class="fas fa-chevron-down faq-icon text-indigo-600 flex-shrink-0"></i>
                            </div>
                        </button>
                        <div class="faq-answer bg-gray-50">
                            <div class="p-5 pt-0">
                                <div class="space-y-2">
                                    <div class="flex items-start">
                                        <span class="bg-indigo-100 text-indigo-700 font-semibold rounded-full w-6 h-6 flex items-center justify-center text-sm mr-2 flex-shrink-0">1</span>
                                        <span class="text-gray-700">Masuk ke menu "Rubrik"</span>
                                    </div>
                                    <div class="flex items-start">
                                        <span class="bg-indigo-100 text-indigo-700 font-semibold rounded-full w-6 h-6 flex items-center justify-center text-sm mr-2 flex-shrink-0">2</span>
                                        <span class="text-gray-700">Klik "Buat Form Baru"</span>
                                    </div>
                                    <div class="flex items-start">
                                        <span class="bg-indigo-100 text-indigo-700 font-semibold rounded-full w-6 h-6 flex items-center justify-center text-sm mr-2 flex-shrink-0">3</span>
                                        <span class="text-gray-700">Beri nama form penilaian</span>
                                    </div>
                                    <div class="flex items-start">
                                        <span class="bg-indigo-100 text-indigo-700 font-semibold rounded-full w-6 h-6 flex items-center justify-center text-sm mr-2 flex-shrink-0">4</span>
                                        <span class="text-gray-700">Tambahkan item penilaian dengan bobot</span>
                                    </div>
                                    <div class="flex items-start">
                                        <span class="bg-indigo-100 text-indigo-700 font-semibold rounded-full w-6 h-6 flex items-center justify-center text-sm mr-2 flex-shrink-0">5</span>
                                        <span class="text-gray-700">Aktifkan form untuk digunakan dosen</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Masalah Teknis -->
            <div>
                <div class="flex items-center mb-5">
                    <div class="bg-indigo-100 rounded-full p-2 mr-3">
                        <i class="fas fa-cogs text-indigo-600 text-lg"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Masalah Teknis</h2>
                </div>
                
                <div class="space-y-3">
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <button class="faq-question w-full text-left p-5 hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-center">
                                <h3 class="font-semibold text-gray-900 pr-4">Website tidak bisa diakses atau lambat?</h3>
                                <i class="fas fa-chevron-down faq-icon text-indigo-600 flex-shrink-0"></i>
                            </div>
                        </button>
                        <div class="faq-answer bg-gray-50">
                            <div class="p-5 pt-0">
                                <div class="space-y-2">
                                    <div class="flex items-start">
                                        <i class="fas fa-wifi text-indigo-600 mt-1 mr-2"></i>
                                        <span class="text-gray-700">Pastikan koneksi internet stabil</span>
                                    </div>
                                    <div class="flex items-start">
                                        <i class="fas fa-trash-alt text-indigo-600 mt-1 mr-2"></i>
                                        <span class="text-gray-700">Clear cache dan cookies browser</span>
                                    </div>
                                    <div class="flex items-start">
                                        <i class="fas fa-browser text-indigo-600 mt-1 mr-2"></i>
                                        <span class="text-gray-700">Coba browser berbeda (Chrome, Firefox, Edge)</span>
                                    </div>
                                    <div class="flex items-start">
                                        <i class="fas fa-headset text-indigo-600 mt-1 mr-2"></i>
                                        <span class="text-gray-700">Jika masalah berlanjut, hubungi admin</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <button class="faq-question w-full text-left p-5 hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-center">
                                <h3 class="font-semibold text-gray-900 pr-4">File tidak bisa diunggah?</h3>
                                <i class="fas fa-chevron-down faq-icon text-indigo-600 flex-shrink-0"></i>
                            </div>
                        </button>
                        <div class="faq-answer bg-gray-50">
                            <div class="p-5 pt-0">
                                <div class="space-y-2">
                                    <div class="flex items-start">
                                        <i class="fas fa-file-pdf text-red-500 mt-1 mr-2"></i>
                                        <span class="text-gray-700">Format file sesuai ketentuan (PDF untuk dokumen)</span>
                                    </div>
                                    <div class="flex items-start">
                                        <i class="fas fa-weight text-indigo-600 mt-1 mr-2"></i>
                                        <span class="text-gray-700">Ukuran file tidak melebihi batas maksimal (5MB)</span>
                                    </div>
                                    <div class="flex items-start">
                                        <i class="fas fa-wifi text-indigo-600 mt-1 mr-2"></i>
                                        <span class="text-gray-700">Koneksi internet stabil</span>
                                    </div>
                                    <div class="flex items-start">
                                        <i class="fas fa-sync text-indigo-600 mt-1 mr-2"></i>
                                        <span class="text-gray-700">Browser sudah diupdate ke versi terbaru</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <button class="faq-question w-full text-left p-5 hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-center">
                                <h3 class="font-semibold text-gray-900 pr-4">Lupa password?</h3>
                                <i class="fas fa-chevron-down faq-icon text-indigo-600 flex-shrink-0"></i>
                            </div>
                        </button>
                        <div class="faq-answer bg-gray-50">
                            <div class="p-5 pt-0">
                                <p class="text-gray-700 leading-relaxed">
                                    Hubungi admin sistem melalui email atau telepon yang tertera di bagian kontak untuk reset password.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fitur Khusus -->
            <div>
                <div class="flex items-center mb-5">
                    <div class="bg-indigo-100 rounded-full p-2 mr-3">
                        <i class="fas fa-star text-indigo-600 text-lg"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Fitur Khusus</h2>
                </div>
                
                <div class="space-y-3">
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <button class="faq-question w-full text-left p-5 hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-center">
                                <h3 class="font-semibold text-gray-900 pr-4">Apa itu metode SAW dalam pemilihan mitra?</h3>
                                <i class="fas fa-chevron-down faq-icon text-indigo-600 flex-shrink-0"></i>
                            </div>
                        </button>
                        <div class="faq-answer bg-gray-50">
                            <div class="p-5 pt-0">
                                <p class="text-gray-700 leading-relaxed">
                                    SAW (Simple Additive Weighting) adalah metode pengambilan keputusan yang menghitung skor setiap mitra berdasarkan kriteria tertentu. Sistem akan menormalisasi nilai, mengalikan dengan bobot, dan mengurutkan mitra dari skor tertinggi.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <button class="faq-question w-full text-left p-5 hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-center">
                                <h3 class="font-semibold text-gray-900 pr-4">Apakah bisa login dengan Google?</h3>
                                <i class="fas fa-chevron-down faq-icon text-indigo-600 flex-shrink-0"></i>
                            </div>
                        </button>
                        <div class="faq-answer bg-gray-50">
                            <div class="p-5 pt-0">
                                <p class="text-gray-700 leading-relaxed">
                                    Ya, sistem mendukung login menggunakan Google OAuth. Setelah login dengan Google, lengkapi profil mahasiswa untuk menggunakan semua fitur.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <button class="faq-question w-full text-left p-5 hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-center">
                                <h3 class="font-semibold text-gray-900 pr-4">Apakah data saya aman?</h3>
                                <i class="fas fa-chevron-down faq-icon text-indigo-600 flex-shrink-0"></i>
                            </div>
                        </button>
                        <div class="faq-answer bg-gray-50">
                            <div class="p-5 pt-0">
                                <p class="text-gray-700 leading-relaxed mb-3">Keamanan data adalah prioritas kami:</p>
                                <div class="space-y-2">
                                    <div class="flex items-start">
                                        <i class="fas fa-lock text-green-600 mt-1 mr-2"></i>
                                        <span class="text-gray-700">Enkripsi password menggunakan bcrypt</span>
                                    </div>
                                    <div class="flex items-start">
                                        <i class="fas fa-shield-alt text-green-600 mt-1 mr-2"></i>
                                        <span class="text-gray-700">CSRF protection untuk semua form</span>
                                    </div>
                                    <div class="flex items-start">
                                        <i class="fas fa-user-lock text-green-600 mt-1 mr-2"></i>
                                        <span class="text-gray-700">Role-based access control (RBAC)</span>
                                    </div>
                                    <div class="flex items-start">
                                        <i class="fas fa-database text-green-600 mt-1 mr-2"></i>
                                        <span class="text-gray-700">Validasi input untuk mencegah SQL injection</span>
                                    </div>
                                    <div class="flex items-start">
                                        <i class="fas fa-history text-green-600 mt-1 mr-2"></i>
                                        <span class="text-gray-700">Activity logging untuk tracking perubahan</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Section -->
        <div class="mt-12 bg-white rounded-lg p-8 border border-gray-200">
            <h2 class="text-2xl font-bold text-gray-900 mb-4 text-center">Butuh Bantuan Lebih Lanjut?</h2>
            <p class="text-gray-600 text-center mb-6">
                Jika pertanyaan Anda tidak terjawab di FAQ ini, silakan hubungi tim support kami
            </p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex items-center justify-center p-4 bg-gray-50 rounded-lg">
                    <i class="fas fa-envelope text-indigo-600 text-2xl mr-3"></i>
                    <div>
                        <p class="font-semibold text-gray-900">Email Support</p>
                        <a href="mailto:support@sipp-pkl.ac.id" class="text-indigo-600 hover:underline">support@sipp-pkl.ac.id</a>
                    </div>
                </div>
                <div class="flex items-center justify-center p-4 bg-gray-50 rounded-lg">
                    <i class="fas fa-phone text-indigo-600 text-2xl mr-3"></i>
                    <div>
                        <p class="font-semibold text-gray-900">Telepon</p>
                        <a href="tel:+622112345678" class="text-indigo-600 hover:underline">(021) 1234-5678</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="mt-8 text-center bg-indigo-50 rounded-lg p-8 border border-indigo-100">
            <h3 class="text-2xl font-bold text-gray-900 mb-3">Siap Memulai?</h3>
            <p class="text-gray-600 mb-6">
                Daftar sekarang dan mulai kelola PKL Anda dengan lebih mudah
            </p>
            <div class="flex justify-center space-x-4">
                <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-md hover:bg-indigo-700 transition-colors font-medium">
                    <i class="fas fa-user-plus mr-2"></i>
                    Daftar Sekarang
                </a>
                <a href="{{ route('login') }}" class="bg-white text-indigo-600 border border-indigo-600 px-6 py-3 rounded-md hover:bg-indigo-50 transition-colors font-medium">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Login
                </a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="text-sm text-gray-500">
                    © {{ date('Y') }} SIP PKL. All rights reserved.
                </p>
                <div class="flex space-x-4 mt-2 md:mt-0">
                    <a href="{{ route('faq') }}" class="text-sm text-gray-500 hover:text-indigo-600 transition-colors">
                        <i class="fas fa-question-circle mr-1"></i>
                        FAQ
                    </a>
                    <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-indigo-600 transition-colors">
                        <i class="fas fa-home mr-1"></i>
                        Home
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // FAQ Toggle Functionality
        document.querySelectorAll('.faq-question').forEach(question => {
            question.addEventListener('click', function() {
                const answer = this.nextElementSibling;
                const icon = this.querySelector('.faq-icon');
                
                // Toggle current answer
                answer.classList.toggle('active');
                icon.classList.toggle('active');
            });
        });
    </script>
</body>
</html>
