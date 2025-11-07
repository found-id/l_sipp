@extends('layouts.app')

@section('title', 'Pilih Mahasiswa - SIPP PKL')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg p-6">
        <div>
            @if(Auth::user()->role === 'admin')
                <h1 class="text-2xl font-bold text-gray-900">Validasi Pemberkasan Mahasiswa</h1>
                <p class="text-gray-600 mt-2">Pilih mahasiswa untuk melihat dan memvalidasi pemberkasan lengkap</p>
            @else
                <h1 class="text-2xl font-bold text-gray-900">Validasi Pemberkasan Mahasiswa Bimbingan</h1>
                <p class="text-gray-600 mt-2">Pilih mahasiswa untuk melihat dan memvalidasi pemberkasan lengkap</p>
            @endif
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
                                // Determine PKL Status
                                $user = $m->user;
                                if ($user) {
                                    $khsCount = $user->khs()->where('status_validasi', 'tervalidasi')->count();
                                    $hasSuratBalasan = $user->suratBalasan()->where('status_validasi', 'tervalidasi')->exists();
                                    $hasLaporan = $user->laporanPkl()->where('status_validasi', 'tervalidasi')->exists();

                                    if ($khsCount >= 5 && $hasSuratBalasan && $hasLaporan) {
                                        $statusPKL = 'Selesai';
                                        $statusColor = 'green';
                                        $statusIcon = 'fa-check-circle';
                                    } elseif ($hasSuratBalasan) {
                                        $statusPKL = 'Berlangsung';
                                        $statusColor = 'blue';
                                        $statusIcon = 'fa-sync';
                                    } elseif ($khsCount > 0 || $m->cek_valid_biodata) {
                                        $statusPKL = 'Persiapan';
                                        $statusColor = 'yellow';
                                        $statusIcon = 'fa-clock';
                                    } else {
                                        $statusPKL = 'Belum Mulai';
                                        $statusColor = 'gray';
                                        $statusIcon = 'fa-hourglass-start';
                                    }
                                } else {
                                    $statusPKL = 'Belum Mulai';
                                    $statusColor = 'gray';
                                    $statusIcon = 'fa-hourglass-start';
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
                        @if(Auth::user()->role === 'admin')
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $m->dosenPembimbing->name ?? 'Belum ditentukan' }}
                        </td>
                        @endif
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="flex flex-col space-y-1">
                                @if($m->cek_valid_biodata)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i> Biodata Valid
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times mr-1"></i> Biodata Belum Valid
                                    </span>
                                @endif

                                @if($m->cek_ipk_nilaisks)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i> IPK Memenuhi
                                    </span>
                                @endif

                                @if($m->cek_min_semester)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i> Semester Memenuhi
                                    </span>
                                @endif
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
