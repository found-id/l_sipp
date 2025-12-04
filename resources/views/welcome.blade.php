<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'SIP PKL') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    @if(isset($fontConfig) && $fontConfig['url'])
        <link href="{{ $fontConfig['url'] }}" rel="stylesheet">
    @else
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @endif

    <style>
        body {
            font-family: {!! isset($fontConfig) ? $fontConfig['family'] : "'Inter', sans-serif" !!};
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        .hero-pattern {
            background-color: #ffffff;
            background-image: radial-gradient(#e5e7eb 1px, transparent 1px);
            background-size: 24px 24px;
        }
        
        /* Smooth Reveal Animation */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s cubic-bezier(0.5, 0, 0, 1);
        }
        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        .delay-100 { transition-delay: 0.1s; }
        .delay-200 { transition-delay: 0.2s; }
        .delay-300 { transition-delay: 0.3s; }
    </style>
</head>
<body class="bg-white text-gray-900 antialiased overflow-x-hidden">

    @include('partials.public-header')

    <!-- Wrapper untuk konten yang bisa di-scale (kecuali header) -->
    <div class="mobile-scalable">

    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden hero-pattern">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <span class="inline-block py-1 px-3 rounded-full bg-indigo-50 text-indigo-600 text-sm font-semibold tracking-wide uppercase mb-6 reveal">
                Sistem Informasi Pengelolaan PKL
            </span>
            
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-gray-900 mb-6 leading-tight reveal delay-100">
                Kelola PKL Anda <br class="hidden sm:block">
                <span class="text-indigo-600">Lebih Mudah & Efisien</span>
            </h1>

            <p class="mt-4 max-w-2xl mx-auto text-xl text-gray-500 mb-10 reveal delay-200">
                Platform terintegrasi untuk mahasiswa, dosen, dan mitra. Sederhanakan administrasi, fokus pada pengalaman belajar.
            </p>

            <div class="flex flex-col sm:flex-row justify-center gap-4 reveal delay-300">
                <a id="hero-cta" href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 text-base font-semibold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 transition-all shadow-lg hover:shadow-xl hover:-translate-y-1">
                    Mulai Sekarang
                    <i class="fas fa-arrow-right"></i>
                </a>
                <a href="#about" class="px-8 py-4 text-base font-semibold rounded-xl text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition-all">
                    Pelajari Lebih Lanjut
                </a>
            </div>
        </div>
        
        <!-- Decorative Blobs -->
        <div class="absolute top-0 left-0 -ml-20 -mt-20 w-72 h-72 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-72 h-72 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-slate-50 scroll-mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
                <div class="reveal">
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">Tentang SIP PKL</h2>
                    <p class="text-base text-gray-600 mb-4 leading-relaxed">
                        Sistem Informasi Pengelolaan Praktik Kerja Lapangan (SIP PKL) adalah solusi digital yang dirancang khusus untuk Program Studi Teknologi Informasi Politeknik Negeri Tanah Laut.
                    </p>
                    <p class="text-base text-gray-600 mb-6 leading-relaxed">
                        Kami menghubungkan mahasiswa, dosen pembimbing, dan mitra industri dalam satu ekosistem terpadu. Tujuannya adalah untuk mempermudah proses administrasi, meningkatkan transparansi penilaian, dan memastikan kualitas pelaksanaan PKL yang optimal.
                    </p>
                    <ul class="space-y-3">
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-teal-500 mr-3"></i>
                            <span class="text-gray-600">Digitalisasi penuh dari pendaftaran hingga pelaporan.</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-teal-500 mr-3"></i>
                            <span class="text-gray-600">Transparansi proses bimbingan dan penilaian.</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-teal-500 mr-3"></i>
                            <span class="text-gray-600">Arsip data yang aman dan terstruktur.</span>
                        </li>
                    </ul>
                </div>
                <div class="reveal delay-200">
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-5 bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                                <i class="fas fa-users text-3xl text-indigo-600 mb-3"></i>
                                <div class="font-bold text-gray-900 mb-1">Mahasiswa</div>
                                <div class="text-sm text-gray-500">Kemudahan Akses</div>
                            </div>
                            <div class="text-center p-5 bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                                <i class="fas fa-desktop text-3xl text-indigo-600 mb-3"></i>
                                <div class="font-bold text-gray-900 mb-1">Dosen</div>
                                <div class="text-sm text-gray-500">Monitoring Efektif</div>
                            </div>
                            <div class="text-center p-5 bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                                <i class="fas fa-building text-3xl text-indigo-600 mb-3"></i>
                                <div class="font-bold text-gray-900 mb-1">Mitra</div>
                                <div class="text-sm text-gray-500">Kolaborasi Luas</div>
                            </div>
                            <div class="text-center p-5 bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                                <i class="fas fa-university text-3xl text-indigo-600 mb-3"></i>
                                <div class="font-bold text-gray-900 mb-1">Prodi</div>
                                <div class="text-sm text-gray-500">Manajemen Terpusat</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section id="stats" class="py-20 bg-gray-50 scroll-mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 reveal">
                <h2 class="text-3xl font-bold text-gray-900">Statistik Kami</h2>
                <p class="mt-4 text-lg text-gray-500">Pertumbuhan dan capaian dalam angka.</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div class="p-6 bg-white rounded-2xl shadow-sm border border-gray-100 reveal hover:-translate-y-1 transition-transform duration-300">
                    <div class="text-4xl font-extrabold text-indigo-600 mb-2">500+</div>
                    <div class="text-gray-600 font-medium">Mahasiswa Aktif</div>
                </div>
                <div class="p-6 bg-white rounded-2xl shadow-sm border border-gray-100 reveal delay-100 hover:-translate-y-1 transition-transform duration-300">
                    <div class="text-4xl font-extrabold text-blue-600 mb-2">50+</div>
                    <div class="text-gray-600 font-medium">Mitra Industri</div>
                </div>
                <div class="p-6 bg-white rounded-2xl shadow-sm border border-gray-100 reveal delay-200 hover:-translate-y-1 transition-transform duration-300">
                    <div class="text-4xl font-extrabold text-cyan-600 mb-2">100%</div>
                    <div class="text-gray-600 font-medium">Digital Process</div>
                </div>
                <div class="p-6 bg-white rounded-2xl shadow-sm border border-gray-100 reveal delay-300 hover:-translate-y-1 transition-transform duration-300">
                    <div class="text-4xl font-extrabold text-teal-600 mb-2">24/7</div>
                    <div class="text-gray-600 font-medium">Support System</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white scroll-mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 reveal">
                <h2 class="text-3xl font-bold text-gray-900">Fitur Unggulan</h2>
                <p class="mt-4 text-lg text-gray-500 max-w-2xl mx-auto">Semua yang Anda butuhkan untuk manajemen PKL yang sukses dalam satu platform.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="p-8 bg-white rounded-2xl border border-gray-100 hover:shadow-lg transition-all duration-300 group reveal">
                    <div class="w-14 h-14 bg-indigo-50 rounded-xl flex items-center justify-center mb-6 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                        <i class="fas fa-laptop-code text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Pemberkasan Digital</h3>
                    <p class="text-gray-500 leading-relaxed">
                        Upload dan validasi dokumen KHS, surat pengantar, dan laporan secara online tanpa ribet kertas.
                    </p>
                </div>
                <!-- Feature 2 -->
                <div class="p-8 bg-white rounded-2xl border border-gray-100 hover:shadow-lg transition-all duration-300 group reveal delay-100">
                    <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center mb-6 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <i class="fas fa-chart-line text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Monitoring Real-time</h3>
                    <p class="text-gray-500 leading-relaxed">
                        Dosen dan admin dapat memantau progress mahasiswa secara langsung dan memberikan feedback cepat.
                    </p>
                </div>
                <!-- Feature 3 -->
                <div class="p-8 bg-white rounded-2xl border border-gray-100 hover:shadow-lg transition-all duration-300 group reveal delay-200">
                    <div class="w-14 h-14 bg-purple-50 rounded-xl flex items-center justify-center mb-6 text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                        <i class="fas fa-star text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Rekomendasi Mitra (SAW)</h3>
                    <p class="text-gray-500 leading-relaxed">
                        Sistem cerdas berbasis SAW membantu merekomendasikan tempat PKL terbaik sesuai kriteria Anda.
                    </p>
                </div>
                <!-- Feature 4 -->
                <div class="p-8 bg-white rounded-2xl border border-gray-100 hover:shadow-lg transition-all duration-300 group reveal">
                    <div class="w-14 h-14 bg-pink-50 rounded-xl flex items-center justify-center mb-6 text-pink-600 group-hover:bg-pink-600 group-hover:text-white transition-colors">
                        <i class="fas fa-book text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Logbook Kegiatan</h3>
                    <p class="text-gray-500 leading-relaxed">
                        Catat aktivitas harian PKL dengan mudah dan dapatkan validasi dari pembimbing lapangan.
                    </p>
                </div>
                <!-- Feature 5 -->
                <div class="p-8 bg-white rounded-2xl border border-gray-100 hover:shadow-lg transition-all duration-300 group reveal delay-100">
                    <div class="w-14 h-14 bg-orange-50 rounded-xl flex items-center justify-center mb-6 text-orange-600 group-hover:bg-orange-600 group-hover:text-white transition-colors">
                        <i class="fas fa-certificate text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Integrasi E-Course</h3>
                    <p class="text-gray-500 leading-relaxed">
                        Upload sertifikat E-Course sebagai syarat pendukung kelayakan PKL Anda.
                    </p>
                </div>
                <!-- Feature 6 -->
                <div class="p-8 bg-white rounded-2xl border border-gray-100 hover:shadow-lg transition-all duration-300 group reveal delay-200">
                    <div class="w-14 h-14 bg-teal-50 rounded-xl flex items-center justify-center mb-6 text-teal-600 group-hover:bg-teal-600 group-hover:text-white transition-colors">
                        <i class="fas fa-user-shield text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Keamanan Data</h3>
                    <p class="text-gray-500 leading-relaxed">
                        Data Anda aman dengan enkripsi standar industri dan proteksi akses berbasis role.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Mitra Section -->
    <section id="mitra" class="py-20 bg-gray-50 scroll-mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 reveal">
                <h2 class="text-3xl font-bold text-gray-900">Mitra Terfavorit</h2>
                <p class="mt-4 text-lg text-gray-500">Instansi tempat PKL yang paling banyak dipilih.</p>
            </div>

            @if(isset($topMitra) && $topMitra->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($topMitra as $mitra)
                    <div class="group bg-slate-50 rounded-2xl border border-slate-100 overflow-hidden hover:shadow-xl transition-all duration-300 reveal hover:-translate-y-1">
                        <div class="relative h-48 bg-slate-100 flex items-center justify-center overflow-hidden group-hover:bg-slate-200 transition-colors">
                            <!-- Decorative Pattern Overlay -->
                            <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(#4f46e5 1px, transparent 1px); background-size: 16px 16px;"></div>
                            
                            <!-- Subtle Gradient Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-100/50 to-transparent"></div>

                            <!-- Ranking Badge (Minimalist) -->
                            <div class="absolute top-4 left-4 z-10">
                                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-white/90 backdrop-blur text-sm font-bold text-slate-700 shadow-sm border border-slate-100">
                                    #{{ $loop->iteration }}
                                </span>
                            </div>

                            @if($mitra->foto_profil)
                                <img src="{{ asset('storage/' . $mitra->foto_profil) }}" alt="{{ $mitra->nama }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105 relative z-0">
                            @else
                                <i class="fas fa-building text-5xl text-slate-300 group-hover:text-indigo-400 transition-colors relative z-0"></i>
                            @endif
                        </div>
                        
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-slate-800 mb-4 line-clamp-2 min-h-[3.5rem] group-hover:text-indigo-600 transition-colors">
                                {{ $mitra->nama }}
                            </h3>
                            
                            <div class="space-y-2.5 mb-6">
                                <div class="flex items-start gap-3">
                                    <div class="w-5 flex justify-center mt-0.5">
                                        <i class="fas fa-map-marker-alt text-slate-400 text-sm"></i>
                                    </div>
                                    <span class="text-sm text-slate-500 leading-snug line-clamp-2">{{ $mitra->alamat ? Str::limit($mitra->alamat, 60) : '-' }}</span>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-slate-200">
                                <a href="{{ route('login') }}" class="block w-full py-2.5 px-4 bg-white border border-slate-200 text-indigo-600 font-medium rounded-xl text-center hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition-all duration-300 shadow-sm">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 bg-white rounded-2xl border border-dashed border-gray-300 reveal">
                    <i class="fas fa-building text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">Belum ada data mitra yang ditampilkan.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center mb-6">
                        <i class="fas fa-graduation-cap text-3xl text-indigo-500 mr-3"></i>
                        <span class="text-2xl font-bold text-white">SIP PKL</span>
                    </div>
                    <p class="text-gray-400 leading-relaxed max-w-sm">
                        Sistem Informasi Pengelolaan Praktik Kerja Lapangan Program Studi Teknologi Informasi Politeknik Negeri Tanah Laut.
                    </p>
                </div>
                <div>
                    <h4 class="text-lg font-bold mb-6">Tautan</h4>
                    <ul class="space-y-4">
                        <li><a href="#about" class="text-gray-400 hover:text-white transition-colors">Tentang</a></li>
                        <li><a href="#features" class="text-gray-400 hover:text-white transition-colors">Fitur</a></li>
                        <li><a href="#mitra" class="text-gray-400 hover:text-white transition-colors">Mitra</a></li>
                        <li><a href="{{ route('faq') }}" class="text-gray-400 hover:text-white transition-colors">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-bold mb-6">Kontak</h4>
                    <ul class="space-y-4">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt mt-1 mr-3 text-indigo-500"></i>
                            <span class="text-gray-400">Jl. A. Yani Km. 06, Pelaihari, Tanah Laut, Kalimantan Selatan</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-3 text-indigo-500"></i>
                            <span class="text-gray-400">ti@politala.ac.id</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-500">
                <p>&copy; {{ date('Y') }} SIP PKL - Politeknik Negeri Tanah Laut. All rights reserved.</p>
            </div>
        </div>
    </footer>

    </div> <!-- End of .mobile-scalable wrapper -->

    <!-- Floating CTA Button -->
    <a id="floating-cta" href="{{ route('login') }}" class="fixed top-24 left-1/2 -translate-x-1/2 z-40 px-6 py-3 text-sm font-semibold rounded-full text-indigo-600 bg-white/30 backdrop-blur-md border border-indigo-200/50 hover:bg-indigo-600 hover:text-white transition-all duration-300 shadow-none hover:shadow-lg opacity-0 -translate-y-10 pointer-events-none flex items-center gap-2">
        Mulai Sekarang
        <i class="fas fa-arrow-right"></i>
    </a>

    <script>
        // Reveal Animation on Scroll
        function reveal() {
            var reveals = document.querySelectorAll(".reveal");
            for (var i = 0; i < reveals.length; i++) {
                var windowHeight = window.innerHeight;
                var elementTop = reveals[i].getBoundingClientRect().top;
                var elementVisible = 150;
                if (elementTop < windowHeight - elementVisible) {
                    reveals[i].classList.add("active");
                }
            }
        }

        // Floating CTA visibility
        function toggleFloatingCTA() {
            const heroCta = document.getElementById('hero-cta');
            const floatingCta = document.getElementById('floating-cta');
            
            if (heroCta && floatingCta) {
                const heroCtaRect = heroCta.getBoundingClientRect();
                const isHeroCtaVisible = heroCtaRect.bottom > 0 && heroCtaRect.top < window.innerHeight;
                
                if (isHeroCtaVisible) {
                    // Hero CTA is visible, hide floating
                    floatingCta.classList.add('opacity-0', '-translate-y-10', 'pointer-events-none');
                    floatingCta.classList.remove('opacity-100', 'translate-y-0', 'pointer-events-auto');
                } else {
                    // Hero CTA is not visible, show floating
                    floatingCta.classList.remove('opacity-0', '-translate-y-10', 'pointer-events-none');
                    floatingCta.classList.add('opacity-100', 'translate-y-0', 'pointer-events-auto');
                }
            }
        }
        
        window.addEventListener("scroll", function() {
            reveal();
            toggleFloatingCTA();
        });
        reveal(); // Trigger once on load
        toggleFloatingCTA(); // Check initial state
    </script>
</body>
</html>
