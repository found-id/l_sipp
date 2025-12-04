<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Informasi Pengelolaan PKL')</title>

    <!-- Favicons -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon.svg') }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    @php
        $fontConfig = \App\Models\SystemSetting::getFontConfig();
    @endphp
    
    @if($fontConfig['url'])
        <link href="{{ $fontConfig['url'] }}" rel="stylesheet">
    @endif
    
    <style>
        /* ============================================
           CONTENT SCALE CONTROL
           Atur nilai scale untuk mengubah ukuran konten
           Contoh: 0.85 = 85%, 0.9 = 90%, 1 = 100%
           
           Width = 100 / scale (otomatis menyesuaikan)
           Misal scale 0.8, maka width = 100/0.8 = 125%
           ============================================ */
        :root {
            --content-scale-mobile: 0.72;   /* Baris 35: Scale untuk MOBILE (< 768px) */
            --content-scale-tablet: 0.8;    /* Baris 36: Scale untuk TABLET (768px - 1024px) */
            --content-scale-desktop: 1;     /* Baris 37: Scale untuk DESKTOP (> 1024px) */
            
            /* Jarak Header ke Konten (dalam px) */
            --header-gap-mobile: 65;        /* Baris 40: Jarak header-konten MOBILE */
            --header-gap-tablet: 80;        /* Baris 41: Jarak header-konten TABLET */
            --header-gap-desktop: 65;       /* Baris 42: Jarak header-konten DESKTOP */
            
            /* Header Scale (hanya untuk mobile) */
            --header-scale-mobile: 0.85;    /* Baris 45: Scale header MOBILE (logo, icon, dll) */
            --header-height-mobile: 50px;   /* Baris 46: Tinggi header MOBILE */
            
            /* ============================================
               MOBILE BOTTOM NAVIGATION SETTINGS
               ============================================ */
            --nav-scale-mobile: 0.9;        /* Scale navigasi bottom (0.8 = 80%, 1 = 100%) */
            --nav-margin-x-mobile: -20px;     /* Margin kiri-kanan navigasi (px) */
            --nav-padding-x-mobile: 30px;    /* Padding kiri-kanan dalam navigasi (px) */
            --nav-border-radius-mobile: 0;  /* Border radius navigasi (px), set > 0 untuk rounded */
            --nav-bottom-offset: 0px;       /* Jarak dari bawah layar (px) */
        }

        body {
            font-family: {!! $fontConfig['family'] !!};
        }
        .nav-link {
            color: #64748b;
            transition: all 0.2s ease;
            position: relative;
            display: flex;
            align-items: center;
            padding: 0 16px;
            height: 100%;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9375rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 200px;
            border-bottom: 2px solid transparent;
        }
        .nav-link i {
            font-size: 1.25rem;
            margin-right: 8px;
            color: #94a3b8;
            transition: all 0.2s ease;
        }
        .nav-link:hover {
            color: #2563eb;
            background-color: transparent;
        }
        .nav-link:hover i {
            color: #2563eb;
            transform: scale(1.1);
        }
        .nav-link.active {
            color: #2563eb;
            background-color: transparent;
            font-weight: 600;
            border-bottom-color: #2563eb;
            box-shadow: none;
        }
        .nav-link.active i {
            color: #2563eb;
        }
        .nav-container {
            display: flex;
            align-items: center;
            gap: 8px;
            height: 100%;
        }

        /* Tablet: hide nav text, show only icons */
        @media (min-width: 768px) and (max-width: 1024px) {
            .nav-link {
                padding: 0 12px;
                max-width: none;
            }
            .nav-link i {
                margin-right: 0;
            }
            .nav-link .nav-text {
                display: none;
            }
        }

        /* Mobile Header Scale */
        @media (max-width: 767px) {
            #top-nav {
                height: var(--header-height-mobile);
            }
            #top-nav > div {
                height: 100%;
            }
            #top-nav .flex.justify-between {
                height: 100%;
            }
            #top-nav .flex-shrink-0 a {
                transform: scale(var(--header-scale-mobile));
                transform-origin: left center;
            }
            #top-nav .profile-icon {
                transform: scale(var(--header-scale-mobile));
            }
        }

        .logo-text {
            color: #1e40af;
            font-weight: 800;
            letter-spacing: -0.5px;
        }
        .profile-icon {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            color: rgba(0, 0, 0, 0.603);
            transition: all 0.3s ease;
            background-color: #0000000c;
            overflow: hidden;
        }
        .profile-icon:hover {
            color: #2563eb;
            background-color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
        }
        .profile-icon img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Mobile Bottom Navigation */
        @media (max-width: 767px) {
            .mobile-bottom-nav {
                position: fixed;
                bottom: var(--nav-bottom-offset);
                left: var(--nav-margin-x-mobile);
                right: var(--nav-margin-x-mobile);
                z-index: 50;
                background: rgba(255, 255, 255, 0.77);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                border-top: 1px solid #e5e7eb;
                padding: 8px var(--nav-padding-x-mobile);
                padding-bottom: calc(8px + env(safe-area-inset-bottom));
                border-radius: var(--nav-border-radius-mobile);
                transform: scale(var(--nav-scale-mobile));
                transform-origin: bottom center;
            }
            .mobile-nav-container {
                display: flex;
                justify-content: space-around;
                align-items: center;
            }
            .mobile-nav-item {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                padding: 6px 12px;
                color: #64748b;
                text-decoration: none;
                transition: all 0.2s ease;
                border-radius: 12px;
                min-width: 60px;
            }
            .mobile-nav-item i {
                font-size: 1.25rem;
                margin-bottom: 4px;
                transition: all 0.2s ease;
            }
            .mobile-nav-item span {
                font-size: 0.65rem;
                font-weight: 500;
            }
            .mobile-nav-item:hover,
            .mobile-nav-item.active {
                color: #2563eb;
            }
            .mobile-nav-item.active {
                background-color: rgba(37, 99, 235, 0.08);
            }
            .mobile-nav-item.active i {
                transform: scale(1.1);
            }
            .mobile-nav-item.disabled {
                opacity: 0.4;
                pointer-events: none;
            }

            /* Hide on scroll animations */
            .top-nav-hidden {
                transform: translateY(-100%);
            }
            .bottom-nav-hidden {
                transform: translateY(100%);
            }
        }

        /* Content Scale - Applied to main content */
        .scalable-content {
            transform-origin: top left;
            transition: transform 0.2s ease, width 0.2s ease;
        }
        
        @media (max-width: 767px) {
            .scalable-content {
                transform: scale(var(--content-scale-mobile));
                width: calc(100% / var(--content-scale-mobile));
                padding-top: calc(var(--header-gap-mobile) * 1px);
            }
        }
        
        @media (min-width: 768px) and (max-width: 1024px) {
            .scalable-content {
                transform: scale(var(--content-scale-tablet));
                width: calc(100% / var(--content-scale-tablet));
                padding-top: calc(var(--header-gap-tablet) * 1px);
            }
        }
        
        @media (min-width: 1025px) {
            .scalable-content {
                transform: scale(var(--content-scale-desktop));
                width: calc(100% / var(--content-scale-desktop));
                padding-top: calc(var(--header-gap-desktop) * 1px);
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    @auth
    <!-- Navigation -->
    <nav id="top-nav" class="fixed top-0 left-0 right-0 z-50 bg-white/85 backdrop-blur-xl shadow-sm border-b border-gray-100 transition-transform duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('dashboard') }}" class="flex items-center text-xl font-bold text-gray-900 hover:text-blue-600 transition-colors">
                            <i class="fas fa-graduation-cap text-2xl text-indigo-600 mr-2"></i>
                            SIP PKL
                        </a>
                    </div>
                    
                    <!-- Navigation Menu -->
                    <div class="hidden md:block ml-10 h-full">
                        <div class="nav-container">
                            @if(auth()->user()->role === 'mahasiswa')
                                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" title="Dashboard">
                                    <i class="fas fa-home mr-2"></i><span class="nav-text">Dashboard</span>
                                </a>
                                <a href="{{ route('documents.index') }}" class="nav-link {{ request()->routeIs('documents.*') ? 'active' : '' }}" title="Pemberkasan">
                                    <i class="fas fa-file-upload mr-2"></i><span class="nav-text">Pemberkasan</span>
                                </a>
                                @if(\App\Models\SystemSetting::isEnabled('instansi_mitra_enabled'))
                                    <a href="{{ route('mitra') }}" class="nav-link {{ request()->routeIs('mitra') ? 'active' : '' }}" title="Instansi Mitra">
                                        <i class="fas fa-building mr-2"></i><span class="nav-text">Instansi Mitra</span>
                                    </a>
                                @else
                                    <span class="nav-link opacity-50 cursor-not-allowed" title="Fitur masih terkunci">
                                        <i class="fas fa-building mr-2"></i><span class="nav-text">Instansi Mitra</span>
                                    </span>
                                @endif
                                
                                @if(\App\Models\SystemSetting::isEnabled('jadwal_seminar_enabled'))
                                    <a href="{{ route('jadwal-seminar') }}" class="nav-link {{ request()->routeIs('jadwal-seminar') ? 'active' : '' }}" title="Jadwal Seminar">
                                        <i class="fas fa-calendar-alt mr-2"></i><span class="nav-text">Jadwal Seminar</span>
                                    </a>
                                @else
                                    <span class="nav-link opacity-50 cursor-not-allowed" title="Fitur masih terkunci">
                                        <i class="fas fa-calendar-alt mr-2"></i><span class="nav-text">Jadwal Seminar</span>
                                    </span>
                                @endif
                            @elseif(auth()->user()->role === 'dospem')
                                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" title="Dashboard">
                                    <i class="fas fa-home mr-2"></i><span class="nav-text">Dashboard</span>
                                </a>
                                <a href="{{ route('dospem.validation') }}" class="nav-link {{ request()->routeIs('dospem.validation') ? 'active' : '' }}" title="Berkas Mahasiswa">
                                    <i class="fas fa-file-alt mr-2"></i><span class="nav-text">Berkas Mahasiswa</span>
                                </a>
                                @if(\App\Models\SystemSetting::isEnabled('penilaian_enabled'))
                                    <a href="{{ route('dospem.penilaian') }}" class="nav-link {{ request()->routeIs('dospem.penilaian') ? 'active' : '' }}" title="Penilaian">
                                        <i class="fas fa-clipboard-check mr-2"></i><span class="nav-text">Penilaian</span>
                                    </a>
                                @else
                                    <span class="nav-link opacity-50 cursor-not-allowed" title="Fitur masih terkunci">
                                        <i class="fas fa-clipboard-check mr-2"></i><span class="nav-text">Penilaian</span>
                                    </span>
                                @endif
                                
                                @if(\App\Models\SystemSetting::isEnabled('jadwal_seminar_enabled'))
                                    <a href="{{ route('jadwal-seminar') }}" class="nav-link {{ request()->routeIs('jadwal-seminar') ? 'active' : '' }}" title="Jadwal Seminar">
                                        <i class="fas fa-calendar-alt mr-2"></i><span class="nav-text">Jadwal Seminar</span>
                                    </a>
                                @else
                                    <span class="nav-link opacity-50 cursor-not-allowed" title="Fitur masih terkunci">
                                        <i class="fas fa-calendar-alt mr-2"></i><span class="nav-text">Jadwal Seminar</span>
                                    </span>
                                @endif
                            @elseif(auth()->user()->role === 'admin')
                                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" title="Dashboard">
                                    <i class="fas fa-home mr-2"></i><span class="nav-text">Dashboard</span>
                                </a>
                                <a href="{{ route('admin.kelola-data') }}" class="nav-link {{ request()->routeIs('admin.kelola-data') ? 'active' : '' }}" title="Menu Kelola">
                                    <i class="fas fa-cogs mr-2"></i><span class="nav-text">Menu Kelola</span>
                                </a>
                                <a href="{{ route('admin.system-settings') }}" class="nav-link {{ request()->routeIs('admin.system-settings') ? 'active' : '' }}" title="Menu Sistem">
                                    <i class="fas fa-sliders-h mr-2"></i><span class="nav-text">Menu Sistem</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center space-x-2">
                    <!-- Profile Button -->
                    <a href="{{ route('profile.index') }}" class="profile-icon rounded-full" title="Profile">
                        <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" class="h-8 w-8 rounded-full object-cover border-2 border-gray-300" referrerpolicy="no-referrer">
                    </a>

                    <!-- Activity Log Button (Hidden for Mahasiswa) -->
                    @if(auth()->user()->role !== 'mahasiswa')
                    <a href="{{ route('activity') }}" class="profile-icon" title="Log Aktivitas">
                        <i class="fas fa-history"></i>
                    </a>
                    @endif

                    <!-- Settings Button -->
                    <a href="{{ route('profile.settings') }}" class="profile-icon" title="Pengaturan">
                        <i class="fas fa-cog"></i>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile Bottom Navigation (only visible on mobile) -->
    <nav id="bottom-nav" class="mobile-bottom-nav md:hidden transition-transform duration-300">
        <div class="mobile-nav-container">
            @if(auth()->user()->role === 'mahasiswa')
                <a href="{{ route('dashboard') }}" class="mobile-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Beranda</span>
                </a>
                <a href="{{ route('documents.index') }}" class="mobile-nav-item {{ request()->routeIs('documents.*') ? 'active' : '' }}">
                    <i class="fas fa-file-upload"></i>
                    <span>Berkas</span>
                </a>
                @if(\App\Models\SystemSetting::isEnabled('instansi_mitra_enabled'))
                    <a href="{{ route('mitra') }}" class="mobile-nav-item {{ request()->routeIs('mitra') ? 'active' : '' }}">
                        <i class="fas fa-building"></i>
                        <span>Mitra</span>
                    </a>
                @else
                    <span class="mobile-nav-item disabled">
                        <i class="fas fa-building"></i>
                        <span>Mitra</span>
                    </span>
                @endif
                @if(\App\Models\SystemSetting::isEnabled('jadwal_seminar_enabled'))
                    <a href="{{ route('jadwal-seminar') }}" class="mobile-nav-item {{ request()->routeIs('jadwal-seminar') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Jadwal</span>
                    </a>
                @else
                    <span class="mobile-nav-item disabled">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Jadwal</span>
                    </span>
                @endif
            @elseif(auth()->user()->role === 'dospem')
                <a href="{{ route('dashboard') }}" class="mobile-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Beranda</span>
                </a>
                <a href="{{ route('dospem.validation') }}" class="mobile-nav-item {{ request()->routeIs('dospem.validation') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i>
                    <span>Berkas</span>
                </a>
                @if(\App\Models\SystemSetting::isEnabled('penilaian_enabled'))
                    <a href="{{ route('dospem.penilaian') }}" class="mobile-nav-item {{ request()->routeIs('dospem.penilaian') ? 'active' : '' }}">
                        <i class="fas fa-clipboard-check"></i>
                        <span>Nilai</span>
                    </a>
                @else
                    <span class="mobile-nav-item disabled">
                        <i class="fas fa-clipboard-check"></i>
                        <span>Nilai</span>
                    </span>
                @endif
                @if(\App\Models\SystemSetting::isEnabled('jadwal_seminar_enabled'))
                    <a href="{{ route('jadwal-seminar') }}" class="mobile-nav-item {{ request()->routeIs('jadwal-seminar') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Jadwal</span>
                    </a>
                @else
                    <span class="mobile-nav-item disabled">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Jadwal</span>
                    </span>
                @endif
            @elseif(auth()->user()->role === 'admin')
                <a href="{{ route('dashboard') }}" class="mobile-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Beranda</span>
                </a>
                <a href="{{ route('admin.kelola-data') }}" class="mobile-nav-item {{ request()->routeIs('admin.kelola-data') ? 'active' : '' }}">
                    <i class="fas fa-cogs"></i>
                    <span>Kelola</span>
                </a>
                <a href="{{ route('admin.system-settings') }}" class="mobile-nav-item {{ request()->routeIs('admin.system-settings') ? 'active' : '' }}">
                    <i class="fas fa-sliders-h"></i>
                    <span>Sistem</span>
                </a>
            @endif
        </div>
    </nav>
    @endauth

    <!-- Scalable Content Wrapper (kecuali header/navigasi) -->
    <div class="scalable-content">

    <!-- Main Content -->
    <main class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-md" role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-3 text-xl"></i>
                        <p class="font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-md" role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-3 text-xl"></i>
                        <p class="font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="border-t mt-12 @auth pb-20 md:pb-0 @endauth">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
            <p class="text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} Sistem Informasi Pengelolaan PKL. All rights reserved.
            </p>
        </div>
    </footer>

    </div> <!-- End of .scalable-content wrapper -->

    {{-- ====== tambahkan baris ini agar @push('scripts') dari view lain ikut dirender ====== --}}
    @stack('scripts')

    {{-- Mobile scroll hide/show navigation --}}
    @auth
    <script>
        (function() {
            // Only run on mobile
            if (window.innerWidth >= 768) return;

            const topNav = document.getElementById('top-nav');
            const bottomNav = document.getElementById('bottom-nav');
            
            if (!topNav || !bottomNav) return;

            let lastScrollY = window.scrollY;
            let ticking = false;

            function isAtBottom() {
                // Check if scrolled to bottom (with 50px threshold)
                return (window.innerHeight + window.scrollY) >= (document.body.scrollHeight - 50);
            }

            function updateNavVisibility() {
                const currentScrollY = window.scrollY;
                const scrollDelta = currentScrollY - lastScrollY;
                
                // If at bottom, show only bottom nav
                if (isAtBottom()) {
                    topNav.classList.add('top-nav-hidden');
                    bottomNav.classList.remove('bottom-nav-hidden');
                    lastScrollY = currentScrollY;
                    ticking = false;
                    return;
                }

                // Only trigger after scrolling more than 10px
                if (Math.abs(scrollDelta) < 10) {
                    ticking = false;
                    return;
                }

                if (scrollDelta > 0 && currentScrollY > 80) {
                    // Scrolling down - hide navs
                    topNav.classList.add('top-nav-hidden');
                    bottomNav.classList.add('bottom-nav-hidden');
                } else if (scrollDelta < 0) {
                    // Scrolling up - show navs
                    topNav.classList.remove('top-nav-hidden');
                    bottomNav.classList.remove('bottom-nav-hidden');
                }

                lastScrollY = currentScrollY;
                ticking = false;
            }

            window.addEventListener('scroll', function() {
                if (!ticking) {
                    window.requestAnimationFrame(updateNavVisibility);
                    ticking = true;
                }
            }, { passive: true });

            // Handle resize - disable on desktop
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 768) {
                    topNav.classList.remove('top-nav-hidden');
                    bottomNav.classList.remove('bottom-nav-hidden');
                }
            });
        })();
    </script>
    @endauth

    <script>
        // Fix scalable content height - limit scroll to footer
        function fixScalableContentHeight() {
            const scalableContent = document.querySelector('.scalable-content');
            if (!scalableContent) return;
            
            const width = window.innerWidth;
            let scale = 1;
            
            if (width < 768) {
                scale = parseFloat(getComputedStyle(document.documentElement).getPropertyValue('--content-scale-mobile')) || 1;
            } else if (width <= 1024) {
                scale = parseFloat(getComputedStyle(document.documentElement).getPropertyValue('--content-scale-tablet')) || 1;
            } else {
                scale = parseFloat(getComputedStyle(document.documentElement).getPropertyValue('--content-scale-desktop')) || 1;
            }
            
            if (scale < 1) {
                // Calculate actual visible height
                const actualHeight = scalableContent.scrollHeight * scale + 20;
                // Set body height to limit scrolling
                document.body.style.height = actualHeight + 'px';
                document.body.style.overflow = 'auto';
            } else {
                document.body.style.height = '';
                document.body.style.overflow = '';
            }
        }
        
        // Run on load and resize
        window.addEventListener('load', fixScalableContentHeight);
        window.addEventListener('resize', fixScalableContentHeight);
        
        // Also run after delays to catch dynamic content
        setTimeout(fixScalableContentHeight, 100);
        setTimeout(fixScalableContentHeight, 500);
        setTimeout(fixScalableContentHeight, 1000);
    </script>
</body>
</html>
