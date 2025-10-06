@extends('layouts.app')

@section('title', 'Kelola Data - SIPP PKL')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Menu Kelola</h1>
                <p class="text-gray-600 mt-2">Kelola semua data dalam sistem</p>
            </div>
            <a href="{{ route('dashboard') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Management Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Kelola Akun -->
        <a href="{{ route('admin.kelola-akun') }}" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <i class="fas fa-users-cog text-3xl text-blue-600 mr-4"></i>
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Kelola Akun</h3>
                    <p class="text-sm text-gray-600">Kelola user dan role dalam sistem</p>
                </div>
            </div>
        </a>


        <!-- Kelola Instansi Mitra -->
        <a href="{{ route('admin.kelola-mitra') }}" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <i class="fas fa-building text-3xl text-orange-600 mr-4"></i>
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Kelola Instansi Mitra</h3>
                    <p class="text-sm text-gray-600">Kelola data mitra PKL</p>
                </div>
            </div>
        </a>

        <!-- Kelola Jadwal Seminar -->
        <a href="{{ route('admin.jadwal-seminar.manage') }}" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <i class="fas fa-calendar-alt text-3xl text-purple-600 mr-4"></i>
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Kelola Jadwal Seminar</h3>
                    <p class="text-sm text-gray-600">Kelola jadwal seminar PKL</p>
                </div>
            </div>
        </a>

        <!-- Kelola Rubrik Penilaian -->
        <a href="{{ route('admin.rubrik.index') }}" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <i class="fas fa-clipboard-list text-3xl text-orange-600 mr-4"></i>
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Kelola Rubrik Penilaian</h3>
                    <p class="text-sm text-gray-600">Kelola rubrik penilaian PKL</p>
                </div>
            </div>
        </a>

        <!-- Kelola Pemberkasan Mahasiswa -->
        <a href="{{ route('admin.validation') }}" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <i class="fas fa-file-alt text-3xl text-indigo-600 mr-4"></i>
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Kelola Pemberkasan Mahasiswa</h3>
                    <p class="text-sm text-gray-600">Validasi dokumen mahasiswa</p>
                </div>
            </div>
        </a>

        <!-- Penilaian Dosen Pembimbing -->
        <a href="{{ route('admin.penilaian-dosen') }}" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <i class="fas fa-chalkboard-teacher text-3xl text-red-600 mr-4"></i>
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Penilaian Dosen Pembimbing</h3>
                    <p class="text-sm text-gray-600">Lihat hasil penilaian oleh dosen pembimbing</p>
                </div>
            </div>
        </a>

        <!-- Nilai Akhir Mahasiswa -->
        <a href="{{ route('admin.nilai-akhir') }}" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <i class="fas fa-graduation-cap text-3xl text-yellow-600 mr-4"></i>
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Nilai Akhir Mahasiswa</h3>
                    <p class="text-sm text-gray-600">Lihat seluruh data nilai akhir mahasiswa</p>
                </div>
            </div>
        </a>

        <!-- Tracking Log Aktivitas -->
        <a href="{{ route('activity') }}" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <i class="fas fa-history text-3xl text-gray-600 mr-4"></i>
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Tracking Log Aktivitas</h3>
                    <p class="text-sm text-gray-600">Lihat semua aktivitas sistem</p>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection
