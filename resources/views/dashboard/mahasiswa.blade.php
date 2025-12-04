<!-- Mahasiswa Dashboard -->
<!-- Mahasiswa Dashboard -->
<div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 md:gap-6">
    
    <!-- Status Keaktifan Card (New) -->
    <div class="group bg-white overflow-hidden shadow-md md:shadow-lg rounded-xl md:rounded-2xl transform transition-all duration-300 hover:-translate-y-1 md:hover:-translate-y-2 hover:shadow-xl md:hover:shadow-2xl border border-gray-100">
        <div class="p-3 md:p-6 relative overflow-hidden h-full">
            <div class="absolute top-0 right-0 w-20 md:w-32 h-20 md:h-32 
                @if($stats['pkl_status'] === 'aktif') bg-blue-50
                @elseif($stats['pkl_status'] === 'siap') bg-green-50
                @elseif($stats['pkl_status'] === 'selesai') bg-green-50
                @else bg-gray-50 @endif
                rounded-full -mr-10 md:-mr-16 -mt-10 md:-mt-16 opacity-50"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-2 md:mb-4">
                    <div class="w-10 h-10 md:w-14 md:h-14 bg-gradient-to-br 
                        @if($stats['pkl_status'] === 'aktif') from-blue-500 to-blue-600
                        @elseif($stats['pkl_status'] === 'siap') from-green-500 to-green-600
                        @elseif($stats['pkl_status'] === 'selesai') from-green-500 to-green-600
                        @else from-gray-400 to-gray-500 @endif
                        rounded-lg md:rounded-xl flex items-center justify-center shadow-md md:shadow-lg">
                        @if($stats['pkl_status'] === 'selesai')
                            <i class="fas fa-check-circle text-lg md:text-2xl text-white"></i>
                        @else
                            <i class="fas fa-user-clock text-lg md:text-2xl text-white"></i>
                        @endif
                    </div>
                    <div class="text-[10px] md:text-xs font-bold px-2 md:px-3 py-0.5 md:py-1 rounded-full
                        @if($stats['pkl_status'] === 'aktif') bg-blue-100 text-blue-700
                        @elseif($stats['pkl_status'] === 'siap') bg-green-100 text-green-700
                        @elseif($stats['pkl_status'] === 'selesai') bg-green-100 text-green-700
                        @else bg-gray-100 text-gray-700 @endif">
                        Status
                    </div>
                </div>
                <dt class="text-xs md:text-sm font-medium text-gray-500 mb-1 md:mb-2">Status Keaktifan</dt>
                <dd class="text-lg md:text-2xl font-bold 
                    @if($stats['pkl_status'] === 'aktif') text-blue-600
                    @elseif($stats['pkl_status'] === 'siap') text-green-600
                    @elseif($stats['pkl_status'] === 'selesai') text-green-600
                    @else text-gray-600 @endif">
                    @if($stats['pkl_status'] === 'aktif') Aktif
                    @elseif($stats['pkl_status'] === 'siap') Siap
                    @elseif($stats['pkl_status'] === 'selesai') Selesai
                    @else Belum @endif
                </dd>
                <p class="text-[10px] md:text-xs text-gray-400 mt-0.5 md:mt-1 hidden md:block">Status Saat Ini</p>
            </div>
        </div>
    </div>

    <!-- Progress Card -->
    <div class="group bg-white overflow-hidden shadow-md md:shadow-lg rounded-xl md:rounded-2xl transform transition-all duration-300 hover:-translate-y-1 md:hover:-translate-y-2 hover:shadow-xl md:hover:shadow-2xl border border-gray-100">
        <div class="p-3 md:p-6 relative overflow-hidden h-full">
            <div class="absolute top-0 right-0 w-20 md:w-32 h-20 md:h-32 bg-blue-50 rounded-full -mr-10 md:-mr-16 -mt-10 md:-mt-16 opacity-50"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-2 md:mb-4">
                    <div class="w-10 h-10 md:w-14 md:h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg md:rounded-xl flex items-center justify-center shadow-md md:shadow-lg">
                        <i class="fas fa-chart-line text-lg md:text-2xl text-white"></i>
                    </div>
                    <div class="bg-blue-100 text-blue-600 text-[10px] md:text-xs font-bold px-2 md:px-3 py-0.5 md:py-1 rounded-full">Progress</div>
                </div>
                <dt class="text-xs md:text-sm font-medium text-gray-500 mb-1 md:mb-2">Progress</dt>
                <dd class="text-xl md:text-3xl font-bold text-gray-900">{{ $stats['progress_berkas'] }}</dd>
                <p class="text-[10px] md:text-xs text-gray-400 mt-0.5 md:mt-1 hidden md:block">Tahapan Selesai</p>
                
                {{-- Missing steps list removed as per request --}}

                <div class="mt-2 md:mt-4">
                    <div class="bg-gray-200 rounded-full h-2 md:h-3 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 md:h-3 rounded-full transition-all duration-500" style="width: {{ $stats['progress_percentage'] }}%"></div>
                    </div>
                    <p class="text-[10px] md:text-xs text-gray-500 mt-0.5 md:mt-1 text-right">{{ $stats['progress_percentage'] }}%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Kelayakan PKL Status -->
    <div class="group bg-white overflow-hidden shadow-md md:shadow-lg rounded-xl md:rounded-2xl transform transition-all duration-300 hover:-translate-y-1 md:hover:-translate-y-2 hover:shadow-xl md:hover:shadow-2xl border border-gray-100">
        <div class="p-3 md:p-6 relative overflow-hidden h-full">
            <div class="absolute top-0 right-0 w-20 md:w-32 h-20 md:h-32
                @if($stats['is_eligible']) bg-green-50
                @else bg-red-50 @endif
                rounded-full -mr-10 md:-mr-16 -mt-10 md:-mt-16 opacity-50"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-2 md:mb-4">
                    <div class="w-10 h-10 md:w-14 md:h-14 bg-gradient-to-br
                        @if($stats['is_eligible']) from-green-500 to-green-600
                        @else from-red-500 to-red-600 @endif
                        rounded-lg md:rounded-xl flex items-center justify-center shadow-md md:shadow-lg">
                        <i class="fas fa-clipboard-check text-lg md:text-2xl text-white"></i>
                    </div>
                    <div class="text-[10px] md:text-xs font-bold px-2 md:px-3 py-0.5 md:py-1 rounded-full
                        @if($stats['is_eligible']) bg-green-100 text-green-700
                        @else bg-red-100 text-red-700 @endif">
                        @if($stats['is_eligible']) Layak
                        @else Tidak @endif
                    </div>
                </div>
                <dt class="text-xs md:text-sm font-medium text-gray-500 mb-1 md:mb-2">Kelayakan</dt>
                <dd class="text-lg md:text-2xl font-bold
                    @if($stats['is_eligible']) text-green-600
                    @else text-red-600 @endif">
                    @if($stats['is_eligible']) Layak
                    @else Tidak @endif
                </dd>
                <p class="text-[10px] md:text-xs text-gray-400 mt-0.5 md:mt-1 hidden md:block">Status Kelayakan</p>
                
                {{-- Missing requirements list removed as per request --}}
            </div>
        </div>
    </div>

    <!-- Dokumen Pendukung Status -->
    <div class="group bg-white overflow-hidden shadow-md md:shadow-lg rounded-xl md:rounded-2xl transform transition-all duration-300 hover:-translate-y-1 md:hover:-translate-y-2 hover:shadow-xl md:hover:shadow-2xl border border-gray-100">
        <div class="p-3 md:p-6 relative overflow-hidden h-full">
            <div class="absolute top-0 right-0 w-20 md:w-32 h-20 md:h-32
                @if($stats['dokumen_pendukung_status'] === 'lengkap') bg-green-50
                @elseif($stats['dokumen_pendukung_status'] === 'sebagian') bg-yellow-50
                @else bg-gray-50 @endif
                rounded-full -mr-10 md:-mr-16 -mt-10 md:-mt-16 opacity-50"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-2 md:mb-4">
                    <div class="w-10 h-10 md:w-14 md:h-14 bg-gradient-to-br
                        @if($stats['dokumen_pendukung_status'] === 'lengkap') from-green-500 to-green-600
                        @elseif($stats['dokumen_pendukung_status'] === 'sebagian') from-yellow-500 to-yellow-600
                        @else from-gray-400 to-gray-500 @endif
                        rounded-lg md:rounded-xl flex items-center justify-center shadow-md md:shadow-lg">
                        <i class="fab fa-google-drive text-lg md:text-2xl text-white"></i>
                    </div>
                    <div class="text-[10px] md:text-xs font-bold px-2 md:px-3 py-0.5 md:py-1 rounded-full
                        @if($stats['dokumen_pendukung_status'] === 'lengkap') bg-green-100 text-green-700
                        @elseif($stats['dokumen_pendukung_status'] === 'sebagian') bg-yellow-100 text-yellow-700
                        @else bg-gray-100 text-gray-700 @endif">
                        @if($stats['dokumen_pendukung_status'] === 'lengkap') Lengkap
                        @elseif($stats['dokumen_pendukung_status'] === 'sebagian') Sebagian
                        @else Belum @endif
                    </div>
                </div>
                <dt class="text-xs md:text-sm font-medium text-gray-500 mb-1 md:mb-2">Dokumen</dt>
                <dd class="text-lg md:text-2xl font-bold
                    @if($stats['dokumen_pendukung_status'] === 'lengkap') text-green-600
                    @elseif($stats['dokumen_pendukung_status'] === 'sebagian') text-yellow-600
                    @else text-gray-600 @endif">
                    @if($stats['dokumen_pendukung_status'] === 'lengkap') Lengkap
                    @elseif($stats['dokumen_pendukung_status'] === 'sebagian') Sebagian
                    @else Belum @endif
                </dd>
                <p class="text-[10px] md:text-xs text-gray-400 mt-0.5 md:mt-1 hidden md:block">Berkas Pendukung</p>
            </div>
        </div>
    </div>

    <!-- Instansi Mitra Status -->
    <div class="group bg-white overflow-hidden shadow-md md:shadow-lg rounded-xl md:rounded-2xl transform transition-all duration-300 hover:-translate-y-1 md:hover:-translate-y-2 hover:shadow-xl md:hover:shadow-2xl border border-gray-100">
        <div class="p-3 md:p-6 relative overflow-hidden h-full">
            <div class="absolute top-0 right-0 w-20 md:w-32 h-20 md:h-32
                @if($stats['instansi_mitra_status'] === 'lengkap') bg-green-50
                @else bg-gray-50 @endif
                rounded-full -mr-10 md:-mr-16 -mt-10 md:-mt-16 opacity-50"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-2 md:mb-4">
                    <div class="w-10 h-10 md:w-14 md:h-14 bg-gradient-to-br
                        @if($stats['instansi_mitra_status'] === 'lengkap') from-green-500 to-green-600
                        @else from-gray-400 to-gray-500 @endif
                        rounded-lg md:rounded-xl flex items-center justify-center shadow-md md:shadow-lg">
                        <i class="fas fa-building text-lg md:text-2xl text-white"></i>
                    </div>
                    <div class="text-[10px] md:text-xs font-bold px-2 md:px-3 py-0.5 md:py-1 rounded-full
                        @if($stats['instansi_mitra_status'] === 'lengkap') bg-green-100 text-green-700
                        @else bg-gray-100 text-gray-700 @endif">
                        @if($stats['instansi_mitra_status'] === 'lengkap') Lengkap
                        @else Belum @endif
                    </div>
                </div>
                <dt class="text-xs md:text-sm font-medium text-gray-500 mb-1 md:mb-2">Mitra</dt>
                <dd class="text-lg md:text-2xl font-bold
                    @if($stats['instansi_mitra_status'] === 'lengkap') text-green-600
                    @else text-gray-600 @endif">
                    @if($stats['instansi_mitra_status'] === 'lengkap') Lengkap
                    @elseif($stats['instansi_mitra_status'] === 'belum_upload') Belum
                    @else Belum @endif
                </dd>
                <p class="text-[10px] md:text-xs text-gray-400 mt-0.5 md:mt-1 hidden md:block">Mitra & Surat</p>
            </div>
        </div>
    </div>

    <!-- Pemberkasan Akhir Status -->
    <div class="group bg-white overflow-hidden shadow-md md:shadow-lg rounded-xl md:rounded-2xl transform transition-all duration-300 hover:-translate-y-1 md:hover:-translate-y-2 hover:shadow-xl md:hover:shadow-2xl border border-gray-100">
        <div class="p-3 md:p-6 relative overflow-hidden h-full">
            <div class="absolute top-0 right-0 w-20 md:w-32 h-20 md:h-32
                @if($stats['pemberkasan_akhir_status'] === 'lengkap') bg-green-50
                @else bg-gray-50 @endif
                rounded-full -mr-10 md:-mr-16 -mt-10 md:-mt-16 opacity-50"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-2 md:mb-4">
                    <div class="w-10 h-10 md:w-14 md:h-14 bg-gradient-to-br
                        @if($stats['pemberkasan_akhir_status'] === 'lengkap') from-green-500 to-green-600
                        @else from-gray-400 to-gray-500 @endif
                        rounded-lg md:rounded-xl flex items-center justify-center shadow-md md:shadow-lg">
                        <i class="fas fa-book text-lg md:text-2xl text-white"></i>
                    </div>
                    <div class="text-[10px] md:text-xs font-bold px-2 md:px-3 py-0.5 md:py-1 rounded-full
                        @if($stats['pemberkasan_akhir_status'] === 'lengkap') bg-green-100 text-green-700
                        @else bg-gray-100 text-gray-700 @endif">
                        @if($stats['pemberkasan_akhir_status'] === 'lengkap') Lengkap
                        @else Belum @endif
                    </div>
                </div>
                <dt class="text-xs md:text-sm font-medium text-gray-500 mb-1 md:mb-2">Laporan</dt>
                <dd class="text-lg md:text-2xl font-bold
                    @if($stats['pemberkasan_akhir_status'] === 'lengkap') text-green-600
                    @else text-gray-600 @endif">
                    @if($stats['pemberkasan_akhir_status'] === 'lengkap') Lengkap
                    @else Belum @endif
                </dd>
                <p class="text-[10px] md:text-xs text-gray-400 mt-0.5 md:mt-1 hidden md:block">Laporan Akhir</p>
            </div>
        </div>
    </div>

    @if($stats['dosen_pembimbing'])
    <!-- Dosen Pembimbing Card -->
    <div class="group bg-white overflow-hidden shadow-lg rounded-2xl transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl border border-gray-100">
        <div class="p-6 relative overflow-hidden h-full">
            <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-50 rounded-full -mr-16 -mt-16 opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg transform group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                        <i class="fas fa-user-tie text-2xl text-white"></i>
                    </div>
                    <div class="bg-indigo-100 text-indigo-600 text-xs font-bold px-3 py-1 rounded-full">Dospem</div>
                </div>
                <dt class="text-sm font-medium text-gray-500 mb-2">Dosen Pembimbing</dt>
                <dd class="text-lg font-bold text-gray-900">{{ $stats['dosen_pembimbing']->name }}</dd>
                <p class="text-xs text-gray-400 mt-1">pembimbing PKL Anda</p>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Informasi Detail Pemberkasan -->
<div class="mt-8">
    <div class="bg-white shadow-lg rounded-2xl border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-indigo-50 to-blue-50 px-6 py-5 border-b border-gray-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-file-invoice text-white"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Informasi Detail Pemberkasan</h3>
                    <p class="text-xs text-gray-600">Status lengkap tahapan pemberkasan PKL Anda</p>
                </div>
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Kelayakan PKL Info -->
                <div class="flex items-start p-4 rounded-xl border-2 transition-all duration-300
                    @if($stats['is_eligible']) border-green-200 bg-green-50
                    @else border-red-200 bg-red-50 @endif">
                    <div class="flex-shrink-0 mr-4">
                        <div class="w-12 h-12 rounded-lg flex items-center justify-center
                            @if($stats['is_eligible']) bg-green-500
                            @else bg-red-500 @endif">
                            <i class="fas
                                @if($stats['is_eligible']) fa-check-circle
                                @else fa-times-circle @endif
                                text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-gray-900 mb-1">1. Pemberkasan Kelayakan</h4>
                        <p class="text-sm font-medium mb-2
                            @if($stats['is_eligible']) text-green-700
                            @else text-red-700 @endif">
                            Status:
                            @if($stats['is_eligible']) LAYAK untuk PKL
                            @else BELUM LAYAK untuk PKL @endif
                        </p>
                        <p class="text-xs text-gray-600">
                            @if($stats['is_eligible'])
                                Selamat! Anda memenuhi syarat untuk melaksanakan PKL (IPK ≥ 2.5, SKS D ≤ 6, tidak ada nilai E).
                            @else
                                Anda belum memenuhi syarat kelayakan PKL. Periksa IPK (min 2.5), SKS D (max 6), dan pastikan tidak ada nilai E.
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Dokumen Pendukung Info -->
                <div class="flex items-start p-4 rounded-xl border-2 transition-all duration-300
                    @if($stats['dokumen_pendukung_status'] === 'lengkap') border-green-200 bg-green-50
                    @else border-gray-200 bg-gray-50 @endif">
                    <div class="flex-shrink-0 mr-4">
                        <div class="w-12 h-12 rounded-lg flex items-center justify-center
                            @if($stats['dokumen_pendukung_status'] === 'lengkap') bg-green-500
                            @else bg-gray-400 @endif">
                            <i class="fas
                                @if($stats['dokumen_pendukung_status'] === 'lengkap') fa-check-circle
                                @else fa-cloud-upload-alt @endif
                                text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-gray-900 mb-1">2. Pemberkasan Dokumen Pendukung</h4>
                        <p class="text-sm font-medium mb-2
                            @if($stats['dokumen_pendukung_status'] === 'lengkap') text-green-700
                            @elseif($stats['dokumen_pendukung_status'] === 'sebagian') text-yellow-700
                            @else text-gray-600 @endif">
                            Status:
                            @if($stats['dokumen_pendukung_status'] === 'lengkap') Dokumen lengkap
                            @elseif($stats['dokumen_pendukung_status'] === 'sebagian') Sebagian terisi
                            @else Belum lengkap - Upload link Google Drive @endif
                        </p>
                        <p class="text-xs text-gray-600">
                            @if($stats['dokumen_pendukung_status'] === 'lengkap')
                                Link Google Drive untuk PKKMB, E-Course, dan Sertifikat Semasa sudah tersimpan.
                            @elseif($stats['dokumen_pendukung_status'] === 'sebagian')
                                Sebagian link sudah terisi. Lengkapi semua link (PKKMB, E-Course, Sertifikat Semasa).
                            @else
                                Upload link Google Drive untuk PKKMB, E-Course, dan Sertifikat Semasa melalui tab "Pemberkasan Dokumen Pendukung".
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Instansi Mitra Info -->
                <div class="flex items-start p-4 rounded-xl border-2 transition-all duration-300
                    @if($stats['instansi_mitra_status'] === 'lengkap') border-green-200 bg-green-50
                    @else border-gray-200 bg-gray-50 @endif">
                    <div class="flex-shrink-0 mr-4">
                        <div class="w-12 h-12 rounded-lg flex items-center justify-center
                            @if($stats['instansi_mitra_status'] === 'lengkap') bg-green-500
                            @else bg-gray-400 @endif">
                            <i class="fas
                                @if($stats['instansi_mitra_status'] === 'lengkap') fa-check-circle
                                @else fa-building @endif
                                text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-gray-900 mb-1">3. Pemberkasan Instansi Mitra</h4>
                        <p class="text-sm font-medium mb-2
                            @if($stats['instansi_mitra_status'] === 'lengkap') text-green-700
                            @else text-gray-600 @endif">
                            Status:
                            @if($stats['instansi_mitra_status'] === 'lengkap') Surat balasan sudah diupload
                            @elseif($stats['instansi_mitra_status'] === 'belum_upload') Belum upload surat balasan
                            @else Belum memilih mitra @endif
                        </p>
                        <p class="text-xs text-gray-600">
                            @if($stats['instansi_mitra_status'] === 'lengkap')
                                Surat balasan dari instansi mitra sudah diupload.
                            @elseif($stats['instansi_mitra_status'] === 'belum_upload')
                                Pilih instansi mitra dan upload surat balasan melalui tab "Pemberkasan Instansi Mitra".
                            @else
                                Pilih instansi mitra terlebih dahulu melalui menu "Instansi Mitra".
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Pemberkasan Akhir Info -->
                <div class="flex items-start p-4 rounded-xl border-2 transition-all duration-300
                    @if($stats['pemberkasan_akhir_status'] === 'lengkap') border-green-200 bg-green-50
                    @else border-gray-200 bg-gray-50 @endif">
                    <div class="flex-shrink-0 mr-4">
                        <div class="w-12 h-12 rounded-lg flex items-center justify-center
                            @if($stats['pemberkasan_akhir_status'] === 'lengkap') bg-green-500
                            @else bg-gray-400 @endif">
                            <i class="fas
                                @if($stats['pemberkasan_akhir_status'] === 'lengkap') fa-check-circle
                                @else fa-book @endif
                                text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-gray-900 mb-1">4. Pemberkasan Akhir</h4>
                        <p class="text-sm font-medium mb-2
                            @if($stats['pemberkasan_akhir_status'] === 'lengkap') text-green-700
                            @else text-gray-600 @endif">
                            Status:
                            @if($stats['pemberkasan_akhir_status'] === 'lengkap') Laporan PKL sudah diupload
                            @else Belum upload laporan PKL @endif
                        </p>
                        <p class="text-xs text-gray-600">
                            @if($stats['pemberkasan_akhir_status'] === 'lengkap')
                                Laporan PKL final Anda sudah diupload.
                            @else
                                Upload laporan PKL final melalui tab "Pemberkasan Akhir" di menu Pemberkasan.
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
