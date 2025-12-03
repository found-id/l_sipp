@extends('layouts.app')

@section('title', 'Pemberkasan Dokumen - SIP PKL')

@section('content')
@php
    $laporanPklEnabled = \App\Models\SystemSetting::isEnabled('laporan_pkl_enabled');
    $instansiMitraEnabled = \App\Models\SystemSetting::isEnabled('instansi_mitra_enabled');
    $dokumenPemberkasanEnabled = \App\Models\SystemSetting::isEnabled('dokumen_pemberkasan_enabled');
@endphp

<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 rounded-lg bg-blue-600 flex items-center justify-center">
                    <i class="fas fa-file-upload text-xl text-white"></i>
                </div>
            </div>
            <div class="ml-4">
                <h1 class="text-2xl font-bold text-gray-900">Pemberkasan Dokumen PKL</h1>
                <p class="text-gray-600 mt-1">Upload dan kelola dokumen yang diperlukan untuk PKL</p>
            </div>
        </div>
    </div>

    <!-- PKL Status & Eligibility -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-slate-800">Status & Kelayakan</h3>
            <div class="flex items-center text-sm text-slate-500 bg-slate-50 px-3 py-1 rounded-full border border-slate-200">
                <i class="fas fa-clock mr-2"></i>
                <span>Update: {{ now()->format('d M Y') }}</span>
            </div>
        </div>

            @php
                $laporan = $laporanPkl;
                $khs = $khs ?? null;
                $suratBalasan = $suratBalasan ?? null;
                $laporanPkl = $laporanPkl ?? null;
                $laporan = $laporan ?? null;
                $tpkData = null;
                $mitra = $mitra ?? collect();

            // Calculate PKL eligibility from khsManualTranskrip data
            $isEligibleForPkl = false;
            $totalSemesters = $khsManualTranskrip->count();
            $totalSksD = 0;
            $totalE = 0;
            $totalIps = 0;

            // Calculate from transcript data
            $countSemestersWithIps = 0;
            foreach ($khsManualTranskrip as $transcript) {
                $totalSksD += $transcript->total_sks_d ?? 0;
                if ($transcript->has_e) {
                    $totalE++;
                }
                // Only count IPS if it has a value
                if (!empty($transcript->ips) && $transcript->ips > 0) {
                    $totalIps += $transcript->ips;
                    $countSemestersWithIps++;
                }
            }

            // Calculate final IPK - only from semesters that have IPS values
            $finalIpk = $countSemestersWithIps > 0 ? $totalIps / $countSemestersWithIps : 0;

            // Check dokumen pendukung (PKKMB dan English Course wajib)
            $hasPkkmb = !empty($user->profilMahasiswa->gdrive_pkkmb ?? '');
            $hasEcourse = !empty($user->profilMahasiswa->gdrive_ecourse ?? '');
            $hasDokumenPendukung = $hasPkkmb && $hasEcourse;

            // Check eligibility: transcript complete (4 semesters), KHS files uploaded (4), IPK >= 2.5, SKS D <= 6, no E, dokumen pendukung lengkap
            $isTranscriptComplete = $totalSemesters >= 4;
            $isKhsComplete = $khsFileCount >= 4;

            if ($isTranscriptComplete && $isKhsComplete && $finalIpk >= 2.5 && $totalSksD <= 6 && $totalE == 0 && $hasDokumenPendukung) {
                $isEligibleForPkl = true;
            }

            $hasValidKhs = $khs && is_object($khs) && $khs->status_validasi === 'tervalidasi';
            $hasValidSuratBalasan = $suratBalasan && is_object($suratBalasan) && $suratBalasan->status_validasi === 'tervalidasi';
            $hasValidLaporan = $laporan && is_object($laporan) && $laporan->status_validasi === 'tervalidasi';

            // Determine PKL Activity Status (4 tahap)
            // 1. Menyiapkan Berkas - belum layak
            // 2. Siap untuk PKL - sudah layak, belum ada dokumen tervalidasi
            // 3. Aktif PKL - sudah layak, status diaktifkan mahasiswa
            // 4. PKL Selesai - semua dokumen tervalidasi

            // Use database status_pkl if available
            $dbStatusPkl = $statusPkl ?? 'siap';

            if ($dbStatusPkl === 'selesai') {
                // Tahap 4: PKL Selesai (dari database)
                $pklStatus = 'selesai';
                $pklStatusText = 'Selesai PKL';
                $pklStatusColor = 'green';
                $pklStatusIcon = 'check-circle';
            } elseif (!$isEligibleForPkl) {
                // Tahap 1: Menyiapkan Berkas (belum layak)
                $pklStatus = 'menyiapkan_berkas';
                $pklStatusText = 'Menyiapkan Berkas';
                $pklStatusColor = 'gray';
                $pklStatusIcon = 'file-alt';
            } elseif ($isEligibleForPkl && $hasValidKhs && $hasValidSuratBalasan && $hasValidLaporan) {
                // Tahap 4: PKL Selesai
                $pklStatus = 'selesai';
                $pklStatusText = 'Selesai PKL';
                $pklStatusColor = 'green';
                $pklStatusIcon = 'check-circle';
            } elseif ($dbStatusPkl === 'aktif') {
                // Tahap 3: Aktif PKL (status diaktifkan oleh mahasiswa)
                $pklStatus = 'aktif';
                $pklStatusText = 'Aktif PKL';
                $pklStatusColor = 'blue';
                $pklStatusIcon = 'building';
                $mitraName = $user->profilMahasiswa->mitraSelected->nama ?? 'Instansi Mitra';
            } else {
                // Default: Siap (jika eligible)
                $pklStatus = 'siap';
                $pklStatusText = 'Siap untuk PKL';
                $pklStatusColor = 'green';
                $pklStatusIcon = 'check-circle';
            }

            // Define Locking Logic
            // Tabs 1, 2, 3 (Kelayakan, Dokumen, Mitra) locked if Aktif OR Selesai
            $isLockedGeneral = in_array($pklStatus, ['aktif', 'selesai']);
            
            // Tab 4 (Akhir) locked if Siap OR Aktif (Unlocked only if Selesai)
            // Note: User request says "jika Siap... hanya tab Pemberkasan Akhir yang terkunci".
            // And "jika Selesai... tab Pemberkasan Akhir... kecuali (unlocked)".
            // So Tab 4 is unlocked ONLY when 'selesai'.
            $isLockedAkhir = $pklStatus !== 'selesai';
            @endphp

        @if($pklStatus === 'selesai')
        <!-- Only show Status Keaktifan PKL when PKL is complete -->
        <div class="grid grid-cols-1 gap-6">
            <!-- PKL Activity Status - Full Width -->
            <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-14 h-14 bg-emerald-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-check-circle text-2xl text-emerald-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-base font-medium text-slate-500">Status Aktivitas</h4>
                            <p class="text-xl font-bold text-emerald-700">PKL Selesai</p>
                            <p class="text-xs text-emerald-600 mt-1">
                                Selamat! Seluruh rangkaian kegiatan PKL Anda telah selesai.
                            </p>
                        </div>
                    </div>
                    <button onclick="revertPklStatus()" class="bg-white border border-emerald-200 text-emerald-700 hover:bg-emerald-50 font-medium py-2 px-4 rounded-lg transition-colors shadow-sm flex items-center text-sm">
                        <i class="fas fa-undo mr-2"></i>
                        Kembali ke Aktif
                    </button>
                </div>
            </div>
        </div>
        @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- PKL Eligibility Status -->
            <div class="{{ $isEligibleForPkl ? 'bg-emerald-50 border-emerald-100' : 'bg-rose-50 border-rose-100' }} border rounded-xl p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        @if($isEligibleForPkl)
                            <div class="w-14 h-14 bg-emerald-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-check text-2xl text-emerald-600"></i>
                            </div>
                        @else
                            <div class="w-14 h-14 bg-rose-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-times text-2xl text-rose-600"></i>
                            </div>
                        @endif
                    </div>
                    <div class="ml-4">
                        <h4 class="text-base font-medium text-slate-500">Status Kelayakan</h4>
                        <p class="text-xl font-bold {{ $isEligibleForPkl ? 'text-emerald-700' : 'text-rose-700' }}">
                            {{ $isEligibleForPkl ? 'LAYAK' : 'BELUM LAYAK' }}
                        </p>
                        <p class="text-xs {{ $isEligibleForPkl ? 'text-emerald-600' : 'text-rose-600' }} mt-1">
                            {{ $isEligibleForPkl ? 'Memenuhi persyaratan' : 'Belum memenuhi syarat' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- PKL Activity Status -->
            <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-14 h-14 bg-indigo-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-{{ $pklStatusIcon }} text-2xl text-indigo-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-base font-medium text-slate-500">Status Aktivitas</h4>
                        <p class="text-xl font-bold text-slate-800">{{ $pklStatusText }}</p>
                        <p class="text-xs text-slate-500 mt-1">
                            @if($pklStatus === 'menyiapkan_berkas')
                                Menyiapkan berkas
                            @elseif($pklStatus === 'siap')
                                Siap memulai PKL
                            @elseif($pklStatus === 'aktif')
                                Aktif di {{ $mitraName ?? 'Instansi Mitra' }}
                            @else
                                Selesai
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Button to Activate/Deactivate PKL Status -->
    @php
        $hasSuratPengantarUploaded = $suratPengantar && is_object($suratPengantar);
        $hasMitraSelectedCheck = $hasMitraSelected ?? false;
        $hasSuratBalasanUploaded = $suratBalasan && is_object($suratBalasan);
        $canActivatePkl = $hasSuratPengantarUploaded && $hasMitraSelectedCheck && $hasSuratBalasanUploaded && $pklStatus === 'siap';
        $canDeactivatePkl = $pklStatus === 'aktif';
    @endphp

    @if($canActivatePkl)
    <div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-4 mb-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-info-circle text-blue-600 text-xl mr-3"></i>
                <div>
                    <h4 class="text-blue-800 font-semibold">Dokumen Instansi Mitra Lengkap</h4>
                    <p class="text-blue-600 text-sm">Surat Pengantar, Instansi Mitra, dan Surat Balasan telah tersedia. Anda dapat mengaktifkan status PKL Anda.</p>
                </div>
            </div>
            <button onclick="activatePklStatus()" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-6 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                Aktifkan Status Aktif PKL Saya
            </button>
        </div>
    </div>
    @elseif($canDeactivatePkl)
    <div class="bg-gradient-to-r from-gray-50 to-gray-100 border border-gray-200 rounded-xl p-4 mb-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-info-circle text-gray-600 text-xl mr-3"></i>
                <div>
                    <h4 class="text-gray-800 font-semibold">Status PKL Aktif</h4>
                    <p class="text-gray-600 text-sm">Status PKL Anda sedang aktif. Anda dapat menghentikan status jika diperlukan.</p>
                </div>
            </div>
            <button onclick="deactivatePklStatus()" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2.5 px-6 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg flex items-center">
                <i class="fas fa-stop-circle mr-2"></i>
                Hentikan Status PKL
            </button>
        </div>
    </div>
    @endif

    <!-- Tab Navigation -->
    <div class="bg-white shadow-sm rounded-xl border border-gray-100 p-4">
        <nav class="flex space-x-3" aria-label="Tabs">
            <button onclick="showTab('pemberkasan')" id="tab-pemberkasan" class="tab-button active flex-1 py-3.5 px-4 rounded-lg font-medium text-sm transition-all duration-200 bg-blue-600 text-white shadow-md hover:shadow-lg">
                <div class="flex items-center justify-center">
                    <i class="fas fa-file-alt mr-2 text-base"></i>
                    <span>Pemberkasan Kelayakan</span>
                </div>
            </button>
            <button onclick="showTab('dokumen-pendukung')" id="tab-dokumen-pendukung" class="tab-button flex-1 py-3.5 px-4 rounded-lg font-medium text-sm transition-all duration-200 bg-white text-gray-600 border border-gray-200 hover:bg-blue-50 hover:border-blue-200 hover:text-blue-700">
                <div class="flex items-center justify-center">
                    <i class="fab fa-google-drive mr-2 text-base"></i>
                    <span>Dokumen Pendukung</span>
                </div>
            </button>
            <button onclick="showTab('surat-balasan')" id="tab-surat-balasan" class="tab-button flex-1 py-3.5 px-4 rounded-lg font-medium text-sm transition-all duration-200 bg-white text-gray-600 border border-gray-200 hover:bg-blue-50 hover:border-blue-200 hover:text-blue-700 {{ !$dokumenPemberkasanEnabled ? 'opacity-50 cursor-not-allowed' : '' }}"
                    {{ !$dokumenPemberkasanEnabled ? 'disabled' : '' }}>
                <div class="flex items-center justify-center">
                    <i class="fas fa-envelope mr-2 text-base"></i>
                    <span>Pemberkasan Instansi Mitra</span>
                    @if(!$dokumenPemberkasanEnabled)
                        <i class="fas fa-lock ml-2 text-gray-400 text-xs"></i>
                    @endif
                </div>
            </button>
            @php
                // Check if instansi mitra selected and surat balasan uploaded
                $hasMitraSelected = !empty($user->profilMahasiswa->mitra_selected ?? '');
                $hasSuratBalasan = $suratBalasan && is_object($suratBalasan);
                // Pemberkasan Akhir requires: eligible, mitra selected, and surat balasan uploaded
                $canAccessPemberkasanAkhir = $laporanPklEnabled && $isEligibleForPkl && $hasMitraSelected && $hasSuratBalasan;
            @endphp
            <button onclick="showTab('laporan')" id="tab-laporan" class="tab-button flex-1 py-3.5 px-4 rounded-lg font-medium text-sm transition-all duration-200 bg-white text-gray-600 border border-gray-200 hover:bg-blue-50 hover:border-blue-200 hover:text-blue-700 {{ !$laporanPklEnabled ? 'opacity-50 cursor-not-allowed' : '' }}"
                    {{ !$laporanPklEnabled ? 'disabled' : '' }}>
                <div class="flex items-center justify-center">
                    <i class="fas fa-book mr-2 text-base"></i>
                    <span>Pemberkasan Akhir</span>
                    @if(!$laporanPklEnabled)
                        <i class="fas fa-lock ml-2 text-gray-400 text-xs"></i>
                    @endif
                </div>
            </button>
        </nav>
    </div>

    <!-- Tab Content -->
    <div id="content-pemberkasan" class="tab-content">
        <div class="grid grid-cols-1 gap-6 mt-6">
            <!-- Final IPK Calculation -->
            <!-- Final IPK Calculation -->
            <div class="bg-white shadow-sm rounded-xl border border-slate-200 overflow-hidden">
                <div class="bg-white px-6 py-4 border-b border-slate-100 cursor-pointer hover:bg-slate-50 transition-colors duration-200" onclick="toggleSection('analisa-kelayakan-section')">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-lg bg-green-50 flex items-center justify-center">
                                    <i class="fas fa-calculator text-green-600"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-bold text-slate-800">Hasil Analisa Kelayakan</h3>
                                <p class="text-slate-500 text-sm">Ringkasan status akademik Anda</p>
                            </div>
                        </div>
                        <i class="fas fa-chevron-down text-slate-400 transition-transform duration-300 transform" id="analisa-kelayakan-section-icon"></i>
                    </div>
                </div>
                
                <div id="analisa-kelayakan-section" class="p-6 transition-all duration-300">
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                        <div class="text-center">
                            <div id="finalIpk" class="text-3xl font-bold text-green-600 mb-1">-</div>
                            <div class="text-sm text-gray-600">IPK Akhir</div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                        <div class="text-center">
                            <div id="totalSemester" class="text-3xl font-bold text-blue-600 mb-1">0/4</div>
                            <div class="text-sm text-gray-600">Kelengkapan Transkrip</div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                        <div class="text-center">
                            <div id="pklStatus" class="text-3xl font-bold text-gray-600 mb-1">-</div>
                            <div class="text-sm text-gray-600">Status PKL</div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                        <div class="text-center">
                            <div id="totalSksD" class="text-3xl font-bold text-yellow-600 mb-1">0</div>
                            <div class="text-sm text-gray-600">Total SKS D</div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                        <div class="text-center">
                            <div id="totalE" class="text-3xl font-bold text-red-600 mb-1">0</div>
                            <div class="text-sm text-gray-600">Jumlah E</div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                        <div class="text-center">
                            <div id="uploadKhs" class="text-3xl font-bold text-purple-600 mb-1">0/5</div>
                            <div class="text-sm text-gray-600">Upload Berkas KHS</div>
                        </div>
                    </div>
                </div>
                
                <div class="text-center text-sm text-gray-600 mt-4">
                    <i class="fas fa-info-circle mr-1"></i>
                    Data yang dimasukkan dapat dipertanggung jawabkan keaslianya dan menerima konsekuensi jika data dan berkas yang dimasukkan tidak tepat
                </div>
                </div>
            </div>

            <!-- Multiple Semester KHS Upload System -->
            <div class="bg-white shadow-sm rounded-xl border border-slate-200 overflow-hidden">
                <div class="bg-white px-6 py-4 border-b border-slate-100 cursor-pointer hover:bg-slate-50 transition-colors duration-200" onclick="toggleSection('khs-upload-section')">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center">
                                    <i class="fas fa-graduation-cap text-indigo-600"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-bold text-slate-800">Upload Kartu Hasil Studi (KHS)</h3>
                                <p class="text-slate-500 text-sm">Upload KHS untuk setiap semester (1-4)</p>
                            </div>
                        </div>
                        <i class="fas fa-chevron-down text-slate-400 transition-transform duration-300 transform" id="khs-upload-section-icon"></i>
                    </div>
                </div>

                <div id="khs-upload-section" class="p-6 transition-all duration-300">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        @for($semester = 1; $semester <= 4; $semester++)
                            <div class="bg-white rounded-xl p-6 border border-slate-200 shadow-sm hover:shadow-md transition-shadow duration-200 flex flex-col items-center text-center">
                                <div class="w-12 h-12 bg-indigo-50 rounded-full flex items-center justify-center mb-4">
                                    <span class="text-indigo-600 font-bold text-lg">{{ $semester }}</span>
                                </div>
                                <h4 class="font-bold text-slate-800 mb-4">Semester {{ $semester }}</h4>
                                
                                @php
                                    $semesterKhs = $khsFiles->where('semester', $semester)->first() ?? null;
                                @endphp
                                
                                <div class="w-full flex-1 flex flex-col justify-center mb-4">
                                @if($semesterKhs)
                                    <div class="p-3 bg-slate-50 rounded-lg border border-slate-100 w-full">
                                        <div class="flex items-center justify-center mb-2">
                                            <i class="fas fa-file-pdf text-red-500 text-2xl"></i>
                                        </div>
                                        <p class="text-xs text-slate-700 font-medium truncate mb-1">{{ basename($semesterKhs->file_path) }}</p>
                                        <div class="flex justify-center space-x-2 mt-2">
                                            <button type="button" onclick="window.previewFile('{{ $semesterKhs->file_path }}')" class="text-indigo-600 hover:text-indigo-800 text-xs font-medium">
                                                View
                                            </button>
                                            @if(!$isLockedGeneral)
                                            <button type="button" onclick="window.deleteFile('khs', {{ $semesterKhs->id }})" class="text-rose-600 hover:text-rose-800 text-xs font-medium">
                                                Delete
                                            </button>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <div class="py-4 border-2 border-dashed border-slate-200 rounded-lg w-full flex flex-col items-center justify-center text-slate-400">
                                        <i class="fas fa-plus text-xl mb-2"></i>
                                        <span class="text-xs">Belum ada file</span>
                                    </div>
                                @endif
                                </div>
                                
                                @if($dokumenPemberkasanEnabled)
                                    <div class="w-full mt-auto">
                                        @if(!$isLockedGeneral)
                                            <div class="relative">
                                                <input type="file" id="file_semester_{{ $semester }}" name="file_semester_{{ $semester }}" accept=".pdf" 
                                                       class="block w-full text-xs text-slate-500 file:mr-2 file:py-2 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-slate-200 rounded-lg cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                                       onchange="updateFilePreview({{ $semester }})">
                                            </div>
                                        @else
                                            <div class="text-center py-2 text-slate-400 bg-slate-50 rounded-lg border border-slate-200 text-xs">
                                                <i class="fas fa-lock mr-1"></i> Terkunci
                                            </div>
                                        @endif
                                        <div id="file_preview_{{ $semester }}" class="hidden mt-2 text-xs text-emerald-600 bg-emerald-50 p-2 rounded border border-emerald-100">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            <span id="file_name_{{ $semester }}"></span>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center py-2 text-slate-400 w-full bg-slate-50 rounded-lg border border-slate-200">
                                        <i class="fas fa-lock text-sm"></i>
                                        <p class="text-xs">Nonaktif</p>
                                    </div>
                                @endif
                            </div>
                        @endfor
                    </div>
                    
                    @if($dokumenPemberkasanEnabled)
                        <!-- Upload All Button -->
                        <div class="mt-6 text-center">
                            @if(!$isLockedGeneral)
                            <form action="{{ route('documents.khs.upload.multiple') }}" method="POST" enctype="multipart/form-data" id="uploadAllForm">
                                @csrf
                                <button type="submit" id="uploadAllBtn" class="bg-indigo-600 text-white py-3 px-8 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors duration-200 font-bold shadow-md hover:shadow-lg">
                                    <i class="fas fa-save mr-2" id="upload-icon"></i>
                                    <span id="upload-text">Simpan dan Upload</span>
                                    <div id="upload-loading" class="hidden flex items-center">
                                        <div class="relative mr-3">
                                            <div class="animate-spin rounded-full h-4 w-4 border-2 border-white border-t-transparent"></div>
                                            <div class="absolute inset-0 flex items-center justify-center">
                                                <div class="w-2 h-2 bg-white rounded-full animate-pulse"></div>
                        </div>
                    </div>
                                        <span class="text-white">Memproses...</span>
                                    </div>
                                </button>
                                <div id="uploadStatus" class="mt-3 text-sm"></div>
                            </form>
                            @else
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 inline-block">
                                <div class="flex items-center text-yellow-700">
                                    <i class="fas fa-lock mr-2"></i>
                                    <span>Upload dinonaktifkan karena status PKL sedang Aktif/Selesai</span>
                                </div>
                            </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Multi Semester Transcript Analysis -->
            <div class="bg-white shadow-sm rounded-xl border border-slate-200 overflow-hidden">
                <div class="bg-white px-6 py-4 border-b border-slate-100 cursor-pointer hover:bg-slate-50 transition-colors duration-200" onclick="toggleSection('transkrip-sipadu-section')">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-lg bg-purple-50 flex items-center justify-center">
                                    <i class="fas fa-chart-line text-purple-600"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-bold text-slate-800">Data Transkrip Sipadu</h3>
                                <p class="text-slate-500 text-sm">Paste data transkrip untuk setiap semester</p>
                            </div>
                        </div>
                        <i class="fas fa-chevron-down text-slate-400 transition-transform duration-300 transform" id="transkrip-sipadu-section-icon"></i>
                    </div>
                </div>
                
                <div id="transkrip-sipadu-section" class="p-6 transition-all duration-300">
                    <!-- Semester Tabs Navigation -->
                    <div class="mb-6">
                        <div class="border-b border-gray-200">
                            <nav class="-mb-px flex space-x-8" aria-label="Semester Tabs">
                        @for($semester = 1; $semester <= 4; $semester++)
                                    <button onclick="showSemesterTab({{ $semester }})" 
                                            id="semester-tab-{{ $semester }}" 
                                            class="semester-tab-button py-4 px-6 border-b-2 font-medium text-sm transition-all duration-200 flex items-center {{ $semester === 1 ? 'border-purple-600 text-purple-700' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                                        <div class="w-6 h-6 rounded-full flex items-center justify-center mr-2 text-xs font-bold {{ $semester === 1 ? 'bg-purple-100 text-purple-700' : 'bg-slate-100 text-slate-500' }}">
                                            {{ $semester }}
                                        </div>
                                        <span>Semester {{ $semester }}</span>
                                    </button>
                                @endfor
                            </nav>
                        </div>
                    </div>

               <!-- Semester Tab Content -->
               @for($semester = 1; $semester <= 4; $semester++)
                   <div id="semester-content-{{ $semester }}" class="semester-content transition-opacity duration-300 {{ $semester === 1 ? 'opacity-100' : 'opacity-0 hidden' }}">
                            <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                                <div class="text-center mb-6">
                                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <span class="text-purple-600 font-bold text-lg">{{ $semester }}</span>
                                    </div>
                                    <h4 class="text-lg font-semibold text-gray-900">Semester {{ $semester }}</h4>
                                </div>
                                
                                <!-- Layout Vertikal: Textfield di atas, Tabel di bawah -->
                                <div class="space-y-6">
                                    <!-- Textfield untuk Paste -->
                                    <div class="space-y-3">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fas fa-paste mr-2 text-purple-600"></i>
                                            Paste Data Transkrip
                                        </label>
                                        <textarea id="pasteArea{{ $semester }}" 
                                                  class="w-full p-3 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 read-only:bg-gray-100 read-only:text-gray-600 read-only:cursor-not-allowed" 
                                                  rows="6" 
                                                  placeholder="Paste data transkrip semester {{ $semester }} di sini...&#10;&#10;Contoh format:&#10;Kode Mata Kuliah&#9;Nama Mata Kuliah&#9;SKS&#9;Nilai&#10;All231203&#9;Pemrograman Web&#9;3&#9;A"
                                                  onpaste="handlePaste({{ $semester }})"
                                                  {{ $isLockedGeneral ? 'readonly' : '' }}></textarea>
                                        
                                        @if(!$isLockedGeneral)
                                        <div class="flex space-x-2">
                                            <button onclick="saveSemester({{ $semester }})" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded text-sm font-medium transition-colors duration-200">
                                                <i class="fas fa-save mr-1"></i>Simpan
                                            </button>
                                            <button onclick="clearSemester({{ $semester }})" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded text-sm font-medium transition-colors duration-200">
                                                <i class="fas fa-trash mr-1"></i>Clear
                                            </button>
                                        </div>
                                        @else
                                        <div class="flex items-center text-sm text-gray-500 bg-gray-50 p-2 rounded border border-gray-200">
                                            <i class="fas fa-lock mr-2"></i>
                                            <span>Edit data dinonaktifkan (Status PKL Aktif/Selesai)</span>
                                        </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Tabel Hasil -->
                                    <div class="space-y-3">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fas fa-table mr-2 text-green-600"></i>
                                            Tabel Hasil
                                        </label>
                                        
                                        <!-- Preview Tabel -->
                                        <div id="preview{{ $semester }}" class="min-h-[300px] border border-gray-200 rounded-lg bg-white p-3 text-sm">
                                            <div class="flex items-center justify-center h-full text-gray-400">
                                                <div class="text-center">
                                                    <i class="fas fa-table text-2xl mb-2"></i>
                                                    <p>Tabel akan muncul setelah paste data</p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Loading indicator -->
                                        <div id="loading{{ $semester }}" class="hidden text-center py-2">
                                            <div class="inline-flex items-center text-blue-600">
                                                <i class="fas fa-spinner fa-spin mr-2"></i>
                                                <span class="text-sm">Memuat data...</span>
                                            </div>
                                        </div>
                                        
                                        <!-- Result Summary -->
                                        <div id="result{{ $semester }}" class="text-sm"></div>
                                        
                                        <!-- Result Table untuk Semester {{ $semester }} -->
                                        <div id="tableResult{{ $semester }}" class="mt-3" style="display: none;">
                                            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                                                <div class="bg-purple-50 px-3 py-2 border-b border-gray-200">
                                                    <h5 class="text-sm font-medium text-purple-800">Hasil Analisis Semester {{ $semester }}</h5>
                                                </div>
                                                <div class="p-3">
                                                    <div class="grid grid-cols-2 gap-2 text-xs">
                                                        <div class="flex justify-between">
                                                            <span class="text-gray-600">IPS:</span>
                                                            <span id="ips{{ $semester }}" class="font-medium text-blue-600">-</span>
                                                        </div>
                                                        <div class="flex justify-between">
                                                            <span class="text-gray-600">SKS D:</span>
                                                            <span id="sksD{{ $semester }}" class="font-medium text-orange-600">-</span>
                                                        </div>
                                                        <div class="flex justify-between">
                                                            <span class="text-gray-600">Ada E:</span>
                                                            <span id="hasE{{ $semester }}" class="font-medium text-red-600">-</span>
                                                        </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Total SKS:</span>
                                                <span id="totalSks{{ $semester }}" class="font-medium text-blue-600">-</span>
                                                        </div>
                                            </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endfor
                </div>
            </div>
                </div>
            </div>


        </div>
    </div>


    <div id="content-laporan" class="tab-content hidden">
        <div class="max-w-5xl mx-auto mt-6" style="max-width: 76rem;">
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
            <div class="bg-white shadow-sm rounded-xl border border-slate-200 overflow-hidden">
                <div class="bg-white px-6 py-4 border-b border-slate-100">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center">
                                <i class="fas fa-file-alt text-indigo-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-bold text-slate-800">Laporan PKL</h3>
                            <p class="text-slate-500 text-sm">Upload laporan akhir PKL</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    @if($laporan && is_object($laporan))
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg border">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center">
                                            <i class="fas fa-file-pdf text-red-500 mr-2"></i>
                                            <p class="text-sm font-medium text-gray-900">{{ basename($laporan->file_path ?? '') }}</p>
                                </div>
                                        <div class="flex space-x-2">
                                            <button type="button" onclick="window.previewFile('{{ $laporan->file_path ?? '' }}')" class="text-blue-600 hover:text-blue-800 text-sm px-2 py-1 rounded hover:bg-blue-50">
                                                <i class="fas fa-eye mr-1"></i>Lihat
                                            </button>
                                            @if(!$isLockedGeneral)
                                            <button type="button" onclick="window.deleteFile('laporan', {{ $laporan->id }})" class="text-red-600 hover:text-red-800 text-sm px-2 py-1 rounded hover:bg-red-50">
                                                <i class="fas fa-trash mr-1"></i>Hapus
                                            </button>
                                            @endif
                            </div>
                                    </div>
                                    <p class="text-xs text-gray-500 mb-3">Uploaded: {{ isset($laporan->created_at) ? $laporan->created_at->format('d M Y H:i') : 'N/A' }}</p>
                                    
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

                    @if(!$isLockedAkhir)
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
                    @else
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
                        <div class="flex items-center justify-center text-yellow-700 mb-2">
                            <i class="fas fa-lock text-xl mr-2"></i>
                            <span class="font-semibold">Upload Laporan Terkunci</span>
                        </div>
                        <p class="text-sm text-yellow-600">Anda tidak dapat mengupload laporan baru karena status PKL sedang Aktif/Selesai.</p>
                    </div>
                    @endif
                </div>
            </div>
        @endif
                            </div>
        </div>
    </div>

    <div id="content-dokumen-pendukung" class="tab-content hidden">
        <div class="max-w-5xl mx-auto mt-6" style="max-width: 76rem;">
        <div class="bg-white shadow-sm rounded-xl border border-slate-200 overflow-hidden mt-6">
            <div class="bg-white px-6 py-4 border-b border-slate-100">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center">
                            <i class="fab fa-google-drive text-emerald-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-bold text-slate-800">Dokumen Pendukung</h3>
                        <p class="text-slate-500 text-sm">Masukkan link Google Drive untuk dokumen pendukung</p>
                    </div>
                </div>
            </div>

            <div class="p-6 space-y-6">
                <!-- Sertifikat PKKMB -->
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center">
                            <i class="fab fa-google-drive text-blue-500 mr-2"></i>
                            <h4 class="text-lg font-medium text-gray-900">Sertifikat PKKMB</h4>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            <i class="fas fa-asterisk mr-1 text-xs"></i>Wajib
                        </span>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <label for="link_pkkmb" class="block text-sm font-medium text-gray-700 mb-2">Link Google Drive Sertifikat PKKMB</label>
                            <input type="url" id="link_pkkmb" name="link_pkkmb" placeholder="https://drive.google.com/file/d/..." 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100 disabled:text-gray-500"
                                   {{ $isLockedGeneral ? 'disabled' : '' }}>
                        </div>
                        <p class="text-xs text-gray-500 flex items-center">
                            <i class="fas fa-info-circle mr-1"></i>
                            Paste link Google Drive yang dapat diakses publik
                        </p>
                    </div>
                </div>

                <!-- Sertifikat English Course -->
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center">
                            <i class="fab fa-google-drive text-blue-500 mr-2"></i>
                            <h4 class="text-lg font-medium text-gray-900">Sertifikat English Course</h4>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            <i class="fas fa-asterisk mr-1 text-xs"></i>Wajib
                        </span>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <label for="link_english" class="block text-sm font-medium text-gray-700 mb-2">Link Google Drive Sertifikat English Course</label>
                            <input type="url" id="link_english" name="link_english" placeholder="https://drive.google.com/file/d/..." 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100 disabled:text-gray-500"
                                   {{ $isLockedGeneral ? 'disabled' : '' }}>
                        </div>
                        <p class="text-xs text-gray-500 flex items-center">
                            <i class="fas fa-info-circle mr-1"></i>
                            Paste link Google Drive yang dapat diakses publik
                        </p>
                    </div>
                </div>

                <!-- Sertifikat Semasa Berkuliah -->
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center">
                            <i class="fab fa-google-drive text-blue-500 mr-2"></i>
                            <h4 class="text-lg font-medium text-gray-900">Sertifikat Semasa Berkuliah di Politeknik Negeri Tanah Laut</h4>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <i class="fas fa-info-circle mr-1"></i>Opsional
                        </span>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <label for="link_semasa" class="block text-sm font-medium text-gray-700 mb-2">Link Google Drive Sertifikat Semasa Berkuliah</label>
                            <input type="url" id="link_semasa" name="link_semasa" placeholder="https://drive.google.com/file/d/..." 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100 disabled:text-gray-500"
                                   {{ $isLockedGeneral ? 'disabled' : '' }}>
                        </div>
                        <p class="text-xs text-gray-500 flex items-center">
                            <i class="fas fa-info-circle mr-1"></i>
                            Paste link Google Drive yang dapat diakses publik
                        </p>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="pt-4 border-t border-gray-200">
                    @if(!$isLockedGeneral)
                    <button type="button" onclick="saveDokumenPendukung()" class="w-full bg-green-600 text-white py-3 px-4 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors duration-200 font-medium">
                        <i class="fas fa-save mr-2"></i>Simpan Link Dokumen Pendukung
                    </button>
                    @else
                    <div class="text-center py-2 text-gray-500 bg-gray-50 rounded border border-gray-200">
                        <i class="fas fa-lock mr-2"></i>
                        <span>Penyimpanan dinonaktifkan (Status PKL Aktif/Selesai)</span>
                    </div>
                    @endif
                </div>
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

    // Check if Tab Pemberkasan Instansi Mitra is disabled
    @if(!$dokumenPemberkasanEnabled)
    if (tabName === 'surat-balasan') {
        alert('Tab Pemberkasan Instansi Mitra sedang dinonaktifkan oleh admin.');
        return;
    }
    @endif

    // Check if Tab Pemberkasan Akhir is disabled
    @if(!$laporanPklEnabled)
    if (tabName === 'laporan') {
        alert('Tab Pemberkasan Akhir sedang dinonaktifkan oleh admin.');
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
        button.classList.remove('active', 'bg-blue-600', 'text-white', 'shadow-md');
        button.classList.add('bg-white', 'text-gray-600', 'border', 'border-gray-200');
    });

    // Show selected tab content with smooth transition
    const selectedContent = document.getElementById(`content-${tabName}`);
    if (selectedContent) {
        selectedContent.classList.remove('hidden');
    }

    // Add active class to selected tab button
    const selectedButton = document.getElementById(`tab-${tabName}`);
    if (selectedButton) {
        selectedButton.classList.remove('bg-white', 'text-gray-600', 'border', 'border-gray-200');
        selectedButton.classList.add('active', 'bg-blue-600', 'text-white', 'shadow-md');
    }
}

