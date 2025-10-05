<!-- Admin Dashboard -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- Total Mahasiswa -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-users text-2xl text-blue-600"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Mahasiswa</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ $stats['total_mahasiswa'] ?? 0 }} mahasiswa</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Dosen -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-chalkboard-teacher text-2xl text-green-600"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Dosen</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ $stats['total_dosen'] ?? 0 }} dosen</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Mitra -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-building text-2xl text-purple-600"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Mitra</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ $stats['total_mitra'] ?? 0 }} mitra</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Berkas Pending -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-clock text-2xl text-yellow-600"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Berkas Pending</dt>
                        <dd class="text-lg font-medium text-yellow-600">{{ $stats['berkas_pending'] ?? 0 }} berkas</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Overview -->
<div class="mt-8">
    <!-- Berkas Status -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Status Berkas</h3>
            <div class="mt-5">
                @php
                    $totalBerkas = ($stats['berkas_pending'] ?? 0) + ($stats['berkas_tervalidasi'] ?? 0) + ($stats['berkas_belum_valid'] ?? 0);
                    $tervalidasiPercent = $totalBerkas > 0 ? round((($stats['berkas_tervalidasi'] ?? 0) / $totalBerkas) * 100) : 0;
                    $pendingPercent = $totalBerkas > 0 ? round((($stats['berkas_pending'] ?? 0) / $totalBerkas) * 100) : 0;
                    $belumValidPercent = $totalBerkas > 0 ? round((($stats['berkas_belum_valid'] ?? 0) / $totalBerkas) * 100) : 0;
                @endphp
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Tervalidasi</span>
                        <span class="text-sm font-medium text-green-600">{{ $tervalidasiPercent }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full" style="width: {{ $tervalidasiPercent }}%"></div>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Menunggu Validasi</span>
                        <span class="text-sm font-medium text-yellow-600">{{ $pendingPercent }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-yellow-600 h-2 rounded-full" style="width: {{ $pendingPercent }}%"></div>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Belum Valid</span>
                        <span class="text-sm font-medium text-red-600">{{ $belumValidPercent }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-red-600 h-2 rounded-full" style="width: {{ $belumValidPercent }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <!-- Unassigned Students -->
    @if(isset($stats['unassigned_students']) && $stats['unassigned_students']->count() > 0)
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
        <div class="flex items-center mb-4">
            <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
            <h3 class="text-lg font-medium text-yellow-800">Mahasiswa Belum Punya Dospem</h3>
        </div>
        <div class="space-y-3">
            @foreach($stats['unassigned_students'] as $student)
            <div class="bg-white p-4 rounded-lg border border-yellow-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="font-medium text-gray-900">{{ $student->name }}</h4>
                        <p class="text-sm text-gray-600">NIM: {{ $student->profilMahasiswa->nim ?? 'Belum diisi' }} | Email: {{ $student->email }}</p>
                    </div>
                    <a href="{{ route('admin.kelola-akun') }}?edit={{ $student->id }}&focus=dospem" 
                       class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-sm">
                        <i class="fas fa-user-plus mr-1"></i>Tetapkan Dospem
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Aktivitas Terbaru</h3>
                <a href="{{ route('activity') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                    Tampilkan semua
                </a>
            </div>
            <div class="mt-5">
                @if(isset($stats['recent_activities']) && $stats['recent_activities']->count() > 0)
                    <div class="space-y-4">
                        @foreach($stats['recent_activities'] as $activity)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-start justify-between">
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        @if($activity->tipe === 'upload_dokumen')
                                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-upload text-blue-600 text-sm"></i>
                                            </div>
                                        @elseif($activity->tipe === 'validasi_dokumen')
                                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-check text-green-600 text-sm"></i>
                                            </div>
                                        @elseif($activity->tipe === 'login')
                                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-sign-in-alt text-purple-600 text-sm"></i>
                                            </div>
                                        @elseif($activity->tipe === 'logout')
                                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-sign-out-alt text-red-600 text-sm"></i>
                                            </div>
                                        @else
                                            <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-info-circle text-gray-600 text-sm"></i>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900">
                                            @if(is_array($activity->pesan))
                                                @if($activity->pesan['action'] === 'upload_dokumen')
                                                    {{ $activity->pesan['mahasiswa'] }} mengunggah berkas {{ $activity->pesan['document_type'] }}
                                                @elseif($activity->pesan['action'] === 'validasi_dokumen')
                                                    {{ $activity->user->name }} memvalidasi dokumen {{ $activity->pesan['document_type'] }} milik {{ $activity->pesan['mahasiswa'] }}
                                                @elseif($activity->pesan['action'] === 'login')
                                                    {{ $activity->pesan['user'] }} ({{ ucfirst($activity->pesan['role']) }}) melakukan login
                                                @elseif($activity->pesan['action'] === 'logout')
                                                    {{ $activity->pesan['user'] }} ({{ ucfirst($activity->pesan['role']) }}) melakukan logout
                                                @else
                                                    {{ json_encode($activity->pesan) }}
                                                @endif
                                            @else
                                                {{ $activity->pesan }}
                                            @endif
                                        </p>
                                        
                                        @if($activity->pesan['file_name'] ?? false)
                                            <p class="text-sm text-gray-500 mt-1">
                                                <i class="fas fa-file mr-1"></i>{{ $activity->pesan['file_name'] }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="text-right">
                                    <p class="text-xs text-gray-500">
                                        {{ $activity->tanggal_dibuat->format('d M Y H:i') }}
                                    </p>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($activity->tipe === 'upload_dokumen') bg-blue-100 text-blue-800
                                        @elseif($activity->tipe === 'validasi_dokumen') bg-green-100 text-green-800
                                        @elseif($activity->tipe === 'login') bg-purple-100 text-purple-800
                                        @elseif($activity->tipe === 'logout') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $activity->tipe)) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <p class="text-sm text-gray-500">Belum ada aktivitas terbaru</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Management Actions -->
<div class="mt-8">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Kelola Sistem</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="#" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <i class="fas fa-users-cog text-2xl text-blue-600 mr-4"></i>
                <div>
                    <h4 class="font-medium text-gray-900">Kelola Akun</h4>
                    <p class="text-sm text-gray-600">Kelola user dan role</p>
                </div>
            </div>
        </a>

        <a href="#" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <i class="fas fa-building text-2xl text-green-600 mr-4"></i>
                <div>
                    <h4 class="font-medium text-gray-900">Kelola Mitra</h4>
                    <p class="text-sm text-gray-600">Kelola data mitra PKL</p>
                </div>
            </div>
        </a>

        <a href="#" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <i class="fas fa-clipboard-list text-2xl text-purple-600 mr-4"></i>
                <div>
                    <h4 class="font-medium text-gray-900">Kelola Rubrik</h4>
                    <p class="text-sm text-gray-600">Kelola rubrik penilaian</p>
                </div>
            </div>
        </a>

        <a href="#" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <i class="fas fa-calendar-alt text-2xl text-yellow-600 mr-4"></i>
                <div>
                    <h4 class="font-medium text-gray-900">Jadwal Seminar</h4>
                    <p class="text-sm text-gray-600">Kelola jadwal seminar</p>
                </div>
            </div>
        </a>
    </div>
</div>
