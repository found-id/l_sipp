<!-- Mahasiswa Dashboard -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- Progress Card -->
    <div class="group bg-white overflow-hidden shadow-lg rounded-2xl transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl border border-gray-100">
        <div class="p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-full -mr-16 -mt-16 opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg transform group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                        <i class="fas fa-chart-line text-2xl text-white"></i>
                    </div>
                    <div class="bg-blue-100 text-blue-600 text-xs font-bold px-3 py-1 rounded-full">Progress</div>
                </div>
                <dt class="text-sm font-medium text-gray-500 mb-2">Progress Pemberkasan</dt>
                <dd class="text-3xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors">{{ $stats['progress_berkas'] }}</dd>
                <p class="text-xs text-gray-400 mt-1">tahapan terselesaikan</p>
                <div class="mt-4">
                    <div class="bg-gray-200 rounded-full h-3 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-3 rounded-full transition-all duration-500" style="width: {{ $stats['progress_percentage'] }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 text-right">{{ $stats['progress_percentage'] }}%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Kelayakan PKL Status -->
    <div class="group bg-white overflow-hidden shadow-lg rounded-2xl transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl border border-gray-100">
        <div class="p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32
                @if($stats['kelayakan_status'] === 'layak') bg-green-50
                @elseif($stats['kelayakan_status'] === 'tidak_layak') bg-red-50
                @else bg-gray-50 @endif
                rounded-full -mr-16 -mt-16 opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br
                        @if($stats['kelayakan_status'] === 'layak') from-green-500 to-green-600
                        @elseif($stats['kelayakan_status'] === 'tidak_layak') from-red-500 to-red-600
                        @else from-gray-400 to-gray-500 @endif
                        rounded-xl flex items-center justify-center shadow-lg transform group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                        <i class="fas fa-clipboard-check text-2xl text-white"></i>
                    </div>
                    <div class="text-xs font-bold px-3 py-1 rounded-full
                        @if($stats['kelayakan_status'] === 'layak') bg-green-100 text-green-700
                        @elseif($stats['kelayakan_status'] === 'tidak_layak') bg-red-100 text-red-700
                        @else bg-gray-100 text-gray-700 @endif">
                        @if($stats['kelayakan_status'] === 'layak') Layak
                        @elseif($stats['kelayakan_status'] === 'tidak_layak') Tidak Layak
                        @else Belum @endif
                    </div>
                </div>
                <dt class="text-sm font-medium text-gray-500 mb-2">Kelayakan PKL</dt>
                <dd class="text-2xl font-bold
                    @if($stats['kelayakan_status'] === 'layak') text-green-600
                    @elseif($stats['kelayakan_status'] === 'tidak_layak') text-red-600
                    @else text-gray-600 @endif">
                    @if($stats['kelayakan_status'] === 'layak') Layak PKL
                    @elseif($stats['kelayakan_status'] === 'tidak_layak') Tidak Layak
                    @else Belum Lengkap @endif
                </dd>
                <p class="text-xs text-gray-400 mt-1">status kelayakan PKL</p>
            </div>
        </div>
    </div>

    <!-- Dokumen Pendukung Status -->
    <div class="group bg-white overflow-hidden shadow-lg rounded-2xl transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl border border-gray-100">
        <div class="p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32
                @if($stats['dokumen_pendukung_status'] === 'lengkap') bg-green-50
                @else bg-gray-50 @endif
                rounded-full -mr-16 -mt-16 opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br
                        @if($stats['dokumen_pendukung_status'] === 'lengkap') from-green-500 to-green-600
                        @else from-gray-400 to-gray-500 @endif
                        rounded-xl flex items-center justify-center shadow-lg transform group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                        <i class="fab fa-google-drive text-2xl text-white"></i>
                    </div>
                    <div class="text-xs font-bold px-3 py-1 rounded-full
                        @if($stats['dokumen_pendukung_status'] === 'lengkap') bg-green-100 text-green-700
                        @else bg-gray-100 text-gray-700 @endif">
                        @if($stats['dokumen_pendukung_status'] === 'lengkap') Lengkap
                        @else Belum @endif
                    </div>
                </div>
                <dt class="text-sm font-medium text-gray-500 mb-2">Dokumen Pendukung</dt>
                <dd class="text-2xl font-bold
                    @if($stats['dokumen_pendukung_status'] === 'lengkap') text-green-600
                    @else text-gray-600 @endif">
                    @if($stats['dokumen_pendukung_status'] === 'lengkap') Lengkap
                    @else Belum Lengkap @endif
                </dd>
                <p class="text-xs text-gray-400 mt-1">link Google Drive</p>
            </div>
        </div>
    </div>

    <!-- Instansi Mitra Status -->
    <div class="group bg-white overflow-hidden shadow-lg rounded-2xl transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl border border-gray-100">
        <div class="p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32
                @if($stats['instansi_mitra_status'] === 'tervalidasi') bg-green-50
                @elseif($stats['instansi_mitra_status'] === 'belum_valid') bg-red-50
                @elseif($stats['instansi_mitra_status'] === 'menunggu_validasi') bg-yellow-50
                @else bg-gray-50 @endif
                rounded-full -mr-16 -mt-16 opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br
                        @if($stats['instansi_mitra_status'] === 'tervalidasi') from-green-500 to-green-600
                        @elseif($stats['instansi_mitra_status'] === 'belum_valid') from-red-500 to-red-600
                        @elseif($stats['instansi_mitra_status'] === 'menunggu_validasi') from-yellow-500 to-yellow-600
                        @else from-gray-400 to-gray-500 @endif
                        rounded-xl flex items-center justify-center shadow-lg transform group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                        <i class="fas fa-building text-2xl text-white"></i>
                    </div>
                    <div class="text-xs font-bold px-3 py-1 rounded-full
                        @if($stats['instansi_mitra_status'] === 'tervalidasi') bg-green-100 text-green-700
                        @elseif($stats['instansi_mitra_status'] === 'belum_valid') bg-red-100 text-red-700
                        @elseif($stats['instansi_mitra_status'] === 'menunggu_validasi') bg-yellow-100 text-yellow-700
                        @else bg-gray-100 text-gray-700 @endif">
                        @if($stats['instansi_mitra_status'] === 'tervalidasi') Valid
                        @elseif($stats['instansi_mitra_status'] === 'belum_valid') Ditolak
                        @elseif($stats['instansi_mitra_status'] === 'menunggu_validasi') Menunggu
                        @else Belum @endif
                    </div>
                </div>
                <dt class="text-sm font-medium text-gray-500 mb-2">Instansi Mitra</dt>
                <dd class="text-2xl font-bold
                    @if($stats['instansi_mitra_status'] === 'tervalidasi') text-green-600
                    @elseif($stats['instansi_mitra_status'] === 'belum_valid') text-red-600
                    @elseif($stats['instansi_mitra_status'] === 'menunggu_validasi') text-yellow-600
                    @else text-gray-600 @endif">
                    @if($stats['instansi_mitra_status'] === 'tervalidasi') Tervalidasi
                    @elseif($stats['instansi_mitra_status'] === 'belum_valid') Ditolak
                    @elseif($stats['instansi_mitra_status'] === 'menunggu_validasi') Menunggu
                    @elseif($stats['instansi_mitra_status'] === 'belum_upload') Belum Upload
                    @else Belum Pilih @endif
                </dd>
                <p class="text-xs text-gray-400 mt-1">mitra & surat balasan</p>
            </div>
        </div>
    </div>
</div>

<!-- Second Row - Pemberkasan Akhir -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">
    <!-- Pemberkasan Akhir Status -->
    <div class="group bg-white overflow-hidden shadow-lg rounded-2xl transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl border border-gray-100">
        <div class="p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32
                @if($stats['pemberkasan_akhir_status'] === 'tervalidasi') bg-green-50
                @elseif($stats['pemberkasan_akhir_status'] === 'belum_valid') bg-red-50
                @elseif($stats['pemberkasan_akhir_status'] === 'menunggu_validasi') bg-yellow-50
                @else bg-gray-50 @endif
                rounded-full -mr-16 -mt-16 opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br
                        @if($stats['pemberkasan_akhir_status'] === 'tervalidasi') from-green-500 to-green-600
                        @elseif($stats['pemberkasan_akhir_status'] === 'belum_valid') from-red-500 to-red-600
                        @elseif($stats['pemberkasan_akhir_status'] === 'menunggu_validasi') from-yellow-500 to-yellow-600
                        @else from-gray-400 to-gray-500 @endif
                        rounded-xl flex items-center justify-center shadow-lg transform group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                        <i class="fas fa-book text-2xl text-white"></i>
                    </div>
                    <div class="text-xs font-bold px-3 py-1 rounded-full
                        @if($stats['pemberkasan_akhir_status'] === 'tervalidasi') bg-green-100 text-green-700
                        @elseif($stats['pemberkasan_akhir_status'] === 'belum_valid') bg-red-100 text-red-700
                        @elseif($stats['pemberkasan_akhir_status'] === 'menunggu_validasi') bg-yellow-100 text-yellow-700
                        @else bg-gray-100 text-gray-700 @endif">
                        @if($stats['pemberkasan_akhir_status'] === 'tervalidasi') Valid
                        @elseif($stats['pemberkasan_akhir_status'] === 'belum_valid') Ditolak
                        @elseif($stats['pemberkasan_akhir_status'] === 'menunggu_validasi') Menunggu
                        @else Belum @endif
                    </div>
                </div>
                <dt class="text-sm font-medium text-gray-500 mb-2">Pemberkasan Akhir</dt>
                <dd class="text-2xl font-bold
                    @if($stats['pemberkasan_akhir_status'] === 'tervalidasi') text-green-600
                    @elseif($stats['pemberkasan_akhir_status'] === 'belum_valid') text-red-600
                    @elseif($stats['pemberkasan_akhir_status'] === 'menunggu_validasi') text-yellow-600
                    @else text-gray-600 @endif">
                    @if($stats['pemberkasan_akhir_status'] === 'tervalidasi') Tervalidasi
                    @elseif($stats['pemberkasan_akhir_status'] === 'belum_valid') Ditolak
                    @elseif($stats['pemberkasan_akhir_status'] === 'menunggu_validasi') Menunggu
                    @else Belum Upload @endif
                </dd>
                <p class="text-xs text-gray-400 mt-1">laporan PKL final</p>
            </div>
        </div>
    </div>

    @if($stats['dosen_pembimbing'])
    <!-- Dosen Pembimbing Card -->
    <div class="group bg-white overflow-hidden shadow-lg rounded-2xl transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl border border-gray-100">
        <div class="p-6 relative overflow-hidden">
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
            <div class="space-y-4">
                <!-- Kelayakan PKL Info -->
                <div class="flex items-start p-4 rounded-xl border-2 transition-all duration-300
                    @if($stats['kelayakan_status'] === 'layak') border-green-200 bg-green-50
                    @elseif($stats['kelayakan_status'] === 'tidak_layak') border-red-200 bg-red-50
                    @else border-gray-200 bg-gray-50 @endif">
                    <div class="flex-shrink-0 mr-4">
                        <div class="w-12 h-12 rounded-lg flex items-center justify-center
                            @if($stats['kelayakan_status'] === 'layak') bg-green-500
                            @elseif($stats['kelayakan_status'] === 'tidak_layak') bg-red-500
                            @else bg-gray-400 @endif">
                            <i class="fas
                                @if($stats['kelayakan_status'] === 'layak') fa-check-circle
                                @elseif($stats['kelayakan_status'] === 'tidak_layak') fa-times-circle
                                @else fa-clipboard-check @endif
                                text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-gray-900 mb-1">1. Pemberkasan Kelayakan</h4>
                        <p class="text-sm font-medium mb-2
                            @if($stats['kelayakan_status'] === 'layak') text-green-700
                            @elseif($stats['kelayakan_status'] === 'tidak_layak') text-red-700
                            @else text-gray-600 @endif">
                            Status:
                            @if($stats['kelayakan_status'] === 'layak') LAYAK untuk PKL
                            @elseif($stats['kelayakan_status'] === 'tidak_layak') TIDAK LAYAK untuk PKL
                            @else Belum lengkap - Upload KHS semester 1-4 @endif
                        </p>
                        <p class="text-xs text-gray-600">
                            @if($stats['kelayakan_status'] === 'layak')
                                Selamat! Anda memenuhi syarat untuk melaksanakan PKL. Lanjutkan ke tahap berikutnya.
                            @elseif($stats['kelayakan_status'] === 'tidak_layak')
                                Anda belum memenuhi syarat kelayakan PKL. Silakan periksa kembali persyaratan.
                            @else
                                Upload KHS semester 1-4 melalui tab "Pemberkasan Kelayakan" di menu Pemberkasan.
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
                            @else text-gray-600 @endif">
                            Status:
                            @if($stats['dokumen_pendukung_status'] === 'lengkap') Dokumen lengkap
                            @else Belum lengkap - Upload link Google Drive @endif
                        </p>
                        <p class="text-xs text-gray-600">
                            @if($stats['dokumen_pendukung_status'] === 'lengkap')
                                Link Google Drive untuk PKKMB dan E-Course sudah tersimpan.
                            @else
                                Upload link Google Drive untuk dokumen PKKMB dan E-Course melalui tab "Pemberkasan Dokumen Pendukung".
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Instansi Mitra Info -->
                <div class="flex items-start p-4 rounded-xl border-2 transition-all duration-300
                    @if($stats['instansi_mitra_status'] === 'tervalidasi') border-green-200 bg-green-50
                    @elseif($stats['instansi_mitra_status'] === 'belum_valid') border-red-200 bg-red-50
                    @elseif($stats['instansi_mitra_status'] === 'menunggu_validasi') border-yellow-200 bg-yellow-50
                    @else border-gray-200 bg-gray-50 @endif">
                    <div class="flex-shrink-0 mr-4">
                        <div class="w-12 h-12 rounded-lg flex items-center justify-center
                            @if($stats['instansi_mitra_status'] === 'tervalidasi') bg-green-500
                            @elseif($stats['instansi_mitra_status'] === 'belum_valid') bg-red-500
                            @elseif($stats['instansi_mitra_status'] === 'menunggu_validasi') bg-yellow-500
                            @else bg-gray-400 @endif">
                            <i class="fas
                                @if($stats['instansi_mitra_status'] === 'tervalidasi') fa-check-circle
                                @elseif($stats['instansi_mitra_status'] === 'belum_valid') fa-times-circle
                                @elseif($stats['instansi_mitra_status'] === 'menunggu_validasi') fa-clock
                                @else fa-building @endif
                                text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-gray-900 mb-1">3. Pemberkasan Instansi Mitra</h4>
                        <p class="text-sm font-medium mb-2
                            @if($stats['instansi_mitra_status'] === 'tervalidasi') text-green-700
                            @elseif($stats['instansi_mitra_status'] === 'belum_valid') text-red-700
                            @elseif($stats['instansi_mitra_status'] === 'menunggu_validasi') text-yellow-700
                            @else text-gray-600 @endif">
                            Status:
                            @if($stats['instansi_mitra_status'] === 'tervalidasi') Surat balasan tervalidasi
                            @elseif($stats['instansi_mitra_status'] === 'belum_valid') Surat balasan ditolak
                            @elseif($stats['instansi_mitra_status'] === 'menunggu_validasi') Menunggu validasi dospem
                            @elseif($stats['instansi_mitra_status'] === 'belum_upload') Belum upload surat balasan
                            @else Belum memilih mitra @endif
                        </p>
                        <p class="text-xs text-gray-600">
                            @if($stats['instansi_mitra_status'] === 'tervalidasi')
                                Surat balasan dari instansi mitra sudah divalidasi oleh dosen pembimbing.
                            @elseif($stats['instansi_mitra_status'] === 'belum_valid')
                                Surat balasan ditolak. Silakan perbaiki dan upload ulang.
                            @elseif($stats['instansi_mitra_status'] === 'menunggu_validasi')
                                Surat balasan sedang menunggu validasi dari dosen pembimbing.
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
                    @if($stats['pemberkasan_akhir_status'] === 'tervalidasi') border-green-200 bg-green-50
                    @elseif($stats['pemberkasan_akhir_status'] === 'belum_valid') border-red-200 bg-red-50
                    @elseif($stats['pemberkasan_akhir_status'] === 'menunggu_validasi') border-yellow-200 bg-yellow-50
                    @else border-gray-200 bg-gray-50 @endif">
                    <div class="flex-shrink-0 mr-4">
                        <div class="w-12 h-12 rounded-lg flex items-center justify-center
                            @if($stats['pemberkasan_akhir_status'] === 'tervalidasi') bg-green-500
                            @elseif($stats['pemberkasan_akhir_status'] === 'belum_valid') bg-red-500
                            @elseif($stats['pemberkasan_akhir_status'] === 'menunggu_validasi') bg-yellow-500
                            @else bg-gray-400 @endif">
                            <i class="fas
                                @if($stats['pemberkasan_akhir_status'] === 'tervalidasi') fa-check-circle
                                @elseif($stats['pemberkasan_akhir_status'] === 'belum_valid') fa-times-circle
                                @elseif($stats['pemberkasan_akhir_status'] === 'menunggu_validasi') fa-clock
                                @else fa-book @endif
                                text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-gray-900 mb-1">4. Pemberkasan Akhir</h4>
                        <p class="text-sm font-medium mb-2
                            @if($stats['pemberkasan_akhir_status'] === 'tervalidasi') text-green-700
                            @elseif($stats['pemberkasan_akhir_status'] === 'belum_valid') text-red-700
                            @elseif($stats['pemberkasan_akhir_status'] === 'menunggu_validasi') text-yellow-700
                            @else text-gray-600 @endif">
                            Status:
                            @if($stats['pemberkasan_akhir_status'] === 'tervalidasi') Laporan PKL tervalidasi
                            @elseif($stats['pemberkasan_akhir_status'] === 'belum_valid') Laporan PKL ditolak
                            @elseif($stats['pemberkasan_akhir_status'] === 'menunggu_validasi') Menunggu validasi dospem
                            @else Belum upload laporan PKL @endif
                        </p>
                        <p class="text-xs text-gray-600">
                            @if($stats['pemberkasan_akhir_status'] === 'tervalidasi')
                                Selamat! Laporan PKL final Anda sudah divalidasi dan diterima.
                            @elseif($stats['pemberkasan_akhir_status'] === 'belum_valid')
                                Laporan PKL ditolak. Silakan perbaiki dan upload ulang.
                            @elseif($stats['pemberkasan_akhir_status'] === 'menunggu_validasi')
                                Laporan PKL sedang menunggu validasi dari dosen pembimbing.
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
