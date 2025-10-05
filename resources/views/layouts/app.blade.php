<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Informasi Pengelolaan PKL')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .nav-link {
            color: #6b7280;
            transition: all 0.2s;
            position: relative;
        }
        .nav-link:hover {
            color: #2563eb;
        }
        .nav-link.active {
            color: #1f2937;
            background-color: #f3f4f6;
            font-weight: 600;
        }
        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background-color: #2563eb;
            border-radius: 0;
        }
    </style>
</head>
<body class="bg-gray-50">
    @auth
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <a href="{{ route('dashboard') }}" class="text-xl font-bold text-gray-900 hover:text-blue-600 transition-colors">
                            SIP PKL
                        </a>
                    </div>
                    
                    <!-- Navigation Menu -->
                    <div class="hidden md:block ml-10">
                        <div class="flex items-baseline space-x-4">
                            @if(auth()->user()->role === 'mahasiswa')
                                <a href="{{ route('dashboard') }}" class="nav-link flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('dashboard') ? 'active' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}">
                                    <i class="fas fa-home mr-2"></i>Dashboard
                                </a>
                                <a href="{{ route('documents.index') }}" class="nav-link flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('documents.*') ? 'active' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}">
                                    <i class="fas fa-file-upload mr-2"></i>Pemberkasan
                                </a>
                                <a href="{{ route('mitra') }}" class="nav-link flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('mitra') ? 'active' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}">
                                    <i class="fas fa-building mr-2"></i>Instansi Mitra
                                </a>
                                <a href="{{ route('jadwal-seminar') }}" class="nav-link flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('jadwal-seminar') ? 'active' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}">
                                    <i class="fas fa-calendar-alt mr-2"></i>Jadwal Seminar
                                </a>
                            @elseif(auth()->user()->role === 'dospem')
                                <a href="{{ route('dashboard') }}" class="nav-link flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('dashboard') ? 'active' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}">
                                    <i class="fas fa-home mr-2"></i>Dashboard
                                </a>
                                <a href="{{ route('dospem.validation') }}" class="nav-link flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('dospem.validation') ? 'active' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}">
                                    <i class="fas fa-file-check mr-2"></i>Berkas Mahasiswa
                                </a>
                                <a href="{{ route('jadwal-seminar') }}" class="nav-link flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('jadwal-seminar') ? 'active' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}">
                                    <i class="fas fa-calendar-alt mr-2"></i>Jadwal Seminar
                                </a>
                            @elseif(auth()->user()->role === 'admin')
                                <a href="{{ route('dashboard') }}" class="nav-link flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('dashboard') ? 'active' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}">
                                    <i class="fas fa-home mr-2"></i>Dashboard
                                </a>
                                <a href="{{ route('admin.kelola-data') }}" class="nav-link flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.kelola-data') ? 'active' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}">
                                    <i class="fas fa-cogs mr-2"></i>Kelola Data
                                </a>
                                <a href="{{ route('admin.system-settings') }}" class="nav-link flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.system-settings') ? 'active' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}">
                                    <i class="fas fa-sliders-h mr-2"></i>Menu Sistem
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <!-- Profile Button -->
                    <a href="{{ route('profile.index') }}" class="text-gray-500 hover:text-gray-700" title="Profile">
                        <i class="fas fa-user"></i>
                    </a>
                    
                    <!-- Activity Log Button (Hidden for Mahasiswa) -->
                    @if(auth()->user()->role !== 'mahasiswa')
                    <a href="{{ route('activity') }}" class="text-gray-500 hover:text-gray-700" title="Log Aktivitas">
                        <i class="fas fa-history"></i>
                    </a>
                    @endif
                    
                    <!-- Settings Button -->
                    <a href="{{ route('profile.settings') }}" class="text-gray-500 hover:text-gray-700" title="Pengaturan">
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
</body>
</html>