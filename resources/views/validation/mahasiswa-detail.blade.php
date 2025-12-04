@extends('layouts.app')

@section('title', 'Detail Pemberkasan Mahasiswa - SIP PKL')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 shadow-lg rounded-xl p-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-5">
                @if($mahasiswa->photo)
                    @if($mahasiswa->google_linked)
                        @php
                            $photoUrl = $mahasiswa->photo;
                            if (str_contains($photoUrl, 'googleusercontent.com')) {
                                $photoUrl = preg_replace('/=s\d+-c/', '', $photoUrl);
                                $photoUrl .= '=s96-c';
                            }
                        @endphp
                        <img src="{{ $photoUrl }}"
                             alt="{{ $mahasiswa->name }}"
                             class="h-20 w-20 rounded-full object-cover border-4 border-white shadow-lg"
                             referrerpolicy="no-referrer"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="h-20 w-20 rounded-full bg-white/20 backdrop-blur-sm items-center justify-center border-4 border-white shadow-lg" style="display: none;">
                            <i class="fas fa-user text-3xl text-white"></i>
                        </div>
                    @else
                        <img src="{{ asset('storage/' . $mahasiswa->photo) }}"
                             alt="{{ $mahasiswa->name }}"
                             class="h-20 w-20 rounded-full object-cover border-4 border-white shadow-lg"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="h-20 w-20 rounded-full bg-white/20 backdrop-blur-sm items-center justify-center border-4 border-white shadow-lg" style="display: none;">
                            <i class="fas fa-user text-3xl text-white"></i>
                        </div>
                    @endif
                @else
                    <div class="h-20 w-20 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border-4 border-white shadow-lg">
                        <i class="fas fa-user text-3xl text-white"></i>
                    </div>
                @endif
                <div>
                    <h1 class="text-3xl font-bold text-white">{{ $mahasiswa->name }}</h1>
                    <p class="text-blue-100 mt-2 flex items-center text-lg">
                        <i class="fas fa-id-card mr-2"></i>
                        <span class="font-semibold">NIM:</span>
                        <span class="ml-2">{{ $mahasiswa->profilMahasiswa->nim ?? 'N/A' }}</span>
                    </p>
                    <p class="text-blue-100 flex items-center mt-1">
                        <i class="fas fa-graduation-cap mr-2"></i>
                        {{ $mahasiswa->profilMahasiswa->prodi ?? 'N/A' }} - Semester {{ $mahasiswa->profilMahasiswa->semester ?? '-' }}
                    </p>
                </div>
            </div>
            <a href="{{ route('dospem.validation') }}" class="inline-flex items-center px-5 py-3 bg-white text-blue-700 rounded-lg hover:bg-blue-50 transition shadow-md font-medium">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>
    </div>

    <!-- Academic Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-5">
        <!-- IPK Card -->
        @php
            // Cek apakah IPK Profile dan KHS sama
            $ipkSame = $ipkFromProfile > 0 && $ipkFromTranskrip > 0 &&
                       number_format($ipkFromProfile, 2) === number_format($ipkFromTranskrip, 2);
        @endphp
        <div class="bg-white overflow-hidden shadow-md rounded-xl border border-gray-100 hover:shadow-lg transition-shadow duration-300">
            <div class="p-5">
                <div class="flex flex-col">
                    <div class="flex items-center justify-between mb-3">
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide">IPK</dt>
                        <div class="h-10 w-10 rounded-lg bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-chart-line text-lg text-blue-600"></i>
                        </div>
                    </div>
                    @if($ipkSame)
                        <dd class="text-2xl font-bold {{ $ipkFromProfile >= 3.0 ? 'text-green-600' : 'text-orange-600' }}">
                            {{ number_format($ipkFromProfile, 2) }}
                        </dd>
                    @else
                        <dd class="space-y-1">
                            <div class="text-sm font-medium text-gray-500">Profil:</div>
                            <div class="text-xl font-bold {{ $ipkFromProfile >= 3.0 ? 'text-green-600' : 'text-orange-600' }}">
                                {{ $ipkFromProfile > 0 ? number_format($ipkFromProfile, 2) : '-' }}
                            </div>
                            <div class="text-sm font-medium text-gray-500 mt-2">KHS:</div>
                            <div id="ipkKhsCard" class="text-xl font-bold {{ $ipkFromTranskrip >= 3.0 ? 'text-green-600' : 'text-orange-600' }}">
                                {{ $ipkFromTranskrip > 0 ? number_format($ipkFromTranskrip, 2) : '-' }}
                            </div>
                        </dd>
                    @endif
                </div>
            </div>
        </div>

        <!-- Kelayakan Card -->
        @php
            $kelayakanStatus = 'Belum';
            $kelayakanColor = 'gray';
            if ($allEligible && $totalSksDCount <= 6 && $totalSksECount == 0) {
                $kelayakanStatus = 'Layak';
                $kelayakanColor = 'green';
            } elseif ($totalSksDCount > 6 || $totalSksECount > 0) {
                $kelayakanStatus = 'Tidak Layak';
                $kelayakanColor = 'red';
            }
        @endphp
        <div class="bg-white overflow-hidden shadow-md rounded-xl border border-gray-100 hover:shadow-lg transition-shadow duration-300">
            <div class="p-5">
                <div class="flex flex-col">
                    <div class="flex items-center justify-between mb-3">
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Kelayakan</dt>
                        <div class="h-10 w-10 rounded-lg bg-{{ $kelayakanColor }}-100 flex items-center justify-center">
                            <i class="fas fa-{{ $kelayakanColor === 'green' ? 'check-circle' : ($kelayakanColor === 'red' ? 'times-circle' : 'clock') }} text-lg text-{{ $kelayakanColor }}-600"></i>
                        </div>
                    </div>
                    <dd class="text-2xl font-bold text-{{ $kelayakanColor }}-600">
                        {{ $kelayakanStatus }}
                    </dd>
                </div>
            </div>
        </div>

        <!-- SKS D dan E Card -->
        <div class="bg-white overflow-hidden shadow-md rounded-xl border border-gray-100 hover:shadow-lg transition-shadow duration-300">
            <div class="p-5">
                <div class="flex flex-col">
                    <div class="flex items-center justify-between mb-3">
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide">SKS D & E</dt>
                        <div class="h-10 w-10 rounded-lg bg-{{ ($totalSksDCount > 0 || $totalSksECount > 0) ? 'yellow' : 'green' }}-100 flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-lg {{ ($totalSksDCount > 0 || $totalSksECount > 0) ? 'text-yellow-600' : 'text-green-600' }}"></i>
                        </div>
                    </div>
                    <dd class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">SKS D:</span>
                            <span id="sksDCard" class="text-xl font-bold {{ $totalSksDCount > 0 ? 'text-yellow-600' : 'text-green-600' }}">
                                {{ $totalSksDCount }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">SKS E:</span>
                            <span id="sksECard" class="text-xl font-bold {{ $totalSksECount > 0 ? 'text-red-600' : 'text-green-600' }}">
                                {{ $totalSksECount }}
                            </span>
                        </div>
                    </dd>
                </div>
            </div>
        </div>

        <!-- Status PKL Card -->
        @php
            $dbStatusPkl = $mahasiswa->profilMahasiswa->status_pkl ?? 'siap';

            if ($dbStatusPkl === 'selesai') {
                $pklStatus = 'Selesai PKL';
                $pklColor = 'green';
                $pklIcon = 'fa-check-circle';
            } elseif ($dbStatusPkl === 'aktif') {
                $pklStatus = 'Aktif PKL';
                $pklColor = 'blue';
                $pklIcon = 'fa-building';
            } else {
                $pklStatus = 'Siap PKL';
                $pklColor = 'yellow';
                $pklIcon = 'fa-clipboard-check';
            }
        @endphp
        <div class="bg-white overflow-hidden shadow-md rounded-xl border border-gray-100 hover:shadow-lg transition-shadow duration-300">
            <div class="p-5">
                <div class="flex flex-col">
                    <div class="flex items-center justify-between mb-3">
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Status PKL</dt>
                        <div class="h-10 w-10 rounded-lg bg-{{ $pklColor }}-100 flex items-center justify-center">
                            <i class="fas {{ $pklIcon }} text-lg text-{{ $pklColor }}-600"></i>
                        </div>
                    </div>
                    <dd class="text-lg font-bold text-{{ $pklColor }}-600">
                        {{ $pklStatus }}
                    </dd>
                </div>
            </div>
        </div>

        <!-- Jumlah Ditolak Card -->
        <div class="bg-white overflow-hidden shadow-md rounded-xl border border-gray-100 hover:shadow-lg transition-shadow duration-300">
            <div class="p-5">
                <div class="flex flex-col">
                    <div class="flex items-center justify-between mb-3">
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Ditolak Mitra</dt>
                        <div class="h-10 w-10 rounded-lg bg-{{ $jumlahDitolak > 0 ? 'red' : 'gray' }}-100 flex items-center justify-center">
                            <i class="fas fa-ban text-lg {{ $jumlahDitolak > 0 ? 'text-red-600' : 'text-gray-400' }}"></i>
                        </div>
                    </div>
                    <dd class="text-2xl font-bold {{ $jumlahDitolak > 0 ? 'text-red-600' : 'text-gray-600' }}">
                        {{ $jumlahDitolak }}
                    </dd>
                </div>
            </div>
        </div>
    </div>

    <!-- Validation Actions for 4 Categories -->
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Validasi Pemberkasan</h2>
        <p class="text-sm text-gray-600 mb-6">Validasi setiap kategori pemberkasan mahasiswa</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- 1. Pemberkasan Kelayakan -->
            <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center space-x-3">
                        <div class="h-12 w-12 rounded-lg bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-file-alt text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-medium text-gray-900">Pemberkasan Kelayakan</h3>
                            <p class="text-sm text-gray-500 mt-0.5">KHS Files + Transkrip Manual</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $validationStatus['kelayakan']['color'] }}-100 text-{{ $validationStatus['kelayakan']['color'] }}-800">
                        {{ $validationStatus['kelayakan']['label'] }}
                    </span>
                    <button onclick="openValidationModal('kelayakan')"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-check-circle mr-2"></i> Validasi
                    </button>
                </div>
            </div>

            <!-- 2. Pemberkasan Dokumen Pendukung -->
            <div class="border border-gray-200 rounded-lg p-4 hover:border-green-300 transition">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center space-x-3">
                        <div class="h-12 w-12 rounded-lg bg-green-100 flex items-center justify-center">
                            <i class="fab fa-google-drive text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-medium text-gray-900">Pemberkasan Dokumen Pendukung</h3>
                            <p class="text-sm text-gray-500 mt-0.5">Sertifikat Google Drive</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $validationStatus['dokumen_pendukung']['color'] }}-100 text-{{ $validationStatus['dokumen_pendukung']['color'] }}-800">
                        {{ $validationStatus['dokumen_pendukung']['label'] }}
                    </span>
                    <button onclick="openValidationModal('dokumen_pendukung')"
                            class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition">
                        <i class="fas fa-check-circle mr-2"></i> Validasi
                    </button>
                </div>
            </div>

            <!-- 3. Pemberkasan Instansi Mitra -->
            <div class="border border-gray-200 rounded-lg p-4 hover:border-purple-300 transition">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center space-x-3">
                        <div class="h-12 w-12 rounded-lg bg-purple-100 flex items-center justify-center">
                            <i class="fas fa-building text-purple-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-medium text-gray-900">Pemberkasan Instansi Mitra</h3>
                            <p class="text-sm text-gray-500 mt-0.5">Surat Balasan</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $validationStatus['instansi_mitra']['color'] }}-100 text-{{ $validationStatus['instansi_mitra']['color'] }}-800">
                        {{ $validationStatus['instansi_mitra']['label'] }}
                    </span>
                    <button onclick="openValidationModal('instansi_mitra')"
                            class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm rounded-lg hover:bg-purple-700 transition">
                        <i class="fas fa-check-circle mr-2"></i> Validasi
                    </button>
                </div>
            </div>

            <!-- 4. Pemberkasan Akhir -->
            <div class="border border-gray-200 rounded-lg p-4 hover:border-orange-300 transition">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center space-x-3">
                        <div class="h-12 w-12 rounded-lg bg-orange-100 flex items-center justify-center">
                            <i class="fas fa-file-pdf text-orange-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-medium text-gray-900">Pemberkasan Akhir</h3>
                            <p class="text-sm text-gray-500 mt-0.5">Laporan PKL</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $validationStatus['akhir']['color'] }}-100 text-{{ $validationStatus['akhir']['color'] }}-800">
                        {{ $validationStatus['akhir']['label'] }}
                    </span>
                    <button onclick="openValidationModal('akhir')"
                            class="inline-flex items-center px-4 py-2 bg-orange-600 text-white text-sm rounded-lg hover:bg-orange-700 transition">
                        <i class="fas fa-check-circle mr-2"></i> Validasi
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex overflow-x-auto" aria-label="Tabs">
                <button onclick="showTab('biodata')" id="tab-biodata" class="tab-button border-blue-500 text-blue-600 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                    <i class="fas fa-user mr-2"></i>Biodata
                </button>
                <button onclick="showTab('khs-files')" id="tab-khs-files" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                    <i class="fas fa-file-pdf mr-2"></i>KHS Files
                </button>
                <button onclick="showTab('khs-transkrip')" id="tab-khs-transkrip" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                    <i class="fas fa-table mr-2"></i>KHS Transkrip Data
                </button>
                <button onclick="showTab('gdrive')" id="tab-gdrive" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                    <i class="fab fa-google-drive mr-2"></i>Dokumen Pendukung
                </button>
                <button onclick="showTab('mitra')" id="tab-mitra" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                    <i class="fas fa-building mr-2"></i>Instansi Mitra & Surat Balasan
                </button>
                <button onclick="showTab('laporan')" id="tab-laporan" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                    <i class="fas fa-file-alt mr-2"></i>Laporan PKL
                </button>
            </nav>
        </div>

        <!-- Tab Contents -->
        <div class="p-6">
            <!-- Biodata Tab -->
            <div id="content-biodata" class="tab-content">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Biodata Lengkap Mahasiswa</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Nama Lengkap</label>
                            <p class="mt-1 text-base text-gray-900">{{ $mahasiswa->name }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">NIM</label>
                            <p class="mt-1 text-base text-gray-900">{{ $mahasiswa->profilMahasiswa->nim ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Email</label>
                            <p class="mt-1 text-base text-gray-900">{{ $mahasiswa->email }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Program Studi</label>
                            <p class="mt-1 text-base text-gray-900">{{ $mahasiswa->profilMahasiswa->prodi ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Semester</label>
                            <p class="mt-1 text-base text-gray-900">{{ $mahasiswa->profilMahasiswa->semester ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Jenis Kelamin</label>
                            <p class="mt-1 text-base text-gray-900">{{ $mahasiswa->profilMahasiswa->jenis_kelamin ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">No. WhatsApp</label>
                            <p class="mt-1 text-base text-gray-900">{{ $mahasiswa->profilMahasiswa->no_whatsapp ? '+62 ' . $mahasiswa->profilMahasiswa->no_whatsapp : 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Dosen Pembimbing</label>
                            <p class="mt-1 text-base text-gray-900">{{ $mahasiswa->profilMahasiswa->dosenPembimbing->name ?? 'Belum ditentukan' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">IPK (dari Profil)</label>
                            <p class="mt-1 text-base font-semibold {{ ($mahasiswa->profilMahasiswa->ipk ?? 0) >= 3.0 ? 'text-green-600' : 'text-orange-600' }}">
                                {{ $mahasiswa->profilMahasiswa->ipk ? number_format($mahasiswa->profilMahasiswa->ipk, 2) : 'N/A' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="text-base font-medium text-gray-900 mb-3">Konfirmasi Persyaratan PKL</h3>
                    <div class="flex flex-wrap gap-2">
                        @if($mahasiswa->profilMahasiswa->cek_valid_biodata)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-2"></i> Biodata Valid
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-2"></i> Biodata Belum Valid
                            </span>
                        @endif

                        @if($mahasiswa->profilMahasiswa->cek_ipk_nilaisks)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-2"></i> IPK & Nilai Memenuhi
                            </span>
                        @endif

                        @if($mahasiswa->profilMahasiswa->cek_min_semester)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-2"></i> Semester Minimum Terpenuhi
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- KHS Files Tab -->
            <div id="content-khs-files" class="tab-content hidden">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">File KHS (Kartu Hasil Studi)</h2>
                <p class="text-sm text-gray-600 mb-4">Total file KHS yang telah diupload: {{ $khsFilesCount }}/4 semester</p>

                @if($mahasiswa->khs->count() > 0)
                    <div class="space-y-4">
                        @foreach($mahasiswa->khs->sortBy('semester')->take(4) as $khs)
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="h-12 w-12 rounded-lg bg-red-100 flex items-center justify-center">
                                        <i class="fas fa-file-pdf text-red-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-base font-medium text-gray-900">KHS Semester {{ $khs->semester ?? 'N/A' }}</h3>
                                        <p class="text-sm text-gray-500 mt-1">{{ basename($khs->file_path) }}</p>
                                        <p class="text-xs text-gray-400 mt-1">Diupload: {{ $khs->created_at->format('d M Y, H:i') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    @if($khs->status_validasi === 'tervalidasi')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i> Tervalidasi
                                        </span>
                                    @elseif($khs->status_validasi === 'menunggu')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i> Menunggu
                                        </span>
                                    @elseif($khs->status_validasi === 'revisi')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                            <i class="fas fa-redo mr-1"></i> Revisi
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times mr-1"></i> Belum Valid
                                        </span>
                                    @endif

                                    <a href="{{ route('dospem.mahasiswa.preview', ['mahasiswaId' => $mahasiswa->id, 'type' => 'khs', 'filename' => basename($khs->file_path)]) }}" target="_blank"
                                       class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">
                                        <i class="fas fa-eye mr-2"></i> Lihat PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
                        <p class="text-gray-500">Belum ada file KHS yang diupload</p>
                    </div>
                @endif
            </div>

            <!-- KHS Transkrip Data Tab -->
            <div id="content-khs-transkrip" class="tab-content hidden">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Data Transkrip KHS Manual dari SIPADU</h2>
                <p class="text-sm text-gray-600 mb-4">Data transkrip yang diinput mahasiswa per semester ({{ $totalSemesters }}/4 semester)</p>

                @if($mahasiswa->khsManualTranskrip->count() > 0)
                    <!-- Semester Tabs -->
                    <div class="mb-4">
                        <div class="border-b border-gray-200">
                            <nav class="-mb-px flex space-x-4" aria-label="Tabs Semester">
                                @foreach($mahasiswa->khsManualTranskrip->sortBy('semester')->take(4) as $index => $transkrip)
                                    <button onclick="showSemesterTab({{ $transkrip->semester }})"
                                            id="semester-tab-{{ $transkrip->semester }}"
                                            class="semester-tab-button {{ $index === 0 ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-3 px-4 border-b-2 font-medium text-sm">
                                        <i class="fas fa-circle-{{ $transkrip->semester }} mr-2"></i>Semester {{ $transkrip->semester }}
                                    </button>
                                @endforeach
                            </nav>
                        </div>
                    </div>

                    <!-- Semester Content -->
                    <div class="semester-tab-contents">
                        @foreach($mahasiswa->khsManualTranskrip->sortBy('semester')->take(4) as $index => $transkrip)
                        <div id="semester-content-{{ $transkrip->semester }}"
                             class="semester-tab-content transition-opacity duration-300 {{ $index !== 0 ? 'hidden' : '' }}">
                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                <div class="p-4">
                                    @if($transkrip->transcript_data)
                                        <!-- Tabel Hasil -->
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                <i class="fas fa-table mr-2 text-green-600"></i>
                                                Tabel Hasil
                                            </label>
                                            <div id="tableDisplay{{ $transkrip->semester }}" class="border border-gray-200 rounded-lg bg-white p-3">
                                                <!-- Table will be rendered here by JavaScript -->
                                            </div>
                                        </div>

                                        <!-- Hasil Analisis Semester -->
                                        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                                            <div class="bg-purple-50 px-3 py-2 border-b border-gray-200">
                                                <h5 class="text-sm font-medium text-purple-800">Hasil Analisis Semester {{ $transkrip->semester }}</h5>
                                            </div>
                                            <div class="p-3">
                                                <div class="grid grid-cols-2 gap-2 text-xs">
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-600">IPS:</span>
                                                        <span id="ips{{ $transkrip->semester }}" class="font-medium text-blue-600">-</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-600">SKS D:</span>
                                                        <span id="sksD{{ $transkrip->semester }}" class="font-medium text-green-600">-</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-600">Ada E:</span>
                                                        <span id="hasE{{ $transkrip->semester }}" class="font-medium text-green-600">-</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-600">Total SKS:</span>
                                                        <span id="totalSks{{ $transkrip->semester }}" class="font-medium text-blue-600">-</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Hidden transcript data for JavaScript -->
                                        <input type="hidden" id="transcriptData{{ $transkrip->semester }}" value="{{ base64_encode($transkrip->transcript_data) }}">
                                    @else
                                        <p class="text-sm text-gray-400 italic">Tidak ada data transkrip</p>
                                    @endif
                                    <p class="text-xs text-gray-400 mt-3">Diinput: {{ $transkrip->created_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Hasil Analisa Kelayakan -->
                    <div class="mt-6 bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-center mb-4">
                            <i class="fas fa-chart-bar text-green-600 text-xl mr-2"></i>
                            <h3 class="text-base font-semibold text-green-900">Hasil Analisa Kelayakan</h3>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            <!-- IPK Akhir -->
                            <div class="bg-white rounded-lg p-4 text-center shadow-sm">
                                <p id="ipkAkhir" class="text-2xl font-bold text-gray-600">-</p>
                                <p class="text-sm text-gray-600 mt-1">IPK Akhir</p>
                            </div>

                            <!-- Kelengkapan Transkrip -->
                            <div class="bg-white rounded-lg p-4 text-center shadow-sm">
                                <p class="text-2xl font-bold {{ $totalSemesters >= 5 ? 'text-blue-600' : 'text-orange-600' }}">
                                    {{ $totalSemesters }}/5
                                </p>
                                <p class="text-sm text-gray-600 mt-1">Kelengkapan Transkrip</p>
                            </div>

                            <!-- Status PKL -->
                            <div class="bg-white rounded-lg p-4 text-center shadow-sm">
                                <p id="statusPKL" class="text-xl font-bold text-gray-600">-</p>
                                <p class="text-sm text-gray-600 mt-1">Status PKL</p>
                            </div>

                            <!-- Total SKS D -->
                            <div class="bg-white rounded-lg p-4 text-center shadow-sm">
                                <p id="totalSksDSummary" class="text-2xl font-bold text-gray-600">-</p>
                                <p class="text-sm text-gray-600 mt-1">Total SKS D</p>
                            </div>

                            <!-- Jumlah E -->
                            <div class="bg-white rounded-lg p-4 text-center shadow-sm">
                                <p id="totalESummary" class="text-2xl font-bold text-gray-600">-</p>
                                <p class="text-sm text-gray-600 mt-1">Jumlah E</p>
                            </div>

                            <!-- Upload Berkas KHS -->
                            <div class="bg-white rounded-lg p-4 text-center shadow-sm">
                                <p class="text-2xl font-bold {{ $khsFilesCount >= 5 ? 'text-purple-600' : 'text-orange-600' }}">
                                    {{ $khsFilesCount }}/5
                                </p>
                                <p class="text-sm text-gray-600 mt-1">Upload Berkas KHS</p>
                            </div>
                        </div>

                        <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <p class="text-xs text-blue-800">
                                <i class="fas fa-info-circle mr-1"></i>
                                Data yang dimasukkan dapat dipertanggung jawabkan keasliannya dan menerima konsekuensi jika data dan berkas yang dimasukkan tidak tepat
                            </p>
                        </div>
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-table text-gray-300 text-5xl mb-4"></i>
                        <p class="text-gray-500">Belum ada data transkrip manual yang diinput</p>
                    </div>
                @endif
            </div>

            <!-- Google Drive Links Tab -->
            <div id="content-gdrive" class="tab-content hidden">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Dokumen Pendukung (Google Drive)</h2>
                <p class="text-sm text-gray-600 mb-6">Link dokumen pendukung yang diupload ke Google Drive</p>

                <div class="space-y-4">
                    <!-- PKKMB Certificate -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="h-10 w-10 rounded-lg bg-green-100 flex items-center justify-center">
                                    <i class="fab fa-google-drive text-green-600 text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900">Sertifikat PKKMB</h3>
                                    <p class="text-xs text-gray-500 mt-0.5">Sertifikat Pengenalan Kehidupan Kampus</p>
                                </div>
                            </div>
                            @if($gdrive['pkkmb'])
                                <a href="{{ $gdrive['pkkmb'] }}" target="_blank"
                                   class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition">
                                    <i class="fas fa-external-link-alt mr-2"></i> Buka Link
                                </a>
                            @else
                                <span class="text-sm text-gray-400 italic">Belum diupload</span>
                            @endif
                        </div>
                        @if($gdrive['pkkmb'])
                            <div class="mt-3 bg-gray-50 rounded p-2">
                                <p class="text-xs text-gray-600 break-all">{{ $gdrive['pkkmb'] }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- E-Course Certificate -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="h-10 w-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                    <i class="fab fa-google-drive text-blue-600 text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900">Sertifikat E-Course Bahasa Inggris</h3>
                                    <p class="text-xs text-gray-500 mt-0.5">Sertifikat Kursus Bahasa Inggris Online</p>
                                </div>
                            </div>
                            @if($gdrive['ecourse'])
                                <a href="{{ $gdrive['ecourse'] }}" target="_blank"
                                   class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">
                                    <i class="fas fa-external-link-alt mr-2"></i> Buka Link
                                </a>
                            @else
                                <span class="text-sm text-gray-400 italic">Belum diupload</span>
                            @endif
                        </div>
                        @if($gdrive['ecourse'])
                            <div class="mt-3 bg-gray-50 rounded p-2">
                                <p class="text-xs text-gray-600 break-all">{{ $gdrive['ecourse'] }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Other Certificates -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="h-10 w-10 rounded-lg bg-purple-100 flex items-center justify-center">
                                    <i class="fab fa-google-drive text-purple-600 text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900">Sertifikat Tambahan Lainnya</h3>
                                    <p class="text-xs text-gray-500 mt-0.5">Sertifikat pendukung lainnya</p>
                                </div>
                            </div>
                            @if($gdrive['more'])
                                <a href="{{ $gdrive['more'] }}" target="_blank"
                                   class="inline-flex items-center px-3 py-1.5 bg-purple-600 text-white text-sm rounded-lg hover:bg-purple-700 transition">
                                    <i class="fas fa-external-link-alt mr-2"></i> Buka Link
                                </a>
                            @else
                                <span class="text-sm text-gray-400 italic">Belum diupload</span>
                            @endif
                        </div>
                        @if($gdrive['more'])
                            <div class="mt-3 bg-gray-50 rounded p-2">
                                <p class="text-xs text-gray-600 break-all">{{ $gdrive['more'] }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                @if(!$gdrive['pkkmb'] && !$gdrive['ecourse'] && !$gdrive['more'])
                    <div class="text-center py-12">
                        <i class="fab fa-google-drive text-gray-300 text-5xl mb-4"></i>
                        <p class="text-gray-500">Belum ada dokumen pendukung yang diupload</p>
                    </div>
                @endif
            </div>

            <!-- Instansi Mitra Tab -->
            <div id="content-mitra" class="tab-content hidden">
                <div class="space-y-6">
                    <!-- Surat Pengantar -->
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Surat Pengantar</h2>
                        <p class="text-sm text-gray-600 mb-4">Surat pengantar dari kampus untuk instansi mitra</p>

                        @if($suratPengantar)
                            <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <div class="h-12 w-12 rounded-lg bg-red-100 flex items-center justify-center">
                                            <i class="fas fa-file-pdf text-red-600 text-xl"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-base font-medium text-gray-900">Surat Pengantar</h3>
                                            <p class="text-sm text-gray-500 mt-1">{{ basename($suratPengantar->file_path) }}</p>
                                            <p class="text-xs text-gray-400 mt-1">Diupload: {{ $suratPengantar->created_at->format('d M Y, H:i') }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        @if($suratPengantar->status_validasi === 'tervalidasi')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check mr-1"></i> Tervalidasi
                                            </span>
                                        @elseif($suratPengantar->status_validasi === 'menunggu')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-clock mr-1"></i> Menunggu
                                            </span>
                                        @elseif($suratPengantar->status_validasi === 'revisi')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                <i class="fas fa-redo mr-1"></i> Revisi
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-times mr-1"></i> Belum Valid
                                            </span>
                                        @endif

                                        <a href="{{ route('dospem.mahasiswa.preview', ['mahasiswaId' => $mahasiswa->id, 'type' => 'surat-pengantar', 'filename' => basename($suratPengantar->file_path)]) }}" target="_blank"
                                           class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">
                                            <i class="fas fa-eye mr-2"></i> Lihat PDF
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-12 border border-gray-200 rounded-lg">
                                <i class="fas fa-file text-gray-300 text-5xl mb-4"></i>
                                <p class="text-gray-500">Mahasiswa belum upload surat pengantar</p>
                            </div>
                        @endif
                    </div>

                    <!-- Instansi Mitra yang Dipilih -->
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Instansi Mitra yang Dipilih</h2>
                        <p class="text-sm text-gray-600 mb-4">Informasi instansi mitra tempat PKL mahasiswa</p>

                        @if($mahasiswa->profilMahasiswa && $mahasiswa->profilMahasiswa->mitraSelected)
                            @php $mitra = $mahasiswa->profilMahasiswa->mitraSelected; @endphp
                            <div class="border border-gray-200 rounded-lg p-6 bg-blue-50">
                                <div class="flex items-start space-x-4">
                                    <div class="h-16 w-16 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-building text-blue-600 text-2xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-xl font-semibold text-gray-900">{{ $mitra->nama }}</h3>
                                        <div class="mt-4 space-y-3">
                                            <div class="flex items-start">
                                                <i class="fas fa-map-marker-alt text-gray-400 mt-1 mr-3"></i>
                                                <p class="text-sm text-gray-600">{{ $mitra->alamat ?? 'Alamat tidak tersedia' }}</p>
                                            </div>
                                            <div class="flex items-center">
                                                <i class="fas fa-phone text-gray-400 mr-3"></i>
                                                <p class="text-sm text-gray-600">{{ $mitra->kontak ?? 'Kontak tidak tersedia' }}</p>
                                            </div>
                                            <div class="flex items-center">
                                                <i class="fas fa-road text-gray-400 mr-3"></i>
                                                <p class="text-sm text-gray-600">Jarak: <span class="font-semibold">{{ $mitra->jarak }} km</span></p>
                                            </div>
                                        </div>

                                        <!-- SAW Criteria -->
                                        <div class="mt-6 pt-6 border-t border-gray-200">
                                            <h4 class="text-sm font-semibold text-gray-700 mb-3">Kriteria Penilaian SAW</h4>
                                            <div class="grid grid-cols-2 gap-3">
                                                <div class="flex items-center justify-between p-3 bg-white rounded-lg">
                                                    <span class="text-sm text-gray-600">Honor</span>
                                                    <span class="text-sm font-semibold {{ $mitra->honor >= 4 ? 'text-green-600' : ($mitra->honor >= 3 ? 'text-blue-600' : 'text-orange-600') }}">
                                                        {{ $mitra->honor_label }}
                                                    </span>
                                                </div>
                                                <div class="flex items-center justify-between p-3 bg-white rounded-lg">
                                                    <span class="text-sm text-gray-600">Fasilitas</span>
                                                    <span class="text-sm font-semibold {{ $mitra->fasilitas >= 4 ? 'text-green-600' : ($mitra->fasilitas >= 3 ? 'text-blue-600' : 'text-orange-600') }}">
                                                        {{ $mitra->fasilitas_label }}
                                                    </span>
                                                </div>
                                                <div class="flex items-center justify-between p-3 bg-white rounded-lg">
                                                    <span class="text-sm text-gray-600">Kesesuaian Jurusan</span>
                                                    <span class="text-sm font-semibold {{ $mitra->kesesuaian_jurusan >= 4 ? 'text-green-600' : ($mitra->kesesuaian_jurusan >= 3 ? 'text-blue-600' : 'text-orange-600') }}">
                                                        {{ $mitra->kesesuaian_jurusan_label }}
                                                    </span>
                                                </div>
                                                <div class="flex items-center justify-between p-3 bg-white rounded-lg">
                                                    <span class="text-sm text-gray-600">Tingkat Kebersihan</span>
                                                    <span class="text-sm font-semibold {{ $mitra->tingkat_kebersihan >= 4 ? 'text-green-600' : ($mitra->tingkat_kebersihan >= 3 ? 'text-blue-600' : 'text-orange-600') }}">
                                                        {{ $mitra->tingkat_kebersihan_label }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-12 border border-gray-200 rounded-lg">
                                <i class="fas fa-building text-gray-300 text-5xl mb-4"></i>
                                <p class="text-gray-500">Mahasiswa belum memilih instansi mitra</p>
                            </div>
                        @endif
                    </div>

                    <!-- Surat Balasan -->
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Surat Balasan dari Instansi Mitra</h2>
                        <p class="text-sm text-gray-600 mb-4">Surat balasan/persetujuan dari perusahaan tempat PKL</p>

                        @if($mahasiswa->suratBalasan->count() > 0)
                            <div class="space-y-4">
                                @foreach($mahasiswa->suratBalasan as $surat)
                                <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <div class="h-12 w-12 rounded-lg bg-blue-100 flex items-center justify-center">
                                                <i class="fas fa-envelope text-blue-600 text-xl"></i>
                                            </div>
                                            <div>
                                                <h3 class="text-base font-medium text-gray-900">Surat Balasan</h3>
                                                <p class="text-sm text-gray-600 mt-1">
                                                    Perusahaan:
                                                    @if($surat->mitra)
                                                        <span class="font-semibold">{{ $surat->mitra->nama }}</span>
                                                    @else
                                                        <span class="font-semibold">{{ $surat->mitra_nama_custom ?? 'N/A' }}</span>
                                                    @endif
                                                </p>
                                                <p class="text-sm text-gray-500 mt-1">{{ basename($surat->file_path) }}</p>
                                                <p class="text-xs text-gray-400 mt-1">Diupload: {{ $surat->created_at->format('d M Y, H:i') }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            @if($surat->status_validasi === 'tervalidasi')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check mr-1"></i> Tervalidasi
                                                </span>
                                            @elseif($surat->status_validasi === 'menunggu')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-clock mr-1"></i> Menunggu
                                                </span>
                                            @elseif($surat->status_validasi === 'revisi')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                    <i class="fas fa-redo mr-1"></i> Revisi
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <i class="fas fa-times mr-1"></i> Belum Valid
                                                </span>
                                            @endif

                                            <a href="{{ route('dospem.mahasiswa.preview', ['mahasiswaId' => $mahasiswa->id, 'type' => 'surat-balasan', 'filename' => basename($surat->file_path)]) }}" target="_blank"
                                               class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">
                                                <i class="fas fa-eye mr-2"></i> Lihat PDF
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12 border border-gray-200 rounded-lg">
                                <i class="fas fa-envelope text-gray-300 text-5xl mb-4"></i>
                                <p class="text-gray-500">Belum ada surat balasan yang diupload</p>
                            </div>
                        @endif
                    </div>

                    <!-- Riwayat Penggantian Mitra -->
                    @if($mahasiswa->profilMahasiswa && $mahasiswa->profilMahasiswa->riwayatPengantianMitra->count() > 0)
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Riwayat Penggantian Instansi Mitra</h2>
                        <p class="text-sm text-gray-600 mb-4">Riwayat perubahan instansi mitra yang dipilih mahasiswa</p>

                        <div class="space-y-4">
                            @foreach($mahasiswa->profilMahasiswa->riwayatPengantianMitra->sortByDesc('created_at') as $riwayat)
                            <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                <div class="flex items-start space-x-4">
                                    <div class="h-10 w-10 rounded-lg bg-yellow-100 flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-exchange-alt text-yellow-600"></i>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between">
                                            <h4 class="text-sm font-semibold text-gray-900">Penggantian Mitra</h4>
                                            <span class="text-xs text-gray-500">{{ $riwayat->created_at->format('d M Y, H:i') }}</span>
                                        </div>
                                        <div class="mt-3 space-y-2">
                                            <div class="flex items-center text-sm">
                                                <i class="fas fa-arrow-right text-gray-400 mr-2"></i>
                                                <span class="text-gray-600">Dari:</span>
                                                <span class="ml-2 font-semibold text-gray-900">
                                                    {{ $riwayat->mitraLama->nama ?? 'Belum ada' }}
                                                </span>
                                            </div>
                                            <div class="flex items-center text-sm">
                                                <i class="fas fa-arrow-right text-gray-400 mr-2"></i>
                                                <span class="text-gray-600">Ke:</span>
                                                <span class="ml-2 font-semibold text-blue-600">
                                                    {{ $riwayat->mitraBaru->nama }}
                                                </span>
                                            </div>
                                            <div class="mt-3 p-3 bg-white border border-gray-200 rounded-lg">
                                                <p class="text-xs font-medium text-gray-500 mb-1">Alasan:</p>
                                                <p class="text-sm text-gray-900 font-semibold">{{ $riwayat->jenis_alasan_label }}</p>
                                                @if($riwayat->alasan_lengkap)
                                                    <p class="text-sm text-gray-600 mt-2 italic">"{{ $riwayat->alasan_lengkap }}"</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Laporan PKL Tab -->
            <div id="content-laporan" class="tab-content hidden">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Laporan PKL</h2>
                <p class="text-sm text-gray-600 mb-6">Laporan akhir Praktik Kerja Lapangan</p>

                @if($mahasiswa->laporanPkl->count() > 0)
                    <div class="space-y-4">
                        @foreach($mahasiswa->laporanPkl as $laporan)
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="h-12 w-12 rounded-lg bg-purple-100 flex items-center justify-center">
                                        <i class="fas fa-file-alt text-purple-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-base font-medium text-gray-900">Laporan PKL</h3>
                                        <p class="text-sm text-gray-500 mt-1">{{ basename($laporan->file_path) }}</p>
                                        <p class="text-xs text-gray-400 mt-1">Diupload: {{ $laporan->created_at->format('d M Y, H:i') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    @if($laporan->status_validasi === 'tervalidasi')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i> Tervalidasi
                                        </span>
                                    @elseif($laporan->status_validasi === 'menunggu')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i> Menunggu
                                        </span>
                                    @elseif($laporan->status_validasi === 'revisi')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                            <i class="fas fa-redo mr-1"></i> Revisi
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times mr-1"></i> Belum Valid
                                        </span>
                                    @endif

                                    <a href="{{ route('dospem.mahasiswa.preview', ['mahasiswaId' => $mahasiswa->id, 'type' => 'laporan', 'filename' => basename($laporan->file_path)]) }}" target="_blank"
                                       class="inline-flex items-center px-3 py-1.5 bg-purple-600 text-white text-sm rounded-lg hover:bg-purple-700 transition">
                                        <i class="fas fa-eye mr-2"></i> Lihat PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-file-alt text-gray-300 text-5xl mb-4"></i>
                        <p class="text-gray-500">Belum ada laporan PKL yang diupload</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Validation Modal -->
<div id="validationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Validasi Pemberkasan</h3>
                <button onclick="closeValidationModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form id="validationForm" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label for="status_validasi" class="block text-sm font-medium text-gray-700 mb-2">
                            Status Validasi <span class="text-red-500">*</span>
                        </label>
                        <select id="status_validasi" name="status_validasi" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih Status --</option>
                            <option value="tervalidasi">Tervalidasi</option>
                            <option value="belum_valid">Belum Valid</option>
                            <option value="revisi">Perlu Revisi</option>
                        </select>
                    </div>

                    <div>
                        <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">
                            Catatan <span class="text-gray-500 text-xs">(opsional)</span>
                        </label>
                        <textarea id="catatan" name="catatan" rows="4"
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Berikan catatan atau feedback untuk mahasiswa..."></textarea>
                        <p class="text-xs text-gray-500 mt-1">Catatan akan dikirim via WhatsApp</p>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeValidationModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-check mr-2"></i>Validasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Tab switching function
function showTab(tabName) {
    // Hide all tab contents
    const tabContents = document.querySelectorAll('.tab-content');
    tabContents.forEach(content => content.classList.add('hidden'));

    // Remove active state from all tab buttons
    const tabButtons = document.querySelectorAll('.tab-button');
    tabButtons.forEach(button => {
        button.classList.remove('border-blue-500', 'text-blue-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });

    // Show selected tab content
    document.getElementById('content-' + tabName).classList.remove('hidden');

    // Activate selected tab button
    const activeButton = document.getElementById('tab-' + tabName);
    activeButton.classList.remove('border-transparent', 'text-gray-500');
    activeButton.classList.add('border-blue-500', 'text-blue-600');
}

// Semester tab switching function with fade animation
function showSemesterTab(semesterNumber) {
    // Hide all semester tab contents with fade out
    const semesterContents = document.querySelectorAll('.semester-tab-content');
    semesterContents.forEach(content => {
        content.style.opacity = '0';
        setTimeout(() => {
            content.classList.add('hidden');
        }, 150);
    });

    // Remove active state from all semester tab buttons
    const semesterButtons = document.querySelectorAll('.semester-tab-button');
    semesterButtons.forEach(button => {
        button.classList.remove('border-purple-500', 'text-purple-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });

    // Show selected semester content with fade in
    setTimeout(() => {
        const selectedContent = document.getElementById('semester-content-' + semesterNumber);
        if (selectedContent) {
            selectedContent.classList.remove('hidden');
            selectedContent.style.opacity = '0';
            // Trigger reflow
            selectedContent.offsetHeight;
            selectedContent.style.opacity = '1';
        }
    }, 150);

    // Activate selected semester tab button
    const activeButton = document.getElementById('semester-tab-' + semesterNumber);
    if (activeButton) {
        activeButton.classList.remove('border-transparent', 'text-gray-500');
        activeButton.classList.add('border-purple-500', 'text-purple-600');
    }
}

// Validation modal functions
const validationTitles = {
    'kelayakan': 'Validasi Pemberkasan Kelayakan',
    'dokumen_pendukung': 'Validasi Pemberkasan Dokumen Pendukung',
    'instansi_mitra': 'Validasi Pemberkasan Instansi Mitra',
    'akhir': 'Validasi Pemberkasan Akhir'
};

const validationRoutes = {
    'kelayakan': '{{ route("dospem.validate.kelayakan", $mahasiswa->id) }}',
    'dokumen_pendukung': '{{ route("dospem.validate.dokumen_pendukung", $mahasiswa->id) }}',
    'instansi_mitra': '{{ route("dospem.validate.instansi_mitra", $mahasiswa->id) }}',
    'akhir': '{{ route("dospem.validate.akhir", $mahasiswa->id) }}'
};

function openValidationModal(category) {
    const modal = document.getElementById('validationModal');
    const form = document.getElementById('validationForm');
    const title = document.getElementById('modalTitle');

    // Set title
    title.textContent = validationTitles[category];

    // Set form action
    form.action = validationRoutes[category];

    // Reset form
    form.reset();

    // Show modal
    modal.classList.remove('hidden');
}

function closeValidationModal() {
    const modal = document.getElementById('validationModal');
    modal.classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('validationModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeValidationModal();
    }
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
                // Remove the first row (title) and use the rest
                result.shift();
            }
        }

        return result;
    }

    if (text.includes(',') && text.split('\n')[0].split(',').length > 1) {
        return text.split('\n').map(r => r.split(',').map(c => c.trim()));
    }

    return text.split('\n').map(r => r.trim()).map(r => r.split(/\s{2,}/).map(c => c.trim()));
}

// Analyze transcript data to extract IPS, SKS D, E count, and Total SKS
function analyzeTranscriptData(rows) {
    if (!rows || rows.length < 2) {
        return { error: 'Data tidak valid' };
    }

    // Search for IPS and Total SKS from text
    let ipsFromText = null;
    let totalSksFromText = null;

    // Combine all text from rows for pattern matching
    const allText = rows.flat().join('\t').toLowerCase();

    // Search for IPS from pattern "Indeks Prestasi Semester\t3.68"
    const ipsMatch = allText.match(/indeks prestasi semester\t([0-9]+\.?[0-9]*)/i);
    if (ipsMatch) {
        ipsFromText = parseFloat(ipsMatch[1]);
    } else {
        // Fallback: search pattern with space
        const ipsMatchSpace = allText.match(/indeks prestasi semester\s+([0-9]+\.?[0-9]*)/i);
        if (ipsMatchSpace) {
            ipsFromText = parseFloat(ipsMatchSpace[1]);
        }
    }

    // Search for Total SKS from pattern "Total SKS\t20\t\t73.5"
    const sksMatch = allText.match(/total sks\t([0-9]+)/i);
    if (sksMatch) {
        totalSksFromText = parseInt(sksMatch[1]);
    } else {
        // Fallback: search pattern with space
        const sksMatchSpace = allText.match(/total sks\s+([0-9]+)/i);
        if (sksMatchSpace) {
            totalSksFromText = parseInt(sksMatchSpace[1]);
        }
    }

    // If still not found, try from table structure
    if (ipsFromText === null) {
        for (let i = 0; i < rows.length; i++) {
            const row = rows[i];
            const rowText = row.join(' ').toLowerCase();

            if (rowText.includes('indeks prestasi semester')) {
                // Search for number in this row
                for (let j = 0; j < row.length; j++) {
                    const cellValue = row[j];
                    const parsedIps = parseFloat(cellValue);
                    if (!isNaN(parsedIps) && parsedIps > 0 && parsedIps <= 4) {
                        ipsFromText = parsedIps;
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
                // Search for number in this row
                for (let j = 0; j < row.length; j++) {
                    const cellValue = row[j];
                    const parsedSks = parseInt(cellValue);
                    if (!isNaN(parsedSks) && parsedSks > 0 && parsedSks <= 50) {
                        totalSksFromText = parsedSks;
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

    // If SKS or Nilai column not found, try flexible search
    if (idxSks === -1 || idxNilai === -1) {
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
            return { error: 'Kolom SKS atau Nilai tidak ditemukan' };
        }

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

    // Use IPS from text if found, otherwise calculate manually
    const calculatedIps = sumSks > 0 ? sumQuality / sumSks : 0;
    const ips = ipsFromText !== null ? ipsFromText : calculatedIps;

    return {
        ips: parseFloat(ips.toFixed(2)),
        total_sks_d: totalSksD,
        has_e: hasE,
        total_sks: totalSksFromText || 0
    };
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
}

// Store semester analysis data
const semesterAnalysisData = {};

// Update summary display based on all semester data
function updateSummaryDisplay() {
    const semesters = Object.keys(semesterAnalysisData);

    if (semesters.length === 0) {
        return;
    }

    // Calculate IPK (weighted average of all IPS)
    let totalQuality = 0;
    let totalSks = 0;
    let totalSksDSum = 0;
    let hasECount = 0;

    semesters.forEach(semester => {
        const data = semesterAnalysisData[semester];
        if (data && !data.error) {
            totalQuality += data.ips * data.total_sks;
            totalSks += data.total_sks;
            totalSksDSum += data.total_sks_d;
            if (data.has_e) {
                hasECount++;
            }
        }
    });

    const ipk = totalSks > 0 ? totalQuality / totalSks : 0;

    // Update IPK Akhir
    const ipkElement = document.getElementById('ipkAkhir');
    if (ipkElement) {
        ipkElement.textContent = ipk > 0 ? ipk.toFixed(2) : '0.00';
        ipkElement.className = 'text-2xl font-bold ' + (ipk >= 3.0 ? 'text-green-600' : 'text-orange-600');
    }

    // Update Total SKS D
    const totalSksDElement = document.getElementById('totalSksDSummary');
    if (totalSksDElement) {
        totalSksDElement.textContent = totalSksDSum;
        totalSksDElement.className = 'text-2xl font-bold ' + (totalSksDSum === 0 ? 'text-green-600' : 'text-orange-600');
    }

    // Update Jumlah E
    const totalEElement = document.getElementById('totalESummary');
    if (totalEElement) {
        totalEElement.textContent = hasECount;
        totalEElement.className = 'text-2xl font-bold ' + (hasECount === 0 ? 'text-green-600' : 'text-red-600');
    }

    // Update Status PKL
    const isEligible = ipk >= 2.5 && totalSksDSum <= 6 && hasECount === 0;
    const statusPKLElement = document.getElementById('statusPKL');
    if (statusPKLElement) {
        statusPKLElement.textContent = isEligible ? 'Lengkap' : 'Belum Lengkap';
        statusPKLElement.className = 'text-xl font-bold ' + (isEligible ? 'text-green-600' : 'text-orange-600');
    }

    // Update card atas - IPK KHS
    const ipkKhsCardElement = document.getElementById('ipkKhsCard');
    if (ipkKhsCardElement) {
        ipkKhsCardElement.innerHTML = 'KHS: ' + (ipk > 0 ? ipk.toFixed(2) : '-');
        ipkKhsCardElement.className = 'font-bold ' + (ipk >= 3.0 ? 'text-green-600' : 'text-orange-600');
    }

    // Update card atas - SKS D
    const sksDCardElement = document.getElementById('sksDCard');
    if (sksDCardElement) {
        sksDCardElement.innerHTML = 'SKS D: ' + totalSksDSum;
        sksDCardElement.className = 'font-bold ' + (totalSksDSum > 0 ? 'text-yellow-600' : 'text-green-600');
    }

    // Update card atas - SKS E
    const sksECardElement = document.getElementById('sksECard');
    if (sksECardElement) {
        sksECardElement.innerHTML = 'SKS E: ' + hasECount;
        sksECardElement.className = 'font-bold ' + (hasECount > 0 ? 'text-red-600' : 'text-green-600');
    }

    console.log('Summary updated:', {
        ipk: ipk.toFixed(2),
        totalSksDSum,
        hasECount,
        isEligible
    });
}

// Render transcript tables on page load and analyze data
document.addEventListener('DOMContentLoaded', function() {
    @foreach($mahasiswa->khsManualTranskrip->sortBy('semester') as $transkrip)
        @if($transkrip->transcript_data)
            (function() {
                const transcriptDataElement = document.getElementById('transcriptData{{ $transkrip->semester }}');
                const tableDisplayElement = document.getElementById('tableDisplay{{ $transkrip->semester }}');

                if (transcriptDataElement && tableDisplayElement) {
                    try {
                        const transcriptData = atob(transcriptDataElement.value);
                        const rows = parseTranscript(transcriptData);

                        // Render the table
                        renderTable(rows, tableDisplayElement);

                        // Analyze the transcript data
                        const analysis = analyzeTranscriptData(rows);
                        console.log('Analysis for semester {{ $transkrip->semester }}:', analysis);

                        if (analysis && !analysis.error) {
                            // Store analysis data
                            semesterAnalysisData[{{ $transkrip->semester }}] = analysis;

                            // Update the "Hasil Analisis Semester" display with calculated values
                            const ipsElement = document.getElementById('ips{{ $transkrip->semester }}');
                            const sksDElement = document.getElementById('sksD{{ $transkrip->semester }}');
                            const hasEElement = document.getElementById('hasE{{ $transkrip->semester }}');
                            const totalSksElement = document.getElementById('totalSks{{ $transkrip->semester }}');

                            if (ipsElement) {
                                ipsElement.textContent = analysis.ips ? analysis.ips.toFixed(2) : '-';
                                ipsElement.className = 'font-medium ' + (analysis.ips >= 3.0 ? 'text-blue-600' : 'text-orange-600');
                            }

                            if (sksDElement) {
                                sksDElement.textContent = analysis.total_sks_d || 0;
                                sksDElement.className = 'font-medium ' + (analysis.total_sks_d > 0 ? 'text-orange-600' : 'text-green-600');
                            }

                            if (hasEElement) {
                                hasEElement.textContent = analysis.has_e ? 'Ya' : 'Tidak';
                                hasEElement.className = 'font-medium ' + (analysis.has_e ? 'text-red-600' : 'text-green-600');
                            }

                            if (totalSksElement) {
                                totalSksElement.textContent = analysis.total_sks || '-';
                                totalSksElement.className = 'font-medium text-blue-600';
                            }

                            // Update summary display after each semester is analyzed
                            updateSummaryDisplay();
                        }
                    } catch (error) {
                        console.error('Error rendering table for semester {{ $transkrip->semester }}:', error);
                        tableDisplayElement.innerHTML = '<div class="text-red-600 text-sm">Gagal memuat tabel</div>';
                    }
                }
            })();
        @endif
    @endforeach
});
</script>
@endsection
