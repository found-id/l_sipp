@extends('layouts.app')

@section('title', 'Dashboard Admin - SIP PKL')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg p-6">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard Admin</h1>
        <p class="text-gray-600 mt-2">Selamat datang di dashboard administrator</p>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-users text-2xl text-blue-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Mahasiswa</dt>
                            <dd class="text-lg font-medium text-blue-600">{{ $stats['total_mahasiswa'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-chalkboard-teacher text-2xl text-green-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Dosen</dt>
                            <dd class="text-lg font-medium text-green-600">{{ $stats['total_dosen'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-building text-2xl text-purple-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Mitra</dt>
                            <dd class="text-lg font-medium text-purple-600">{{ $stats['total_mitra'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-clock text-2xl text-orange-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Pending Validasi</dt>
                            <dd class="text-lg font-medium text-orange-600">{{ $stats['berkas_pending'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-2xl text-green-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Berkas Tervalidasi</dt>
                            <dd class="text-lg font-medium text-green-600">{{ $stats['berkas_tervalidasi'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-times-circle text-2xl text-red-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Berkas Belum Valid</dt>
                            <dd class="text-lg font-medium text-red-600">{{ $stats['berkas_belum_valid'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-user-shield text-2xl text-indigo-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Admin</dt>
                            <dd class="text-lg font-medium text-indigo-600">{{ $stats['total_admin'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    @if(isset($stats['recent_activities']) && $stats['recent_activities']->count() > 0)
    <div class="mt-8">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Aktivitas Terbaru</h3>
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                @foreach($stats['recent_activities'] as $activity)
                <li class="px-6 py-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            <div class="h-10 w-10 rounded-full flex items-center justify-center
                                @if($activity->tipe === 'upload_dokumen') bg-blue-100
                                @elseif($activity->tipe === 'validasi_dokumen') bg-green-100
                                @elseif($activity->tipe === 'login') bg-purple-100
                                @elseif($activity->tipe === 'logout') bg-red-100
                                @else bg-gray-100 @endif">
                                <i class="fas 
                                    @if($activity->tipe === 'upload_dokumen') fa-upload
                                    @elseif($activity->tipe === 'validasi_dokumen') fa-check
                                    @elseif($activity->tipe === 'login') fa-sign-in-alt
                                    @elseif($activity->tipe === 'logout') fa-sign-out-alt
                                    @else fa-info @endif
                                    @if($activity->tipe === 'upload_dokumen') text-blue-600
                                    @elseif($activity->tipe === 'validasi_dokumen') text-green-600
                                    @elseif($activity->tipe === 'login') text-purple-600
                                    @elseif($activity->tipe === 'logout') text-red-600
                                    @else text-gray-600 @endif"></i>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-sm font-medium text-gray-900">
                                @if($activity->tipe === 'upload_dokumen')
                                    {{ $activity->mahasiswa->name ?? $activity->user->name }} mengupload {{ $activity->pesan['document_type'] ?? 'dokumen' }}
                                @elseif($activity->tipe === 'validasi_dokumen')
                                    {{ $activity->user->name }} memvalidasi dokumen {{ $activity->pesan['document_type'] ?? '' }} milik {{ $activity->mahasiswa->name ?? '' }}
                                @elseif($activity->tipe === 'login')
                                    {{ $activity->user->name }} melakukan login
                                @elseif($activity->tipe === 'logout')
                                    {{ $activity->user->name }} melakukan logout
                                @else
                                    {{ $activity->pesan['action'] ?? 'Aktivitas' }}
                                @endif
                            </p>
                            <p class="text-sm text-gray-500">{{ $activity->tanggal_dibuat->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <!-- Management Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <a href="{{ route('admin.kelola-data') }}" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <i class="fas fa-cogs text-2xl text-blue-600 mr-4"></i>
                <div>
                    <h4 class="font-medium text-gray-900">Kelola Data</h4>
                    <p class="text-sm text-gray-600">Kelola semua data sistem</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.validation') }}" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <i class="fas fa-clipboard-check text-2xl text-purple-600 mr-4"></i>
                <div>
                    <h4 class="font-medium text-gray-900">Validasi Dokumen</h4>
                    <p class="text-sm text-gray-600">Validasi semua dokumen mahasiswa</p>
                </div>
            </div>
        </a>

        <a href="{{ route('activity') }}" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <i class="fas fa-history text-2xl text-indigo-600 mr-4"></i>
                <div>
                    <h4 class="font-medium text-gray-900">Log Aktivitas</h4>
                    <p class="text-sm text-gray-600">Lihat semua aktivitas sistem</p>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection