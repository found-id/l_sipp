@extends('layouts.app')

@section('title', 'Kelola Data - SIP PKL')

@section('content')
<div class="space-y-4 md:space-y-8">
    <!-- Header with Gradient -->
    <div class="relative overflow-hidden bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 shadow-lg md:shadow-2xl rounded-xl md:rounded-2xl p-4 md:p-8">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="relative z-10">
            <h1 class="text-xl md:text-3xl font-bold text-white mb-1 md:mb-2">Menu Kelola</h1>
            <p class="text-sm md:text-base text-blue-100">Kelola data sistem</p>
        </div>
        <!-- Decorative circles -->
        <div class="absolute top-0 right-0 w-32 md:w-64 h-32 md:h-64 bg-white/5 rounded-full -mr-16 md:-mr-32 -mt-16 md:-mt-32"></div>
        <div class="absolute bottom-0 left-0 w-24 md:w-48 h-24 md:h-48 bg-white/5 rounded-full -ml-12 md:-ml-24 -mb-12 md:-mb-24"></div>
    </div>

    <!-- Management Cards with Modern Design -->
    <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-6">
        <!-- Kelola Akun -->
        <a href="{{ route('admin.kelola-akun') }}" class="group relative bg-white p-3 md:p-6 rounded-xl md:rounded-2xl shadow-md md:shadow-lg hover:shadow-xl md:hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 md:hover:-translate-y-2 border border-gray-100 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative z-10">
                <div class="flex items-start justify-between mb-2 md:mb-4">
                    <div class="w-10 h-10 md:w-14 md:h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg md:rounded-xl flex items-center justify-center shadow-md md:shadow-lg">
                        <i class="fas fa-users-cog text-lg md:text-2xl text-white"></i>
                    </div>
                    <div class="bg-blue-100 text-blue-600 text-[10px] md:text-xs font-semibold px-2 md:px-3 py-0.5 md:py-1 rounded-full">Akun</div>
                </div>
                <h3 class="text-sm md:text-xl font-bold text-gray-900 mb-1 md:mb-2">Kelola Akun</h3>
                <p class="text-xs md:text-sm text-gray-600 hidden md:block">Kelola user dan role dalam sistem</p>
                <div class="mt-2 md:mt-4 hidden md:flex items-center text-blue-600 text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity">
                    <span>Kelola Sekarang</span>
                    <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform"></i>
                </div>
            </div>
        </a>

        <!-- Kelola Instansi Mitra -->
        <a href="{{ route('admin.kelola-mitra') }}" class="group relative bg-white p-3 md:p-6 rounded-xl md:rounded-2xl shadow-md md:shadow-lg hover:shadow-xl md:hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 md:hover:-translate-y-2 border border-gray-100 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-orange-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative z-10">
                <div class="flex items-start justify-between mb-2 md:mb-4">
                    <div class="w-10 h-10 md:w-14 md:h-14 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg md:rounded-xl flex items-center justify-center shadow-md md:shadow-lg">
                        <i class="fas fa-building text-lg md:text-2xl text-white"></i>
                    </div>
                    <div class="bg-orange-100 text-orange-600 text-[10px] md:text-xs font-semibold px-2 md:px-3 py-0.5 md:py-1 rounded-full">Mitra</div>
                </div>
                <h3 class="text-sm md:text-xl font-bold text-gray-900 mb-1 md:mb-2">Instansi Mitra</h3>
                <p class="text-xs md:text-sm text-gray-600 hidden md:block">Kelola data mitra PKL</p>
                <div class="mt-2 md:mt-4 hidden md:flex items-center text-orange-600 text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity">
                    <span>Kelola Sekarang</span>
                    <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform"></i>
                </div>
            </div>
        </a>

        <!-- Kelola Jadwal Seminar -->
        <a href="{{ route('admin.jadwal-seminar.manage') }}" class="group relative bg-white p-3 md:p-6 rounded-xl md:rounded-2xl shadow-md md:shadow-lg hover:shadow-xl md:hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 md:hover:-translate-y-2 border border-gray-100 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-purple-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative z-10">
                <div class="flex items-start justify-between mb-2 md:mb-4">
                    <div class="w-10 h-10 md:w-14 md:h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg md:rounded-xl flex items-center justify-center shadow-md md:shadow-lg">
                        <i class="fas fa-calendar-alt text-lg md:text-2xl text-white"></i>
                    </div>
                    <div class="bg-purple-100 text-purple-600 text-[10px] md:text-xs font-semibold px-2 md:px-3 py-0.5 md:py-1 rounded-full">Jadwal</div>
                </div>
                <h3 class="text-sm md:text-xl font-bold text-gray-900 mb-1 md:mb-2">Jadwal Seminar</h3>
                <p class="text-xs md:text-sm text-gray-600 hidden md:block">Kelola jadwal seminar PKL</p>
                <div class="mt-2 md:mt-4 hidden md:flex items-center text-purple-600 text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity">
                    <span>Kelola Sekarang</span>
                    <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform"></i>
                </div>
            </div>
        </a>

        <!-- Penilaian Seminar Mahasiswa -->
        <a href="{{ route('admin.rubrik.index') }}" class="group relative bg-white p-3 md:p-6 rounded-xl md:rounded-2xl shadow-md md:shadow-lg hover:shadow-xl md:hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 md:hover:-translate-y-2 border border-gray-100 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-teal-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative z-10">
                <div class="flex items-start justify-between mb-2 md:mb-4">
                    <div class="w-10 h-10 md:w-14 md:h-14 bg-gradient-to-br from-teal-500 to-teal-600 rounded-lg md:rounded-xl flex items-center justify-center shadow-md md:shadow-lg">
                        <i class="fas fa-user-check text-lg md:text-2xl text-white"></i>
                    </div>
                    <div class="bg-teal-100 text-teal-600 text-[10px] md:text-xs font-semibold px-2 md:px-3 py-0.5 md:py-1 rounded-full">Penilaian</div>
                </div>
                <h3 class="text-sm md:text-xl font-bold text-gray-900 mb-1 md:mb-2">Penilaian</h3>
                <p class="text-xs md:text-sm text-gray-600 hidden md:block">Penilaian mahasiswa PKL</p>
                <div class="mt-2 md:mt-4 hidden md:flex items-center text-teal-600 text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity">
                    <span>Kelola Sekarang</span>
                    <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform"></i>
                </div>
            </div>
        </a>

        <!-- Kelola Pemberkasan Mahasiswa -->
        <a href="{{ route('admin.validation') }}" class="group relative bg-white p-3 md:p-6 rounded-xl md:rounded-2xl shadow-md md:shadow-lg hover:shadow-xl md:hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 md:hover:-translate-y-2 border border-gray-100 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative z-10">
                <div class="flex items-start justify-between mb-2 md:mb-4">
                    <div class="w-10 h-10 md:w-14 md:h-14 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg md:rounded-xl flex items-center justify-center shadow-md md:shadow-lg">
                        <i class="fas fa-file-alt text-lg md:text-2xl text-white"></i>
                    </div>
                    <div class="bg-indigo-100 text-indigo-600 text-[10px] md:text-xs font-semibold px-2 md:px-3 py-0.5 md:py-1 rounded-full">Berkas</div>
                </div>
                <h3 class="text-sm md:text-xl font-bold text-gray-900 mb-1 md:mb-2">Pemberkasan</h3>
                <p class="text-xs md:text-sm text-gray-600 hidden md:block">Validasi dokumen mahasiswa</p>
                <div class="mt-2 md:mt-4 hidden md:flex items-center text-indigo-600 text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity">
                    <span>Kelola Sekarang</span>
                    <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform"></i>
                </div>
            </div>
        </a>

        <!-- Tracking Log Aktivitas -->
        <a href="{{ route('activity') }}" class="group relative bg-white p-3 md:p-6 rounded-xl md:rounded-2xl shadow-md md:shadow-lg hover:shadow-xl md:hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 md:hover:-translate-y-2 border border-gray-100 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-gray-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative z-10">
                <div class="flex items-start justify-between mb-2 md:mb-4">
                    <div class="w-10 h-10 md:w-14 md:h-14 bg-gradient-to-br from-gray-500 to-gray-600 rounded-lg md:rounded-xl flex items-center justify-center shadow-md md:shadow-lg">
                        <i class="fas fa-history text-lg md:text-2xl text-white"></i>
                    </div>
                    <div class="bg-gray-100 text-gray-700 text-[10px] md:text-xs font-semibold px-2 md:px-3 py-0.5 md:py-1 rounded-full">Log</div>
                </div>
                <h3 class="text-sm md:text-xl font-bold text-gray-900 mb-1 md:mb-2">Log Aktivitas</h3>
                <p class="text-xs md:text-sm text-gray-600 hidden md:block">Lihat aktivitas sistem</p>
                <div class="mt-2 md:mt-4 hidden md:flex items-center text-gray-700 text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity">
                    <span>Kelola Sekarang</span>
                    <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform"></i>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection
