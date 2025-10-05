<!-- Mahasiswa Dashboard -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- Progress Card -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-chart-line text-2xl text-blue-600"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Progress Berkas</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ $stats['progress_berkas'] }} berkas</dd>
                    </dl>
                </div>
            </div>
            <div class="mt-4">
                <div class="bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $stats['progress_percentage'] }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- KHS Status -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-file-alt text-2xl text-green-600"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">KHS</dt>
                        <dd class="text-lg font-medium 
                            @if($stats['khs_status'] === 'tervalidasi') text-green-600
                            @elseif($stats['khs_status'] === 'belum_valid') text-red-600
                            @elseif($stats['khs_status'] === 'revisi') text-yellow-600
                            @else text-gray-600 @endif">
                            @if($stats['khs_status'] === 'tervalidasi') Tervalidasi
                            @elseif($stats['khs_status'] === 'belum_valid') Belum Valid
                            @elseif($stats['khs_status'] === 'revisi') Revisi
                            @else Belum Upload @endif
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Surat Balasan Status -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-envelope text-2xl text-yellow-600"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Surat Balasan</dt>
                        <dd class="text-lg font-medium 
                            @if($stats['surat_status'] === 'tervalidasi') text-green-600
                            @elseif($stats['surat_status'] === 'belum_valid') text-red-600
                            @elseif($stats['surat_status'] === 'revisi') text-yellow-600
                            @else text-gray-600 @endif">
                            @if($stats['surat_status'] === 'tervalidasi') Tervalidasi
                            @elseif($stats['surat_status'] === 'belum_valid') Belum Valid
                            @elseif($stats['surat_status'] === 'revisi') Revisi
                            @else Belum Upload @endif
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Laporan Status -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-book text-2xl text-red-600"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Laporan PKL</dt>
                        <dd class="text-lg font-medium 
                            @if($stats['laporan_status'] === 'tervalidasi') text-green-600
                            @elseif($stats['laporan_status'] === 'belum_valid') text-red-600
                            @elseif($stats['laporan_status'] === 'revisi') text-yellow-600
                            @else text-gray-600 @endif">
                            @if($stats['laporan_status'] === 'tervalidasi') Tervalidasi
                            @elseif($stats['laporan_status'] === 'belum_valid') Belum Valid
                            @elseif($stats['laporan_status'] === 'revisi') Revisi
                            @else Belum Upload @endif
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="mt-8">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Aksi Cepat</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('documents.index') }}" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <i class="fas fa-upload text-2xl text-blue-600 mr-4"></i>
                <div>
                    <h4 class="font-medium text-gray-900">Upload Dokumen</h4>
                    <p class="text-sm text-gray-600">Upload KHS, Surat Balasan, atau Laporan</p>
                </div>
            </div>
        </a>

        <a href="{{ route('profile.index') }}" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <i class="fas fa-user text-2xl text-green-600 mr-4"></i>
                <div>
                    <h4 class="font-medium text-gray-900">Profile</h4>
                    <p class="text-sm text-gray-600">Kelola data pribadi dan foto</p>
                </div>
            </div>
        </a>

        <a href="#" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <i class="fas fa-calendar text-2xl text-purple-600 mr-4"></i>
                <div>
                    <h4 class="font-medium text-gray-900">Jadwal Seminar</h4>
                    <p class="text-sm text-gray-600">Lihat jadwal seminar PKL</p>
                </div>
            </div>
        </a>
    </div>
</div>