// Initialize first tab as active
document.addEventListener('DOMContentLoaded', function() {
    // Initialize KHS file count display immediately
    const khsFileCount = @json($khsFileCount ?? 0);
    console.log('Initializing KHS file count:', khsFileCount);
    document.getElementById('uploadKhs').textContent = `${khsFileCount}/4`;
    
    // Initialize PKL status based on both transcript and KHS file count
    const pklStatusElement = document.getElementById('pklStatus');
    const isTranscriptComplete = false; // Will be updated by calculateFinalIpk
    const isKhsComplete = khsFileCount >= 4;
    
    if (!isTranscriptComplete || !isKhsComplete) {
        pklStatusElement.textContent = 'Belum Lengkap';
        pklStatusElement.className = 'text-3xl font-bold text-yellow-600 mb-1';
    } else {
        pklStatusElement.textContent = 'Tidak Layak';
        pklStatusElement.className = 'text-3xl font-bold text-red-600 mb-1';
    }
    
    showTab('pemberkasan');
    
    // Restore scroll position after page load
    restoreScrollPosition();
});

// Scroll position preservation functions
function saveScrollPosition() {
    const scrollY = window.scrollY;
    sessionStorage.setItem('documentsScrollPosition', scrollY.toString());
}

function restoreScrollPosition() {
    const savedScrollPosition = sessionStorage.getItem('documentsScrollPosition');
    if (savedScrollPosition) {
        // Use setTimeout to ensure DOM is fully loaded
        setTimeout(() => {
            window.scrollTo(0, parseInt(savedScrollPosition));
        }, 100);
    }
}

