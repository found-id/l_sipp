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
        }
        .nav-link:hover {
            color: #2563eb;
        }
        .nav-link.active {
            color: #1f2937;
            background-color: #f3f4f6;
            font-weight: 600;
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
                            SIPP PKL
                        </a>
                    </div>
                    
                    <!-- Navigation Menu -->
                    <div class="hidden md:block ml-10">
                        <div class="flex items-baseline space-x-4">
                            @if(auth()->user()->role === 'mahasiswa')
                                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }} px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
                                <a href="{{ route('documents.index') }}" class="nav-link {{ request()->routeIs('documents.*') ? 'active' : '' }} px-3 py-2 rounded-md text-sm font-medium">Pemberkasan</a>
                                <a href="{{ route('mitra') }}" class="nav-link {{ request()->routeIs('mitra') ? 'active' : '' }} px-3 py-2 rounded-md text-sm font-medium">Instansi Mitra</a>
                                <a href="{{ route('jadwal-seminar') }}" class="nav-link {{ request()->routeIs('jadwal-seminar') ? 'active' : '' }} px-3 py-2 rounded-md text-sm font-medium">Jadwal Seminar</a>
                            @elseif(auth()->user()->role === 'dospem')
                                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }} px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
                                <a href="{{ route('dospem.validation') }}" class="nav-link {{ request()->routeIs('dospem.validation') ? 'active' : '' }} px-3 py-2 rounded-md text-sm font-medium">Berkas Mahasiswa</a>
                                <a href="{{ route('jadwal-seminar') }}" class="nav-link {{ request()->routeIs('jadwal-seminar') ? 'active' : '' }} px-3 py-2 rounded-md text-sm font-medium">Jadwal Seminar</a>
                            @elseif(auth()->user()->role === 'admin')
                                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }} px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
                                <a href="{{ route('admin.kelola-data') }}" class="nav-link {{ request()->routeIs('admin.kelola-data') ? 'active' : '' }} px-3 py-2 rounded-md text-sm font-medium">Kelola Data</a>
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