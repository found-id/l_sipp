@extends('layouts.app')

@section('title', 'Pemberkasan Dokumen - SIPP PKL')

@section('content')
@php
    // Feature flags
    $laporanPklEnabled = \App\Models\SystemSetting::isEnabled('laporan_pkl_enabled');
    $instansiMitraEnabled = \App\Models\SystemSetting::isEnabled('instansi_mitra_enabled');
    $dokumenPemberkasanEnabled = \App\Models\SystemSetting::isEnabled('dokumen_pemberkasan_enabled');

    // Dokumen terakhir per user (sekali ambil di awal)
    $user    = Auth::user();
    $khs     = optional($user->khs())->latest()->first();
    $surat   = optional($user->suratBalasan())->latest()->first();
    $laporan = optional($user->laporanPkl())->latest()->first();

    // Step header "Langkah 1: Cek Kelayakan"
    $stepBadgeOk   = ($khs && $khs->status_validasi === 'tervalidasi');
    $stepBadgeCls  = $stepBadgeOk ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-800';
    $stepBadgeText = $stepBadgeOk ? 'KHS tervalidasi' : 'Menunggu unggah/validasi KHS';

    // Gate: Ajukan Pemberkasan baru dibuka kalau KHS tervalidasi
    $canAjukan = $stepBadgeOk;
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

                <button onclick="showTab('laporan')" id="tab-laporan"
                        class="tab-button py-4 px-6 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-colors duration-200 {{ !$laporanPklEnabled ? 'opacity-50 cursor-not-allowed' : '' }}" 
                        {{ !$laporanPklEnabled ? 'disabled' : '' }}>
                    <i class="fas fa-book mr-2"></i>Pemberkasan Akhir
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
                            <i class="fas fa-graduation-cap text-2xl text-white"></i>
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
                                            <p class="text-sm font-medium text-gray-900">{{ basename($suratBalasan->file_path ?? '') }}</p>
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
                                            @if(isset($suratBalasan->status_validasi) && $suratBalasan->status_validasi === 'tervalidasi') bg-green-100 text-green-800
                                            @elseif(isset($suratBalasan->status_validasi) && $suratBalasan->status_validasi === 'belum_valid') bg-red-100 text-red-800
                                            @elseif(isset($suratBalasan->status_validasi) && $suratBalasan->status_validasi === 'revisi') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            @if(isset($suratBalasan->status_validasi) && $suratBalasan->status_validasi === 'tervalidasi')
                                                <i class="fas fa-check-circle mr-1"></i>Tervalidasi
                                            @elseif(isset($suratBalasan->status_validasi) && $suratBalasan->status_validasi === 'belum_valid')
                                                <i class="fas fa-times-circle mr-1"></i>Belum Valid
                                            @elseif(isset($suratBalasan->status_validasi) && $suratBalasan->status_validasi === 'revisi')
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

                    <form action="{{ route('documents.surat.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div>
                            <label for="surat_file" class="block text-sm font-medium text-gray-700 mb-2">Pilih File Surat Balasan</label>
                                <div class="relative">
                                <input type="file" id="surat_file" name="file" accept=".pdf" required
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100 border border-gray-300 rounded-lg cursor-pointer focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                </div>
                                <p class="mt-2 text-xs text-gray-500 flex items-center">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Format: PDF, Maksimal: 10MB
                                </p>
                            </div>
                            
                        <div>
                            <label for="mitra_id" class="block text-sm font-medium text-gray-700 mb-2">Pilih Instansi Mitra</label>
                            <select id="mitra_id" name="mitra_id" required class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                <option value="">-- Pilih Instansi --</option>
                                @if(isset($mitraList) && $mitraList->count() > 0)
                                    @foreach($mitraList as $m)
                                        @if($m->nama_instansi && $m->alamat)
                                            <option value="{{ $m->id }}" 
                                                @if(isset($suratBalasan) && $suratBalasan->mitra_id == $m->id) selected @endif>
                                                {{ $m->nama_instansi ?? 'N/A' }} - {{ $m->alamat ?? 'N/A' }}
                                            </option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        
                        <button type="submit" class="w-full bg-orange-600 text-white py-3 px-4 rounded-lg hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-colors duration-200 font-medium">
                            <i class="fas fa-upload mr-2"></i>Upload Surat Balasan
                            </button>
                        </form>
                </div>
                        </div>
                    @endif
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
                            <h3 class="text-lg font-semibold text-white">Laporan PKL</h3>
                            <p class="text-gray-100 text-sm">Fitur dinonaktifkan oleh admin</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-6 text-center">
                    <i class="fas fa-lock text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">Fitur upload laporan PKL sedang dinonaktifkan</p>
                                    </div>
                                    </div>
        @else
            <div class="bg-white shadow-lg rounded-xl border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-file-alt text-2xl text-white"></i>
                                    </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-semibold text-white">Laporan PKL</h3>
                            <p class="text-indigo-100 text-sm">Upload laporan akhir PKL</p>
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
                                    
                                    <div class="flex items-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                            @if(isset($laporan->status_validasi) && $laporan->status_validasi === 'tervalidasi') bg-green-100 text-green-800
                                            @elseif(isset($laporan->status_validasi) && $laporan->status_validasi === 'belum_valid') bg-red-100 text-red-800
                                            @elseif(isset($laporan->status_validasi) && $laporan->status_validasi === 'revisi') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            @if(isset($laporan->status_validasi) && $laporan->status_validasi === 'tervalidasi')
                                                <i class="fas fa-check-circle mr-1"></i>Tervalidasi
                                            @elseif(isset($laporan->status_validasi) && $laporan->status_validasi === 'belum_valid')
                                                <i class="fas fa-times-circle mr-1"></i>Belum Valid
                                            @elseif(isset($laporan->status_validasi) && $laporan->status_validasi === 'revisi')
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
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-gray-300 rounded-lg cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                            <p class="mt-2 text-xs text-gray-500 flex items-center">
                                <i class="fas fa-info-circle mr-1"></i>
                                Format: PDF, Maksimal: 10MB
                            </p>
                                </div>
                        
                        <button type="submit" class="w-full bg-indigo-600 text-white py-3 px-4 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors duration-200 font-medium">
                            <i class="fas fa-upload mr-2"></i>Upload Laporan PKL
                        </button>
                    </form>
                </div>
            </div>
        @endif
                            </div>
                        </div>

    <!-- Tab Content: Dokumen Pendukung -->
    <div id="content-dokumen-pendukung" class="tab-content hidden">
        <div class="bg-white shadow-lg rounded-xl border border-gray-100 overflow-hidden mt-6">
            <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fab fa-google-drive text-2xl text-white"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-semibold text-white">Dokumen Pendukung</h3>
                        <p class="text-green-100 text-sm">Masukkan link Google Drive untuk dokumen pendukung</p>
                    </div>
                </div>
            </div>
            
            <div class="p-6 space-y-6">
                <!-- Sertifikat PKKMB -->
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="flex items-center mb-3">
                        <i class="fab fa-google-drive text-blue-500 mr-2"></i>
                        <h4 class="text-lg font-medium text-gray-900">Sertifikat PKKMB</h4>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <label for="link_pkkmb" class="block text-sm font-medium text-gray-700 mb-2">Link Google Drive Sertifikat PKKMB</label>
                            <input type="url" id="link_pkkmb" name="link_pkkmb" placeholder="https://drive.google.com/file/d/..." 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <p class="text-xs text-gray-500 flex items-center">
                            <i class="fas fa-info-circle mr-1"></i>
                            Paste link Google Drive yang dapat diakses publik
                        </p>
                    </div>
                </div>

                <!-- Sertifikat English Course -->
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="flex items-center mb-3">
                        <i class="fab fa-google-drive text-blue-500 mr-2"></i>
                        <h4 class="text-lg font-medium text-gray-900">Sertifikat English Course</h4>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <label for="link_english" class="block text-sm font-medium text-gray-700 mb-2">Link Google Drive Sertifikat English Course</label>
                            <input type="url" id="link_english" name="link_english" placeholder="https://drive.google.com/file/d/..." 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <p class="text-xs text-gray-500 flex items-center">
                            <i class="fas fa-info-circle mr-1"></i>
                            Paste link Google Drive yang dapat diakses publik
                        </p>
                    </div>
                </div>

                <!-- Sertifikat Semasa Berkuliah -->
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="flex items-center mb-3">
                        <i class="fab fa-google-drive text-blue-500 mr-2"></i>
                        <h4 class="text-lg font-medium text-gray-900">Sertifikat Semasa Berkuliah di Politeknik Negeri Tanah Laut</h4>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <label for="link_semasa" class="block text-sm font-medium text-gray-700 mb-2">Link Google Drive Sertifikat Semasa Berkuliah</label>
                            <input type="url" id="link_semasa" name="link_semasa" placeholder="https://drive.google.com/file/d/..." 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <p class="text-xs text-gray-500 flex items-center">
                            <i class="fas fa-info-circle mr-1"></i>
                            Paste link Google Drive yang dapat diakses publik
                        </p>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="pt-4 border-t border-gray-200">
                    <button type="button" onclick="saveDokumenPendukung()" class="w-full bg-green-600 text-white py-3 px-4 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors duration-200 font-medium">
                        <i class="fas fa-save mr-2"></i>Simpan Link Dokumen Pendukung
                    </button>
                </div>
            </div>
        </div>
    </div>

<script>
// Tab switching functionality
function showTab(tabName) {
    // Check if features are disabled
    @if(!$dokumenPemberkasanEnabled)
    if (tabName === 'pemberkasan') {
        alert('Fitur pemberkasan sedang dinonaktifkan oleh admin.');
        return;
    }
    @endif
    
    // Check if Surat Balasan is disabled
    @if(!$instansiMitraEnabled)
    if (tabName === 'surat-balasan') {
        alert('Fitur surat balasan sedang dinonaktifkan oleh admin.');
        return;
    }
                                        @endif
    
    // Check if Laporan PKL is disabled
    @if(!$laporanPklEnabled)
    if (tabName === 'laporan') {
        alert('Fitur laporan PKL sedang dinonaktifkan oleh admin.');
        return;
    }
    @endif
    
    // Hide all tab contents with smooth transition
    const tabContents = document.querySelectorAll('.tab-content');
    tabContents.forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active class from all tab buttons
    const tabButtons = document.querySelectorAll('.tab-button');
    tabButtons.forEach(button => {
        button.classList.remove('border-blue-500', 'text-blue-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content with smooth transition
    const selectedContent = document.getElementById(`content-${tabName}`);
    if (selectedContent) {
        selectedContent.classList.remove('hidden');
    }
    
    // Add active class to selected tab button
    const selectedButton = document.getElementById(`tab-${tabName}`);
    if (selectedButton) {
        selectedButton.classList.remove('border-transparent', 'text-gray-500');
        selectedButton.classList.add('border-blue-500', 'text-blue-600');
    }
}

// Initialize first tab as active
document.addEventListener('DOMContentLoaded', function() {
    // Initialize KHS file count display immediately
    const khsFileCount = @json($khsFileCount ?? 0);
    console.log('Initializing KHS file count:', khsFileCount);
    document.getElementById('uploadKhs').textContent = `${khsFileCount}/5`;
    
    // Initialize PKL status based on both transcript and KHS file count
    const pklStatusElement = document.getElementById('pklStatus');
    const isTranscriptComplete = false; // Will be updated by calculateFinalIpk
    const isKhsComplete = khsFileCount >= 5;
    
    if (!isTranscriptComplete || !isKhsComplete) {
        pklStatusElement.textContent = 'Belum Lengkap';
        pklStatusElement.className = 'text-3xl font-bold text-yellow-600 mb-1';
    } else {
        pklStatusElement.textContent = 'Tidak Layak';
        pklStatusElement.className = 'text-3xl font-bold text-red-600 mb-1';
    }
    
    showTab('pemberkasan');
});

// Ensure functions are available globally
window.previewFile = function(filePath) {
    console.log('previewFile called with:', filePath);
    
    if (!filePath || filePath === '') {
        alert('File path tidak ditemukan');
        return;
    }
    
    const filename = filePath.split('/').pop();
    let fileType = 'khs';
    if (filePath.includes('surat_balasan')) {
        fileType = 'surat-balasan';
    } else if (filePath.includes('laporan')) {
        fileType = 'laporan';
    }
    
    console.log('Filename:', filename, 'FileType:', fileType);
    
    const url = `{{ route('documents.preview', ['type' => 'TYPE_PLACEHOLDER', 'filename' => 'FILENAME_PLACEHOLDER']) }}`
        .replace('TYPE_PLACEHOLDER', fileType)
        .replace('FILENAME_PLACEHOLDER', encodeURIComponent(filename));
    
    console.log('Opening URL:', url);
    
    // Try to open in new tab
    const newWindow = window.open(url, '_blank');
    if (!newWindow) {
        alert('Pop-up blocked. Please allow pop-ups for this site.');
    }
};

window.downloadPdf = function(filePath) {
    console.log('downloadPdf called with:', filePath);
    
    if (!filePath || filePath === '') {
        alert('File path tidak ditemukan');
        return;
    }
    
    const filename = filePath.split('/').pop();
    let fileType = 'khs';
    if (filePath.includes('surat_balasan')) {
        fileType = 'surat-balasan';
    } else if (filePath.includes('laporan')) {
        fileType = 'laporan';
    }
    
    console.log('Filename:', filename, 'FileType:', fileType);
    
    const url = `{{ route('documents.download', ['type' => 'TYPE_PLACEHOLDER', 'filename' => 'FILENAME_PLACEHOLDER']) }}`
        .replace('TYPE_PLACEHOLDER', fileType)
        .replace('FILENAME_PLACEHOLDER', encodeURIComponent(filename));
    
    console.log('Download URL:', url);
    
    // Create temporary link and trigger download
    const link = document.createElement('a');
    link.href = url;
    link.download = filename;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
};

// TPK refresh function removed - data now stored in KHS table

window.deleteFile = function(type, id) {
    console.log('deleteFile called with type:', type, 'id:', id);
    
    if (!confirm('Apakah Anda yakin ingin menghapus file ini?')) {
        console.log('User cancelled deletion');
        return;
    }
    
    let url = '';
    if (type === 'khs') {
        url = '{{ route("documents.khs.delete", ":id") }}'.replace(':id', id);
    } else if (type === 'surat-balasan') {
        url = '{{ route("documents.surat-balasan.delete", ":id") }}'.replace(':id', id);
    } else if (type === 'laporan') {
        url = '{{ route("documents.laporan.delete", ":id") }}'.replace(':id', id);
    }
    
    console.log('Delete URL:', url);
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error('CSRF token not found');
        alert('CSRF token tidak ditemukan');
        return;
    }
    
    console.log('CSRF token found:', csrfToken.getAttribute('content'));
    
    // Show loading state
    const button = document.querySelector(`button[onclick*="deleteFile('${type}', ${id})"]`);
    const originalText = button ? button.innerHTML : '';
    if (button) {
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Menghapus...';
        button.disabled = true;
    }
    
    fetch(url, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken.getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Delete response:', data);
        if (data.success) {
            alert('File berhasil dihapus!');
            window.location.reload();
        } else {
            alert('Gagal menghapus file: ' + (data.message || 'Unknown error'));
            // Restore button state
            if (button) {
                button.innerHTML = originalText;
                button.disabled = false;
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menghapus file: ' + error.message);
        // Restore button state
        if (button) {
            button.innerHTML = originalText;
            button.disabled = false;
        }
    });
};

// Multi Semester Analysis Functions
let semesterData = {};

    // Load saved semester data from database
    function loadSavedSemesterData() {
        console.log('=== LOADING SAVED SEMESTER DATA ===');
        
        // Data KHS Manual Transkrip dari PHP (passed from controller)
        const khsManualTranskrip = @json($khsManualTranskrip ?? []);
        const khsFileCount = @json($khsFileCount ?? 0);
        console.log('KHS Manual Transkrip from database:', khsManualTranskrip);
        console.log('Number of records:', khsManualTranskrip.length);
        console.log('KHS Files in storage count:', khsFileCount);
        console.log('Type of khsFileCount:', typeof khsFileCount);
        
        // Debug: Check if data is being passed correctly
        if (khsManualTranskrip.length === 0) {
            console.error('❌ No KHS Manual Transkrip data found!');
            console.log('This might be the issue - no data is being passed from controller');
        } else {
            console.log('✅ KHS Manual Transkrip data found, processing...');
        }
    
    if (khsManualTranskrip.length === 0) {
        console.log('No saved data found');
        return;
    }
    
    console.log(`Found ${khsManualTranskrip.length} KHS Manual Transkrip files to process`);
    
    khsManualTranskrip.forEach((khs, index) => {
        console.log(`Processing KHS Manual Transkrip ${index + 1}:`, {
            id: khs.id,
            semester: khs.semester,
            has_transcript_data: !!khs.transcript_data,
            transcript_data_length: khs.transcript_data ? khs.transcript_data.length : 0
        });
        
        if (khs.transcript_data && khs.semester) {
            const semester = khs.semester;
            console.log(`Loading data for semester ${semester}:`, khs.transcript_data.substring(0, 100) + '...');
            
            // Show loading indicator
            const loadingElement = document.getElementById(`loading${semester}`);
            if (loadingElement) {
                loadingElement.classList.remove('hidden');
            }
            
            // Populate textarea
            const textarea = document.getElementById(`pasteArea${semester}`);
            if (textarea) {
                textarea.value = khs.transcript_data;
                
                // Selalu ekstrak dari transcript_data yang tersimpan
                let analysis = null;
                
                if (khs.transcript_data) {
                    console.log(`Extracting from saved transcript_data for semester ${semester}:`, khs.transcript_data.substring(0, 200) + '...');
                    
                    // Parse transcript_data menjadi rows
                    const rows = parseTranscript(khs.transcript_data);
                    if (rows && rows.length > 1) {
                        analysis = analyzeTranscriptData(rows);
                        console.log(`Extracted analysis from saved data for semester ${semester}:`, analysis);
                    }
                }
                
                if (analysis) {
                    semesterData[semester] = analysis;
                    console.log(`Setting semesterData[${semester}] =`, analysis);
                    console.log(`Semester ${semester} IPS: ${analysis.ips}`);
                    
                    // Parse and render table first
                    const rows = parseTranscript(khs.transcript_data);
                    if (rows && rows.length > 1) {
                        const preview = document.getElementById(`preview${semester}`);
                        if (preview) {
                            renderTable(rows, preview);
                        }
                        
                        // Tunggu sebentar agar tabel selesai di-render, lalu update result table
                        setTimeout(() => {
                            console.log(`Calling updateResultTable for semester ${semester} with analysis:`, analysis);
                            updateResultTable(semester, analysis);
                            console.log(`✅ Successfully loaded and analyzed semester ${semester}`);
                        }, 100);
                    }
                    
                    // Show success message
                    const resultElement = document.getElementById(`result${semester}`);
                    if (resultElement) {
                        resultElement.innerHTML = `
                            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-3 py-2 rounded text-sm">
                                <i class="fas fa-download mr-1"></i>Data dimuat dari database
                            </div>
                        `;
                    }
                    
                    console.log(`Restored semester ${semester} data:`, analysis);
                } else {
                    console.log(`❌ No analysis data found for semester ${semester}`);
                    console.log(`   transcript_data exists: ${!!khs.transcript_data}`);
                    console.log(`   transcript_data length: ${khs.transcript_data ? khs.transcript_data.length : 0}`);
                }
                
                // Hide loading indicator
                if (loadingElement) {
                    loadingElement.classList.add('hidden');
                }
            }
        }
    });
    
        // Recalculate final IPK after loading all data
        setTimeout(() => {
            console.log('Recalculating final IPK after loading all saved data');
            console.log('Current semesterData before calculation:', semesterData);
            calculateFinalIpk();
        }, 500);
}

// Auto-parse on paste/input for each semester
document.addEventListener('DOMContentLoaded', function() {
    console.log('=== DOM LOADED - SETTING UP EVENT LISTENERS ===');
    
    // Debug: Check if all required elements exist
    for (let semester = 1; semester <= 5; semester++) {
        const textarea = document.getElementById(`pasteArea${semester}`);
        const preview = document.getElementById(`preview${semester}`);
        const result = document.getElementById(`result${semester}`);
        const ipsElement = document.getElementById(`ips${semester}`);
        const totalSksElement = document.getElementById(`totalSks${semester}`);
        
        console.log(`Semester ${semester} elements:`, {
            textarea: !!textarea,
            preview: !!preview,
            result: !!result,
            ipsElement: !!ipsElement,
            totalSksElement: !!totalSksElement
        });
    }
    
    // Check final IPK elements
    const finalIpkElement = document.getElementById('finalIpk');
    const totalSemesterElement = document.getElementById('totalSemester');
    const pklStatusElement = document.getElementById('pklStatus');
    
    console.log('Final IPK elements:', {
        finalIpk: !!finalIpkElement,
        totalSemester: !!totalSemesterElement,
        pklStatus: !!pklStatusElement
    });
    
    // Load saved data first
    loadSavedSemesterData();
    
    for (let semester = 1; semester <= 5; semester++) {
        const textarea = document.getElementById(`pasteArea${semester}`);
        console.log(`Setting up listeners for semester ${semester}, textarea found:`, !!textarea);
        
        if (textarea) {
            // Auto-parse on paste
            textarea.addEventListener('paste', function(e) {
                console.log(`Paste detected in semester ${semester}`);
                setTimeout(() => {
                    console.log(`Auto-analyzing semester ${semester} after paste`);
                    autoAnalyzeSemester(semester);
                }, 100); // Small delay to ensure paste content is in textarea
            });
            
                // Auto-parse on input (typing)
                textarea.addEventListener('input', function(e) {
                    console.log(`Input detected in semester ${semester}, length:`, e.target.value.trim().length);
                    setTimeout(() => {
                        console.log(`Auto-analyzing semester ${semester} after input`);
                        autoAnalyzeSemester(semester);
                    }, 300); // Reduced debounce delay for faster response
                });
        }
    }
});

// Auto-analyze function (called on paste/input)
function autoAnalyzeSemester(semester) {
    console.log(`autoAnalyzeSemester called for semester ${semester}`);
    
    const textarea = document.getElementById(`pasteArea${semester}`);
    const preview = document.getElementById(`preview${semester}`);
    const result = document.getElementById(`result${semester}`);
    
    console.log(`Elements found - textarea: ${!!textarea}, preview: ${!!preview}, result: ${!!result}`);
    
    if (!textarea || !textarea.value.trim()) {
        console.log(`No content in textarea for semester ${semester}, clearing...`);
        // Clear everything if no content
        if (preview) {
            preview.innerHTML = `
                <div class="flex items-center justify-center h-full text-gray-400">
                    <div class="text-center">
                        <i class="fas fa-table text-2xl mb-2"></i>
                        <p>Tabel akan muncul setelah paste data</p>
                    </div>
                </div>
            `;
        }
        if (result) {
            result.innerHTML = '';
        }
        hideResultTable(semester);
        delete semesterData[semester];
        calculateFinalIpk();
        return;
    }
    
    const text = textarea.value;
    console.log(`Text content for semester ${semester}:`, text.substring(0, 100) + '...');
    
    const rows = parseTranscript(text);
    console.log(`Parsed rows for semester ${semester}:`, rows);
    
        if (rows && rows.length > 1) {
            console.log(`Rendering table for semester ${semester}`);
            if (preview) {
                renderTable(rows, preview);
            }
            
            // Analyze the data
            const analysis = analyzeTranscriptData(rows);
            console.log(`Analysis result for semester ${semester}:`, analysis);
            
            if (analysis && !analysis.error) {
                semesterData[semester] = analysis;
                
                // Auto-save to database
                autoSaveSemester(semester, text);
                
                // Tunggu sebentar agar tabel selesai di-render, lalu update result table
                setTimeout(() => {
                    console.log(`Updating result table for semester ${semester} after table render`);
                    updateResultTable(semester, analysis);
                }, 100);
            
            // Show success message
            if (result) {
                result.innerHTML = `
                    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-3 py-2 rounded text-sm">
                        <i class="fas fa-info-circle mr-1"></i>Data berhasil diproses! Klik Simpan untuk menyimpan ke database.
                    </div>
                `;
            }
            
            // Recalculate final IPK
            calculateFinalIpk();
        } else {
            console.log(`Analysis failed for semester ${semester}:`, analysis);
            if (result) {
                result.innerHTML = `
                    <div class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded text-sm">
                        <i class="fas fa-exclamation-triangle mr-1"></i>Analisis gagal: ${analysis?.error || 'Unknown error'}
                    </div>
                `;
            }
            hideResultTable(semester);
            delete semesterData[semester];
            calculateFinalIpk();
        }
    } else {
        console.log(`Parsing failed for semester ${semester}, rows:`, rows);
        // Show error if parsing failed
        if (preview) {
            preview.innerHTML = `
                <div class="flex items-center justify-center h-full text-red-400">
                    <div class="text-center">
                        <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                        <p>Format data tidak valid</p>
                        <p class="text-xs mt-1">Pastikan data memiliki header dan baris data</p>
                    </div>
                </div>
            `;
        }
        if (result) {
            result.innerHTML = `
                <div class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded text-sm">
                    <i class="fas fa-exclamation-triangle mr-1"></i>Format data tidak valid
                </div>
            `;
        }
        hideResultTable(semester);
        delete semesterData[semester];
        calculateFinalIpk();
    }
}

// Manual analyze function (kept for compatibility)
function analyzeSemester(semester) {
    autoAnalyzeSemester(semester);
}

// Manual save function
function saveSemester(semester) {
    console.log(`=== Manual save triggered for semester ${semester} ===`);
    
    const textarea = document.getElementById(`pasteArea${semester}`);
    const preview = document.getElementById(`preview${semester}`);
    const result = document.getElementById(`result${semester}`);
    
    if (!textarea || !textarea.value.trim()) {
        console.log(`No content in textarea for semester ${semester}`);
        if (result) {
            result.innerHTML = `
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-3 py-2 rounded text-sm">
                    <i class="fas fa-exclamation-triangle mr-1"></i>Silakan paste data transkrip terlebih dahulu
                </div>
            `;
        }
        return;
    }
    
    const text = textarea.value;
    console.log(`Manual save - Text content for semester ${semester}:`, text.substring(0, 100) + '...');
    
    // Show loading indicator
    if (result) {
        result.innerHTML = `
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-3 py-2 rounded text-sm">
                <i class="fas fa-spinner fa-spin mr-1"></i>Menyimpan data...
            </div>
        `;
    }
    
    // Parse and analyze
    const rows = parseTranscript(text);
    console.log(`Manual save - Parsed rows for semester ${semester}:`, rows);
    
    if (rows && rows.length > 1) {
        console.log(`Manual save - Rendering table for semester ${semester}`);
        if (preview) {
            renderTable(rows, preview);
        }
        
        // Analyze the data
        const analysis = analyzeTranscriptData(rows);
        console.log(`Manual save - Analysis result for semester ${semester}:`, analysis);
        
        if (analysis && !analysis.error) {
            semesterData[semester] = analysis;
            
            // Update result table immediately
            console.log(`Manual save - Updating result table for semester ${semester}`);
            updateResultTable(semester, analysis);
            
            // Save to database
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('semester', semester);
            formData.append('transcript_data', text);
            
            fetch("{{ route('documents.save-semester-data') }}", {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log(`Semester ${semester} data saved successfully:`, data);
                    
                    // Show success message
                    if (result) {
                        result.innerHTML = `
                            <div class="bg-green-100 border border-green-400 text-green-700 px-3 py-2 rounded text-sm">
                                <i class="fas fa-check-circle mr-1"></i>Data berhasil disimpan! Halaman akan di-refresh...
                            </div>
                        `;
                    }
                    
                    // Recalculate final IPK
                    calculateFinalIpk();
                    
                    console.log(`✅ Manual save completed for semester ${semester}`);
                    
                    // Refresh halaman setelah 1.5 detik
                    setTimeout(() => {
                        console.log(`Refreshing page after save for semester ${semester}`);
                        window.location.reload();
                    }, 1500);
                } else {
                    console.error(`Failed to save semester ${semester} data:`, data.message);
                    if (result) {
                        result.innerHTML = `
                            <div class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded text-sm">
                                <i class="fas fa-exclamation-triangle mr-1"></i>Gagal menyimpan: ${data.message}
                            </div>
                        `;
                    }
                }
            })
            .catch(error => {
                console.error(`Error saving semester ${semester} data:`, error);
                if (result) {
                    result.innerHTML = `
                        <div class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded text-sm">
                            <i class="fas fa-exclamation-triangle mr-1"></i>Error koneksi: ${error.message}
                        </div>
                    `;
                }
            });
        } else {
            console.log(`Manual save - Analysis failed for semester ${semester}:`, analysis);
            if (result) {
                result.innerHTML = `
                    <div class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded text-sm">
                        <i class="fas fa-exclamation-triangle mr-1"></i>Analisis gagal: ${analysis?.error || 'Unknown error'}
                    </div>
                `;
            }
        }
    } else {
        console.log(`Manual save - Parsing failed for semester ${semester}, rows:`, rows);
        if (result) {
            result.innerHTML = `
                <div class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded text-sm">
                    <i class="fas fa-exclamation-triangle mr-1"></i>Format data tidak valid
                </div>
            `;
        }
    }
}


    // Helper function to update result table
    function updateResultTable(semester, analysis) {
        console.log(`=== updateResultTable called for semester ${semester} ===`);
        console.log(`Analysis data:`, analysis);
        
        const tableResult = document.getElementById(`tableResult${semester}`);
        const ipsElement = document.getElementById(`ips${semester}`);
        const sksDElement = document.getElementById(`sksD${semester}`);
        const hasEElement = document.getElementById(`hasE${semester}`);
        const totalSksElement = document.getElementById(`totalSks${semester}`);
        
        console.log(`Elements found for semester ${semester}:`, {
            tableResult: !!tableResult,
            ipsElement: !!ipsElement,
            sksDElement: !!sksDElement,
            hasEElement: !!hasEElement,
            totalSksElement: !!totalSksElement
        });
        
        if (!tableResult) {
            console.error(`❌ tableResult element not found for semester ${semester}`);
            return;
        }
        
        // Ambil IPS dari tabel yang sudah di-render (baris 12 kolom 2)
        const preview = document.getElementById(`preview${semester}`);
        let ipsFromTable = null;
        let totalSksFromTable = null;
        
        if (preview) {
            const table = preview.querySelector('table');
            if (table) {
                const rows = table.querySelectorAll('tr');
                console.log(`Found ${rows.length} rows in table for semester ${semester}`);
                
                // Cari baris yang mengandung "Indeks Prestasi Semester" (biasanya baris 12)
                for (let i = 0; i < rows.length; i++) {
                    const row = rows[i];
                    const rowText = row.textContent.toLowerCase();
                    
                    if (rowText.includes('indeks prestasi semester')) {
                        console.log(`Found IPS row at index ${i}:`, row.textContent);
                        const cells = row.querySelectorAll('td, th');
                        if (cells.length >= 2) {
                            // Ambil dari kolom 2 (index 1)
                            const ipsValue = cells[1].textContent.trim();
                            const parsedIps = parseFloat(ipsValue);
                            if (!isNaN(parsedIps) && parsedIps > 0) {
                                ipsFromTable = parsedIps;
                                console.log(`✅ IPS extracted from table: ${ipsFromTable}`);
                            }
                        }
                        break;
                    }
                }
                
                // Cari baris yang mengandung "Total SKS"
                for (let i = 0; i < rows.length; i++) {
                    const row = rows[i];
                    const rowText = row.textContent.toLowerCase();
                    
                    if (rowText.includes('total sks')) {
                        console.log(`Found Total SKS row at index ${i}:`, row.textContent);
                        const cells = row.querySelectorAll('td, th');
                        if (cells.length >= 2) {
                            // Ambil dari kolom 2 (index 1)
                            const sksValue = cells[1].textContent.trim();
                            const parsedSks = parseInt(sksValue);
                            if (!isNaN(parsedSks) && parsedSks > 0) {
                                totalSksFromTable = parsedSks;
                                console.log(`✅ Total SKS extracted from table: ${totalSksFromTable}`);
                            }
                        }
                        break;
                    }
                }
            }
        }
        
        // Gunakan nilai dari tabel jika tersedia, fallback ke analysis
        const finalIps = ipsFromTable !== null ? ipsFromTable : (analysis.ips || '-');
        const finalTotalSks = totalSksFromTable !== null ? totalSksFromTable : (analysis.total_sks || '-');
    
        if (ipsElement) {
            ipsElement.textContent = finalIps;
            console.log(`Set IPS for semester ${semester} to:`, finalIps);
        } else {
            console.log(`IPS element not found for semester ${semester}`);
        }
        
        if (sksDElement) {
            const sksDValue = analysis.total_sks_d || 0;
            sksDElement.textContent = sksDValue;
            // Styling: hijau jika 0, kuning jika 1-9, merah jika 10+
            if (sksDValue === 0) {
                sksDElement.className = 'text-green-600 font-semibold';
            } else if (sksDValue >= 1 && sksDValue <= 9) {
                sksDElement.className = 'text-yellow-600 font-semibold';
            } else {
                sksDElement.className = 'text-red-600 font-semibold';
            }
            console.log(`Set SKS D for semester ${semester} to:`, sksDValue);
        }
        
        if (hasEElement) {
            const hasEValue = analysis.has_e ? 'Ya' : 'Tidak';
            hasEElement.textContent = hasEValue;
            // Styling: merah jika Ya, biru jika Tidak
            if (analysis.has_e) {
                hasEElement.className = 'text-red-600 font-semibold';
            } else {
                hasEElement.className = 'text-blue-600 font-semibold';
            }
            console.log(`Set Has E for semester ${semester} to:`, hasEValue);
        }
        
        if (totalSksElement) {
            totalSksElement.textContent = finalTotalSks;
            console.log(`Set Total SKS for semester ${semester} to:`, finalTotalSks);
        } else {
            console.log(`Total SKS element not found for semester ${semester}`);
        }
        
        // Show the result table
        tableResult.style.display = 'block';
        console.log(`✅ Showing result table for semester ${semester}`);
        console.log(`=== updateResultTable completed for semester ${semester} ===`);
}

// Helper function to hide result table
function hideResultTable(semester) {
    const tableResult = document.getElementById(`tableResult${semester}`);
    if (tableResult) {
        tableResult.style.display = 'none';
    }
}


function clearSemester(semester) {
    console.log(`=== CLEARING SEMESTER ${semester} ===`);
    
    const textarea = document.getElementById(`pasteArea${semester}`);
    const preview = document.getElementById(`preview${semester}`);
    const result = document.getElementById(`result${semester}`);
    
    // Show loading message
    if (result) {
        result.innerHTML = `
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-3 py-2 rounded text-sm">
                <i class="fas fa-spinner fa-spin mr-1"></i>Menghapus data...
            </div>
        `;
    }
    
    // Clear UI first
    if (textarea) {
        textarea.value = '';
        console.log(`Cleared textarea for semester ${semester}`);
    }
    
    if (preview) {
        preview.innerHTML = `
            <div class="flex items-center justify-center h-full text-gray-400">
                <div class="text-center">
                    <i class="fas fa-table text-2xl mb-2"></i>
                    <p>Tabel akan muncul setelah paste data</p>
                </div>
            </div>
        `;
        console.log(`Reset preview for semester ${semester}`);
    }
    
    // Hide and reset result table
    hideResultTable(semester);
    
    // Reset result table values
    const ipsElement = document.getElementById(`ips${semester}`);
    const sksDElement = document.getElementById(`sksD${semester}`);
    const hasEElement = document.getElementById(`hasE${semester}`);
    const totalSksElement = document.getElementById(`totalSks${semester}`);
    
    if (ipsElement) ipsElement.textContent = '-';
    if (sksDElement) sksDElement.textContent = '-';
    if (hasEElement) hasEElement.textContent = '-';
    if (totalSksElement) totalSksElement.textContent = '-';
    
    delete semesterData[semester];
    console.log(`Deleted semesterData[${semester}]`);
    
    // Delete from database
    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('semester', semester);
    
    fetch("{{ route('documents.delete-semester-data') }}", {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log(`Semester ${semester} data deleted successfully`);
            if (result) {
                result.innerHTML = `
                    <div class="bg-green-100 border border-green-400 text-green-700 px-3 py-2 rounded text-sm">
                        <i class="fas fa-check-circle mr-1"></i>Data berhasil dihapus! Halaman akan di-refresh...
                    </div>
                `;
            }
            
            // Recalculate final IPK
            calculateFinalIpk();
            
            // Refresh page after 1.5 seconds
            setTimeout(() => {
                console.log(`Refreshing page after clear for semester ${semester}`);
                window.location.reload();
            }, 1500);
        } else {
            console.error(`Failed to delete semester ${semester} data:`, data.message);
            if (result) {
                result.innerHTML = `
                    <div class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded text-sm">
                        <i class="fas fa-exclamation-triangle mr-1"></i>Gagal menghapus: ${data.message}
                    </div>
                `;
            }
        }
    })
    .catch(error => {
        console.error(`Error deleting semester ${semester} data:`, error);
        if (result) {
            result.innerHTML = `
                <div class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded text-sm">
                    <i class="fas fa-exclamation-triangle mr-1"></i>Error koneksi: ${error.message}
                </div>
            `;
        }
    });
    
    console.log(`=== SEMESTER ${semester} CLEARED ===`);
}

function calculateFinalIpk() {
    console.log('=== CALCULATING FINAL IPK ===');
    console.log('Current semesterData:', semesterData);
    
    const activeSemesters = Object.keys(semesterData).filter(sem => semesterData[sem]);
    const totalSemester = activeSemesters.length;
    
    // Get khsFileCount from global scope
    const khsFileCount = @json($khsFileCount ?? 0);
    
    console.log('Active semesters:', activeSemesters);
    console.log('Total semesters with data:', totalSemester);
    
    if (totalSemester === 0) {
        console.log('No active semesters found, setting default values');
        document.getElementById('finalIpk').textContent = '-';
        document.getElementById('totalSemester').textContent = '0/5';
        document.getElementById('totalSksD').textContent = '0';
        document.getElementById('totalE').textContent = '0';
        document.getElementById('uploadKhs').textContent = `${khsFileCount}/5`;
        
        // Set PKL status based on both transcript and KHS file count
        const pklStatusElement = document.getElementById('pklStatus');
        const isTranscriptComplete = false; // No transcript data
        const isKhsComplete = khsFileCount >= 5;
        
        if (!isTranscriptComplete || !isKhsComplete) {
            pklStatusElement.textContent = 'Belum Lengkap';
            pklStatusElement.className = 'text-3xl font-bold text-yellow-600 mb-1';
        } else {
            pklStatusElement.textContent = 'Tidak Layak';
            pklStatusElement.className = 'text-3xl font-bold text-red-600 mb-1';
        }
        return;
    }
    
    // Calculate total IPS and show detailed breakdown
    let totalIps = 0;
    let totalSksD = 0;
    let totalE = 0;
    console.log('IPS breakdown:');
    activeSemesters.forEach(sem => {
        const ips = semesterData[sem].ips || 0;
        const sksD = semesterData[sem].total_sks_d || 0;
        const hasE = semesterData[sem].has_e || false;
        
        totalIps += ips;
        totalSksD += sksD;
        if (hasE) totalE += 1;
        
        console.log(`  Semester ${sem}: IPS = ${ips}, SKS D = ${sksD}, Has E = ${hasE}`);
    });
    
    const finalIpk = totalIps / totalSemester;
    const isEligible = finalIpk >= 2.5;
    
    console.log(`Total IPS: ${totalIps}`);
    console.log(`Total SKS D: ${totalSksD}`);
    console.log(`Total E: ${totalE}`);
    console.log(`Final IPK: ${finalIpk.toFixed(2)} (${totalIps} / ${totalSemester})`);
    
    // Update display values
    document.getElementById('finalIpk').textContent = finalIpk.toFixed(2);
    document.getElementById('totalSemester').textContent = `${totalSemester}/5`;
    document.getElementById('totalSksD').textContent = totalSksD;
    document.getElementById('totalE').textContent = totalE;
    document.getElementById('uploadKhs').textContent = `${khsFileCount}/5`;
    
    // Update PKL status with new logic
    const pklStatusElement = document.getElementById('pklStatus');
    
    // Check if both Kelengkapan Transkrip and Upload Berkas KHS are complete
    const isTranscriptComplete = totalSemester >= 5;
    const isKhsComplete = khsFileCount >= 5;
    
    console.log('PKL Status Logic Check:');
    console.log(`- totalSemester: ${totalSemester}, isTranscriptComplete: ${isTranscriptComplete}`);
    console.log(`- khsFileCount: ${khsFileCount}, isKhsComplete: ${isKhsComplete}`);
    console.log(`- finalIpk: ${finalIpk}, totalSksD: ${totalSksD}, totalE: ${totalE}`);
    
    if (!isTranscriptComplete || !isKhsComplete) {
        // Belum Lengkap if either is incomplete
        pklStatusElement.textContent = 'Belum Lengkap';
        pklStatusElement.className = 'text-3xl font-bold text-yellow-600 mb-1';
        console.log('Status: Belum Lengkap (incomplete data)');
        
        // Update Kelayakan PKL in the header section
        updateKelayakanPklStatus('TIDAK LAYAK', false);
    } else {
        // Both are complete, check eligibility criteria
        console.log('Both transcript and KHS are complete, checking eligibility...');
        
        if (totalSksD > 9) {
            pklStatusElement.textContent = 'Tidak Layak';
            pklStatusElement.className = 'text-3xl font-bold text-red-600 mb-1';
            console.log('Status: Tidak Layak (SKS D > 9)');
            
            // Update Kelayakan PKL in the header section
            updateKelayakanPklStatus('TIDAK LAYAK', false);
        } else if (totalE > 0) {
            pklStatusElement.textContent = 'Tidak Layak';
            pklStatusElement.className = 'text-3xl font-bold text-red-600 mb-1';
            console.log('Status: Tidak Layak (has E grades)');
            
            // Update Kelayakan PKL in the header section
            updateKelayakanPklStatus('TIDAK LAYAK', false);
        } else if (finalIpk >= 2.5) {
            pklStatusElement.textContent = 'Layak PKL';
            pklStatusElement.className = 'text-3xl font-bold text-green-600 mb-1';
            console.log('Status: Layak PKL (all criteria met)');
            
            // Update Kelayakan PKL in the header section
            updateKelayakanPklStatus('LAYAK', true);
        } else {
            pklStatusElement.textContent = 'Tidak Layak';
            pklStatusElement.className = 'text-3xl font-bold text-red-600 mb-1';
            console.log('Status: Tidak Layak (IPK < 2.5)');
            
            // Update Kelayakan PKL in the header section
            updateKelayakanPklStatus('TIDAK LAYAK', false);
        }
    }
    
    console.log('=== FINAL IPK CALCULATION COMPLETED ===');
}

// Function to update Kelayakan PKL status in the header section
function updateKelayakanPklStatus(statusText, isEligible) {
    console.log(`Updating Kelayakan PKL to: ${statusText} (${isEligible ? 'eligible' : 'not eligible'})`);
    
    // Find all h4 elements and look for "Kelayakan PKL"
    const allH4Elements = document.querySelectorAll('h4');
    let kelayakanSection = null;
    
    allH4Elements.forEach(h4 => {
        if (h4.textContent.includes('Kelayakan PKL')) {
            kelayakanSection = h4;
            console.log('Found Kelayakan PKL section:', h4);
        }
    });
    
    if (kelayakanSection) {
        // Update the text content
        const statusElement = kelayakanSection.nextElementSibling;
        if (statusElement) {
            statusElement.textContent = statusText;
            statusElement.className = `text-${isEligible ? 'green' : 'red'}-700 font-medium`;
            console.log('Updated status text:', statusElement.textContent);
        }
        
        // Update the icon
        const iconElement = kelayakanSection.closest('.flex')?.querySelector('i');
        if (iconElement) {
            iconElement.className = `fas fa-${isEligible ? 'check-circle' : 'times-circle'} text-3xl text-${isEligible ? 'green' : 'red'}-600`;
            console.log('Updated icon:', iconElement.className);
        }
        
        // Update the header color
        kelayakanSection.className = `text-lg font-semibold text-${isEligible ? 'green' : 'red'}-800`;
        console.log('Updated header color:', kelayakanSection.className);
        
        console.log(`✅ Updated Kelayakan PKL to: ${statusText} (${isEligible ? 'eligible' : 'not eligible'})`);
    } else {
        console.log('❌ Kelayakan PKL section not found in header');
        console.log('Available h4 elements:', Array.from(allH4Elements).map(el => el.textContent));
    }
}

// Function to auto-save semester data
function autoSaveSemester(semester, transcriptData) {
    console.log(`Auto-saving semester ${semester} data...`);
    
    // Debounce auto-save to avoid too many requests
    if (window.autoSaveTimeout) {
        clearTimeout(window.autoSaveTimeout);
    }
    
    window.autoSaveTimeout = setTimeout(() => {
        fetch('/documents/save-semester-data', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                semester: semester,
                transcript_data: transcriptData
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log(`✅ Auto-saved semester ${semester} data successfully`);
                
                // Show auto-save indicator
                showAutoSaveIndicator(semester, true);
            } else {
                console.error(`❌ Auto-save failed for semester ${semester}:`, data.message);
                showAutoSaveIndicator(semester, false);
            }
        })
        .catch(error => {
            console.error(`❌ Auto-save error for semester ${semester}:`, error);
            showAutoSaveIndicator(semester, false);
        });
    }, 1000); // 1 second debounce
}

// Function to show auto-save indicator
function showAutoSaveIndicator(semester, success) {
    const textarea = document.getElementById(`pasteArea${semester}`);
    if (!textarea) return;
    
    // Remove existing indicator
    const existingIndicator = textarea.parentNode.querySelector('.auto-save-indicator');
    if (existingIndicator) {
        existingIndicator.remove();
    }
    
    // Create new indicator
    const indicator = document.createElement('div');
    indicator.className = 'auto-save-indicator text-xs mt-1';
    indicator.innerHTML = `
        <span class="inline-flex items-center ${success ? 'text-green-600' : 'text-red-600'}">
            <i class="fas fa-${success ? 'check' : 'times'} mr-1"></i>
            ${success ? 'Tersimpan otomatis' : 'Gagal menyimpan'}
        </span>
    `;
    
    textarea.parentNode.appendChild(indicator);
    
    // Remove indicator after 3 seconds
    setTimeout(() => {
        if (indicator.parentNode) {
            indicator.remove();
        }
    }, 3000);
}

// saveAllTranscripts function removed - data is now auto-saved

function analyzeTranscriptData(rows) {
    if (!rows || rows.length < 2) {
        return { error: 'Data tidak valid' };
    }
    
    // Cari IPS dan Total SKS dari text yang sudah tersimpan di database
    let ipsFromText = null;
    let totalSksFromText = null;
    
    // Gabungkan semua text dari rows untuk pattern matching
    const allText = rows.flat().join('\t').toLowerCase();
    console.log('All text for pattern matching:', allText);
    
    // Cari IPS dari pattern "Indeks Prestasi Semester\t3.68"
    const ipsMatch = allText.match(/indeks prestasi semester\t([0-9]+\.?[0-9]*)/i);
    if (ipsMatch) {
        ipsFromText = parseFloat(ipsMatch[1]);
        console.log('IPS found from pattern matching:', ipsFromText);
    } else {
        // Fallback: cari pattern dengan spasi
        const ipsMatchSpace = allText.match(/indeks prestasi semester\s+([0-9]+\.?[0-9]*)/i);
        if (ipsMatchSpace) {
            ipsFromText = parseFloat(ipsMatchSpace[1]);
            console.log('IPS found from space pattern:', ipsFromText);
        }
    }
    
    // Cari Total SKS dari pattern "Total SKS\t20\t\t73.5"
    const sksMatch = allText.match(/total sks\t([0-9]+)/i);
    if (sksMatch) {
        totalSksFromText = parseInt(sksMatch[1]);
        console.log('Total SKS found from pattern matching:', totalSksFromText);
    } else {
        // Fallback: cari pattern dengan spasi
        const sksMatchSpace = allText.match(/total sks\s+([0-9]+)/i);
        if (sksMatchSpace) {
            totalSksFromText = parseInt(sksMatchSpace[1]);
            console.log('Total SKS found from space pattern:', totalSksFromText);
        }
    }
    
    // Jika masih tidak ditemukan, coba dari tabel structure
    if (ipsFromText === null) {
        for (let i = 0; i < rows.length; i++) {
            const row = rows[i];
            const rowText = row.join(' ').toLowerCase();
            
            if (rowText.includes('indeks prestasi semester')) {
                console.log('Found IPS row:', row);
                
                // Cari angka di baris ini
                for (let j = 0; j < row.length; j++) {
                    const cellValue = row[j];
                    const parsedIps = parseFloat(cellValue);
                    if (!isNaN(parsedIps) && parsedIps > 0 && parsedIps <= 4) {
                        ipsFromText = parsedIps;
                        console.log('IPS found in table row:', ipsFromText);
                        break;
                    }
                }
                break;
            }
        }
    }
    
    if (totalSksFromText === null) {
        for (let i = 0; i < rows.length; i++) {
            const row = rows[i];
            const rowText = row.join(' ').toLowerCase();
            
            if (rowText.includes('total sks')) {
                console.log('Found Total SKS row:', row);
                
                // Cari angka di baris ini
                for (let j = 0; j < row.length; j++) {
                    const cellValue = row[j];
                    const parsedSks = parseInt(cellValue);
                    if (!isNaN(parsedSks) && parsedSks > 0 && parsedSks <= 50) {
                        totalSksFromText = parsedSks;
                        console.log('Total SKS found in table row:', totalSksFromText);
                        break;
                    }
                }
                break;
            }
        }
    }
    
    const header = rows[0].map(h => h.toLowerCase());
    let idxSks = header.indexOf('sks');
    let idxNilai = header.indexOf('nilai');
    
    // Jika kolom SKS atau Nilai tidak ditemukan, coba cari dengan pattern yang lebih fleksibel
    if (idxSks === -1 || idxNilai === -1) {
        console.log('Header not found with exact match, trying flexible search...');
        console.log('Available headers:', header);
        
        // Cari kolom SKS dengan pattern yang lebih fleksibel
        let flexibleIdxSks = -1;
        let flexibleIdxNilai = -1;
        
        for (let i = 0; i < header.length; i++) {
            const h = header[i];
            if (h.includes('sks') && flexibleIdxSks === -1) {
                flexibleIdxSks = i;
            }
            if (h.includes('nilai') && flexibleIdxNilai === -1) {
                flexibleIdxNilai = i;
            }
        }
        
        if (flexibleIdxSks === -1 || flexibleIdxNilai === -1) {
            console.log('Flexible search also failed');
            console.log('SKS index:', flexibleIdxSks, 'Nilai index:', flexibleIdxNilai);
            return { error: 'Kolom SKS atau Nilai tidak ditemukan' };
        }
        
        console.log('Using flexible indices - SKS:', flexibleIdxSks, 'Nilai:', flexibleIdxNilai);
        // Gunakan indeks yang ditemukan dengan pencarian fleksibel
        idxSks = flexibleIdxSks;
        idxNilai = flexibleIdxNilai;
    }
    
    let totalSksD = 0;
    let hasE = false;
    let sumQuality = 0;
    let sumSks = 0;
    
    const gradeMap = {
        'A': 4.0, 'A+': 4.0,
        'B+': 3.5, 'B': 3.0,
        'C+': 2.5, 'C': 2.0,
        'D': 1.0, 'E': 0.0
    };
    
    for (let i = 1; i < rows.length; i++) {
        const row = rows[i];
        if (!row[idxSks] || !row[idxNilai]) continue;
        
        const sks = parseInt(row[idxSks]) || 0;
        const nilai = row[idxNilai].toUpperCase().trim();
        
        if (nilai === 'D') totalSksD += sks;
        if (nilai === 'E') hasE = true;
        
        if (gradeMap[nilai] !== undefined) {
            sumQuality += gradeMap[nilai] * sks;
            sumSks += sks;
        }
    }
    
    // Gunakan IPS dari text jika ditemukan, jika tidak hitung manual
    const calculatedIps = sumSks > 0 ? sumQuality / sumSks : 0;
    const ips = ipsFromText !== null ? ipsFromText : calculatedIps;
    
    const eligible = ips >= 2.5 && totalSksD <= 6 && !hasE;
    
    return {
        ips: parseFloat(ips.toFixed(2)),
        total_sks_d: totalSksD,
        has_e: hasE,
        eligible: eligible,
        total_sks: totalSksFromText || 0
    };
}

