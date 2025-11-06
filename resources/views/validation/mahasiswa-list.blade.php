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
            <h2 class="text-lg font-medium text-gray-900">Daftar Mahasiswa</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            No
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Mahasiswa
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Prodi / Semester
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            IPK
                        </th>
                        @if(Auth::user()->role === 'admin')
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Dosen Pembimbing
                        </th>
                        @endif
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($mahasiswa as $index => $m)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <i class="fas fa-user text-blue-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $m->user->name ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">{{ $m->user->nim ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $m->prodi ?? 'N/A' }}</div>
                            <div class="text-sm text-gray-500">Semester {{ $m->semester ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($m->ipk)
                                <span class="text-sm font-semibold {{ $m->ipk >= 3.0 ? 'text-green-600' : 'text-orange-600' }}">
                                    {{ number_format($m->ipk, 2) }}
                                </span>
                            @else
                                <span class="text-sm text-gray-400">-</span>
                            @endif
                        </td>
                        @if(Auth::user()->role === 'admin')
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $m->dosenPembimbing->name ?? 'Belum ditentukan' }}
                        </td>
                        @endif
                        <td class="px-6 py-4 whitespace-nowrap">
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
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <a href="{{ route('dospem.mahasiswa.detail', $m->id_mahasiswa) }}"
                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                <i class="fas fa-eye mr-2"></i>
                                Lihat Detail Pemberkasan
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ Auth::user()->role === 'admin' ? '7' : '6' }}" class="px-6 py-8 text-center text-gray-500">
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