// Save scroll position before page unload
window.addEventListener('beforeunload', saveScrollPosition);

// Save scroll position on scroll (throttled)
let scrollTimeout;
window.addEventListener('scroll', function() {
    clearTimeout(scrollTimeout);
    scrollTimeout = setTimeout(saveScrollPosition, 100);
});

// File preview and upload handling
function updateFilePreview(semester) {
    const fileInput = document.getElementById(`file_semester_${semester}`);
    const preview = document.getElementById(`file_preview_${semester}`);
    const fileName = document.getElementById(`file_name_${semester}`);
    
    if (fileInput.files.length > 0) {
        const file = fileInput.files[0];
        fileName.textContent = file.name;
        preview.classList.remove('hidden');
    } else {
        preview.classList.add('hidden');
    }
}

// Handle paste and clean data automatically
function handlePaste(semester) {
    setTimeout(() => {
        const textarea = document.getElementById(`pasteArea${semester}`);
        let content = textarea.value;
        
        // Clean the content by removing "Periode" lines and year lines
        content = cleanTranskripData(content);
        
        // Update the textarea with cleaned content
        textarea.value = content;
        
        // Trigger analysis if content is not empty
        if (content.trim()) {
            analyzeSemester(semester);
        }
    }, 100); // Small delay to ensure paste is complete
}

