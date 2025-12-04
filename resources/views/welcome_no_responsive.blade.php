<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <title>{{ config('app.name', 'SIP PKL') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    @if(isset($fontConfig) && $fontConfig['url'])
        <link href="{{ $fontConfig['url'] }}" rel="stylesheet">
    @else
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @endif

    <style>
        *, *::before, *::after {
            -webkit-text-size-adjust: none !important;
            -moz-text-size-adjust: none !important;
            -ms-text-size-adjust: none !important;
            text-size-adjust: none !important;
        }
        
        html {
            -webkit-text-size-adjust: none !important;
            touch-action: manipulation;
            font-size: 16px;
        }
        
        body {
            font-family: {!! isset($fontConfig) ? $fontConfig['family'] : "'Inter', sans-serif" !!};
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            touch-action: manipulation;
            font-size: 16px;
            min-width: 320px;
        }
        
        /* Force minimum font size on all text elements */
        p, span, div, a, button, input, select, textarea, label, li {
            font-size: 16px !important;
            -webkit-text-size-adjust: none !important;
        }
        
        /* Prevent zoom on input focus */
        input, select, textarea, button, a {
            font-size: 16px !important;
            touch-action: manipulation;
            -webkit-tap-highlight-color: transparent;
        }
        
        /* Prevent double-tap zoom */
        a, button, * {
            touch-action: manipulation;
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            user-select: none;
        }
        
        .hero-pattern {
            background-color: #ffffff;
            background-image: radial-gradient(#e5e7eb 1px, transparent 1px);
            background-size: 24px 24px;
        }
        
        /* Hero Text Blur Animation (Only for Hero) */
        .hero-blur {
            animation: blurIn 1s ease-out forwards;
            opacity: 0;
            filter: blur(10px);
            transform: scale(0.95);
        }
        
        @keyframes blurIn {
            to {
                opacity: 1;
                filter: blur(0);
                transform: scale(1);
            }
        }

        /* Bouncy Reveal Animation (No Blur) */
        .reveal {
            opacity: 0;
            transform: translateY(40px);
            transition: opacity 0.8s ease-out, transform 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        /* Staggered delays */
        .delay-100 { transition-delay: 0.1s; }
        .delay-200 { transition-delay: 0.2s; }
        .delay-300 { transition-delay: 0.3s; }

        /* Blob Animation */
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob {
            animation: blob 7s infinite;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        .animation-delay-4000 {
            animation-delay: 4s;
        }
    </style>
</head>
<body class="bg-white text-gray-900 antialiased overflow-x-hidden">

    @include('partials.public-header')

    <!-- Hero Section -->
    <section class="relative pt-28 pb-12 overflow-hidden hero-pattern">
        <div class="max-w-7xl mx-auto px-4 relative z-10 text-center">
            <div>
                <span class="inline-block py-1 px-3 rounded-full bg-indigo-100 text-indigo-700 text-sm font-bold tracking-wide uppercase mb-4 shadow-sm reveal">
                    Sistem Informasi Pengelolaan PKL
                </span>
                
                <!-- Hero Text with Blur Effect -->
                <h1 class="hero-blur text-3xl font-extrabold tracking-tight text-gray-900 mb-4 leading-tight">
                    Kelola PKL Anda <br class="hidden">
                    <span class="text-indigo-600">Lebih Mudah & Efisien</span>
                </h1>

                <p class="mt-4 max-w-2xl mx-auto text-lg text-gray-500 mb-2 px-4 reveal delay-100">
                    Prodi Teknologi Informasi, Politeknik Negeri Tanah Laut
                </p>
                <p class="mt-2 max-w-2xl mx-auto text-base text-gray-400 mb-8 px-4 reveal delay-200">
                    Platform terintegrasi untuk mahasiswa, dosen, dan mitra. Sederhanakan administrasi, fokus pada pengalaman belajar.
                </p>
                <div class="flex flex-col justify-center gap-4 px-4 reveal delay-300">
                    <a href="{{ route('login') }}" class="w-full px-6 py-3 border border-transparent text-base font-semibold rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition-all duration-200 shadow-sm hover:shadow-md flex items-center justify-center">
                        <i class="fas fa-sign-in-alt mr-2"></i> Mulai Sekarang
                    </a>
                    <a href="#about" class="w-full px-6 py-3 border border-gray-300 text-base font-semibold rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200 flex items-center justify-center">
                        Pelajari Lebih Lanjut
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Decorative blobs -->
        <div class="absolute top-0 left-0 -ml-20 -mt-20 w-60 h-60 bg-indigo-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-60 h-60 bg-blue-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-12 bg-white relative scroll-mt-28">
        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <div class="grid grid-cols-1 gap-8 items-center">
                <div class="reveal">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Tentang SIP PKL</h2>
                    <p class="text-base text-gray-600 mb-6 leading-relaxed">
                        Sistem Informasi Pengelolaan Praktik Kerja Lapangan (SIP PKL) adalah solusi digital yang dirancang khusus untuk Program Studi Teknologi Informasi Politeknik Negeri Tanah Laut.
                    </p>
                    <p class="text-base text-gray-500 mb-6 leading-relaxed">
                        Kami menghubungkan mahasiswa, dosen pembimbing, dan mitra industri dalam satu ekosistem terpadu. Tujuannya adalah untuk mempermudah proses administrasi, meningkatkan transparansi penilaian, dan memastikan kualitas pelaksanaan PKL yang optimal.
                    </p>
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <div class="flex-shrink-0 w-6 h-6 rounded-full bg-green-100 flex items-center justify-center mt-1">
                                <i class="fas fa-check text-green-600 text-xs"></i>
                            </div>
                            <span class="ml-3 text-base text-gray-600">Digitalisasi penuh dari pendaftaran hingga pelaporan.</span>
                        </li>
                        <li class="flex items-start">
                            <div class="flex-shrink-0 w-6 h-6 rounded-full bg-green-100 flex items-center justify-center mt-1">
                                <i class="fas fa-check text-green-600 text-xs"></i>
                            </div>
                            <span class="ml-3 text-base text-gray-600">Transparansi proses bimbingan dan penilaian.</span>
                        </li>
                        <li class="flex items-start">
                            <div class="flex-shrink-0 w-6 h-6 rounded-full bg-green-100 flex items-center justify-center mt-1">
                                <i class="fas fa-check text-green-600 text-xs"></i>
                            </div>
                            <span class="ml-3 text-base text-gray-600">Arsip data yang aman dan terstruktur.</span>
                        </li>
                    </ul>
                </div>
                <div class="relative reveal delay-200 mt-8">
                    <div class="absolute inset-0 bg-gradient-to-tr from-indigo-100 to-blue-50 rounded-3xl transform rotate-3 scale-105 opacity-50"></div>
                    <div class="bg-white p-6 rounded-3xl shadow-xl border border-gray-100 relative">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-4 rounded-2xl text-center">
                                <i class="fas fa-users text-2xl text-indigo-500 mb-2"></i>
                                <div class="font-bold text-sm text-gray-900">Mahasiswa</div>
                                <div class="text-xs text-gray-500">Kemudahan Akses</div>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-2xl text-center">
                                <i class="fas fa-chalkboard-teacher text-2xl text-blue-500 mb-2"></i>
                                <div class="font-bold text-sm text-gray-900">Dosen</div>
                                <div class="text-xs text-gray-500">Monitoring Efektif</div>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-2xl text-center">
                                <i class="fas fa-building text-2xl text-purple-500 mb-2"></i>
                                <div class="font-bold text-sm text-gray-900">Mitra</div>
                                <div class="text-xs text-gray-500">Kolaborasi Luas</div>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-2xl text-center">
                                <i class="fas fa-university text-2xl text-green-500 mb-2"></i>
                                <div class="font-bold text-sm text-gray-900">Prodi</div>
                                <div class="text-xs text-gray-500">Manajemen Terpusat</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Abstract Blob -->
        <div class="absolute bottom-0 right-0 -mb-20 -mr-20 w-64 h-64 bg-gray-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob animation-delay-4000"></div>
    </section>

    <!-- Stats Section -->
    <section id="stats" class="py-12 bg-gray-50 relative overflow-hidden scroll-mt-28">
        <div class="absolute top-0 left-1/2 transform -translate-x-1/2 w-full h-full overflow-hidden z-0">
            <div class="absolute top-1/4 left-1/4 w-40 h-40 bg-indigo-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
            <div class="absolute bottom-1/4 right-1/4 w-40 h-40 bg-blue-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <div class="text-center mb-12 reveal">
                <h2 class="text-2xl font-bold text-gray-900">Statistik Kami</h2>
                <p class="mt-4 text-lg text-gray-500">Pertumbuhan dan capaian dalam angka.</p>
            </div>

            <div class="grid grid-cols-2 gap-4 text-center">
                <div class="p-4 bg-white rounded-xl shadow-sm border border-gray-100 reveal delay-100 hover:-translate-y-1 transition-transform duration-300">
                    <div class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-blue-600 mb-2">500+</div>
                    <div class="text-base text-gray-600 font-medium">Mahasiswa Aktif</div>
                </div>
                <div class="p-4 bg-white rounded-xl shadow-sm border border-gray-100 reveal delay-200 hover:-translate-y-1 transition-transform duration-300">
                    <div class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-600 mb-2">50+</div>
                    <div class="text-base text-gray-600 font-medium">Mitra Industri</div>
                </div>
                <div class="p-4 bg-white rounded-xl shadow-sm border border-gray-100 reveal delay-300 hover:-translate-y-1 transition-transform duration-300">
                    <div class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-cyan-600 to-teal-600 mb-2">100%</div>
                    <div class="text-base text-gray-600 font-medium">Digital Process</div>
                </div>
                <div class="p-4 bg-white rounded-xl shadow-sm border border-gray-100 reveal delay-100 hover:-translate-y-1 transition-transform duration-300">
                    <div class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-teal-600 to-green-600 mb-2">24/7</div>
                    <div class="text-base text-gray-600 font-medium">Support System</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-12 bg-white scroll-mt-28 relative">
        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <div class="text-center mb-12 reveal">
                <h2 class="text-2xl font-bold text-gray-900">Fitur Unggulan</h2>
                <p class="mt-4 text-lg text-gray-500 max-w-2xl mx-auto">Semua yang Anda butuhkan untuk manajemen PKL yang sukses dalam satu platform.</p>
            </div>

            <div class="grid grid-cols-1 gap-6">
                <!-- Feature 1 -->
                <div class="group p-6 bg-white rounded-xl border border-gray-100 hover:border-indigo-100 hover:shadow-xl transition-all duration-300 reveal delay-100">
                    <div class="w-10 h-10 bg-indigo-50 rounded-2xl flex items-center justify-center mb-6 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300">
                        <i class="fas fa-laptop-code text-lg"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-3">Pemberkasan Digital</h3>
                    <p class="text-base text-gray-500 leading-relaxed">
                        Upload dan validasi dokumen KHS, surat pengantar, dan laporan secara online tanpa ribet kertas.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="group p-6 bg-white rounded-xl border border-gray-100 hover:border-indigo-100 hover:shadow-xl transition-all duration-300 reveal delay-200">
                    <div class="w-10 h-10 bg-blue-50 rounded-2xl flex items-center justify-center mb-6 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                        <i class="fas fa-chart-line text-lg"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-3">Monitoring Real-time</h3>
                    <p class="text-base text-gray-500 leading-relaxed">
                        Dosen dan admin dapat memantau progress mahasiswa secara langsung dan memberikan feedback cepat.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="group p-6 bg-white rounded-xl border border-gray-100 hover:border-indigo-100 hover:shadow-xl transition-all duration-300 reveal delay-300">
                    <div class="w-10 h-10 bg-purple-50 rounded-2xl flex items-center justify-center mb-6 text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-colors duration-300">
                        <i class="fas fa-star text-lg"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-3">Rekomendasi Mitra (SAW)</h3>
                    <p class="text-base text-gray-500 leading-relaxed">
                        Sistem cerdas berbasis SAW membantu merekomendasikan tempat PKL terbaik sesuai kriteria Anda.
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="group p-6 bg-white rounded-xl border border-gray-100 hover:border-indigo-100 hover:shadow-xl transition-all duration-300 reveal delay-100">
                    <div class="w-10 h-10 bg-pink-50 rounded-2xl flex items-center justify-center mb-6 text-pink-600 group-hover:bg-pink-600 group-hover:text-white transition-colors duration-300">
                        <i class="fas fa-book text-lg"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-3">Logbook Kegiatan</h3>
                    <p class="text-base text-gray-500 leading-relaxed">
                        Catat aktivitas harian PKL dengan mudah dan dapatkan validasi dari pembimbing lapangan.
                    </p>
                </div>

                <!-- Feature 5 -->
                <div class="group p-6 bg-white rounded-xl border border-gray-100 hover:border-indigo-100 hover:shadow-xl transition-all duration-300 reveal delay-200">
                    <div class="w-10 h-10 bg-orange-50 rounded-2xl flex items-center justify-center mb-6 text-orange-600 group-hover:bg-orange-600 group-hover:text-white transition-colors duration-300">
                        <i class="fas fa-certificate text-lg"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-3">Integrasi E-Course</h3>
                    <p class="text-base text-gray-500 leading-relaxed">
                        Upload sertifikat E-Course sebagai syarat pendukung kelayakan PKL Anda.
                    </p>
                </div>

                <!-- Feature 6 -->
                <div class="group p-6 bg-white rounded-xl border border-gray-100 hover:border-indigo-100 hover:shadow-xl transition-all duration-300 reveal delay-300">
                    <div class="w-10 h-10 bg-teal-50 rounded-2xl flex items-center justify-center mb-6 text-teal-600 group-hover:bg-teal-600 group-hover:text-white transition-colors duration-300">
                        <i class="fas fa-user-shield text-lg"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-3">Keamanan Data</h3>
                    <p class="text-base text-gray-500 leading-relaxed">
                        Data Anda aman dengan enkripsi standar industri dan proteksi akses berbasis role.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-12 bg-gray-50 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <div class="text-center mb-12 reveal">
                <h2 class="text-2xl font-bold text-gray-900">Alur Pendaftaran</h2>
                <p class="mt-4 text-lg text-gray-500">Langkah mudah memulai PKL Anda.</p>
            </div>

            <div class="relative">
                <!-- Connecting Line -->
                <div class="hidden absolute top-1/2 left-0 w-full h-0.5 bg-gray-200 -translate-y-1/2 z-0"></div>

                <div class="grid grid-cols-1 gap-6">
                    <!-- Step 1 -->
                    <div class="relative z-10 bg-gray-50 p-4 rounded-xl text-center reveal delay-100">
                        <div class="w-12 h-12 mx-auto bg-white border-4 border-indigo-100 rounded-full flex items-center justify-center text-lg font-bold text-indigo-600 mb-4 shadow-sm">1</div>
                        <h3 class="font-bold text-lg mb-2">Registrasi</h3>
                        <p class="text-base text-gray-500">Buat akun mahasiswa dan lengkapi profil Anda.</p>
                    </div>
                    <!-- Step 2 -->
                    <div class="relative z-10 bg-gray-50 p-4 rounded-xl text-center reveal delay-200">
                        <div class="w-12 h-12 mx-auto bg-white border-4 border-indigo-100 rounded-full flex items-center justify-center text-lg font-bold text-indigo-600 mb-4 shadow-sm">2</div>
                        <h3 class="font-bold text-lg mb-2">Pemberkasan</h3>
                        <p class="text-base text-gray-500">Upload dokumen syarat seperti KHS dan Transkrip.</p>
                    </div>
                    <!-- Step 3 -->
                    <div class="relative z-10 bg-gray-50 p-4 rounded-xl text-center reveal delay-300">
                        <div class="w-12 h-12 mx-auto bg-white border-4 border-indigo-100 rounded-full flex items-center justify-center text-lg font-bold text-indigo-600 mb-4 shadow-sm">3</div>
                        <h3 class="font-bold text-lg mb-2">Pilih Mitra</h3>
                        <p class="text-base text-gray-500">Cari dan ajukan permohonan ke mitra industri.</p>
                    </div>
                    <!-- Step 4 -->
                    <div class="relative z-10 bg-gray-50 p-4 rounded-xl text-center reveal delay-100">
                        <div class="w-12 h-12 mx-auto bg-white border-4 border-indigo-100 rounded-full flex items-center justify-center text-lg font-bold text-indigo-600 mb-4 shadow-sm">4</div>
                        <h3 class="font-bold text-lg mb-2">Mulai PKL</h3>
                        <p class="text-base text-gray-500">Cetak surat pengantar dan mulai kegiatan PKL.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Top Mitra Section -->
    <section id="mitra" class="py-12 bg-white scroll-mt-28">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12 reveal">
                <h2 class="text-2xl font-bold text-gray-900">Mitra Terfavorit</h2>
                <p class="mt-4 text-lg text-gray-500">Instansi tempat PKL yang paling banyak dipilih.</p>
            </div>

            @if(isset($topMitra) && $topMitra->count() > 0)
                <div class="grid grid-cols-1 gap-6">
                    @foreach($topMitra as $index => $mitra)
                        <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 relative reveal delay-{{ ($index + 1) * 100 }} group">
                            <!-- Rank Badge -->
                            <div class="absolute top-4 right-4 w-8 h-8 rounded-full flex items-center justify-center font-bold shadow-sm z-10 border-2 border-white text-xs {{ $index == 0 ? 'bg-yellow-400 text-white' : ($index == 1 ? 'bg-gray-400 text-white' : ($index == 2 ? 'bg-orange-400 text-white' : 'bg-gray-100 text-gray-600')) }}">
                                {{ $index + 1 }}
                            </div>
                            
                            <!-- Header -->
                            <div class="h-24 bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center border-b border-gray-100 group-hover:from-indigo-50 group-hover:to-blue-50 transition-colors duration-300">
                                <i class="fas fa-building text-3xl text-gray-300 group-hover:text-indigo-300 transition-colors duration-300"></i>
                            </div>
                            
                            <div class="p-5">
                                <h3 class="text-base font-bold text-gray-900 mb-2 truncate text-center">{{ $mitra->nama }}</h3>
                                <div class="flex items-center justify-center text-gray-500 text-xs mb-4">
                                    <i class="fas fa-map-marker-alt mr-2 text-gray-400"></i>
                                    <span class="truncate max-w-[200px]">{{ $mitra->alamat ?? 'Alamat tidak tersedia' }}</span>
                                </div>
                                
                                <a href="{{ route('login') }}" class="block w-full text-center py-2 border border-gray-200 text-gray-600 rounded-xl hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition-all font-medium text-xs">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 bg-gray-50 rounded-3xl border border-dashed border-gray-300 reveal">
                    <i class="fas fa-info-circle text-3xl text-gray-300 mb-4"></i>
                    <p class="text-sm text-gray-500">Belum ada data mitra favorit yang tersedia saat ini.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-12 bg-gray-50 relative overflow-hidden">
        <!-- Abstract Blobs -->
        <div class="absolute top-0 left-0 -ml-20 -mt-20 w-40 h-40 bg-purple-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute bottom-0 right-0 -mr-20 -mb-20 w-40 h-40 bg-pink-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>

        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <div class="text-center mb-12 reveal">
                <h2 class="text-2xl font-bold text-gray-900">Kata Mereka</h2>
                <p class="mt-4 text-lg text-gray-500">Pengalaman pengguna SIP PKL.</p>
            </div>

            <div class="grid grid-cols-1 gap-6">
                <!-- Testimonial 1 -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 reveal delay-100">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 font-bold text-base mr-4">A</div>
                        <div>
                            <div class="font-bold text-base text-gray-900">Ahmad Fauzi</div>
                            <div class="text-sm text-gray-500">Mahasiswa TI Angkatan 2021</div>
                        </div>
                    </div>
                    <p class="text-base text-gray-600 italic">"Sistem ini sangat membantu saya dalam mengurus berkas PKL. Tidak perlu lagi bolak-balik kampus untuk tanda tangan, semua serba digital!"</p>
                </div>
                <!-- Testimonial 2 -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 reveal delay-200">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold text-base mr-4">S</div>
                        <div>
                            <div class="font-bold text-base text-gray-900">Siti Aminah</div>
                            <div class="text-sm text-gray-500">Dosen Pembimbing</div>
                        </div>
                    </div>
                    <p class="text-base text-gray-600 italic">"Monitoring mahasiswa jadi lebih mudah. Saya bisa cek logbook mereka kapan saja dan memberikan feedback secara real-time."</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-indigo-600 relative overflow-hidden">
        <div class="absolute inset-0 bg-pattern opacity-10"></div>
        <div class="max-w-4xl mx-auto px-4 text-center relative z-10 reveal">
            <h2 class="text-2xl font-bold text-white mb-6">Siap Memulai Perjalanan PKL Anda?</h2>
            <p class="text-indigo-100 text-base mb-10 max-w-2xl mx-auto">Bergabunglah dengan ratusan mahasiswa lainnya yang telah merasakan kemudahan pengelolaan PKL secara digital.</p>
            <a href="{{ route('register') }}" class="inline-flex items-center justify-center bg-white text-indigo-600 px-6 py-3 border border-transparent text-base font-semibold rounded-lg shadow-sm hover:bg-gray-50 transition-all duration-200">
                <i class="fas fa-user-plus mr-2"></i> Daftar Akun Sekarang
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-8 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 gap-8 mb-8">
                <div class="col-span-1">
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-indigo-600 to-blue-600 flex items-center justify-center text-white mr-3">
                            <i class="fas fa-graduation-cap text-lg"></i>
                        </div>
                        <span class="font-bold text-xl tracking-tight">SIP PKL</span>
                    </div>
                    <p class="text-gray-400 text-xs leading-relaxed max-w-sm">
                        Sistem Informasi Pengelolaan Praktik Kerja Lapangan yang memudahkan administrasi dan monitoring kegiatan PKL mahasiswa Prodi Teknologi Informasi.
                    </p>
                </div>
                <div>
                    <h4 class="font-bold text-base mb-4 text-gray-100">Tautan Cepat</h4>
                    <ul class="space-y-2 text-xs text-gray-400">
                        <li><a href="#about" class="hover:text-white transition-colors flex items-center"><i class="fas fa-chevron-right text-xs mr-2"></i>Tentang</a></li>
                        <li><a href="#features" class="hover:text-white transition-colors flex items-center"><i class="fas fa-chevron-right text-xs mr-2"></i>Fitur</a></li>
                        <li><a href="#mitra" class="hover:text-white transition-colors flex items-center"><i class="fas fa-chevron-right text-xs mr-2"></i>Mitra</a></li>
                        <li><a href="{{ route('faq') }}" class="hover:text-white transition-colors flex items-center"><i class="fas fa-chevron-right text-xs mr-2"></i>FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-base mb-4 text-gray-100">Hubungi Kami</h4>
                    <ul class="space-y-2 text-xs text-gray-400">
                        <li class="flex items-start"><i class="fas fa-envelope mt-1 mr-3 text-indigo-400"></i> support@sipp-pkl.ac.id</li>
                        <li class="flex items-start"><i class="fas fa-phone mt-1 mr-3 text-indigo-400"></i> (021) 1234-5678</li>
                        <li class="flex items-start"><i class="fas fa-map-marker-alt mt-1 mr-3 text-indigo-400"></i> Politeknik Negeri Tanah Laut</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-6 flex flex-col justify-between items-center">
                <p class="text-xs text-gray-500 text-center">© {{ date('Y') }} SIP PKL. All rights reserved.</p>
                <div class="flex space-x-4 mt-4 justify-center">
                    <a href="#" class="text-gray-400 hover:text-white transition-colors"><i class="fab fa-instagram text-lg"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors"><i class="fab fa-twitter text-lg"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors"><i class="fab fa-linkedin text-lg"></i></a>
                </div>
            </div>
        </div>
    </footer>


    <script>
        // Scroll Reveal Animation with Reset
        document.addEventListener('DOMContentLoaded', function() {
            const reveals = document.querySelectorAll('.reveal');

            const observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.15 // Trigger when 15% of the element is visible
            };

            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('active');
                    } else {
                        // Reset animation when element leaves viewport
                        // entry.target.classList.remove('active'); // Optional: Uncomment if you want re-animation
                    }
                });
            }, observerOptions);

            reveals.forEach(reveal => {
                observer.observe(reveal);
            });
        });
    </script>
</body>
</html>

