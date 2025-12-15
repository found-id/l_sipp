@extends('layouts.app')

@section('title', 'Pilih Mahasiswa - SIP PKL')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 shadow-lg rounded-xl p-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                @if(Auth::user()->role === 'admin')
                    <h1 class="text-3xl font-bold">Validasi Pemberkasan Mahasiswa</h1>
                    <p class="text-blue-100 mt-2 text-lg">Pilih mahasiswa untuk melihat dan memvalidasi pemberkasan lengkap</p>
                @else
                    <h1 class="text-3xl font-bold">Validasi Pemberkasan Mahasiswa Bimbingan</h1>
                    <p class="text-blue-100 mt-2 text-lg">Pilih mahasiswa untuk melihat dan memvalidasi pemberkasan lengkap</p>
                @endif
            </div>
            <div class="hidden md:block">
                <i class="fas fa-user-check text-6xl text-blue-300 opacity-50"></i>
            </div>
        </div>
    </div>

    <!-- Statistics Card -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white overflow-hidden shadow-md rounded-xl border border-gray-100 hover:shadow-xl transition-shadow duration-300">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Total Mahasiswa</dt>
                        <dd class="text-3xl font-bold text-gray-900 mt-2">{{ $mahasiswa->count() }}</dd>
                    </div>
                    <div class="bg-blue-100 p-4 rounded-xl">
                        <i class="fas fa-users text-3xl text-blue-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-md rounded-xl border border-gray-100 hover:shadow-xl transition-shadow duration-300">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Biodata Valid</dt>
                        <dd class="text-3xl font-bold text-gray-900 mt-2">{{ $mahasiswa->where('cek_valid_biodata', true)->count() }}</dd>
                    </div>
                    <div class="bg-green-100 p-4 rounded-xl">
                        <i class="fas fa-check-circle text-3xl text-green-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-md rounded-xl border border-gray-100 hover:shadow-xl transition-shadow duration-300">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">IPK Memenuhi</dt>
                        <dd class="text-3xl font-bold text-gray-900 mt-2">{{ $mahasiswa->where('cek_ipk_nilaisks', true)->count() }}</dd>
                    </div>
                    <div class="bg-purple-100 p-4 rounded-xl">
                        <i class="fas fa-graduation-cap text-3xl text-purple-600"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="bg-white shadow-md rounded-xl border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-blue-50">
            <h2 class="text-xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-history text-indigo-600 mr-3"></i>
                Riwayat Pemberkasan Terbaru
            </h2>
            <p class="text-sm text-gray-600 mt-1">3 aktivitas pemberkasan terakhir (upload & validasi)</p>
        </div>
        <div class="p-6">
            @forelse($recentActivities as $activity)
                @php
                    $pesan = is_array($activity->pesan) ? $activity->pesan : json_decode($activity->pesan, true);
                    $documentTypeLabels = [
                        'KHS' => 'KHS',
                        'khs' => 'KHS',
                        'surat_balasan' => 'Surat Balasan',
                        'laporan_pkl' => 'Laporan PKL',
                        'pemberkasan_kelayakan' => 'Pemberkasan Kelayakan',
                        'pemberkasan_dokumen_pendukung' => 'Dokumen Pendukung',
                        'pemberkasan_instansi_mitra' => 'Instansi Mitra',
                        'pemberkasan_akhir' => 'Pemberkasan Akhir',
                    ];
                    $docType = $pesan['document_type'] ?? 'unknown';
                    $docLabel = $documentTypeLabels[$docType] ?? ucfirst(str_replace('_', ' ', $docType));

                    // Determine activity type and icon
                    $isUpload = $activity->tipe === 'upload_dokumen';
                    $activityIcon = $isUpload ? 'fa-upload' : 'fa-check-circle';
                    $activityBg = $isUpload ? 'from-green-500 to-emerald-600' : 'from-blue-500 to-indigo-600';
                    $activityText = $isUpload ? 'mengupload' : 'memvalidasi';

                    $statusColors = [
                        'tervalidasi' => 'bg-green-100 text-green-800 border-green-200',
                        'belum_valid' => 'bg-red-100 text-red-800 border-red-200',
                        'revisi' => 'bg-orange-100 text-orange-800 border-orange-200',
                        'menunggu' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                        'uploaded' => 'bg-blue-100 text-blue-800 border-blue-200',
                    ];
                    $statusIcons = [
                        'tervalidasi' => 'fa-check-circle',
                        'belum_valid' => 'fa-times-circle',
                        'revisi' => 'fa-exclamation-circle',
                        'menunggu' => 'fa-clock',
                        'uploaded' => 'fa-check',
                    ];

                    if ($isUpload) {
                        $displayStatus = 'uploaded';
                        $statusLabel = 'Berhasil Upload';
                    } else {
                        $displayStatus = $pesan['new_status'] ?? 'menunggu';
                        $statusLabel = ucfirst(str_replace('_', ' ', $displayStatus));
                    }

                    $statusColor = $statusColors[$displayStatus] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                    $statusIcon = $statusIcons[$displayStatus] ?? 'fa-circle';
                @endphp
                <div class="flex items-start space-x-4 pb-4 mb-4 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                    <div class="flex-shrink-0 mt-1">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br {{ $activityBg }} flex items-center justify-center">
                            <i class="fas {{ $activityIcon }} text-white"></i>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-900">
                                    {{ $activity->user->name ?? 'System' }}
                                    <span class="font-normal text-gray-600">{{ $activityText }}</span>
                                    <span class="font-semibold text-blue-600">{{ $docLabel }}</span>
                                    @if($isUpload && !empty($pesan['semester']))
                                        <span class="font-normal text-gray-600">Semester {{ $pesan['semester'] }}</span>
                                    @endif
                                </p>
                                @if(!$isUpload)
                                    <p class="text-sm text-gray-600 mt-1">
                                        Mahasiswa: <span class="font-medium text-gray-900">{{ $pesan['mahasiswa'] ?? 'N/A' }}</span>
                                    </p>
                                @endif
                                @if(!empty($pesan['catatan']))
                                    <p class="text-sm text-gray-500 mt-1 italic">
                                        <i class="fas fa-comment-dots mr-1"></i>{{ $pesan['catatan'] }}
                                    </p>
                                @endif
                                @if($isUpload && !empty($pesan['file_name']))
                                    <p class="text-xs text-gray-500 mt-1">
                                        <i class="fas fa-file-pdf mr-1"></i>{{ $pesan['file_name'] }}
                                    </p>
                                @endif
                            </div>
                            <div class="flex flex-col items-end ml-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $statusColor }}">
                                    <i class="fas {{ $statusIcon }} mr-1"></i>
                                    {{ $statusLabel }}
                                </span>
                                <span class="text-xs text-gray-400 mt-2">
                                    <i class="fas fa-clock mr-1"></i>{{ $activity->tanggal_dibuat ? $activity->tanggal_dibuat->diffForHumans() : '-' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <i class="fas fa-inbox text-5xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500">Belum ada riwayat validasi</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Mahasiswa Table -->
    <div class="bg-white shadow-md rounded-xl border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <h2 class="text-xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-list-ul text-blue-600 mr-3"></i>
                    Daftar Mahasiswa
                </h2>

                <!-- Search and Sort Controls -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <!-- Search Bar -->
                    <form method="GET" action="{{ route('dospem.validation') }}" class="flex gap-2">
                        <input type="hidden" name="sort" value="{{ request('sort', 'name') }}">
                        <input type="hidden" name="order" value="{{ request('order', 'asc') }}">
                        <div class="relative">
                            <input type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="Cari nama atau NIM..."
                                   class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-64">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-search"></i>
                        </button>
                        @if(request('search'))
                            <a href="{{ route('dospem.validation') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </form>

                    <!-- Sort Dropdown -->
                    <form method="GET" action="{{ route('dospem.validation') }}" id="sortForm">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <select name="sort" onchange="document.getElementById('sortForm').submit()"
                                class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="name" {{ request('sort', 'name') == 'name' ? 'selected' : '' }}>Urutkan: Nama</option>
                            <option value="nim" {{ request('sort') == 'nim' ? 'selected' : '' }}>Urutkan: NIM</option>
                            <option value="semester" {{ request('sort') == 'semester' ? 'selected' : '' }}>Urutkan: Semester</option>
                            <option value="ipk" {{ request('sort') == 'ipk' ? 'selected' : '' }}>Urutkan: IPK</option>
                        </select>
                        <input type="hidden" name="order" value="{{ request('order', 'asc') }}">
                    </form>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto relative">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">
                            No
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-64 sticky left-0 bg-gray-50 z-10">
                            Mahasiswa
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                            Semester
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                            Status PKL
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
                            IPK
                        </th>
                        @if(Auth::user()->role === 'admin')
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-48">
                            Dosen Pembimbing
                        </th>
                        @endif
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-48">
                            Status
                        </th>
                        <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-56 sticky right-0 bg-gray-50 z-10 shadow-[-4px_0_6px_-1px_rgba(0,0,0,0.1)]">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($mahasiswa as $index => $m)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap sticky left-0 bg-white z-10 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    @if($m->user && $m->user->photo)
                                        @if($m->user->google_linked)
                                            @php
                                                $photoUrl = $m->user->photo;
                                                if (str_contains($photoUrl, 'googleusercontent.com')) {
                                                    $photoUrl = preg_replace('/=s\d+-c/', '', $photoUrl);
                                                    $photoUrl .= '=s96-c';
                                                }
                                            @endphp
                                            <img src="{{ $photoUrl }}"
                                                 alt="{{ $m->user->name }}"
                                                 class="h-10 w-10 rounded-full object-cover"
                                                 referrerpolicy="no-referrer"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="h-10 w-10 rounded-full bg-blue-100 items-center justify-center" style="display: none;">
                                                <i class="fas fa-user text-blue-600"></i>
                                            </div>
                                        @else
                                            <img src="{{ asset('storage/' . $m->user->photo) }}"
                                                 alt="{{ $m->user->name }}"
                                                 class="h-10 w-10 rounded-full object-cover"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="h-10 w-10 rounded-full bg-blue-100 items-center justify-center" style="display: none;">
                                                <i class="fas fa-user text-blue-600"></i>
                                            </div>
                                        @endif
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            <i class="fas fa-user text-blue-600"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $m->user->name ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">NIM: {{ $m->nim ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">
                                <i class="fas fa-graduation-cap mr-1"></i>
                                Semester {{ $m->semester ?? '-' }}
                            </span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            @php
                                // Determine PKL Status from database
                                $dbStatusPkl = $m->status_pkl ?? 'siap';
                                $ipkTranskrip = $m->ipk_transkrip ?? 0;

                                if ($dbStatusPkl === 'selesai') {
                                    $statusPKL = 'Selesai PKL';
                                    $statusColor = 'green';
                                    $statusIcon = 'fa-check-circle';
                                } elseif ($dbStatusPkl === 'aktif') {
                                    $statusPKL = 'Aktif PKL';
                                    $statusColor = 'blue';
                                    $statusIcon = 'fa-building';
                                } elseif ($ipkTranskrip > 0 && $ipkTranskrip < 2.5) {
                                    // IPK from transkrip is below 2.5 - Not eligible
                                    $statusPKL = 'Tidak Layak';
                                    $statusColor = 'red';
                                    $statusIcon = 'fa-times-circle';
                                } else {
                                    // Check if eligible for PKL
                                    $user = $m->user;
                                    if ($user) {
                                        $khsCount = $user->khs()->whereBetween('semester', [1, 4])->distinct()->count('semester');
                                        $hasPkkmb = !empty($m->gdrive_pkkmb ?? '');
                                        $hasEcourse = !empty($m->gdrive_ecourse ?? '');
                                        $hasDokumenPendukung = $hasPkkmb && $hasEcourse;

                                        $isEligible = $khsCount >= 4 &&
                                                      $ipkTranskrip >= 2.5 &&
                                                      $m->cek_min_semester &&
                                                      $m->cek_ipk_nilaisks &&
                                                      $m->cek_valid_biodata &&
                                                      $hasDokumenPendukung;

                                        if ($isEligible) {
                                            $statusPKL = 'Siap PKL';
                                            $statusColor = 'yellow';
                                            $statusIcon = 'fa-clipboard-check';
                                        } else {
                                            $statusPKL = 'Menyiapkan Berkas';
                                            $statusColor = 'gray';
                                            $statusIcon = 'fa-file-alt';
                                        }
                                    } else {
                                        $statusPKL = 'Menyiapkan Berkas';
                                        $statusColor = 'gray';
                                        $statusIcon = 'fa-file-alt';
                                    }
                                }
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800">
                                <i class="fas {{ $statusIcon }} mr-1"></i>
                                {{ $statusPKL }}
                            </span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            @if($m->ipk_transkrip)
                                <span class="text-sm font-semibold {{ $m->ipk_transkrip >= 3.0 ? 'text-green-600' : 'text-orange-600' }}">
                                    {{ number_format($m->ipk_transkrip, 2) }}
                                </span>
                            @else
                                <span class="text-sm text-gray-400">-</span>
                            @endif
                        </td>
                        @if(Auth::user()->role === 'admin')
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $m->dosenPembimbing->name ?? 'Belum ditentukan' }}
                        </td>
                        @endif
                        <td class="px-4 py-4 whitespace-nowrap">
                            @php
                                $user = $m->user;

                                // Dokumen Pendukung Check (for Dokumen Pendukung icon only)
                                $hasPkkmb = !empty($m->gdrive_pkkmb ?? '');
                                $hasEcourse = !empty($m->gdrive_ecourse ?? '');
                                $dokPendukungComplete = $hasPkkmb && $hasEcourse;

                                // 1. Pemberkasan Kelayakan (Based on KHS, IPK, Semester, Biodata - WITHOUT Dokumen Pendukung)
                                $khsCount = $user ? $user->khs()->whereBetween('semester', [1, 4])->distinct()->count('semester') : 0;
                                
                                // Kelayakan eligibility: KHS >= 4, IPK >= 2.5, semester check, ipk check, biodata valid
                                $isKelayakanEligible = $khsCount >= 4 &&
                                              ($m->ipk ?? 0) >= 2.5 &&
                                              $m->cek_min_semester &&
                                              $m->cek_ipk_nilaisks &&
                                              $m->cek_valid_biodata;

                                $hasValidatedKhs = $user ? $user->khs()->where('status_validasi', 'tervalidasi')->exists() : false;
                                // Hijau jika Layak (Eligible), Biru jika Tervalidasi
                                $kelayakanStatus = $hasValidatedKhs ? 'validated' : ($isKelayakanEligible ? 'complete' : 'incomplete');

                                // 2. Dokumen Pendukung Status
                                $statusDokPendukung = $m->status_dokumen_pendukung ?? 'menunggu';
                                if (!$dokPendukungComplete) {
                                    $dokPendukungStatus = 'incomplete';
                                } elseif ($statusDokPendukung === 'tervalidasi') {
                                    $dokPendukungStatus = 'validated';
                                } else {
                                    $dokPendukungStatus = 'complete';
                                }

                                // 3. Instansi Mitra (Surat Balasan)
                                $hasSuratBalasan = $user ? $user->suratBalasan()->exists() : false;
                                $hasValidatedSuratBalasan = $user ? $user->suratBalasan()->where('status_validasi', 'tervalidasi')->exists() : false;
                                $instansiMitraStatus = $hasSuratBalasan ? ($hasValidatedSuratBalasan ? 'validated' : 'complete') : 'incomplete';

                                // 4. Pemberkasan Akhir (Laporan PKL)
                                $hasLaporan = $user ? $user->laporanPkl()->exists() : false;
                                $hasValidatedLaporan = $user ? $user->laporanPkl()->where('status_validasi', 'tervalidasi')->exists() : false;
                                $akhirStatus = $hasLaporan ? ($hasValidatedLaporan ? 'validated' : 'complete') : 'incomplete';
                            @endphp
                            <div class="flex items-center space-x-2">
                                <!-- Pemberkasan Kelayakan -->
                                <div class="relative group">
                                    <i class="fas fa-file-alt text-lg
                                        @if($kelayakanStatus === 'validated') text-blue-600
                                        @elseif($kelayakanStatus === 'complete') text-green-600
                                        @else text-gray-400 @endif"></i>
                                    <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-20">
                                        Kelayakan
                                    </div>
                                </div>

                                <!-- Dokumen Pendukung -->
                                <div class="relative group">
                                    <i class="fab fa-google-drive text-lg
                                        @if($dokPendukungStatus === 'validated') text-blue-600
                                        @elseif($dokPendukungStatus === 'complete') text-green-600
                                        @else text-gray-400 @endif"></i>
                                    <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-20">
                                        Dok. Pendukung
                                    </div>
                                </div>

                                <!-- Instansi Mitra -->
                                <div class="relative group">
                                    <i class="fas fa-envelope text-lg
                                        @if($instansiMitraStatus === 'validated') text-blue-600
                                        @elseif($instansiMitraStatus === 'complete') text-green-600
                                        @else text-gray-400 @endif"></i>
                                    <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-20">
                                        Instansi Mitra
                                    </div>
                                </div>

                                <!-- Pemberkasan Akhir -->
                                <div class="relative group">
                                    <i class="fas fa-book text-lg
                                        @if($akhirStatus === 'validated') text-blue-600
                                        @elseif($akhirStatus === 'complete') text-green-600
                                        @else text-gray-400 @endif"></i>
                                    <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-20">
                                        Akhir
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-center text-sm font-medium sticky right-0 bg-white z-10 hover:bg-gray-50 transition-colors shadow-[-4px_0_6px_-1px_rgba(0,0,0,0.1)]">
                            <a href="{{ route('dospem.mahasiswa.detail', $m->id_mahasiswa) }}"
                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                <i class="fas fa-eye mr-2"></i>
                                Lihat Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ Auth::user()->role === 'admin' ? '8' : '7' }}" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                            <p class="text-lg">Tidak ada mahasiswa yang ditemukan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
