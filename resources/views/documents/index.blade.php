@extends('layouts.app')

@section('title', 'Pemberkasan Dokumen - SIPP PKL')

@section('content')
@php
    $laporanPklEnabled = \App\Models\SystemSetting::isEnabled('laporan_pkl_enabled');
    $dokumenPemberkasanEnabled = \App\Models\SystemSetting::isEnabled('dokumen_pemberkasan_enabled');
@endphp
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-file-upload text-3xl"></i>
            </div>
            <div class="ml-4">
                <h1 class="text-2xl font-bold">Pemberkasan Dokumen PKL</h1>
                <p class="text-blue-100 mt-1">Upload dan kelola dokumen yang diperlukan untuk PKL</p>
            </div>
        </div>
    </div>

    <!-- Progress Status -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Status Pemberkasan</h3>
            <div class="flex items-center text-sm text-gray-500">
                <i class="fas fa-info-circle mr-1"></i>
                <span>Terakhir diperbarui: {{ now()->format('d M Y H:i') }}</span>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @php
                $khs = Auth::user()->khs()->latest()->first();
                $surat = Auth::user()->suratBalasan()->latest()->first();
                $laporan = Auth::user()->laporanPkl()->latest()->first();
            @endphp
            
            <!-- KHS Status -->
            <div class="flex items-center p-4 border rounded-lg {{ $khs && $khs->status_validasi === 'tervalidasi' ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }}">
                <div class="flex-shrink-0">
                    <i class="fas fa-file-alt text-2xl {{ $khs && $khs->status_validasi === 'tervalidasi' ? 'text-green-600' : 'text-gray-400' }}"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">KHS</p>
                    <p class="text-xs text-gray-500">
                        @if($khs)
                            @if($khs->status_validasi === 'tervalidasi') Tervalidasi
                            @elseif($khs->status_validasi === 'belum_valid') Belum Valid
                            @elseif($khs->status_validasi === 'revisi') Perlu Revisi
                            @else Menunggu Validasi
                            @endif
                        @else
                            Belum Upload
                        @endif
                    </p>
                </div>
            </div>

            <!-- Surat Balasan Status -->
            <div class="flex items-center p-4 border rounded-lg {{ $surat && $surat->status_validasi === 'tervalidasi' ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }}">
                <div class="flex-shrink-0">
                    <i class="fas fa-envelope text-2xl {{ $surat && $surat->status_validasi === 'tervalidasi' ? 'text-green-600' : 'text-gray-400' }}"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">Surat Balasan</p>
                    <p class="text-xs text-gray-500">
                        @if($surat)
                            @if($surat->status_validasi === 'tervalidasi') Tervalidasi
                            @elseif($surat->status_validasi === 'belum_valid') Belum Valid
                            @elseif($surat->status_validasi === 'revisi') Perlu Revisi
                            @else Menunggu Validasi
                            @endif
                        @else
                            Belum Upload
                        @endif
                    </p>
                </div>
            </div>

            <!-- Laporan PKL Status -->
            <div class="flex items-center p-4 border rounded-lg {{ $laporan && $laporan->status_validasi === 'tervalidasi' ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }}">
                <div class="flex-shrink-0">
                    <i class="fas fa-book text-2xl {{ $laporan && $laporan->status_validasi === 'tervalidasi' ? 'text-green-600' : 'text-gray-400' }}"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">Laporan PKL</p>
                    <p class="text-xs text-gray-500">
                        @if($laporan)
                            @if($laporan->status_validasi === 'tervalidasi') Tervalidasi
                            @elseif($laporan->status_validasi === 'belum_valid') Belum Valid
                            @elseif($laporan->status_validasi === 'revisi') Perlu Revisi
                            @else Menunggu Validasi
                            @endif
                        @else
                            Belum Upload
                        @endif
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Progress Bar -->
        @php
            $totalDocs = 3;
            $completedDocs = 0;
            if($khs && $khs->status_validasi === 'tervalidasi') $completedDocs++;
            if($surat && $surat->status_validasi === 'tervalidasi') $completedDocs++;
            if($laporan && $laporan->status_validasi === 'tervalidasi') $completedDocs++;
            $progressPercentage = ($completedDocs / $totalDocs) * 100;
        @endphp
        
        <div class="mt-6">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-gray-700">Progress Pemberkasan</span>
                <span class="text-sm text-gray-500">{{ $completedDocs }}/{{ $totalDocs }} dokumen tervalidasi</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-gradient-to-r from-blue-500 to-green-500 h-2 rounded-full transition-all duration-500" 
                     style="width: {{ $progressPercentage }}%"></div>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="bg-white shadow rounded-lg">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button onclick="showTab('pemberkasan')" id="tab-pemberkasan" class="tab-button active py-4 px-6 border-b-2 font-medium text-sm border-blue-500 text-blue-600 transition-colors duration-200">
                    <i class="fas fa-file-alt mr-2"></i>Dokumen Pemberkasan
                </button>
                <button onclick="showTab('laporan')" id="tab-laporan" class="tab-button py-4 px-6 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-colors duration-200 {{ !$laporanPklEnabled ? 'opacity-50 cursor-not-allowed' : '' }}" 
                        {{ !$laporanPklEnabled ? 'disabled' : '' }}>
                    <i class="fas fa-book mr-2"></i>Laporan PKL
                    @if(!$laporanPklEnabled)
                        <i class="fas fa-lock ml-2 text-gray-400"></i>
                    @endif
                </button>
            </nav>
        </div>
    </div>

    <!-- Tab Content -->
    <div id="content-pemberkasan" class="tab-content">
        <!-- Pemberkasan Forms -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- KHS Upload -->
            <div class="bg-white shadow-lg rounded-xl border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-file-alt text-2xl text-white"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-semibold text-white">Kartu Hasil Studi (KHS)</h3>
                            <p class="text-blue-100 text-sm">Upload transkrip nilai terbaru</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    @php
                        $khs = Auth::user()->khs()->latest()->first();
                    @endphp
                    
                    @if($khs)
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg border">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center">
                                            <i class="fas fa-file-pdf text-red-500 mr-2"></i>
                                            <p class="text-sm font-medium text-gray-900">{{ basename($khs->file_path) }}</p>
                                        </div>
                                        <button onclick="previewFile('{{ $khs->file_path }}')" class="text-blue-600 hover:text-blue-800 text-sm">
                                            <i class="fas fa-eye mr-1"></i>Lihat
                                        </button>
                                        <!-- Alternative direct link -->
                                        <a href="/storage/{{ $khs->file_path }}" target="_blank" class="text-green-600 hover:text-green-800 text-sm ml-2">
                                            <i class="fas fa-external-link-alt mr-1"></i>Direct
                                        </a>
                                        <!-- Debug info -->
                                        <div class="text-xs text-gray-400 mt-1">
                                            Debug: {{ $khs->file_path }}
                                        </div>
                                        <!-- Test button -->
                                        <button onclick="testFileAccess('{{ $khs->file_path }}')" class="text-purple-600 hover:text-purple-800 text-xs mt-1">
                                            <i class="fas fa-bug mr-1"></i>Test
                                        </button>
                                    </div>
                                    <p class="text-xs text-gray-500 mb-3">Uploaded: {{ $khs->created_at->format('d M Y H:i') }}</p>
                                    
                                    <div class="flex items-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                            @if($khs->status_validasi === 'tervalidasi') bg-green-100 text-green-800
                                            @elseif($khs->status_validasi === 'belum_valid') bg-red-100 text-red-800
                                            @elseif($khs->status_validasi === 'revisi') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            @if($khs->status_validasi === 'tervalidasi')
                                                <i class="fas fa-check-circle mr-1"></i>Tervalidasi
                                            @elseif($khs->status_validasi === 'belum_valid')
                                                <i class="fas fa-times-circle mr-1"></i>Belum Valid
                                            @elseif($khs->status_validasi === 'revisi')
                                                <i class="fas fa-exclamation-triangle mr-1"></i>Perlu Revisi
                                            @else
                                                <i class="fas fa-clock mr-1"></i>Menunggu Validasi
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($dokumenPemberkasanEnabled)
                        <form action="{{ route('documents.khs.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div>
                                <label for="khs_file" class="block text-sm font-medium text-gray-700 mb-2">Pilih File KHS</label>
                                <div class="relative">
                                    <input type="file" id="khs_file" name="file" accept=".pdf" required
                                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-lg cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <p class="mt-2 text-xs text-gray-500 flex items-center">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Format: PDF, Maksimal: 10MB
                                </p>
                            </div>
                            
                            <button type="submit" class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200 font-medium">
                                <i class="fas fa-upload mr-2"></i>Upload KHS
                            </button>
                        </form>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-lock text-3xl mb-2"></i>
                            <p class="text-sm">Fitur upload dokumen pemberkasan sedang dinonaktifkan oleh admin</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Surat Balasan Upload -->
            <div class="bg-white shadow-lg rounded-xl border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-envelope text-2xl text-white"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-semibold text-white">Surat Balasan</h3>
                            <p class="text-green-100 text-sm">Upload surat balasan dari mitra</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    @php
                        $surat = Auth::user()->suratBalasan()->latest()->first();
                    @endphp
                    
                    @if($surat)
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg border">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center">
                                            <i class="fas fa-file-pdf text-red-500 mr-2"></i>
                                            <p class="text-sm font-medium text-gray-900">{{ basename($surat->file_path) }}</p>
                                        </div>
                                        <button onclick="previewFile('{{ $surat->file_path }}')" class="text-blue-600 hover:text-blue-800 text-sm">
                                            <i class="fas fa-eye mr-1"></i>Lihat
                                        </button>
                                        <!-- Alternative direct link -->
                                        <a href="/storage/{{ $surat->file_path }}" target="_blank" class="text-green-600 hover:text-green-800 text-sm ml-2">
                                            <i class="fas fa-external-link-alt mr-1"></i>Direct
                                        </a>
                                        <!-- Debug info -->
                                        <div class="text-xs text-gray-400 mt-1">
                                            Debug: {{ $surat->file_path }}
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 mb-2">Uploaded: {{ $surat->created_at->format('d M Y H:i') }}</p>
                                    
                                    @if($surat->mitra)
                                        <div class="mb-3 p-2 bg-blue-50 rounded border-l-4 border-blue-400">
                                            <div class="flex items-center">
                                                <i class="fas fa-building text-blue-600 mr-2"></i>
                                                <div>
                                                    <p class="text-sm font-medium text-blue-900">Mitra PKL</p>
                                                    <p class="text-xs text-blue-700">{{ $surat->mitra->nama }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($surat->mitra_nama_custom)
                                        <div class="mb-3 p-2 bg-green-50 rounded border-l-4 border-green-400">
                                            <div class="flex items-center">
                                                <i class="fas fa-building text-green-600 mr-2"></i>
                                                <div>
                                                    <p class="text-sm font-medium text-green-900">Mitra PKL (Custom)</p>
                                                    <p class="text-xs text-green-700">{{ $surat->mitra_nama_custom }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <div class="flex items-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                            @if($surat->status_validasi === 'tervalidasi') bg-green-100 text-green-800
                                            @elseif($surat->status_validasi === 'belum_valid') bg-red-100 text-red-800
                                            @elseif($surat->status_validasi === 'revisi') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            @if($surat->status_validasi === 'tervalidasi')
                                                <i class="fas fa-check-circle mr-1"></i>Tervalidasi
                                            @elseif($surat->status_validasi === 'belum_valid')
                                                <i class="fas fa-times-circle mr-1"></i>Belum Valid
                                            @elseif($surat->status_validasi === 'revisi')
                                                <i class="fas fa-exclamation-triangle mr-1"></i>Perlu Revisi
                                            @else
                                                <i class="fas fa-clock mr-1"></i>Menunggu Validasi
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($dokumenPemberkasanEnabled)
                        <form action="{{ route('documents.surat.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div>
                                <label for="mitra_id" class="block text-sm font-medium text-gray-700 mb-2">Mitra PKL</label>
                                <select id="mitra_id" name="mitra_id" 
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                    <option value="">-- Pilih Mitra --</option>
                                    @foreach(\App\Models\Mitra::all() as $mitra)
                                        <option value="{{ $mitra->id }}">{{ $mitra->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        
                        <div>
                            <label for="mitra_custom" class="block text-sm font-medium text-gray-700 mb-2">Atau Tulis Nama Mitra</label>
                            <input type="text" id="mitra_custom" name="mitra_nama_custom" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                   placeholder="Nama mitra jika tidak ada di daftar">
                        </div>
                        
                        <div>
                            <label for="surat_file" class="block text-sm font-medium text-gray-700 mb-2">Pilih File Surat Balasan</label>
                            <div class="relative">
                                <input type="file" id="surat_file" name="file" accept=".pdf" required
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 border border-gray-300 rounded-lg cursor-pointer focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            </div>
                            <p class="mt-2 text-xs text-gray-500 flex items-center">
                                <i class="fas fa-info-circle mr-1"></i>
                                Format: PDF, Maksimal: 10MB
                            </p>
                        </div>
                        
                            <button type="submit" class="w-full bg-green-600 text-white py-3 px-4 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors duration-200 font-medium">
                                <i class="fas fa-upload mr-2"></i>Upload Surat Balasan
                            </button>
                        </form>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-lock text-3xl mb-2"></i>
                            <p class="text-sm">Fitur upload dokumen pemberkasan sedang dinonaktifkan oleh admin</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div id="content-laporan" class="tab-content hidden">
        @if(!$laporanPklEnabled)
            <!-- Disabled State -->
            <div class="bg-white shadow-lg rounded-xl border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-400 to-gray-500 px-6 py-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-ban text-2xl text-white"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-semibold text-white">Laporan PKL Dinonaktifkan</h3>
                            <p class="text-gray-100 text-sm">Fitur ini sedang dinonaktifkan oleh admin</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 text-center">
                    <div class="mb-4">
                        <i class="fas fa-lock text-4xl text-gray-400"></i>
                    </div>
                    <h4 class="text-lg font-medium text-gray-900 mb-2">Laporan PKL Tidak Tersedia</h4>
                    <p class="text-gray-600 mb-4">Admin telah menonaktifkan fitur upload Laporan PKL. Silakan hubungi admin jika Anda memerlukan akses.</p>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-800">
                                    <strong>Informasi:</strong> Fitur ini dapat diaktifkan kembali oleh admin melalui Menu Sistem.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Laporan PKL Upload -->
            <div class="bg-white shadow-lg rounded-xl border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-6 py-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-book text-2xl text-white"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-semibold text-white">Laporan PKL</h3>
                        <p class="text-purple-100 text-sm">Upload laporan akhir PKL</p>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                @php
                    $laporan = Auth::user()->laporanPkl()->latest()->first();
                @endphp
                
                @if($laporan)
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg border">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-file-pdf text-red-500 mr-2"></i>
                                    <p class="text-sm font-medium text-gray-900">{{ basename($laporan->file_path) }}</p>
                                </div>
                                <p class="text-xs text-gray-500 mb-3">Uploaded: {{ $laporan->created_at->format('d M Y H:i') }}</p>
                                
                                <div class="flex items-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                        @if($laporan->status_validasi === 'tervalidasi') bg-green-100 text-green-800
                                        @elseif($laporan->status_validasi === 'belum_valid') bg-red-100 text-red-800
                                        @elseif($laporan->status_validasi === 'revisi') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        @if($laporan->status_validasi === 'tervalidasi')
                                            <i class="fas fa-check-circle mr-1"></i>Tervalidasi
                                        @elseif($laporan->status_validasi === 'belum_valid')
                                            <i class="fas fa-times-circle mr-1"></i>Belum Valid
                                        @elseif($laporan->status_validasi === 'revisi')
                                            <i class="fas fa-exclamation-triangle mr-1"></i>Perlu Revisi
                                        @else
                                            <i class="fas fa-clock mr-1"></i>Menunggu Validasi
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('documents.laporan.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label for="laporan_file" class="block text-sm font-medium text-gray-700 mb-2">Pilih File Laporan PKL</label>
                        <div class="relative">
                            <input type="file" id="laporan_file" name="file" accept=".pdf" required
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 border border-gray-300 rounded-lg cursor-pointer focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        </div>
                        <p class="mt-2 text-xs text-gray-500 flex items-center">
                            <i class="fas fa-info-circle mr-1"></i>
                            Format: PDF, Maksimal: 10MB
                        </p>
                    </div>
                    
                    <button type="submit" class="w-full bg-purple-600 text-white py-3 px-4 rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-colors duration-200 font-medium">
                        <i class="fas fa-upload mr-2"></i>Upload Laporan PKL
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Help Section -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-question-circle text-blue-600 text-xl"></i>
            </div>
            <div class="ml-3">
                <h4 class="text-lg font-medium text-blue-900 mb-2">Panduan Upload Dokumen</h4>
                <div class="text-sm text-blue-800 space-y-2">
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-2 mt-0.5"></i>
                        <span><strong>KHS:</strong> Upload transkrip nilai terbaru dalam format PDF</span>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-2 mt-0.5"></i>
                        <span><strong>Surat Balasan:</strong> Upload surat balasan dari mitra PKL</span>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-2 mt-0.5"></i>
                        <span><strong>Laporan PKL:</strong> Upload laporan akhir PKL yang telah diselesaikan</span>
                    </div>
                    <div class="mt-3 p-3 bg-blue-100 rounded-lg">
                        <p class="text-xs text-blue-700">
                            <i class="fas fa-info-circle mr-1"></i>
                            <strong>Catatan:</strong> Semua dokumen harus dalam format PDF dengan ukuran maksimal 10MB. 
                            Pastikan dokumen sudah lengkap dan jelas sebelum diupload.
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
function showTab(tabName) {
    // Check if Laporan PKL is disabled
    @if(!$laporanPklEnabled)
    if (tabName === 'laporan') {
        alert('Fitur Laporan PKL sedang dinonaktifkan oleh admin.');
        return;
    }
    @endif
    
    // Hide all tab contents with smooth transition
    document.querySelectorAll('.tab-content').forEach(content => {
        content.style.opacity = '0';
        setTimeout(() => {
            content.classList.add('hidden');
        }, 150);
    });
    
    // Remove active class from all tabs
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active', 'border-blue-500', 'text-blue-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content with smooth transition
    setTimeout(() => {
        const targetContent = document.getElementById('content-' + tabName);
        targetContent.classList.remove('hidden');
        targetContent.style.opacity = '0';
        setTimeout(() => {
            targetContent.style.opacity = '1';
        }, 50);
    }, 150);
    
    // Add active class to selected tab
    const activeTab = document.getElementById('tab-' + tabName);
    activeTab.classList.add('active', 'border-blue-500', 'text-blue-600');
    activeTab.classList.remove('border-transparent', 'text-gray-500');
}

// Add smooth transitions to tab content
document.addEventListener('DOMContentLoaded', function() {
    const tabContents = document.querySelectorAll('.tab-content');
    tabContents.forEach(content => {
        content.style.transition = 'opacity 0.3s ease-in-out';
    });
});
</script>
@endsection

@push('scripts')
<script>
function previewFile(filePath) {
    console.log('=== FILE PREVIEW DEBUG ===');
    console.log('Original filePath:', filePath);
    console.log('Type of filePath:', typeof filePath);
    
    // Handle null/undefined
    if (!filePath) {
        console.error('File path is null or undefined');
        alert('File path tidak ditemukan');
        return;
    }
    
    // Convert to string and clean the path
    let cleanPath = filePath.toString().trim();
    
    // Remove any leading slashes or storage prefixes
    cleanPath = cleanPath.replace(/^\/+/, ''); // Remove leading slashes
    cleanPath = cleanPath.replace(/^storage\//, ''); // Remove storage/ prefix
    cleanPath = cleanPath.replace(/^\/storage\//, ''); // Remove /storage/ prefix
    
    console.log('Clean path:', cleanPath);
    
    // Test if URL is valid
    if (!cleanPath || cleanPath.trim() === '') {
        console.error('Clean path is empty');
        alert('Path file tidak valid');
        return;
    }
    
    // Build final URL - files are stored in documents/khs/ or documents/surat_balasan/
    const url = '/storage/' + cleanPath;
    console.log('Final URL:', url);
    
    // Open file in new tab
    console.log('Attempting to open:', url);
    
    // Create a test link to check if file exists
    const testLink = document.createElement('a');
    testLink.href = url;
    testLink.target = '_blank';
    testLink.style.display = 'none';
    document.body.appendChild(testLink);
    
    // Try to open the file
    try {
        testLink.click();
        console.log('File opened successfully');
        
        // Clean up test link
        setTimeout(() => {
            document.body.removeChild(testLink);
        }, 1000);
        
    } catch (error) {
        console.error('Error opening file:', error);
        alert('Gagal membuka file: ' + error.message);
        
        // Clean up test link
        document.body.removeChild(testLink);
    }
}

function testFileAccess(filePath) {
    console.log('=== TEST FILE ACCESS ===');
    console.log('File path:', filePath);
    
    // Test different URL formats
    const urls = [
        '/storage/' + filePath,
        '/storage/documents/khs/' + filePath,
        '/storage/documents/surat_balasan/' + filePath,
        '/storage/' + filePath.replace('documents/', ''),
        filePath.startsWith('documents/') ? '/storage/' + filePath : '/storage/documents/' + filePath
    ];
    
    console.log('Testing URLs:', urls);
    
    // Test each URL
    urls.forEach((url, index) => {
        console.log(`Testing URL ${index + 1}: ${url}`);
        
        // Create test link
        const testLink = document.createElement('a');
        testLink.href = url;
        testLink.target = '_blank';
        testLink.style.display = 'none';
        testLink.textContent = `Test ${index + 1}`;
        document.body.appendChild(testLink);
        
        // Try to click
        try {
            testLink.click();
            console.log(`URL ${index + 1} clicked successfully`);
        } catch (error) {
            console.error(`URL ${index + 1} failed:`, error);
        }
        
        // Clean up
        setTimeout(() => {
            if (document.body.contains(testLink)) {
                document.body.removeChild(testLink);
            }
        }, 1000);
    });
}
</script>
@endpush