// Manual Transcript Analysis Functions
document.addEventListener('DOMContentLoaded', function() {
    const ta = document.getElementById('pasteArea');
    const preview = document.getElementById('preview');
    const analyzeBtn = document.getElementById('analyzeBtn');
    const clearBtn = document.getElementById('clearBtn');
    const result = document.getElementById('result');
    const saveForm = document.getElementById('saveForm');

    if (!ta || !preview || !analyzeBtn || !clearBtn || !result || !saveForm) {
        console.log('Transcript analysis elements not found');
        return;
    }

    // Handle paste event
    ta.addEventListener('paste', async (e) => {
        const text = (e.clipboardData || window.clipboardData).getData('text');
        const rows = parseTranscript(text);
        renderTable(rows, preview);
    });

    // Handle analyze button
    analyzeBtn.addEventListener('click', () => {
        const table = preview.querySelector('table');
        if (!table) {
            alert('Paste dulu data transkrip.');
            return;
        }
        
        const arr = Array.from(table.querySelectorAll('tr')).map(tr => 
            Array.from(tr.children).map(td => td.innerText.trim())
        );
        
        fetch("#", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({table: arr})
        })
        .then(r => r.json())
        .then(data => {
            if (data.error) {
                result.innerHTML = `<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded'>${data.error}</div>`;
                saveForm.style.display = 'none';
            } else {
                result.innerHTML = `
                    <div class='bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded'>
                        <div class="grid grid-cols-2 gap-4">
                            <div><strong>IPK:</strong> ${data.ipk ?? '-'}</div>
                            <div><strong>Total SKS D:</strong> ${data.total_sks_d}</div>
                            <div><strong>Ada Nilai E:</strong> ${data.has_e ? 'Ya' : 'Tidak'}</div>
                            <div><strong>Status:</strong> ${data.eligible ? '<span class="text-green-600 font-semibold">Layak PKL</span>' : '<span class="text-red-600 font-semibold">Tidak Layak PKL</span>'}</div>
                                </div>
                            </div>
                `;
                
                // Fill hidden inputs
                document.getElementById('ipkInput').value = data.ipk || '';
                document.getElementById('sksDInput').value = data.total_sks_d;
                document.getElementById('hasEInput').value = data.has_e ? 1 : 0;
                document.getElementById('eligibleInput').value = data.eligible ? 1 : 0;
                
                // Show save form
                saveForm.style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            result.innerHTML = `<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded'>Terjadi kesalahan: ${error.message}</div>`;
            saveForm.style.display = 'none';
        });
    });

    // Handle clear button
    clearBtn.addEventListener('click', () => {
        ta.value = '';
        preview.innerHTML = '';
        result.innerHTML = '';
        saveForm.style.display = 'none';
    });
});

