@extends('layouts.app')

@section('title', 'Kelola Data - SIPP PKL')

@section('content')
<div class="space-y-8">
    <!-- Header with Gradient -->
    <div class="relative overflow-hidden bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 shadow-2xl rounded-2xl p-8">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="relative z-10">
            <h1 class="text-3xl font-bold text-white mb-2">Menu Kelola</h1>
            <p class="text-blue-100">Kelola semua data dalam sistem dengan mudah dan efisien</p>
        </div>
        <!-- Decorative circles -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mr-32 -mt-32"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full -ml-24 -mb-24"></div>
    </div>

    <!-- Management Cards with Modern Design -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Kelola Akun -->
        <a href="{{ route('admin.kelola-akun') }}" class="group relative bg-white p-6 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative z-10">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg transform group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-users-cog text-2xl text-white"></i>
                    </div>
                    <div class="bg-blue-100 text-blue-600 text-xs font-semibold px-3 py-1 rounded-full">Akun</div>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors">Kelola Akun</h3>
                <p class="text-sm text-gray-600">Kelola user dan role dalam sistem</p>
                <div class="mt-4 flex items-center text-blue-600 text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity">
                    <span>Kelola Sekarang</span>
                    <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform"></i>
                </div>
            </div>
        </a>

        <!-- Kelola Instansi Mitra -->
        <a href="{{ route('admin.kelola-mitra') }}" class="group relative bg-white p-6 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-orange-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative z-10">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg transform group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-building text-2xl text-white"></i>
                    </div>
                    <div class="bg-orange-100 text-orange-600 text-xs font-semibold px-3 py-1 rounded-full">Mitra</div>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-orange-600 transition-colors">Kelola Instansi Mitra</h3>
                <p class="text-sm text-gray-600">Kelola data mitra PKL</p>
                <div class="mt-4 flex items-center text-orange-600 text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity">
                    <span>Kelola Sekarang</span>
                    <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform"></i>
                </div>
            </div>
        </a>

        <!-- Kelola Jadwal Seminar -->
        <a href="{{ route('admin.jadwal-seminar.manage') }}" class="group relative bg-white p-6 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-purple-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative z-10">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg transform group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-calendar-alt text-2xl text-white"></i>
                    </div>
                    <div class="bg-purple-100 text-purple-600 text-xs font-semibold px-3 py-1 rounded-full">Jadwal</div>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-purple-600 transition-colors">Kelola Jadwal Seminar</h3>
                <p class="text-sm text-gray-600">Kelola jadwal seminar PKL</p>
                <div class="mt-4 flex items-center text-purple-600 text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity">
                    <span>Kelola Sekarang</span>
                    <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform"></i>
                </div>
            </div>
        </a>

        <!-- Penilaian Mahasiswa -->
        <a href="{{ route('admin.rubrik.index') }}" class="group relative bg-white p-6 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-teal-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative z-10">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl flex items-center justify-center shadow-lg transform group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-user-check text-2xl text-white"></i>
                    </div>
                    <div class="bg-teal-100 text-teal-600 text-xs font-semibold px-3 py-1 rounded-full">Penilaian</div>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-teal-600 transition-colors">Penilaian Mahasiswa</h3>
                <p class="text-sm text-gray-600">Lakukan penilaian mahasiswa PKL</p>
                <div class="mt-4 flex items-center text-teal-600 text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity">
                    <span>Kelola Sekarang</span>
                    <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform"></i>
                </div>
            </div>
        </a>

        <!-- Kelola Pemberkasan Mahasiswa -->
        <a href="{{ route('admin.validation') }}" class="group relative bg-white p-6 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative z-10">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg transform group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-file-alt text-2xl text-white"></i>
                    </div>
                    <div class="bg-indigo-100 text-indigo-600 text-xs font-semibold px-3 py-1 rounded-full">Berkas</div>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-indigo-600 transition-colors">Kelola Pemberkasan Mahasiswa</h3>
                <p class="text-sm text-gray-600">Validasi dokumen mahasiswa</p>
                <div class="mt-4 flex items-center text-indigo-600 text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity">
                    <span>Kelola Sekarang</span>
                    <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform"></i>
                </div>
            </div>
        </a>

        <!-- Penilaian Dosen Pembimbing -->
        <a href="{{ route('admin.penilaian-dosen') }}" class="group relative bg-white p-6 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-red-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative z-10">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg transform group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-chalkboard-teacher text-2xl text-white"></i>
                    </div>
                    <div class="bg-red-100 text-red-600 text-xs font-semibold px-3 py-1 rounded-full">Dospem</div>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-red-600 transition-colors">Penilaian Dosen Pembimbing</h3>
                <p class="text-sm text-gray-600">Lihat hasil penilaian oleh dosen pembimbing</p>
                <div class="mt-4 flex items-center text-red-600 text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity">
                    <span>Kelola Sekarang</span>
                    <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform"></i>
                </div>
            </div>
        </a>

        <!-- Nilai Akhir Mahasiswa -->
        <a href="{{ route('admin.nilai-akhir') }}" class="group relative bg-white p-6 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-yellow-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative z-10">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center shadow-lg transform group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-graduation-cap text-2xl text-white"></i>
                    </div>
                    <div class="bg-yellow-100 text-yellow-700 text-xs font-semibold px-3 py-1 rounded-full">Nilai</div>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-yellow-600 transition-colors">Nilai Akhir Mahasiswa</h3>
                <p class="text-sm text-gray-600">Lihat seluruh data nilai akhir mahasiswa</p>
                <div class="mt-4 flex items-center text-yellow-600 text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity">
                    <span>Kelola Sekarang</span>
                    <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform"></i>
                </div>
            </div>
        </a>

        <!-- Tracking Log Aktivitas -->
        <a href="{{ route('activity') }}" class="group relative bg-white p-6 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-gray-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative z-10">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-gray-500 to-gray-600 rounded-xl flex items-center justify-center shadow-lg transform group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-history text-2xl text-white"></i>
                    </div>
                    <div class="bg-gray-100 text-gray-700 text-xs font-semibold px-3 py-1 rounded-full">Log</div>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-gray-700 transition-colors">Tracking Log Aktivitas</h3>
                <p class="text-sm text-gray-600">Lihat semua aktivitas sistem</p>
                <div class="mt-4 flex items-center text-gray-700 text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity">
                    <span>Kelola Sekarang</span>
                    <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform"></i>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection
