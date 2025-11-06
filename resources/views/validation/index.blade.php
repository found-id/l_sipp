@extends('layouts.app')

@section('title', 'Validasi Dokumen - SIPP PKL')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                @if(Auth::user()->role === 'admin')
                    <h1 class="text-2xl font-bold text-gray-900">Validasi Dokumen Semua Mahasiswa</h1>
                    <p class="text-gray-600 mt-2">Validasi dan kelola dokumen semua mahasiswa</p>
                @else
                    <h1 class="text-2xl font-bold text-gray-900">Validasi Dokumen Mahasiswa Bimbingan</h1>
                    <p class="text-gray-600 mt-2">Validasi dan kelola dokumen mahasiswa bimbingan Anda</p>
                @endif
            </div>
            <a href="{{ route('dospem.mahasiswa.list') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-md">
                <i class="fas fa-user-graduate mr-2"></i>
                Lihat Detail Per Mahasiswa
            </a>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-file-alt text-2xl text-blue-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Dokumen</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['total_documents'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-clock text-2xl text-yellow-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Menunggu Validasi</dt>
                            <dd class="text-lg font-medium text-yellow-600">{{ $stats['pending_validation'] }}</dd>
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
                            <dt class="text-sm font-medium text-gray-500 truncate">Tervalidasi</dt>
                            <dd class="text-lg font-medium text-green-600">{{ $stats['validated'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-edit text-2xl text-orange-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Perlu Revisi</dt>
                            <dd class="text-lg font-medium text-orange-600">{{ $stats['need_revision'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="bg-white shadow rounded-lg">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                <button onclick="showTab('khs')" id="khs-tab" class="tab-button active py-4 px-1 border-b-2 border-blue-500 font-medium text-sm text-blue-600">
                    <i class="fas fa-file-alt mr-2"></i>
                    KHS ({{ $documents['khs']->count() }})
                </button>
                <button onclick="showTab('surat')" id="surat-tab" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700">
                    <i class="fas fa-envelope mr-2"></i>
                    Surat Balasan ({{ $documents['surat_balasan']->count() }})
                </button>
                <button onclick="showTab('laporan')" id="laporan-tab" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700">
                    <i class="fas fa-book mr-2"></i>
                    Laporan PKL ({{ $documents['laporan_pkl']->count() }})
                </button>
            </nav>
        </div>

        <!-- KHS Tab Content -->
        <div id="khs-content" class="tab-content p-6">
            <div class="mb-4 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Dokumen KHS</h3>
                <button onclick="openBulkValidationModal('khs')" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    <i class="fas fa-check-double mr-2"></i>Validasi Massal
                </button>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <input type="checkbox" id="select-all-khs" onchange="toggleAllCheckboxes('khs')">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mahasiswa</th>
                            @if(Auth::user()->role === 'admin')
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dosen Pembimbing</th>
                            @endif
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">File</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Upload</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($documents['khs'] as $khs)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" class="khs-checkbox" value="{{ $khs->id }}">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                            <i class="fas fa-user text-gray-600"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $khs->mahasiswa->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $khs->mahasiswa->profilMahasiswa->nim ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                    <button onclick="viewBiodata({{ $khs->mahasiswa->id }})" 
                                            class="text-blue-600 hover:text-blue-800 text-xs">
                                        <i class="fas fa-user mr-1"></i>Lihat Biodata
                                    </button>
                                </div>
                            </td>
                            @if(Auth::user()->role === 'admin')
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $khs->mahasiswa->profilMahasiswa->dosenPembimbing->name ?? 'Belum ditentukan' }}
                                </td>
                            @endif
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ basename($khs->file_path) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ Storage::url($khs->file_path) }}" target="_blank" 
                                   class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye mr-1"></i>Lihat
                                </a>
                                <button onclick="openValidationModal('khs', {{ $khs->id }}, '{{ $khs->status_validasi }}')" 
                                        class="text-green-600 hover:text-green-900">
                                    <i class="fas fa-edit mr-1"></i>Validasi
                                </button>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($khs->status_validasi === 'tervalidasi') bg-green-100 text-green-800
                                    @elseif($khs->status_validasi === 'belum_valid') bg-red-100 text-red-800
                                    @elseif($khs->status_validasi === 'revisi') bg-yellow-100 text-yellow-800
                                    @else bg-blue-100 text-blue-800 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $khs->status_validasi)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $khs->created_at->format('d M Y H:i') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada dokumen KHS</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Surat Balasan Tab Content -->
        <div id="surat-content" class="tab-content p-6 hidden">
            <div class="mb-4 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Dokumen Surat Balasan</h3>
                <button onclick="openBulkValidationModal('surat_balasan')" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    <i class="fas fa-check-double mr-2"></i>Validasi Massal
                </button>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <input type="checkbox" id="select-all-surat" onchange="toggleAllCheckboxes('surat')">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mahasiswa</th>
                            @if(Auth::user()->role === 'admin')
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dosen Pembimbing</th>
                            @endif
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mitra</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">File</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Upload</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($documents['surat_balasan'] as $surat)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" class="surat-checkbox" value="{{ $surat->id }}">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                        <i class="fas fa-user text-gray-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $surat->mahasiswa->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $surat->mahasiswa->profilMahasiswa->nim ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            @if(Auth::user()->role === 'admin')
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $surat->mahasiswa->profilMahasiswa->dosenPembimbing->name ?? 'Belum ditentukan' }}
                                </td>
                            @endif
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $surat->mitra->nama ?? $surat->mitra_nama_custom ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ basename($surat->file_path) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($surat->status_validasi === 'tervalidasi') bg-green-100 text-green-800
                                    @elseif($surat->status_validasi === 'belum_valid') bg-red-100 text-red-800
                                    @elseif($surat->status_validasi === 'revisi') bg-yellow-100 text-yellow-800
                                    @else bg-blue-100 text-blue-800 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $surat->status_validasi)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $surat->created_at->format('d M Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ Storage::url($surat->file_path) }}" target="_blank" 
                                   class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye mr-1"></i>Lihat
                                </a>
                                <button onclick="openValidationModal('surat_balasan', {{ $surat->id }}, '{{ $surat->status_validasi }}')" 
                                        class="text-green-600 hover:text-green-900">
                                    <i class="fas fa-edit mr-1"></i>Validasi
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">Tidak ada dokumen Surat Balasan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Laporan PKL Tab Content -->
        <div id="laporan-content" class="tab-content p-6 hidden">
            <div class="mb-4 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Dokumen Laporan PKL</h3>
                <button onclick="openBulkValidationModal('laporan_pkl')" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    <i class="fas fa-check-double mr-2"></i>Validasi Massal
                </button>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <input type="checkbox" id="select-all-laporan" onchange="toggleAllCheckboxes('laporan')">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mahasiswa</th>
                            @if(Auth::user()->role === 'admin')
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dosen Pembimbing</th>
                            @endif
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">File</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Upload</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($documents['laporan_pkl'] as $laporan)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" class="laporan-checkbox" value="{{ $laporan->id }}">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                        <i class="fas fa-user text-gray-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $laporan->mahasiswa->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $laporan->mahasiswa->profilMahasiswa->nim ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            @if(Auth::user()->role === 'admin')
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $laporan->mahasiswa->profilMahasiswa->dosenPembimbing->name ?? 'Belum ditentukan' }}
                                </td>
                            @endif
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ basename($laporan->file_path) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($laporan->status_validasi === 'tervalidasi') bg-green-100 text-green-800
                                    @elseif($laporan->status_validasi === 'belum_valid') bg-red-100 text-red-800
                                    @elseif($laporan->status_validasi === 'revisi') bg-yellow-100 text-yellow-800
                                    @else bg-blue-100 text-blue-800 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $laporan->status_validasi)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $laporan->created_at->format('d M Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ Storage::url($laporan->file_path) }}" target="_blank" 
                                   class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye mr-1"></i>Lihat
                                </a>
                                <button onclick="openValidationModal('laporan_pkl', {{ $laporan->id }}, '{{ $laporan->status_validasi }}')" 
                                        class="text-green-600 hover:text-green-900">
                                    <i class="fas fa-edit mr-1"></i>Validasi
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada dokumen Laporan PKL</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Validation Modal -->
<div id="validationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Validasi Dokumen</h3>
            <form id="validationForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="status_validasi" class="block text-sm font-medium text-gray-700 mb-2">
                        Status Validasi
                    </label>
                    <select id="status_validasi" name="status_validasi" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="tervalidasi">Tervalidasi</option>
                        <option value="belum_valid">Belum Valid</option>
                        <option value="revisi">Revisi</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan (Opsional)
                    </label>
                    <textarea id="catatan" name="catatan" rows="3" 
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Tambahkan catatan untuk mahasiswa..."></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeValidationModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Validasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Validation Modal -->
<div id="bulkValidationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Validasi Massal</h3>
            <form id="bulkValidationForm" method="POST" action="{{ route('dospem.validation.bulk') }}">
                @csrf
                <input type="hidden" id="bulk_document_type" name="document_type">
                <input type="hidden" id="bulk_document_ids" name="document_ids">
                
                <div class="mb-4">
                    <label for="bulk_status_validasi" class="block text-sm font-medium text-gray-700 mb-2">
                        Status Validasi
                    </label>
                    <select id="bulk_status_validasi" name="status_validasi" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="tervalidasi">Tervalidasi</option>
                        <option value="belum_valid">Belum Valid</option>
                        <option value="revisi">Revisi</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="bulk_catatan" class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan (Opsional)
                    </label>
                    <textarea id="bulk_catatan" name="catatan" rows="3" 
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Tambahkan catatan untuk mahasiswa..."></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeBulkValidationModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Validasi Massal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Biodata Modal -->
<div id="biodataModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Biodata Mahasiswa</h3>
                <button onclick="closeBiodataModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama</label>
                    <p id="biodataNama" class="mt-1 text-sm text-gray-900"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">NIM</label>
                    <p id="biodataNim" class="mt-1 text-sm text-gray-900"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Program Studi</label>
                    <p id="biodataProdi" class="mt-1 text-sm text-gray-900"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Semester</label>
                    <p id="biodataSemester" class="mt-1 text-sm text-gray-900"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Dosen Pembimbing</label>
                    <p id="biodataDospem" class="mt-1 text-sm text-gray-900"></p>
                </div>
            </div>
            <div class="mt-6 flex justify-end">
                <button onclick="closeBiodataModal()" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function showTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active class from all tabs
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active', 'border-blue-500', 'text-blue-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    document.getElementById(tabName + '-content').classList.remove('hidden');
    
    // Add active class to selected tab
    const activeTab = document.getElementById(tabName + '-tab');
    activeTab.classList.add('active', 'border-blue-500', 'text-blue-600');
    activeTab.classList.remove('border-transparent', 'text-gray-500');
}