// Parse transcript text to array
function parseTranscript(text) {
    text = text.replace(/\r\n/g, '\n').trim();
    
    if (text.includes('\t')) {
        const lines = text.split('\n');
        const result = [];
        
        for (let i = 0; i < lines.length; i++) {
            const line = lines[i].trim();
            if (line) { // Skip empty lines
                const columns = line.split('\t').map(c => c.trim());
                result.push(columns);
            }
        }
        
        // Check if first row is not a header (like "Periode 20242")
        if (result.length > 1) {
            const firstRow = result[0];
            const secondRow = result[1];
            
            // If first row has only 1 column and second row has multiple columns,
            // then first row is likely a title/period, not a header
            if (firstRow.length === 1 && secondRow.length > 1) {
                console.log('Detected title row, using second row as header');
                // Remove the first row (title) and use the rest
                result.shift();
            }
        }
        
        console.log('Parsed transcript with tabs:', result);
        return result;
    }
    
    if (text.includes(',') && text.split('\n')[0].split(',').length > 1) {
        return text.split('\n').map(r => r.split(',').map(c => c.trim()));
    }
    
    return text.split('\n').map(r => r.trim()).map(r => r.split(/\s{2,}/).map(c => c.trim()));
}

// Render array to HTML table
function renderTable(rows, container) {
        if (!rows || rows.length < 2) {
            container.innerHTML = '<div class="text-red-600 text-sm">Data tidak valid. Pastikan ada header dan minimal 1 baris data.</div>';
            return;
        }
        
        let html = '<div class="overflow-x-auto"><table class="min-w-full bg-white border border-gray-300 rounded-lg overflow-hidden"><thead class="bg-gray-50"><tr>';
        const header = rows[0];
        
        for (let h of header) {
            html += `<th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">${h}</th>`;
        }
        
        html += '</tr></thead><tbody class="bg-white divide-y divide-gray-200">';
        
        for (let i = 1; i < rows.length; i++) {
            const row = rows[i];
            const rowText = row.join(' ').toLowerCase();
            
            // Check if this row contains "Indeks Prestasi Semester"
            const isIpsRow = rowText.includes('indeks prestasi semester') || rowText.includes('ips');
            
            // Apply different styling for IPS row
            const rowClass = isIpsRow ? 'bg-blue-50 hover:bg-blue-100 border-l-4 border-blue-500' : 'hover:bg-gray-50';
            const cellClass = isIpsRow ? 'px-4 py-3 text-sm font-semibold text-blue-900 border-b border-gray-200' : 'px-4 py-3 text-sm text-gray-900 border-b border-gray-200';
            
            html += `<tr class="${rowClass}">`;
            for (let c of row) {
                html += `<td class="${cellClass}">${c}</td>`;
            }
            html += '</tr>';
        }
        
        html += '</tbody></table></div>';
        container.innerHTML = html;
        
        // Table is now read-only, no event listeners needed
    }
    
    // Helper function to find semester from container
    function findSemesterFromContainer(container) {
        for (let semester = 1; semester <= 5; semester++) {
            const preview = document.getElementById(`preview${semester}`);
            if (preview === container) {
                return semester;
            }
        }
        return null;
    }
    
    // Helper function to update textarea from table data
    function updateTextareaFromTable(semester) {
        const preview = document.getElementById(`preview${semester}`);
        const textarea = document.getElementById(`pasteArea${semester}`);
        
        if (!preview || !textarea) return;
        
        const table = preview.querySelector('table');
        if (!table) return;
        
        const rows = [];
        const tableRows = table.querySelectorAll('tr');
        
        tableRows.forEach(row => {
            const cells = row.querySelectorAll('td, th');
            const rowData = Array.from(cells).map(cell => cell.textContent.trim());
            rows.push(rowData);
        });
        
        // Convert rows back to text format
        const textData = rows.map(row => row.join('\t')).join('\n');
        textarea.value = textData;
        
        console.log(`Updated textarea for semester ${semester} from table data`);
    }
</script>


        </div>
    </div>

    <div id="content-surat-balasan" class="tab-content hidden">
        @if(!$instansiMitraEnabled)
            <!-- Disabled State -->
            <div class="bg-white shadow-lg rounded-xl border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-400 to-gray-500 px-6 py-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-ban text-2xl text-white"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-semibold text-white">Surat Balasan Dinonaktifkan</h3>
                            <p class="text-gray-100 text-sm">Fitur ini sedang dinonaktifkan oleh admin</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 text-center">
                    <div class="mb-4">
                        <i class="fas fa-lock text-4xl text-gray-400"></i>
                    </div>
                    <h4 class="text-lg font-medium text-gray-900 mb-2">Surat Balasan Tidak Tersedia</h4>
                    <p class="text-gray-600 mb-4">Admin telah menonaktifkan fitur upload Surat Balasan. Silakan hubungi admin jika Anda memerlukan akses.</p>
                </div>
            </div>
        @else
            <!-- Surat Balasan Upload -->
            <div class="bg-white shadow-lg rounded-xl border border-gray-100 overflow-hidden mt-6">
                <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-envelope text-2xl text-white"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-semibold text-white">Surat Balasan</h3>
                            <p class="text-green-100 text-sm">Upload surat balasan dari instansi mitra</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    @if($suratBalasan && is_object($suratBalasan))
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg border">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center">
                                            <i class="fas fa-file-pdf text-red-500 mr-2"></i>
                                            <p class="text-sm font-medium text-gray-900">{{ basename($suratBalasan->file_path) }}</p>
                                        </div>
                                        <div class="flex space-x-2">
                                            <button type="button" onclick="window.previewFile('{{ $suratBalasan->file_path }}')" class="text-blue-600 hover:text-blue-800 text-sm px-2 py-1 rounded hover:bg-blue-50">
                                                <i class="fas fa-eye mr-1"></i>Lihat
                                            </button>
                                            <button type="button" onclick="window.deleteFile('surat-balasan', {{ $suratBalasan->id }})" class="text-red-600 hover:text-red-800 text-sm px-2 py-1 rounded hover:bg-red-50">
                                                <i class="fas fa-trash mr-1"></i>Hapus
                                            </button>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 mb-3">Uploaded: {{ $suratBalasan->created_at->format('d M Y H:i') }}</p>
                                    
                                    @if($suratBalasan->mitra)
                                        <div class="mb-3 p-3 bg-blue-50 rounded-lg">
                                            <h4 class="text-sm font-medium text-blue-900 mb-1">Mitra PKL</h4>
                                            <p class="text-sm text-blue-700">{{ $suratBalasan->mitra->nama_instansi }}</p>
                                            <p class="text-xs text-blue-600">{{ $suratBalasan->mitra->alamat }}</p>
                                        </div>
                                    @endif
                                    
                                    <div class="flex items-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                            @if($suratBalasan->status_validasi === 'tervalidasi') bg-green-100 text-green-800
                                            @elseif($suratBalasan->status_validasi === 'belum_valid') bg-red-100 text-red-800
                                            @elseif($suratBalasan->status_validasi === 'revisi') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            @if($suratBalasan->status_validasi === 'tervalidasi')
                                                <i class="fas fa-check-circle mr-1"></i>Tervalidasi
                                            @elseif($suratBalasan->status_validasi === 'belum_valid')
                                                <i class="fas fa-times-circle mr-1"></i>Belum Valid
                                            @elseif($suratBalasan->status_validasi === 'revisi')
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
                            <i class="fas fa-upload mr-2"></i>{{ ($suratBalasan && is_object($suratBalasan)) ? 'Update Surat Balasan' : 'Upload Surat Balasan' }}
                        </button>
                    </form>
                </div>
            </div>
        @endif
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
    // Gate: kunci Pemberkasan kalau KHS belum tervalidasi
    @if(!$canAjukan)
    if (tabName === 'pemberkasan') {
        alert('Ajukan Pemberkasan terkunci. Selesaikan Langkah 1 (KHS tervalidasi) dulu.');
        return;
    }
    @endif

    // Check if Laporan PKL is disabled
    @if(!$laporanPklEnabled)
    if (tabName === 'laporan') {
        alert('Fitur Laporan PKL sedang dinonaktifkan oleh admin.');
        return;
    }
    @endif
    
    // Check if Surat Balasan is disabled
    @if(!$instansiMitraEnabled)
    if (tabName === 'surat-balasan') {
        alert('Fitur Surat Balasan sedang dinonaktifkan oleh admin.');
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
    
    // Simple event listeners - no complex delegation
    console.log('Document ready - buttons should work with direct onclick');
    
    // Test if functions are available
    console.log('window.previewFile available:', typeof window.previewFile);
    console.log('window.deleteFile available:', typeof window.deleteFile);
    // TPK refresh function removed
});
</script>

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
});

</script>
@endpush