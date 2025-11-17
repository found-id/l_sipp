@extends('layouts.app')

@section('title', 'Validasi Pemberkasan Mahasiswa - SIPP PKL')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg p-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Validasi Pemberkasan Mahasiswa</h1>
            <p class="text-gray-600 mt-2">Pilih mahasiswa untuk melihat dan memvalidasi pemberkasan lengkap</p>
        </div>
    </div>

    <!-- Statistics Card -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-users text-2xl text-blue-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Mahasiswa</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $mahasiswa->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-2xl text-green-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Biodata Valid</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $mahasiswa->where('cek_valid_biodata', true)->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-graduation-cap text-2xl text-purple-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">IPK Memenuhi</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $mahasiswa->where('cek_ipk_nilaisks', true)->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mahasiswa Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <h2 class="text-lg font-medium text-gray-900">Daftar Mahasiswa</h2>

                <!-- Search and Sort Controls -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <!-- Search Bar -->
                    <form method="GET" action="{{ route('admin.validation') }}" class="flex gap-2">
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
                            <a href="{{ route('admin.validation') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </form>

                    <!-- Sort Dropdown -->
                    <form method="GET" action="{{ route('admin.validation') }}" id="sortForm">
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
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-48">
                            Dosen Pembimbing
                        </th>
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
                                    @if($m->user && $m->user->photo && $m->user->google_linked)
                                        <img src="{{ $m->user->photo }}"
                                             alt="{{ $m->user->name }}"
                                             class="h-10 w-10 rounded-full object-cover"
                                             onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="h-10 w-10 rounded-full bg-blue-100 items-center justify-center hidden">
                                            <i class="fas fa-user text-blue-600"></i>
                                        </div>
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

                                if ($dbStatusPkl === 'selesai') {
                                    $statusPKL = 'Selesai PKL';
                                    $statusColor = 'green';
                                    $statusIcon = 'fa-check-circle';
                                } elseif ($dbStatusPkl === 'aktif') {
                                    $statusPKL = 'Aktif PKL';
                                    $statusColor = 'blue';
                                    $statusIcon = 'fa-building';
                                } else {
                                    // Check if eligible for PKL
                                    $user = $m->user;
                                    if ($user) {
                                        $khsCount = $user->khs()->whereBetween('semester', [1, 5])->distinct()->count('semester');
                                        $hasPkkmb = !empty($m->gdrive_pkkmb ?? '');
                                        $hasEcourse = !empty($m->gdrive_ecourse ?? '');
                                        $hasDokumenPendukung = $hasPkkmb && $hasEcourse;

                                        $isEligible = $khsCount >= 5 &&
                                                      ($m->ipk ?? 0) >= 2.5 &&
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
                            @if($m->ipk)
                                <span class="text-sm font-semibold {{ $m->ipk >= 3.0 ? 'text-green-600' : 'text-orange-600' }}">
                                    {{ number_format($m->ipk, 2) }}
                                </span>
                            @else
                                <span class="text-sm text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $m->dosenPembimbing->name ?? 'Belum ditentukan' }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            @php
                                $user = $m->user;

                                // 1. Pemberkasan Kelayakan (KHS)
                                $khsCount = $user ? $user->khs()->whereBetween('semester', [1, 5])->distinct()->count('semester') : 0;
                                $hasValidatedKhs = $user ? $user->khs()->where('status_validasi', 'tervalidasi')->exists() : false;
                                $kelayakanStatus = $khsCount >= 5 ? ($hasValidatedKhs ? 'validated' : 'complete') : 'incomplete';

                                // 2. Dokumen Pendukung (PKKMB & English Course)
                                $hasPkkmb = !empty($m->gdrive_pkkmb ?? '');
                                $hasEcourse = !empty($m->gdrive_ecourse ?? '');
                                $dokPendukungComplete = $hasPkkmb && $hasEcourse;
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
                            <a href="{{ route('admin.mahasiswa.detail', $m->id_mahasiswa) }}"
                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                <i class="fas fa-eye mr-2"></i>
                                Lihat Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-gray-500">
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
