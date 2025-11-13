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
    <style>
        .nav-link {
            color: #6b7280;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            display: flex;
            align-items: center;
            padding: 10px 16px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9375rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 200px;
        }
        .nav-link i {
            font-size: 1.125rem;
            transition: transform 0.3s ease;
        }
        .nav-link:hover {
            color: #2563eb;
            background-color: #f1f5f9;
            transform: translateY(-1px);
        }
        .nav-link:hover i {
            transform: scale(1.1);
        }
        .nav-link.active {
            color: #ffffff;
            background-color: #1770ff;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        .nav-link.active i {
            color: #ffffff;
        }
        .nav-container {
            display: flex;
            align-items: center;
            gap: 6px;
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
            color: #6b7280;
            transition: all 0.3s ease;
            background-color: #f8fafc;
            overflow: hidden;
        }
        .profile-icon:hover {
            color: #2563eb;
            background-color: #eff6ff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
        }
        .profile-icon img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
</head>
<body class="bg-gray-50">
    @auth
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-sm shadow-sm border-b border-gray-100">
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
                    <div class="hidden md:block ml-10">
                        <div class="nav-container">
                            @if(auth()->user()->role === 'mahasiswa')
                                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                    <i class="fas fa-home mr-2"></i>Dashboard
                                </a>
                                <a href="{{ route('documents.index') }}" class="nav-link {{ request()->routeIs('documents.*') ? 'active' : '' }}">
                                    <i class="fas fa-file-upload mr-2"></i>Pemberkasan
                                </a>
                                @if(\App\Models\SystemSetting::isEnabled('instansi_mitra_enabled'))
                                    <a href="{{ route('mitra') }}" class="nav-link {{ request()->routeIs('mitra') ? 'active' : '' }}">
                                        <i class="fas fa-building mr-2"></i>Instansi Mitra
                                    </a>
                                @else
                                    <span class="nav-link opacity-50 cursor-not-allowed" title="Fitur masih terkunci">
                                        <i class="fas fa-building mr-2"></i>Instansi Mitra
                                    </span>
                                @endif
                                
                                @if(\App\Models\SystemSetting::isEnabled('jadwal_seminar_enabled'))
                                    <a href="{{ route('jadwal-seminar') }}" class="nav-link {{ request()->routeIs('jadwal-seminar') ? 'active' : '' }}">
                                        <i class="fas fa-calendar-alt mr-2"></i>Jadwal Seminar
                                    </a>
                                @else
                                    <span class="nav-link opacity-50 cursor-not-allowed" title="Fitur masih terkunci">
                                        <i class="fas fa-calendar-alt mr-2"></i>Jadwal Seminar
                                    </span>
                                @endif
                            @elseif(auth()->user()->role === 'dospem')
                                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                    <i class="fas fa-home mr-2"></i>Dashboard
                                </a>
                                <a href="{{ route('dospem.validation') }}" class="nav-link {{ request()->routeIs('dospem.validation') ? 'active' : '' }}">
                                    <i class="fas fa-file-alt mr-2"></i>Berkas Mahasiswa
                                </a>
                                @if(\App\Models\SystemSetting::isEnabled('penilaian_enabled'))
                                    <a href="{{ route('dospem.penilaian') }}" class="nav-link {{ request()->routeIs('dospem.penilaian') ? 'active' : '' }}">
                                        <i class="fas fa-clipboard-check mr-2"></i>Penilaian
                                    </a>
                                @else
                                    <span class="nav-link opacity-50 cursor-not-allowed" title="Fitur masih terkunci">
                                        <i class="fas fa-clipboard-check mr-2"></i>Penilaian
                                    </span>
                                @endif
                                
                                @if(\App\Models\SystemSetting::isEnabled('jadwal_seminar_enabled'))
                                    <a href="{{ route('jadwal-seminar') }}" class="nav-link {{ request()->routeIs('jadwal-seminar') ? 'active' : '' }}">
                                        <i class="fas fa-calendar-alt mr-2"></i>Jadwal Seminar
                                    </a>
                                @else
                                    <span class="nav-link opacity-50 cursor-not-allowed" title="Fitur masih terkunci">
                                        <i class="fas fa-calendar-alt mr-2"></i>Jadwal Seminar
                                    </span>
                                @endif
                            @elseif(auth()->user()->role === 'admin')
                                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                    <i class="fas fa-home mr-2"></i>Dashboard
                                </a>
                                <a href="{{ route('admin.kelola-data') }}" class="nav-link {{ request()->routeIs('admin.kelola-data') ? 'active' : '' }}">
                                    <i class="fas fa-cogs mr-2"></i>Menu Kelola
                                </a>
                                <a href="{{ route('admin.system-settings') }}" class="nav-link {{ request()->routeIs('admin.system-settings') ? 'active' : '' }}">
                                    <i class="fas fa-sliders-h mr-2"></i>Menu Sistem
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center space-x-2">
                    <!-- Profile Button -->
                    <a href="{{ route('profile.index') }}" class="profile-icon" title="Profile">
                        @if(auth()->user()->photo)
                            @if(auth()->user()->google_linked)
                                <img src="{{ auth()->user()->photo }}" alt="Profile" class="h-8 w-8 rounded-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='inline';">
                                <i class="fas fa-user" style="display: none;"></i>
                            @else
                                <img src="{{ asset('storage/' . auth()->user()->photo) }}" alt="Profile" class="h-8 w-8 rounded-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='inline';">
                                <i class="fas fa-user" style="display: none;"></i>
                            @endif
                        @else
                            <i class="fas fa-user"></i>
                        @endif
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
    @endauth

    <!-- Main Content -->
    <main class="py-6 @auth pt-24 @endauth">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="border-t mt-12">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
            <p class="text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} Sistem Informasi Pengelolaan PKL. All rights reserved.
            </p>
        </div>
    </footer>

    {{-- ====== tambahkan baris ini agar @push('scripts') dari view lain ikut dirender ====== --}}
    @stack('scripts')
</body>
</html>