function openValidationModal(type, id, currentStatus) {
    const modal = document.getElementById('validationModal');
    const form = document.getElementById('validationForm');
    const statusSelect = document.getElementById('status_validasi');
    
    // Set form action based on type
    if (type === 'khs') {
        form.action = `/dospem/validation/khs/${id}`;
    } else if (type === 'surat_balasan') {
        form.action = `/dospem/validation/surat-balasan/${id}`;
    } else if (type === 'laporan_pkl') {
        form.action = `/dospem/validation/laporan/${id}`;
    }
    
    // Set current status
    statusSelect.value = currentStatus;
    
    modal.classList.remove('hidden');
}

function closeValidationModal() {
    document.getElementById('validationModal').classList.add('hidden');
}

function openBulkValidationModal(documentType) {
    const modal = document.getElementById('bulkValidationModal');
    const form = document.getElementById('bulkValidationForm');
    const documentTypeInput = document.getElementById('bulk_document_type');
    const documentIdsInput = document.getElementById('bulk_document_ids');
    
    // Get selected checkboxes
    const checkboxes = document.querySelectorAll(`.${documentType}-checkbox:checked`);
    const selectedIds = Array.from(checkboxes).map(cb => cb.value);
    
    if (selectedIds.length === 0) {
        alert('Pilih minimal satu dokumen untuk divalidasi!');
        return;
    }
    
    documentTypeInput.value = documentType;
    documentIdsInput.value = JSON.stringify(selectedIds);
    
    modal.classList.remove('hidden');
}

function closeBulkValidationModal() {
    document.getElementById('bulkValidationModal').classList.add('hidden');
}

function toggleAllCheckboxes(type) {
    const selectAll = document.getElementById(`select-all-${type}`);
    const checkboxes = document.querySelectorAll(`.${type}-checkbox`);
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
}

function viewBiodata(mahasiswaId) {
    // Fetch biodata from server
    fetch(`/dospem/biodata/${mahasiswaId}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Biodata response:', data);
        if (data.success) {
            // Populate modal with data
            document.getElementById('biodataNama').textContent = data.biodata.name;
            document.getElementById('biodataNim').textContent = data.biodata.nim || 'N/A';
            document.getElementById('biodataProdi').textContent = data.biodata.prodi || 'N/A';
            document.getElementById('biodataSemester').textContent = data.biodata.semester || 'N/A';
            document.getElementById('biodataDospem').textContent = data.biodata.dospem || 'Belum ditentukan';
            
            // Show modal
            document.getElementById('biodataModal').classList.remove('hidden');
        } else {
            console.error('API Error:', data);
            alert('Gagal memuat biodata mahasiswa: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Fetch Error:', error);
        alert('Gagal memuat biodata mahasiswa: ' + error.message);
    });
}

function closeBiodataModal() {
    document.getElementById('biodataModal').classList.add('hidden');
}

function openBulkValidationModal(type) {
    const modal = document.getElementById('bulkValidationModal');
    const form = document.getElementById('bulkValidationForm');
    const typeInput = document.getElementById('bulk_document_type');
    const idsInput = document.getElementById('bulk_document_ids');
    
    // Get selected document IDs
    const checkboxes = document.querySelectorAll(`.${type}-checkbox:checked`);
    const selectedIds = Array.from(checkboxes).map(cb => cb.value);
    
    if (selectedIds.length === 0) {
        alert('Pilih minimal satu dokumen untuk divalidasi!');
        return;
    }
    
    typeInput.value = type;
    idsInput.value = JSON.stringify(selectedIds);
    modal.classList.remove('hidden');
}

function closeBulkValidationModal() {
    document.getElementById('bulkValidationModal').classList.add('hidden');
}

function toggleSelectAll(type) {
    const selectAll = document.getElementById(`select-all-${type}`);
    const checkboxes = document.querySelectorAll(`.${type}-checkbox`);
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
}
</script>

<style>
.tab-button.active {
    border-bottom-color: #3B82F6;
    color: #2563EB;
}
</style>
@endsection