// Clean transkrip data by removing "Periode" and year lines at the beginning, and "Indeks Prestasi Semester" lines at the end
function cleanTranskripData(content) {
    if (!content) return content;
    
    const lines = content.split('\n');
    const cleanedLines = [];
    let foundDataStart = false;
    let foundDataEnd = false;
    
    for (let i = 0; i < lines.length; i++) {
        const line = lines[i].trim();
        
        // Skip empty lines at the beginning
        if (!foundDataStart && !line) {
            continue;
        }
        
        // Skip "Periode" lines at the beginning
        if (line.toLowerCase().includes('periode') && !foundDataStart) {
            continue;
        }
        
        // Skip year lines (like "20241", "20242", etc.) that come after "Periode"
        if (!foundDataStart && /^\d{5}$/.test(line)) {
            continue;
        }
        
        // Skip lines that are just "Periode" followed by year
        if (!foundDataStart && line.match(/^periode\s+\d{5}$/i)) {
            continue;
        }
        
        // If we find a line that looks like data (contains tab or multiple spaces), start collecting
        if (!foundDataStart && (line.includes('\t') || line.split(/\s{2,}/).length > 2)) {
            foundDataStart = true;
        }
        
        // Check if we've reached the end of data (Indeks Prestasi Semester)
        if (foundDataStart && !foundDataEnd && line.toLowerCase().includes('indeks prestasi semester')) {
            // Include this line (Indeks Prestasi Semester) but mark that we've reached the end
            cleanedLines.push(lines[i]);
            foundDataEnd = true;
            // Don't include any lines after this
            continue;
        }
        
        // If we've found the start of data and haven't reached the end, include the line
        if (foundDataStart && !foundDataEnd) {
            cleanedLines.push(lines[i]);
        }
    }
    
    return cleanedLines.join('\n');
}

