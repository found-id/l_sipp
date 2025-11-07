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
                <dt class="text-sm font-medium text-gray-500 mb-2">Progress Berkas</dt>
                <dd class="text-3xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors">{{ $stats['progress_berkas'] }}/3</dd>
                <p class="text-xs text-gray-400 mt-1">dokumen terselesaikan</p>
                <div class="mt-4">
                    <div class="bg-gray-200 rounded-full h-3 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-3 rounded-full transition-all duration-500" style="width: {{ $stats['progress_percentage'] }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 text-right">{{ $stats['progress_percentage'] }}%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- KHS Status -->
    <div class="group bg-white overflow-hidden shadow-lg rounded-2xl transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl border border-gray-100">
        <div class="p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32
                @if($stats['khs_status'] === 'tervalidasi') bg-green-50
                @elseif($stats['khs_status'] === 'belum_valid') bg-red-50
                @elseif($stats['khs_status'] === 'revisi') bg-yellow-50
                @else bg-gray-50 @endif
                rounded-full -mr-16 -mt-16 opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br
                        @if($stats['khs_status'] === 'tervalidasi') from-green-500 to-green-600
                        @elseif($stats['khs_status'] === 'belum_valid') from-red-500 to-red-600
                        @elseif($stats['khs_status'] === 'revisi') from-yellow-500 to-yellow-600
                        @else from-gray-400 to-gray-500 @endif
                        rounded-xl flex items-center justify-center shadow-lg transform group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                        <i class="fas fa-file-alt text-2xl text-white"></i>
                    </div>
                    <div class="text-xs font-bold px-3 py-1 rounded-full
                        @if($stats['khs_status'] === 'tervalidasi') bg-green-100 text-green-700
                        @elseif($stats['khs_status'] === 'belum_valid') bg-red-100 text-red-700
                        @elseif($stats['khs_status'] === 'revisi') bg-yellow-100 text-yellow-700
                        @else bg-gray-100 text-gray-700 @endif">
                        @if($stats['khs_status'] === 'tervalidasi') Valid
                        @elseif($stats['khs_status'] === 'belum_valid') Ditolak
                        @elseif($stats['khs_status'] === 'revisi') Revisi
                        @else Belum @endif
                    </div>
                </div>
                <dt class="text-sm font-medium text-gray-500 mb-2">KHS</dt>
                <dd class="text-2xl font-bold
                    @if($stats['khs_status'] === 'tervalidasi') text-green-600
                    @elseif($stats['khs_status'] === 'belum_valid') text-red-600
                    @elseif($stats['khs_status'] === 'revisi') text-yellow-600
                    @else text-gray-600 @endif">
                    @if($stats['khs_status'] === 'tervalidasi') Tervalidasi
                    @elseif($stats['khs_status'] === 'belum_valid') Belum Valid
                    @elseif($stats['khs_status'] === 'revisi') Perlu Revisi
                    @else Belum Upload @endif
                </dd>
                <p class="text-xs text-gray-400 mt-1">status dokumen KHS</p>
            </div>
        </div>
    </div>

    <!-- Surat Balasan Status -->
    <div class="group bg-white overflow-hidden shadow-lg rounded-2xl transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl border border-gray-100">
        <div class="p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32
                @if($stats['surat_status'] === 'tervalidasi') bg-green-50
                @elseif($stats['surat_status'] === 'belum_valid') bg-red-50
                @elseif($stats['surat_status'] === 'revisi') bg-yellow-50
                @else bg-gray-50 @endif
                rounded-full -mr-16 -mt-16 opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br
                        @if($stats['surat_status'] === 'tervalidasi') from-green-500 to-green-600
                        @elseif($stats['surat_status'] === 'belum_valid') from-red-500 to-red-600
                        @elseif($stats['surat_status'] === 'revisi') from-yellow-500 to-yellow-600
                        @else from-gray-400 to-gray-500 @endif
                        rounded-xl flex items-center justify-center shadow-lg transform group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                        <i class="fas fa-envelope text-2xl text-white"></i>
                    </div>
                    <div class="text-xs font-bold px-3 py-1 rounded-full
                        @if($stats['surat_status'] === 'tervalidasi') bg-green-100 text-green-700
                        @elseif($stats['surat_status'] === 'belum_valid') bg-red-100 text-red-700
                        @elseif($stats['surat_status'] === 'revisi') bg-yellow-100 text-yellow-700
                        @else bg-gray-100 text-gray-700 @endif">
                        @if($stats['surat_status'] === 'tervalidasi') Valid
                        @elseif($stats['surat_status'] === 'belum_valid') Ditolak
                        @elseif($stats['surat_status'] === 'revisi') Revisi
                        @else Belum @endif
                    </div>
                </div>
                <dt class="text-sm font-medium text-gray-500 mb-2">Surat Balasan</dt>
                <dd class="text-2xl font-bold
                    @if($stats['surat_status'] === 'tervalidasi') text-green-600
                    @elseif($stats['surat_status'] === 'belum_valid') text-red-600
                    @elseif($stats['surat_status'] === 'revisi') text-yellow-600
                    @else text-gray-600 @endif">
                    @if($stats['surat_status'] === 'tervalidasi') Tervalidasi
                    @elseif($stats['surat_status'] === 'belum_valid') Belum Valid
                    @elseif($stats['surat_status'] === 'revisi') Perlu Revisi
                    @else Belum Upload @endif
                </dd>
                <p class="text-xs text-gray-400 mt-1">status surat balasan</p>
            </div>
        </div>
    </div>

    <!-- Laporan Status -->
    <div class="group bg-white overflow-hidden shadow-lg rounded-2xl transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl border border-gray-100">
        <div class="p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32
                @if($stats['laporan_status'] === 'tervalidasi') bg-green-50
                @elseif($stats['laporan_status'] === 'belum_valid') bg-red-50
                @elseif($stats['laporan_status'] === 'revisi') bg-yellow-50
                @else bg-gray-50 @endif
                rounded-full -mr-16 -mt-16 opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br
                        @if($stats['laporan_status'] === 'tervalidasi') from-green-500 to-green-600
                        @elseif($stats['laporan_status'] === 'belum_valid') from-red-500 to-red-600
                        @elseif($stats['laporan_status'] === 'revisi') from-yellow-500 to-yellow-600
                        @else from-gray-400 to-gray-500 @endif
                        rounded-xl flex items-center justify-center shadow-lg transform group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                        <i class="fas fa-book text-2xl text-white"></i>
                    </div>
                    <div class="text-xs font-bold px-3 py-1 rounded-full
                        @if($stats['laporan_status'] === 'tervalidasi') bg-green-100 text-green-700
                        @elseif($stats['laporan_status'] === 'belum_valid') bg-red-100 text-red-700
                        @elseif($stats['laporan_status'] === 'revisi') bg-yellow-100 text-yellow-700
                        @else bg-gray-100 text-gray-700 @endif">
                        @if($stats['laporan_status'] === 'tervalidasi') Valid
                        @elseif($stats['laporan_status'] === 'belum_valid') Ditolak
                        @elseif($stats['laporan_status'] === 'revisi') Revisi
                        @else Belum @endif
                    </div>
                </div>
                <dt class="text-sm font-medium text-gray-500 mb-2">Laporan PKL</dt>
                <dd class="text-2xl font-bold
                    @if($stats['laporan_status'] === 'tervalidasi') text-green-600
                    @elseif($stats['laporan_status'] === 'belum_valid') text-red-600
                    @elseif($stats['laporan_status'] === 'revisi') text-yellow-600
                    @else text-gray-600 @endif">
                    @if($stats['laporan_status'] === 'tervalidasi') Tervalidasi
                    @elseif($stats['laporan_status'] === 'belum_valid') Belum Valid
                    @elseif($stats['laporan_status'] === 'revisi') Perlu Revisi
                    @else Belum Upload @endif
                </dd>
                <p class="text-xs text-gray-400 mt-1">status laporan PKL</p>
            </div>
        </div>
    </div>
</div>

<!-- Informasi Update Pemberkasan -->
<div class="mt-8">
    <div class="bg-white shadow-lg rounded-2xl border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-indigo-50 to-blue-50 px-6 py-5 border-b border-gray-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-file-invoice text-white"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Informasi Pemberkasan</h3>
                    <p class="text-xs text-gray-600">Status lengkap dokumen PKL Anda</p>
                </div>
            </div>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <!-- KHS Info -->
                <div class="flex items-start p-4 rounded-xl border-2 transition-all duration-300
                    @if($stats['khs_status'] === 'tervalidasi') border-green-200 bg-green-50
                    @elseif($stats['khs_status'] === 'belum_valid') border-red-200 bg-red-50
                    @elseif($stats['khs_status'] === 'revisi') border-yellow-200 bg-yellow-50
                    @else border-gray-200 bg-gray-50 @endif">
                    <div class="flex-shrink-0 mr-4">
                        <div class="w-12 h-12 rounded-lg flex items-center justify-center
                            @if($stats['khs_status'] === 'tervalidasi') bg-green-500
                            @elseif($stats['khs_status'] === 'belum_valid') bg-red-500
                            @elseif($stats['khs_status'] === 'revisi') bg-yellow-500
                            @else bg-gray-400 @endif">
                            <i class="fas
                                @if($stats['khs_status'] === 'tervalidasi') fa-check-circle
                                @elseif($stats['khs_status'] === 'belum_valid') fa-times-circle
                                @elseif($stats['khs_status'] === 'revisi') fa-exclamation-circle
                                @else fa-upload @endif
                                text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-gray-900 mb-1">KHS (Kartu Hasil Studi)</h4>
                        <p class="text-sm font-medium mb-2
                            @if($stats['khs_status'] === 'tervalidasi') text-green-700
                            @elseif($stats['khs_status'] === 'belum_valid') text-red-700
                            @elseif($stats['khs_status'] === 'revisi') text-yellow-700
                            @else text-gray-600 @endif">
                            Status:
                            @if($stats['khs_status'] === 'tervalidasi') Tervalidasi oleh Dospem
                            @elseif($stats['khs_status'] === 'belum_valid') Ditolak - Perlu diperbaiki
                            @elseif($stats['khs_status'] === 'revisi') Perlu revisi
                            @else Belum diunggah - Segera upload @endif
                        </p>
                        <p class="text-xs text-gray-600">
                            @if($stats['khs_status'] === 'tervalidasi') Dokumen KHS Anda sudah divalidasi dan diterima.
                            @elseif($stats['khs_status'] === 'belum_valid') Silakan perbaiki dan upload ulang dokumen KHS Anda.
                            @elseif($stats['khs_status'] === 'revisi') Periksa catatan revisi dan perbaiki dokumen.
                            @else Upload dokumen KHS semester 1-4 melalui menu Pemberkasan. @endif
                        </p>
                    </div>
                </div>

                <!-- Surat Balasan Info -->
                <div class="flex items-start p-4 rounded-xl border-2 transition-all duration-300
                    @if($stats['surat_status'] === 'tervalidasi') border-green-200 bg-green-50
                    @elseif($stats['surat_status'] === 'belum_valid') border-red-200 bg-red-50
                    @elseif($stats['surat_status'] === 'revisi') border-yellow-200 bg-yellow-50
                    @else border-gray-200 bg-gray-50 @endif">
                    <div class="flex-shrink-0 mr-4">
                        <div class="w-12 h-12 rounded-lg flex items-center justify-center
                            @if($stats['surat_status'] === 'tervalidasi') bg-green-500
                            @elseif($stats['surat_status'] === 'belum_valid') bg-red-500
                            @elseif($stats['surat_status'] === 'revisi') bg-yellow-500
                            @else bg-gray-400 @endif">
                            <i class="fas
                                @if($stats['surat_status'] === 'tervalidasi') fa-check-circle
                                @elseif($stats['surat_status'] === 'belum_valid') fa-times-circle
                                @elseif($stats['surat_status'] === 'revisi') fa-exclamation-circle
                                @else fa-upload @endif
                                text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-gray-900 mb-1">Surat Balasan Mitra</h4>
                        <p class="text-sm font-medium mb-2
                            @if($stats['surat_status'] === 'tervalidasi') text-green-700
                            @elseif($stats['surat_status'] === 'belum_valid') text-red-700
                            @elseif($stats['surat_status'] === 'revisi') text-yellow-700
                            @else text-gray-600 @endif">
                            Status:
                            @if($stats['surat_status'] === 'tervalidasi') Tervalidasi oleh Dospem
                            @elseif($stats['surat_status'] === 'belum_valid') Ditolak - Perlu diperbaiki
                            @elseif($stats['surat_status'] === 'revisi') Perlu revisi
                            @else Belum diunggah - Segera upload @endif
                        </p>
                        <p class="text-xs text-gray-600">
                            @if($stats['surat_status'] === 'tervalidasi') Surat balasan dari mitra sudah divalidasi.
                            @elseif($stats['surat_status'] === 'belum_valid') Silakan perbaiki dan upload ulang surat balasan.
                            @elseif($stats['surat_status'] === 'revisi') Periksa catatan revisi dan perbaiki dokumen.
                            @else Upload surat balasan dari instansi mitra melalui menu Pemberkasan. @endif
                        </p>
                    </div>
                </div>

                <!-- Laporan PKL Info -->
                <div class="flex items-start p-4 rounded-xl border-2 transition-all duration-300
                    @if($stats['laporan_status'] === 'tervalidasi') border-green-200 bg-green-50
                    @elseif($stats['laporan_status'] === 'belum_valid') border-red-200 bg-red-50
                    @elseif($stats['laporan_status'] === 'revisi') border-yellow-200 bg-yellow-50
                    @else border-gray-200 bg-gray-50 @endif">
                    <div class="flex-shrink-0 mr-4">
                        <div class="w-12 h-12 rounded-lg flex items-center justify-center
                            @if($stats['laporan_status'] === 'tervalidasi') bg-green-500
                            @elseif($stats['laporan_status'] === 'belum_valid') bg-red-500
                            @elseif($stats['laporan_status'] === 'revisi') bg-yellow-500
                            @else bg-gray-400 @endif">
                            <i class="fas
                                @if($stats['laporan_status'] === 'tervalidasi') fa-check-circle
                                @elseif($stats['laporan_status'] === 'belum_valid') fa-times-circle
                                @elseif($stats['laporan_status'] === 'revisi') fa-exclamation-circle
                                @else fa-upload @endif
                                text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-gray-900 mb-1">Laporan PKL</h4>
                        <p class="text-sm font-medium mb-2
                            @if($stats['laporan_status'] === 'tervalidasi') text-green-700
                            @elseif($stats['laporan_status'] === 'belum_valid') text-red-700
                            @elseif($stats['laporan_status'] === 'revisi') text-yellow-700
                            @else text-gray-600 @endif">
                            Status:
                            @if($stats['laporan_status'] === 'tervalidasi') Tervalidasi oleh Dospem
                            @elseif($stats['laporan_status'] === 'belum_valid') Ditolak - Perlu diperbaiki
                            @elseif($stats['laporan_status'] === 'revisi') Perlu revisi
                            @else Belum diunggah - Segera upload @endif
                        </p>
                        <p class="text-xs text-gray-600">
                            @if($stats['laporan_status'] === 'tervalidasi') Laporan PKL final Anda sudah divalidasi.
                            @elseif($stats['laporan_status'] === 'belum_valid') Silakan perbaiki dan upload ulang laporan PKL.
                            @elseif($stats['laporan_status'] === 'revisi') Periksa catatan revisi dan perbaiki laporan.
                            @else Upload laporan PKL final melalui menu Pemberkasan. @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