// Handle multiple upload
document.getElementById('uploadAllForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData();
    const uploadStatus = document.getElementById('uploadStatus');
    const uploadBtn = document.getElementById('uploadAllBtn');
    
    // Collect all selected files
    let hasFiles = false;
    for (let semester = 1; semester <= 4; semester++) {
        const fileInput = document.getElementById(`file_semester_${semester}`);
        if (fileInput.files.length > 0) {
            formData.append('files[]', fileInput.files[0]);
            formData.append('semesters[]', semester);
            hasFiles = true;
        }
    }
    
    if (!hasFiles) {
        uploadStatus.innerHTML = '<div class="text-red-600"><i class="fas fa-exclamation-triangle mr-1"></i>Pilih minimal satu file untuk diupload</div>';
        return;
    }
    
    // Add CSRF token
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    
    // Show loading state with same animation as login
    showUploadLoading();
    
    // Submit form
    fetch('{{ route("documents.khs.upload.multiple") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            uploadStatus.innerHTML = '<div class="text-green-600"><i class="fas fa-check-circle mr-1"></i>' + data.message + '</div>';
            // Reload page after 2 seconds
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            uploadStatus.innerHTML = '<div class="text-red-600"><i class="fas fa-exclamation-triangle mr-1"></i>' + (data.message || 'Terjadi kesalahan saat upload') + '</div>';
        }
    })
    .catch(error => {
        console.error('Upload error:', error);
        uploadStatus.innerHTML = '<div class="text-red-600"><i class="fas fa-exclamation-triangle mr-1"></i>Terjadi kesalahan saat upload</div>';
    })
    .finally(() => {
        hideUploadLoading();
    });
});

// Upload loading functions
function showUploadLoading() {
    document.getElementById('upload-icon').classList.add('hidden');
    document.getElementById('upload-text').classList.add('hidden');
    document.getElementById('upload-loading').classList.remove('hidden');
    document.getElementById('uploadAllBtn').classList.add('opacity-75', 'cursor-not-allowed');
}

function hideUploadLoading() {
    document.getElementById('upload-icon').classList.remove('hidden');
    document.getElementById('upload-text').classList.remove('hidden');
    document.getElementById('upload-loading').classList.add('hidden');
    document.getElementById('uploadAllBtn').classList.remove('opacity-75', 'cursor-not-allowed');
}

// Ensure functions are available globally
window.previewFile = function(filePath) {
    console.log('previewFile called with:', filePath);
    
    if (!filePath || filePath === '') {
        alert('File path tidak ditemukan');
        return;
    }
    
    const filename = filePath.split('/').pop();
    let fileType = 'khs';
    if (filePath.includes('surat_pengantar')) {
        fileType = 'surat-pengantar';
    } else if (filePath.includes('surat_balasan')) {
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
    if (filePath.includes('surat_pengantar')) {
        fileType = 'surat-pengantar';
    } else if (filePath.includes('surat_balasan')) {
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
    } else if (type === 'surat-pengantar') {
        url = '{{ route("documents.surat-pengantar.delete", ":id") }}'.replace(':id', id);
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
    for (let semester = 1; semester <= 4; semester++) {
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
    
    for (let semester = 1; semester <= 4; semester++) {
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
                autoSaveSemester(semester, text, analysis);
                
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
            formData.append('ips', analysis.ips || 0);
            formData.append('total_sks', analysis.total_sks || 0);
            formData.append('total_sks_d', analysis.total_sks_d || 0);
            formData.append('has_e', analysis.has_e ? 1 : 0);
            formData.append('eligible', analysis.eligible ? 1 : 0);

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
        document.getElementById('totalSemester').textContent = '0/4';
        document.getElementById('totalSksD').textContent = '0';
        document.getElementById('totalE').textContent = '0';
        document.getElementById('uploadKhs').textContent = `${khsFileCount}/4`;

        // Update status: Belum Layak dan Menyiapkan Berkas
        updateKelayakanPklStatus('BELUM LAYAK', false);
        updateStatusKeaktifanPkl('Menyiapkan Berkas', 'menyiapkan_berkas');
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
    document.getElementById('totalSemester').textContent = `${totalSemester}/4`;
    document.getElementById('totalSksD').textContent = totalSksD;
    document.getElementById('totalE').textContent = totalE;
    document.getElementById('uploadKhs').textContent = `${khsFileCount}/4`;

    // Update Status PKL based on Kelengkapan Transkrip and Upload Berkas KHS
    const pklStatusEl = document.getElementById('pklStatus');
    if (totalSemester >= 4 && khsFileCount >= 4) {
        pklStatusEl.textContent = 'Lengkap';
        pklStatusEl.className = 'text-3xl font-bold text-green-600 mb-1';
    } else {
        pklStatusEl.textContent = 'Belum Lengkap';
        pklStatusEl.className = 'text-3xl font-bold text-red-600 mb-1';
    }

    // Update PKL status with new logic (4 tahap)
    const pklStatusElement = document.getElementById('pklStatus');

    // Check if both Kelengkapan Transkrip and Upload Berkas KHS are complete
    const isTranscriptComplete = totalSemester >= 4;
    const isKhsComplete = khsFileCount >= 4;

    // Check eligibility criteria
    const isEligibleForPkl = isTranscriptComplete && isKhsComplete && finalIpk >= 2.5 && totalSksD <= 9 && totalE == 0;

    console.log('PKL Status Logic Check:');
    console.log(`- totalSemester: ${totalSemester}, isTranscriptComplete: ${isTranscriptComplete}`);
    console.log(`- khsFileCount: ${khsFileCount}, isKhsComplete: ${isKhsComplete}`);
    console.log(`- finalIpk: ${finalIpk}, totalSksD: ${totalSksD}, totalE: ${totalE}`);
    console.log(`- isEligibleForPkl: ${isEligibleForPkl}`);

    // Update Kelayakan PKL status
    // Get current database status
    const dbStatusPkl = '{{ $statusPkl ?? "siap" }}';

    // If status is 'selesai', don't update anything - respect the completed status
    if (dbStatusPkl === 'selesai') {
        console.log('PKL status is selesai, skipping status updates');
        return;
    }

    if (isEligibleForPkl) {
        updateKelayakanPklStatus('LAYAK', true);
        // Respect database status - don't override if already 'aktif'
        if (dbStatusPkl === 'aktif') {
            const mitraName = '{{ $user->profilMahasiswa->mitraSelected->nama ?? "Instansi Mitra" }}';
            updateStatusKeaktifanPkl('Aktif PKL', 'aktif', mitraName);
        } else {
            updateStatusKeaktifanPkl('Siap untuk PKL', 'siap');
        }
    } else {
        updateKelayakanPklStatus('BELUM LAYAK', false);
        // Update Status Keaktifan: Menyiapkan Berkas
        updateStatusKeaktifanPkl('Menyiapkan Berkas', 'menyiapkan_berkas');
    }

    console.log('=== FINAL IPK CALCULATION COMPLETED ===');
}

// Function to update Kelayakan PKL status in the header section
function updateKelayakanPklStatus(statusText, isEligible) {
    console.log(`Updating Kelayakan PKL to: ${statusText} (${isEligible ? 'eligible' : 'not eligible'})`);

    // Find the Kelayakan PKL section
    const allH4Elements = document.querySelectorAll('h4');
    let kelayakanSection = null;

    allH4Elements.forEach(h4 => {
        if (h4.textContent.includes('Kelayakan PKL')) {
            kelayakanSection = h4;
        }
    });

    if (kelayakanSection) {
        const parentDiv = kelayakanSection.closest('.bg-gradient-to-r');
        if (!parentDiv) {
            console.log('❌ Parent div not found');
            return;
        }

        // Update background color
        parentDiv.className = `bg-gradient-to-r from-${isEligible ? 'green' : 'red'}-50 to-${isEligible ? 'green' : 'red'}-100 border border-${isEligible ? 'green' : 'red'}-200 rounded-lg p-6`;

        // Update icon container
        const iconContainer = parentDiv.querySelector('.flex-shrink-0');
        if (iconContainer) {
            if (isEligible) {
                // Create contreng icon in circle
                iconContainer.innerHTML = `
                    <div class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-check text-3xl text-white"></i>
                    </div>
                `;
            } else {
                // Times circle icon
                iconContainer.innerHTML = `<i class="fas fa-times-circle text-5xl text-red-600"></i>`;
            }
        }

        // Update header color
        kelayakanSection.className = `text-lg font-semibold text-${isEligible ? 'green' : 'red'}-800`;

        // Update status text
        const statusElement = kelayakanSection.nextElementSibling;
        if (statusElement && statusElement.tagName === 'P') {
            statusElement.textContent = statusText;
            statusElement.className = `text-2xl text-${isEligible ? 'green' : 'red'}-700 font-bold`;
        }

        // Update description
        const descElement = statusElement?.nextElementSibling;
        if (descElement && descElement.tagName === 'P') {
            descElement.innerHTML = isEligible ? 'Memenuhi semua persyaratan PKL' : 'Belum memenuhi persyaratan PKL';
            descElement.className = `text-sm text-${isEligible ? 'green' : 'red'}-600 mt-1`;
        }

        console.log(`✅ Updated Kelayakan PKL to: ${statusText}`);
    } else {
        console.log('❌ Kelayakan PKL section not found');
    }
}

// Function to update Status Keaktifan PKL
function updateStatusKeaktifanPkl(statusText, statusType, mitraName = '') {
    console.log(`Updating Status Keaktifan PKL to: ${statusText} (${statusType})`);

    // Find the Status Keaktifan PKL section
    const allH4Elements = document.querySelectorAll('h4');
    let statusSection = null;

    allH4Elements.forEach(h4 => {
        if (h4.textContent.includes('Status Keaktifan PKL')) {
            statusSection = h4;
        }
    });

    if (statusSection) {
        const parentDiv = statusSection.closest('.bg-gradient-to-r');
        if (!parentDiv) {
            console.log('❌ Parent div not found');
            return;
        }

        // Determine color and icon based on status type
        let color, icon, desc;
        switch(statusType) {
            case 'menyiapkan_berkas':
                color = 'gray';
                icon = 'file-alt';
                desc = 'Sedang menyiapkan berkas kelayakan PKL';
                break;
            case 'siap':
                color = 'yellow';
                icon = 'clipboard-check';
                desc = 'Memenuhi syarat kelayakan, siap memulai PKL';
                break;
            case 'aktif':
                color = 'blue';
                icon = 'building';
                desc = mitraName ? `Aktif di ${mitraName}` : 'Sedang melaksanakan PKL di instansi';
                break;
            case 'selesai':
                color = 'green';
                icon = 'check-circle';
                desc = 'Semua tahapan PKL telah selesai';
                break;
            default:
                color = 'gray';
                icon = 'file-alt';
                desc = 'Belum memulai proses PKL';
        }

        // Update background color
        parentDiv.className = `bg-gradient-to-r from-${color}-50 to-${color}-100 border border-${color}-200 rounded-lg p-6`;

        // Update icon
        const iconElement = parentDiv.querySelector('.flex-shrink-0 i');
        if (iconElement) {
            iconElement.className = `fas fa-${icon} text-5xl text-${color}-600`;
        }

        // Update header color
        statusSection.className = `text-lg font-semibold text-${color}-800`;

        // Update status text
        const statusElement = statusSection.nextElementSibling;
        if (statusElement && statusElement.tagName === 'P') {
            statusElement.textContent = statusText;
            statusElement.className = `text-2xl text-${color}-700 font-bold`;
        }

        // Update description
        const descElement = statusElement?.nextElementSibling;
        if (descElement && descElement.tagName === 'P') {
            descElement.innerHTML = desc;
            descElement.className = `text-sm text-${color}-600 mt-1`;
        }

        console.log(`✅ Updated Status Keaktifan PKL to: ${statusText}`);
    } else {
        console.log('❌ Status Keaktifan PKL section not found');
    }
}

// Function to auto-save semester data
function autoSaveSemester(semester, transcriptData, analysis) {
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
                transcript_data: transcriptData,
                ips: analysis.ips || 0,
                total_sks: analysis.total_sks || 0,
                total_sks_d: analysis.total_sks_d || 0,
                has_e: analysis.has_e ? 1 : 0,
                eligible: analysis.eligible ? 1 : 0
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
        for (let semester = 1; semester <= 4; semester++) {
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

    // Toggle Section Function with smooth animation
    function toggleSection(sectionId) {
        const section = document.getElementById(sectionId);
        const icon = document.getElementById(sectionId + '-icon');
        
        if (section.classList.contains('hidden')) {
            // Show section with smooth animation
            section.classList.remove('hidden');
            section.style.maxHeight = '0px';
            section.style.opacity = '0';
            
            // Force reflow
            section.offsetHeight;
            
            // Animate to full height
            requestAnimationFrame(() => {
                section.style.transition = 'max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.4s ease-in-out';
                section.style.maxHeight = section.scrollHeight + 'px';
                section.style.opacity = '1';
            });
            
            // Rotate icon
            if (icon) {
                icon.style.transition = 'transform 0.3s ease-in-out';
                icon.style.transform = 'rotate(180deg)';
            }
        } else {
            // Hide section with smooth animation
            section.style.maxHeight = section.scrollHeight + 'px';
            
            requestAnimationFrame(() => {
                section.style.transition = 'max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.4s ease-in-out';
                section.style.maxHeight = '0px';
                section.style.opacity = '0';
            });
            
            // Wait for animation to finish before hiding
            setTimeout(() => {
                section.classList.add('hidden');
            }, 400);
            
            // Rotate icon back
            if (icon) {
                icon.style.transition = 'transform 0.3s ease-in-out';
                icon.style.transform = 'rotate(0deg)';
            }
        }
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
            <!-- Pemberkasan Instansi Mitra Content -->
            <div class="max-w-5xl mx-auto mt-6" style="max-width: 76rem;">
                <div class="grid grid-cols-1 gap-6">

                <!-- Row 1: Surat Pengantar -->
                <div class="bg-white shadow-sm rounded-xl border border-slate-200 overflow-hidden">
                    <div class="bg-white px-6 py-4 border-b border-slate-100 cursor-pointer hover:bg-slate-50 transition-colors duration-200" onclick="toggleSection('surat-pengantar-section')">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-file-alt text-xl text-blue-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-bold text-slate-800">1. Surat Pengantar</h3>
                                    <p class="text-slate-500 text-sm">Upload surat pengantar dari kampus</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <span class="bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1 rounded-full">Step 1</span>
                                <i class="fas fa-chevron-down text-slate-400 transition-transform duration-300 transform" id="surat-pengantar-section-icon"></i>
                            </div>
                        </div>
                    </div>

                    <div id="surat-pengantar-section" class="p-6 transition-all duration-300">
                        @if($suratPengantar ?? false)
                            <div class="mb-4 p-4 bg-gray-50 rounded-lg border">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="flex items-center">
                                                <i class="fas fa-file-pdf text-red-500 mr-2"></i>
                                                <p class="text-sm font-medium text-gray-900">{{ basename($suratPengantar->file_path) }}</p>
                                            </div>
                                            <div class="flex space-x-2">
                                                <button type="button" onclick="window.previewFile('{{ $suratPengantar->file_path }}')" class="text-blue-600 hover:text-blue-800 text-sm px-2 py-1 rounded hover:bg-blue-50">
                                                    <i class="fas fa-eye mr-1"></i>Lihat
                                                </button>
                                                @if(!$isLockedGeneral)
                                                <button type="button" onclick="window.deleteFile('surat-pengantar', {{ $suratPengantar->id }})" class="text-red-600 hover:text-red-800 text-sm px-2 py-1 rounded hover:bg-red-50">
                                                    <i class="fas fa-trash mr-1"></i>Hapus
                                                </button>
                                                @endif
                                            </div>
                                        </div>
                                        <p class="text-xs text-gray-500 mb-2">Uploaded: {{ $suratPengantar->created_at->format('d M Y H:i') }}</p>
                                        <div class="flex items-center">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                                @if($suratPengantar->status_validasi === 'tervalidasi') bg-green-100 text-green-800
                                                @elseif($suratPengantar->status_validasi === 'belum_valid') bg-red-100 text-red-800
                                                @elseif($suratPengantar->status_validasi === 'revisi') bg-yellow-100 text-yellow-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                @if($suratPengantar->status_validasi === 'tervalidasi')
                                                    <i class="fas fa-check-circle mr-1"></i>Tervalidasi
                                                @elseif($suratPengantar->status_validasi === 'belum_valid')
                                                    <i class="fas fa-times-circle mr-1"></i>Belum Valid
                                                @elseif($suratPengantar->status_validasi === 'revisi')
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

                        @if(!$isLockedGeneral)
                        <form action="{{ route('documents.surat-pengantar.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div>
                                <label for="surat_pengantar_file" class="block text-sm font-medium text-gray-700 mb-2">Pilih File Surat Pengantar</label>
                                <input type="file" id="surat_pengantar_file" name="file" accept=".pdf" required
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-lg cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <p class="mt-2 text-xs text-gray-500 flex items-center">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Format: PDF, Maksimal: 10MB
                                </p>
                            </div>
                            <button type="submit" class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors font-medium">
                                <i class="fas fa-upload mr-2"></i>{{ ($suratPengantar ?? false) ? 'Update Surat Pengantar' : 'Upload Surat Pengantar' }}
                            </button>
                        </form>
                        @else
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
                            <div class="flex items-center justify-center text-yellow-700 mb-2">
                                <i class="fas fa-lock text-xl mr-2"></i>
                                <span class="font-semibold">Upload Terkunci</span>
                            </div>
                            <p class="text-sm text-yellow-600">Anda tidak dapat mengupload surat pengantar baru karena status PKL sedang Aktif/Selesai.</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Row 2: Pilih Instansi Mitra -->
                <div class="bg-white shadow-sm rounded-xl border border-slate-200 overflow-hidden">
                    <div class="bg-white px-6 py-4 border-b border-slate-100 cursor-pointer hover:bg-slate-50 transition-colors duration-200" onclick="toggleSection('mitra-section')">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-indigo-50 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-building text-xl text-indigo-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-bold text-slate-800">2. Pilih Instansi Mitra</h3>
                                    <p class="text-slate-500 text-sm">Pilih instansi mitra untuk PKL</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <span class="bg-indigo-100 text-indigo-700 text-xs font-bold px-3 py-1 rounded-full">Step 2</span>
                                <i class="fas fa-chevron-down text-slate-400 transition-transform duration-300 transform" id="mitra-section-icon"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div id="mitra-section" class="p-6 transition-all duration-300">
                        @if($user->profilMahasiswa && $user->profilMahasiswa->mitraSelected)
                            @php
                                $selectedMitra = $user->profilMahasiswa->mitraSelected;
                                $selectedMitra->loadCount('mahasiswaTerpilih as mahasiswa_count');
                            @endphp
                            <!-- Selected Mitra Display with Full Details -->
                            <div class="border border-blue-200 rounded-lg overflow-hidden">
                                <div class="bg-blue-50 px-4 py-3 border-b border-blue-200">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="h-12 w-12 rounded-lg bg-blue-600 flex items-center justify-center mr-3 shadow">
                                                <i class="fas fa-building text-white text-lg"></i>
                                            </div>
                                            <div>
                                                <h4 class="text-base font-semibold text-gray-900">{{ $selectedMitra->nama }}</h4>
                                                <p class="text-xs text-gray-600 flex items-center mt-1">
                                                    <i class="fas fa-map-marker-alt mr-1.5 text-blue-600"></i>{{ $selectedMitra->alamat }}
                                                </p>
                                            </div>
                                        </div>
                                        @if(!$isLockedGeneral)
                                        <a href="{{ route('mitra') }}" class="px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 text-sm font-medium transition">
                                            <i class="fas fa-edit mr-1"></i>Ganti
                                        </a>
                                        @endif
                                    </div>
                                </div>
                                <div class="p-4 bg-white">
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        <div class="flex items-start space-x-2">
                                            <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                                                <i class="fas fa-phone text-gray-600 text-sm"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs text-gray-500 font-medium">Kontak</p>
                                                <p class="text-sm text-gray-900 font-semibold truncate">{{ $selectedMitra->kontak ?? '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start space-x-2">
                                            <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                                                <i class="fas fa-road text-gray-600 text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 font-medium">Jarak</p>
                                                <p class="text-sm text-gray-900 font-semibold">{{ $selectedMitra->jarak ?? 0 }} km</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start space-x-2">
                                            <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                                                <i class="fas fa-users text-gray-600 text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 font-medium">Kuota</p>
                                                <p class="text-sm text-gray-900 font-semibold">{{ $selectedMitra->mahasiswa_count ?? 0 }}/{{ $selectedMitra->max_mahasiswa ?? 4 }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start space-x-2">
                                            <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                                                <i class="fas fa-money-bill-wave text-gray-600 text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 font-medium">Honor</p>
                                                <p class="text-sm text-gray-900 font-semibold">{{ $selectedMitra->honor_label ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-4 pt-4 border-t border-gray-100">
                                        <div class="grid grid-cols-3 gap-3 text-center">
                                            <div class="px-3 py-2 bg-gray-50 rounded-lg">
                                                <p class="text-xs text-gray-500 mb-1">Fasilitas</p>
                                                <p class="text-sm font-semibold text-gray-900">{{ $selectedMitra->fasilitas_label ?? '-' }}</p>
                                            </div>
                                            <div class="px-3 py-2 bg-gray-50 rounded-lg">
                                                <p class="text-xs text-gray-500 mb-1">Kesesuaian</p>
                                                <p class="text-sm font-semibold text-gray-900">{{ $selectedMitra->kesesuaian_jurusan_label ?? '-' }}</p>
                                            </div>
                                            <div class="px-3 py-2 bg-gray-50 rounded-lg">
                                                <p class="text-xs text-gray-500 mb-1">Kebersihan</p>
                                                <p class="text-sm font-semibold text-gray-900">{{ $selectedMitra->tingkat_kebersihan_label ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- No Mitra Selected -->
                            <div class="p-8 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300 text-center">
                                <i class="fas fa-building text-4xl text-gray-400 mb-3"></i>
                                <p class="text-sm text-gray-700 font-medium mb-2">Belum Ada Instansi Mitra</p>
                                <p class="text-xs text-gray-500 mb-4">Pilih instansi mitra terlebih dahulu untuk melanjutkan</p>
                                <a href="{{ route('mitra') }}" class="inline-flex items-center bg-blue-600 text-white py-2.5 px-6 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors font-medium">
                                    <i class="fas fa-search mr-2"></i>Cari Instansi Mitra
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Row 3: Surat Balasan -->
                <div class="bg-white shadow-sm rounded-xl border border-slate-200 overflow-hidden">
                <div class="bg-white px-6 py-4 border-b border-slate-100 cursor-pointer hover:bg-slate-50 transition-colors duration-200" onclick="toggleSection('surat-balasan-section')">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-envelope text-xl text-green-600"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-bold text-slate-800">3. Surat Balasan</h3>
                                    <p class="text-slate-500 text-sm">Upload surat balasan dari instansi</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <span class="bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full">Step 3</span>
                            <i class="fas fa-chevron-down text-slate-400 transition-transform duration-300 transform" id="surat-balasan-section-icon"></i>
                        </div>
                    </div>
                </div>
                
                <div id="surat-balasan-section" class="p-6 transition-all duration-300">
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
                                            @if(!$isLockedGeneral)
                                            <button type="button" onclick="window.deleteFile('surat-balasan', {{ $suratBalasan->id }})" class="text-red-600 hover:text-red-800 text-sm px-2 py-1 rounded hover:bg-red-50">
                                                <i class="fas fa-trash mr-1"></i>Hapus
                                            </button>
                                            @endif
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

                    @if(!$isLockedGeneral)
                    <form action="{{ route('documents.surat.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        
                            @if($user->profilMahasiswa && $user->profilMahasiswa->mitraSelected)
                                <input type="hidden" name="mitra_id" value="{{ $user->profilMahasiswa->mitraSelected->id }}">
                            @else
                                <input type="hidden" name="mitra_id" value="">
                                        @endif
                        
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
                    @else
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
                        <div class="flex items-center justify-center text-yellow-700 mb-2">
                            <i class="fas fa-lock text-xl mr-2"></i>
                            <span class="font-semibold">Upload Terkunci</span>
                        </div>
                        <p class="text-sm text-yellow-600">Anda tidak dapat mengupload surat balasan baru karena status PKL sedang Aktif/Selesai.</p>
                    </div>
                    @endif
                    </div>
                </div>
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
            <div class="bg-white shadow-lg rounded-xl border border-gray-100 overflow-hidden mt-6">
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
                    @if($laporan && is_object($laporan))
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg border">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center">
                                            <i class="fas fa-file-pdf text-red-500 mr-2"></i>
                                            <p class="text-sm font-medium text-gray-900">{{ basename($laporan->file_path) }}</p>
                                        </div>
                                        <div class="flex space-x-2">
                                            <button type="button" onclick="window.previewFile('{{ $laporan->file_path }}')" class="text-blue-600 hover:text-blue-800 text-sm px-2 py-1 rounded hover:bg-blue-50">
                                                <i class="fas fa-eye mr-1"></i>Lihat
                                            </button>
                                            <button type="button" onclick="window.deleteFile('laporan', {{ $laporan->id }})" class="text-red-600 hover:text-red-800 text-sm px-2 py-1 rounded hover:bg-red-50">
                                                <i class="fas fa-trash mr-1"></i>Hapus
                                            </button>
                                        </div>
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
        button.classList.remove('active', 'bg-blue-600', 'text-white', 'shadow-md');
        button.classList.add('bg-white', 'text-gray-600', 'border', 'border-gray-200');
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
    activeTab.classList.remove('bg-white', 'text-gray-600', 'border', 'border-gray-200');
    activeTab.classList.add('active', 'bg-blue-600', 'text-white', 'shadow-md');
}

// Semester tab switching functionality with fade animation
function showSemesterTab(semester) {
    // Get all semester contents
    const allContents = document.querySelectorAll('.semester-content');
    const targetContent = document.getElementById('semester-content-' + semester);
    
    // Remove active class from all semester tabs
    document.querySelectorAll('.semester-tab-button').forEach(button => {
        button.classList.remove('border-purple-600', 'text-purple-700');
        button.classList.add('border-transparent', 'text-slate-500');
        
        // Update circle
        const circle = button.querySelector('div');
        if (circle) {
            circle.classList.remove('bg-purple-100', 'text-purple-700');
            circle.classList.add('bg-slate-100', 'text-slate-500');
        }
    });
    
    // Add active class to selected semester tab
    const activeTab = document.getElementById('semester-tab-' + semester);
    activeTab.classList.add('border-purple-600', 'text-purple-700');
    activeTab.classList.remove('border-transparent', 'text-slate-500');
    
    // Update active circle
    const activeCircle = activeTab.querySelector('div');
    if (activeCircle) {
        activeCircle.classList.remove('bg-slate-100', 'text-slate-500');
        activeCircle.classList.add('bg-purple-100', 'text-purple-700');
    }
    
    // Fade out all contents first
    allContents.forEach(content => {
        if (!content.classList.contains('hidden')) {
            content.classList.add('opacity-0');
            setTimeout(() => {
                content.classList.add('hidden');
                content.classList.remove('opacity-100');
            }, 150); // Half of transition duration
        }
    });
    
    // Fade in target content
    setTimeout(() => {
        targetContent.classList.remove('hidden');
        targetContent.classList.add('opacity-0');
        // Force reflow
        targetContent.offsetHeight;
        targetContent.classList.remove('opacity-0');
        targetContent.classList.add('opacity-100');
    }, 150);
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

// Mitra management functions
function searchMitra() {
    window.location.href = '{{ route("mitra") }}';
}

function changeMitra() {
    if (confirm('Apakah Anda yakin ingin mengubah instansi mitra yang dipilih?')) {
        window.location.href = '{{ route("mitra") }}';
    }
}
</script>

<script>
// Ensure functions are available globally
window.previewFile = function(filePath) {
    console.log('previewFile called with:', filePath);
    
    if (!filePath || filePath === '') {
        alert('File path tidak ditemukan');
        return;
    }
    
    const filename = filePath.split('/').pop();
    let fileType = 'khs';
    if (filePath.includes('surat_pengantar')) {
        fileType = 'surat-pengantar';
    } else if (filePath.includes('surat_balasan')) {
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
    if (filePath.includes('surat_pengantar')) {
        fileType = 'surat-pengantar';
    } else if (filePath.includes('surat_balasan')) {
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
    } else if (type === 'surat-pengantar') {
        url = '{{ route("documents.surat-pengantar.delete", ":id") }}'.replace(':id', id);
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
    let originalText = '';
    if (button) {
        originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Menghapus...';
        button.disabled = true;
    }
    
    fetch(url, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            alert('File berhasil dihapus');
            location.reload();
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
        renderTable(rows);
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

// Function to save dokumen pendukung links
function saveDokumenPendukung() {
    const linkPkkmb = document.getElementById('link_pkkmb').value;
    const linkEnglish = document.getElementById('link_english').value;
    const linkSemasa = document.getElementById('link_semasa').value;

    // Validation: PKKMB dan English Course wajib diisi
    if (!linkPkkmb || !linkEnglish) {
        alert('Sertifikat PKKMB dan English Course wajib diisi!');
        return;
    }

    // Show loading state
    const saveButton = document.querySelector('button[onclick="saveDokumenPendukung()"]');
    const originalText = saveButton.innerHTML;
    saveButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
    saveButton.disabled = true;

    // Save to database
    fetch('{{ route("documents.save-gdrive-links") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            gdrive_pkkmb: linkPkkmb,
            gdrive_ecourse: linkEnglish,
            gdrive_more: linkSemasa
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Link dokumen pendukung berhasil disimpan!');
            // Reload halaman untuk update status kelayakan
            window.location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan data.');
    })
    .finally(() => {
        // Restore button state
        saveButton.innerHTML = originalText;
        saveButton.disabled = false;
    });
}

// Load saved dokumen pendukung links on page load
document.addEventListener('DOMContentLoaded', function() {
    // Load from database via AJAX
    fetch('{{ route("documents.load-gdrive-links") }}', {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.profile) {
            if (data.profile.gdrive_pkkmb) document.getElementById('link_pkkmb').value = data.profile.gdrive_pkkmb;
            if (data.profile.gdrive_ecourse) document.getElementById('link_english').value = data.profile.gdrive_ecourse;
            if (data.profile.gdrive_more) document.getElementById('link_semasa').value = data.profile.gdrive_more;
        }
    })
    .catch(error => {
        console.error('Error loading saved links:', error);
    });
    
    // Auto-switch to tab based on URL parameter
    const urlParams = new URLSearchParams(window.location.search);
    const tabParam = urlParams.get('tab');
    if (tabParam === 'surat-balasan') {
        // Switch to Pemberkasan Instansi Mitra tab
        showTab('surat-balasan');
    }
});

// Custom Modal Functions
function showModal(title, message, type = 'info', onConfirm = null) {
    const modal = document.getElementById('customModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalMessage = document.getElementById('modalMessage');
    const modalIcon = document.getElementById('modalIcon');
    const confirmBtn = document.getElementById('modalConfirmBtn');
    const cancelBtn = document.getElementById('modalCancelBtn');

    modalTitle.textContent = title;
    modalMessage.textContent = message;

    // Set icon and colors based on type
    if (type === 'confirm') {
        modalIcon.className = 'fas fa-question-circle text-5xl text-blue-500 mb-4';
        confirmBtn.className = 'bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-6 rounded-lg transition-all duration-200';
        confirmBtn.textContent = 'Ya, Lanjutkan';
        cancelBtn.style.display = 'inline-block';
    } else if (type === 'success') {
        modalIcon.className = 'fas fa-check-circle text-5xl text-green-500 mb-4';
        confirmBtn.className = 'bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 px-6 rounded-lg transition-all duration-200';
        confirmBtn.textContent = 'OK';
        cancelBtn.style.display = 'none';
    } else if (type === 'error') {
        modalIcon.className = 'fas fa-times-circle text-5xl text-red-500 mb-4';
        confirmBtn.className = 'bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 px-6 rounded-lg transition-all duration-200';
        confirmBtn.textContent = 'OK';
        cancelBtn.style.display = 'none';
    } else {
        modalIcon.className = 'fas fa-info-circle text-5xl text-blue-500 mb-4';
        confirmBtn.className = 'bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-6 rounded-lg transition-all duration-200';
        confirmBtn.textContent = 'OK';
        cancelBtn.style.display = 'none';
    }

    // Store callback
    window.modalConfirmCallback = onConfirm;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeModal() {
    const modal = document.getElementById('customModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    window.modalConfirmCallback = null;
}

function confirmModal() {
    if (window.modalConfirmCallback) {
        window.modalConfirmCallback();
    }
    closeModal();
}

// Function to activate PKL status
function activatePklStatus() {
    showModal(
        'Konfirmasi Aktivasi',
        'Apakah Anda yakin ingin mengaktifkan status Aktif PKL? Status ini akan menandakan bahwa Anda telah memulai kegiatan PKL.',
        'confirm',
        () => {
            fetch('{{ route("documents.activate-pkl-status") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showModal('Berhasil', data.message, 'success', () => {
                        window.location.reload();
                    });
                } else {
                    showModal('Gagal', data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showModal('Error', 'Terjadi kesalahan saat mengaktifkan status PKL.', 'error');
            });
        }
    );
}

// Function to deactivate PKL status
function deactivatePklStatus() {
    showPklOptionsModal();
}

// Function to show PKL options modal
function showPklOptionsModal() {
    const modal = document.getElementById('pklOptionsModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

// Function to close PKL options modal
function closePklOptionsModal() {
    const modal = document.getElementById('pklOptionsModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Function to handle stop PKL status
function stopPklStatus() {
    closePklOptionsModal();
    showModal(
        'Konfirmasi Penghentian',
        'Apakah Anda yakin ingin menghentikan status Aktif PKL? Status akan kembali ke "Siap untuk PKL".',
        'confirm',
        () => {
            fetch('{{ route("documents.deactivate-pkl-status") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showModal('Berhasil', data.message, 'success', () => {
                        window.location.reload();
                    });
                } else {
                    showModal('Gagal', data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showModal('Error', 'Terjadi kesalahan saat menghentikan status PKL.', 'error');
            });
        }
    );
}

// Function to handle complete PKL
function completePklStatus() {
    closePklOptionsModal();
    showModal(
        'Konfirmasi Menyelesaikan PKL',
        'Apakah Anda yakin ingin menyelesaikan PKL?',
        'confirm',
        () => {
            fetch('{{ route("documents.complete-pkl-status") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showModal('Selamat!', 'Anda telah menyelesaikan PKL. Silahkan lanjut ke tahap pemberkasan akhir.', 'success', () => {
                        window.location.reload();
                    });
                } else {
                    showModal('Gagal', data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showModal('Error', 'Terjadi kesalahan saat menyelesaikan PKL.', 'error');
            });
        }
    );
}

// Function to revert PKL status back to aktif
function revertPklStatus() {
    showModal(
        'Konfirmasi Kembali ke Status Aktif',
        'Apakah Anda yakin ingin mengubah status PKL kembali ke "Aktif PKL"?',
        'confirm',
        () => {
            fetch('{{ route("documents.revert-pkl-status") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showModal('Berhasil', data.message, 'success', () => {
                        window.location.reload();
                    });
                } else {
                    showModal('Gagal', data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showModal('Error', 'Terjadi kesalahan saat mengubah status PKL.', 'error');
            });
        }
    );
}

</script>

<!-- PKL Options Modal -->
<div id="pklOptionsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-2xl p-8 max-w-lg w-full mx-4 transform transition-all">
        <div class="text-center">
            <i class="fas fa-question-circle text-5xl text-blue-500 mb-4"></i>
            <h3 class="text-xl font-bold text-gray-900 mb-3">Pilih Aksi PKL</h3>
            <p class="text-gray-600 mb-6">Pilih salah satu opsi di bawah ini:</p>
            <div class="space-y-3">
                <button onclick="stopPklStatus()" class="w-full bg-gray-600 hover:bg-gray-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 flex items-center justify-center">
                    <i class="fas fa-stop-circle mr-2"></i>
                    Hentikan Status PKL
                </button>
                <button onclick="completePklStatus()" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 flex items-center justify-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    Menyelesaikan PKL
                </button>
                <button onclick="closePklOptionsModal()" class="w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-3 px-6 rounded-lg transition-all duration-200">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Custom Modal -->
<div id="customModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-2xl p-8 max-w-md w-full mx-4 transform transition-all">
        <div class="text-center">
            <i id="modalIcon" class="fas fa-info-circle text-5xl text-blue-500 mb-4"></i>
            <h3 id="modalTitle" class="text-xl font-bold text-gray-900 mb-3"></h3>
            <p id="modalMessage" class="text-gray-600 mb-6"></p>
            <div class="flex justify-center space-x-3">
                <button id="modalCancelBtn" onclick="closeModal()" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2.5 px-6 rounded-lg transition-all duration-200">
                    Batal
                </button>
                <button id="modalConfirmBtn" onclick="confirmModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-6 rounded-lg transition-all duration-200">
                    OK
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Requirements Not Checked Modal -->
@if(!($requirementsChecked ?? true))
<div id="requirementsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-2xl p-8 max-w-md w-full mx-4 transform transition-all">
        <div class="text-center">
            <i class="fas fa-exclamation-triangle text-5xl text-yellow-500 mb-4"></i>
            <h3 class="text-xl font-bold text-gray-900 mb-3">Perhatian</h3>
            <p class="text-gray-600 mb-6">Silahkan setujui persyaratan PKL terlebih dahulu sebelum melanjutkan pemberkasan.</p>
            <div class="flex justify-center">
                <a href="{{ route('profile.edit') }}#konfirmasi-persyaratan" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-6 rounded-lg transition-all duration-200">
                    <i class="fas fa-edit mr-2"></i>Lengkapi Persyaratan
                </a>
            </div>
        </div>
    </div>
</div>
@endif
@endsection