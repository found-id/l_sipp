<!-- Admin Dashboard -->

<!-- Quick Stats Today -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
    <div class="bg-white rounded-xl p-3 md:p-4 shadow-sm border border-gray-100 hover:shadow-md transition">
        <div class="flex items-center gap-2 md:gap-3">
            <div class="h-8 w-8 md:h-10 md:w-10 rounded-lg bg-blue-50 flex items-center justify-center">
                <i class="fas fa-sign-in-alt text-blue-500 text-sm md:text-base"></i>
            </div>
            <div>
                <p class="text-lg md:text-2xl font-bold text-gray-800">{{ $stats['login_hari_ini'] ?? 0 }}</p>
                <p class="text-[10px] md:text-xs text-gray-500">Login Hari Ini</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl p-3 md:p-4 shadow-sm border border-gray-100 hover:shadow-md transition">
        <div class="flex items-center gap-2 md:gap-3">
            <div class="h-8 w-8 md:h-10 md:w-10 rounded-lg bg-green-50 flex items-center justify-center">
                <i class="fas fa-upload text-green-500 text-sm md:text-base"></i>
            </div>
            <div>
                <p class="text-lg md:text-2xl font-bold text-gray-800">{{ $stats['upload_hari_ini'] ?? 0 }}</p>
                <p class="text-[10px] md:text-xs text-gray-500">Upload Hari Ini</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl p-3 md:p-4 shadow-sm border border-gray-100 hover:shadow-md transition">
        <div class="flex items-center gap-2 md:gap-3">
            <div class="h-8 w-8 md:h-10 md:w-10 rounded-lg bg-purple-50 flex items-center justify-center">
                <i class="fas fa-check-circle text-purple-500 text-sm md:text-base"></i>
            </div>
            <div>
                <p class="text-lg md:text-2xl font-bold text-gray-800">{{ $stats['validasi_hari_ini'] ?? 0 }}</p>
                <p class="text-[10px] md:text-xs text-gray-500">Validasi Hari Ini</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl p-3 md:p-4 shadow-sm border border-gray-100 hover:shadow-md transition">
        <div class="flex items-center gap-2 md:gap-3">
            <div class="h-8 w-8 md:h-10 md:w-10 rounded-lg bg-orange-50 flex items-center justify-center">
                <i class="fas fa-hourglass-half text-orange-500 text-sm md:text-base"></i>
            </div>
            <div>
                <p class="text-lg md:text-2xl font-bold text-gray-800">{{ $stats['berkas_pending'] ?? 0 }}</p>
                <p class="text-[10px] md:text-xs text-gray-500">Perlu Validasi</p>
            </div>
        </div>
    </div>
</div>

<!-- Main Statistics Cards -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 mb-6">
    <!-- Total Mahasiswa -->
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl md:rounded-2xl p-4 md:p-5 text-white shadow-lg">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs md:text-sm font-medium text-blue-100">Mahasiswa</p>
                <p class="text-2xl md:text-3xl font-bold mt-1 md:mt-2">{{ $stats['total_mahasiswa'] ?? 0 }}</p>
                <p class="text-[10px] md:text-xs text-blue-200 mt-1 md:mt-2">
                    <i class="fas fa-male mr-1"></i>{{ $stats['mahasiswa_laki_laki'] ?? 0 }}
                    <i class="fas fa-female ml-1 mr-1"></i>{{ $stats['mahasiswa_perempuan'] ?? 0 }}
                </p>
            </div>
            <div class="h-10 w-10 md:h-12 md:w-12 rounded-lg md:rounded-xl bg-white/20 flex items-center justify-center">
                <i class="fas fa-user-graduate text-lg md:text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Dosen -->
    <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl md:rounded-2xl p-4 md:p-5 text-white shadow-lg">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs md:text-sm font-medium text-emerald-100">Dospem</p>
                <p class="text-2xl md:text-3xl font-bold mt-1 md:mt-2">{{ $stats['total_dosen'] ?? 0 }}</p>
                <p class="text-[10px] md:text-xs text-emerald-200 mt-1 md:mt-2">
                    <i class="fas fa-users mr-1"></i>Aktif
                </p>
            </div>
            <div class="h-10 w-10 md:h-12 md:w-12 rounded-lg md:rounded-xl bg-white/20 flex items-center justify-center">
                <i class="fas fa-chalkboard-teacher text-lg md:text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Mitra -->
    <div class="bg-gradient-to-br from-violet-500 to-violet-600 rounded-xl md:rounded-2xl p-4 md:p-5 text-white shadow-lg">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs md:text-sm font-medium text-violet-100">Mitra</p>
                <p class="text-2xl md:text-3xl font-bold mt-1 md:mt-2">{{ $stats['total_mitra'] ?? 0 }}</p>
                <p class="text-[10px] md:text-xs text-violet-200 mt-1 md:mt-2">
                    <i class="fas fa-door-open mr-1"></i>{{ $stats['mitra_tersedia'] ?? 0 }} tersedia
                </p>
            </div>
            <div class="h-10 w-10 md:h-12 md:w-12 rounded-lg md:rounded-xl bg-white/20 flex items-center justify-center">
                <i class="fas fa-building text-lg md:text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Admin -->
    <div class="bg-gradient-to-br from-slate-600 to-slate-700 rounded-xl md:rounded-2xl p-4 md:p-5 text-white shadow-lg">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs md:text-sm font-medium text-slate-200">Admin</p>
                <p class="text-2xl md:text-3xl font-bold mt-1 md:mt-2">{{ $stats['total_admin'] ?? 0 }}</p>
                <p class="text-[10px] md:text-xs text-slate-300 mt-1 md:mt-2">
                    <i class="fas fa-user-cog mr-1"></i>Pengelola
                </p>
            </div>
            <div class="h-10 w-10 md:h-12 md:w-12 rounded-lg md:rounded-xl bg-white/20 flex items-center justify-center">
                <i class="fas fa-user-shield text-lg md:text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Status Berkas - Redesigned Minimalist -->
<div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
    <div class="p-4 md:p-5 border-b border-gray-100 flex items-center justify-between">
        <div class="flex items-center gap-2 md:gap-3">
            <div class="h-8 w-8 md:h-10 md:w-10 rounded-lg md:rounded-xl bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center">
                <i class="fas fa-folder-open text-white text-sm md:text-base"></i>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900 text-sm md:text-base">Status Berkas</h3>
                <p class="text-[10px] md:text-xs text-gray-500">Ringkasan dokumen</p>
            </div>
        </div>
        <a href="{{ route('admin.validation') }}" class="text-xs md:text-sm text-indigo-600 hover:text-indigo-700 font-medium">
            Validasi <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>
    
    <div class="p-4 md:p-5">
        @php
            $totalBerkas = ($stats['total_khs'] ?? 0) + ($stats['total_surat_balasan'] ?? 0) + ($stats['total_laporan'] ?? 0) + ($stats['total_surat_pengantar'] ?? 0);
            $pctTervalidasi = $totalBerkas > 0 ? round(($stats['berkas_tervalidasi'] / $totalBerkas) * 100) : 0;
            $pctPending = $totalBerkas > 0 ? round(($stats['berkas_pending'] / $totalBerkas) * 100) : 0;
            $pctRevisi = $totalBerkas > 0 ? round(($stats['berkas_belum_valid'] / $totalBerkas) * 100) : 0;
        @endphp
        
        <!-- Summary Stats -->
        <div class="grid grid-cols-3 gap-2 md:gap-4 mb-4 md:mb-6">
            <div class="text-center p-3 md:p-4 bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg md:rounded-xl border border-green-100">
                <div class="inline-flex items-center justify-center h-8 w-8 md:h-10 md:w-10 rounded-full bg-green-100 mb-1 md:mb-2">
                    <i class="fas fa-check text-green-600 text-sm md:text-base"></i>
                </div>
                <p class="text-xl md:text-2xl font-bold text-green-600">{{ $stats['berkas_tervalidasi'] ?? 0 }}</p>
                <p class="text-[10px] md:text-xs text-gray-500 mt-0.5">Tervalidasi</p>
            </div>
            <div class="text-center p-3 md:p-4 bg-gradient-to-br from-amber-50 to-yellow-50 rounded-lg md:rounded-xl border border-amber-100">
                <div class="inline-flex items-center justify-center h-8 w-8 md:h-10 md:w-10 rounded-full bg-amber-100 mb-1 md:mb-2">
                    <i class="fas fa-clock text-amber-600 text-sm md:text-base"></i>
                </div>
                <p class="text-xl md:text-2xl font-bold text-amber-600">{{ $stats['berkas_pending'] ?? 0 }}</p>
                <p class="text-[10px] md:text-xs text-gray-500 mt-0.5">Menunggu</p>
            </div>
            <div class="text-center p-3 md:p-4 bg-gradient-to-br from-red-50 to-rose-50 rounded-lg md:rounded-xl border border-red-100">
                <div class="inline-flex items-center justify-center h-8 w-8 md:h-10 md:w-10 rounded-full bg-red-100 mb-1 md:mb-2">
                    <i class="fas fa-redo text-red-600 text-sm md:text-base"></i>
                </div>
                <p class="text-xl md:text-2xl font-bold text-red-600">{{ $stats['berkas_belum_valid'] ?? 0 }}</p>
                <p class="text-[10px] md:text-xs text-gray-500 mt-0.5">Revisi</p>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="mb-4 md:mb-6">
            <div class="flex items-center justify-between text-[10px] md:text-xs text-gray-500 mb-1.5 md:mb-2">
                <span>Total: <strong class="text-gray-700">{{ $totalBerkas }}</strong></span>
                <span>Validasi: <strong class="text-green-600">{{ $pctTervalidasi }}%</strong></span>
            </div>
            <div class="h-2.5 md:h-3 bg-gray-100 rounded-full overflow-hidden flex">
                <div class="bg-green-500 transition-all duration-500" style="width: {{ $pctTervalidasi }}%"></div>
                <div class="bg-amber-400 transition-all duration-500" style="width: {{ $pctPending }}%"></div>
                <div class="bg-red-400 transition-all duration-500" style="width: {{ $pctRevisi }}%"></div>
            </div>
        </div>

        <!-- Detail per Jenis -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-2 md:gap-3">
            <div class="p-2.5 md:p-3 bg-gray-50 rounded-lg md:rounded-xl">
                <div class="flex items-center justify-between mb-1.5 md:mb-2">
                    <span class="text-[10px] md:text-xs font-medium text-gray-600">KHS</span>
                    <i class="fas fa-file-pdf text-red-400 text-xs md:text-sm"></i>
                </div>
                <p class="text-base md:text-lg font-bold text-gray-800">{{ $stats['total_khs'] ?? 0 }}</p>
                <div class="flex gap-1.5 md:gap-2 mt-0.5 md:mt-1 text-[9px] md:text-[10px]">
                    <span class="text-green-600 flex items-center gap-0.5"><i class="fas fa-check text-[8px]"></i>{{ $stats['berkas_khs_tervalidasi'] ?? 0 }}</span>
                    <span class="text-amber-600 flex items-center gap-0.5"><i class="fas fa-clock text-[8px]"></i>{{ $stats['berkas_khs_pending'] ?? 0 }}</span>
                    <span class="text-red-600 flex items-center gap-0.5"><i class="fas fa-redo text-[8px]"></i>{{ $stats['berkas_khs_revisi'] ?? 0 }}</span>
                </div>
            </div>
            <div class="p-2.5 md:p-3 bg-gray-50 rounded-lg md:rounded-xl">
                <div class="flex items-center justify-between mb-1.5 md:mb-2">
                    <span class="text-[10px] md:text-xs font-medium text-gray-600">Surat Balasan</span>
                    <i class="fas fa-envelope text-blue-400 text-xs md:text-sm"></i>
                </div>
                <p class="text-base md:text-lg font-bold text-gray-800">{{ $stats['total_surat_balasan'] ?? 0 }}</p>
                <div class="flex gap-1.5 md:gap-2 mt-0.5 md:mt-1 text-[9px] md:text-[10px]">
                    <span class="text-green-600 flex items-center gap-0.5"><i class="fas fa-check text-[8px]"></i>{{ $stats['berkas_surat_balasan_tervalidasi'] ?? 0 }}</span>
                    <span class="text-amber-600 flex items-center gap-0.5"><i class="fas fa-clock text-[8px]"></i>{{ $stats['berkas_surat_balasan_pending'] ?? 0 }}</span>
                </div>
            </div>
            <div class="p-2.5 md:p-3 bg-gray-50 rounded-lg md:rounded-xl">
                <div class="flex items-center justify-between mb-1.5 md:mb-2">
                    <span class="text-[10px] md:text-xs font-medium text-gray-600">Laporan</span>
                    <i class="fas fa-file-alt text-purple-400 text-xs md:text-sm"></i>
                </div>
                <p class="text-base md:text-lg font-bold text-gray-800">{{ $stats['total_laporan'] ?? 0 }}</p>
                <div class="flex gap-1.5 md:gap-2 mt-0.5 md:mt-1 text-[9px] md:text-[10px]">
                    <span class="text-green-600 flex items-center gap-0.5"><i class="fas fa-check text-[8px]"></i>{{ $stats['berkas_laporan_tervalidasi'] ?? 0 }}</span>
                    <span class="text-amber-600 flex items-center gap-0.5"><i class="fas fa-clock text-[8px]"></i>{{ $stats['berkas_laporan_pending'] ?? 0 }}</span>
                </div>
            </div>
            <div class="p-2.5 md:p-3 bg-gray-50 rounded-lg md:rounded-xl">
                <div class="flex items-center justify-between mb-1.5 md:mb-2">
                    <span class="text-[10px] md:text-xs font-medium text-gray-600">Surat Pengantar</span>
                    <i class="fas fa-paper-plane text-green-400 text-xs md:text-sm"></i>
                </div>
                <p class="text-base md:text-lg font-bold text-gray-800">{{ $stats['total_surat_pengantar'] ?? 0 }}</p>
                <div class="flex gap-1.5 md:gap-2 mt-0.5 md:mt-1 text-[9px] md:text-[10px]">
                    <span class="text-green-600 flex items-center gap-0.5"><i class="fas fa-check text-[8px]"></i>{{ $stats['berkas_surat_pengantar_tervalidasi'] ?? 0 }}</span>
                    <span class="text-amber-600 flex items-center gap-0.5"><i class="fas fa-clock text-[8px]"></i>{{ $stats['berkas_surat_pengantar_pending'] ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mahasiswa & Mitra Statistics -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-6">
    <!-- Status Mahasiswa -->
    <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 md:p-5 border-b border-gray-100">
            <div class="flex items-center gap-2 md:gap-3">
                <div class="h-8 w-8 md:h-10 md:w-10 rounded-lg md:rounded-xl bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center">
                    <i class="fas fa-users text-white text-sm md:text-base"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 text-sm md:text-base">Status Mahasiswa</h3>
                    <p class="text-[10px] md:text-xs text-gray-500">Kelengkapan data</p>
                </div>
            </div>
        </div>
        <div class="p-4 md:p-5 space-y-3 md:space-y-4">
            <!-- IPK Stats -->
            <div class="p-3 md:p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg md:rounded-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] md:text-xs text-gray-500">Rata-rata IPK</p>
                        <p class="text-xl md:text-2xl font-bold text-blue-600">{{ number_format($stats['avg_ipk'] ?? 0, 2) }}</p>
                    </div>
                    <div class="text-right text-[10px] md:text-xs">
                        <p class="text-gray-500">Min: <span class="font-semibold text-gray-700">{{ number_format($stats['min_ipk'] ?? 0, 2) }}</span></p>
                        <p class="text-gray-500">Max: <span class="font-semibold text-gray-700">{{ number_format($stats['max_ipk'] ?? 0, 2) }}</span></p>
                    </div>
                </div>
            </div>

            <!-- Status Bars -->
            @php
                $pctDospem = ($stats['total_mahasiswa'] ?? 0) > 0 ? round(($stats['mahasiswa_dengan_dospem'] / $stats['total_mahasiswa']) * 100) : 0;
                $pctMitra = ($stats['total_mahasiswa'] ?? 0) > 0 ? round(($stats['mahasiswa_dengan_mitra'] / $stats['total_mahasiswa']) * 100) : 0;
                $pctLayak = ($stats['total_mahasiswa'] ?? 0) > 0 ? round(($stats['mahasiswa_layak'] / $stats['total_mahasiswa']) * 100) : 0;
            @endphp
            <div class="space-y-2.5 md:space-y-3">
                <div>
                    <div class="flex justify-between text-[10px] md:text-xs mb-1">
                        <span class="text-gray-600">Memiliki Dospem</span>
                        <span class="font-semibold">{{ $stats['mahasiswa_dengan_dospem'] ?? 0 }}/{{ $stats['total_mahasiswa'] ?? 0 }}</span>
                    </div>
                    <div class="h-1.5 md:h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-emerald-500 rounded-full transition-all" style="width: {{ $pctDospem }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-[10px] md:text-xs mb-1">
                        <span class="text-gray-600">Memilih Mitra</span>
                        <span class="font-semibold">{{ $stats['mahasiswa_dengan_mitra'] ?? 0 }}/{{ $stats['total_mahasiswa'] ?? 0 }}</span>
                    </div>
                    <div class="h-1.5 md:h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-violet-500 rounded-full transition-all" style="width: {{ $pctMitra }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-[10px] md:text-xs mb-1">
                        <span class="text-gray-600">Layak PKL</span>
                        <span class="font-semibold">{{ $stats['mahasiswa_layak'] ?? 0 }}/{{ $stats['total_mahasiswa'] ?? 0 }}</span>
                    </div>
                    <div class="h-1.5 md:h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-blue-500 rounded-full transition-all" style="width: {{ $pctLayak }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Mitra & Kuota -->
    <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 md:p-5 border-b border-gray-100">
            <div class="flex items-center gap-2 md:gap-3">
                <div class="h-8 w-8 md:h-10 md:w-10 rounded-lg md:rounded-xl bg-gradient-to-br from-violet-500 to-purple-500 flex items-center justify-center">
                    <i class="fas fa-building text-white text-sm md:text-base"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 text-sm md:text-base">Status Mitra & Kuota</h3>
                    <p class="text-[10px] md:text-xs text-gray-500">Ketersediaan instansi</p>
                </div>
            </div>
        </div>
        <div class="p-4 md:p-5">
            <!-- Kuota Overview -->
            <div class="p-3 md:p-4 bg-gradient-to-r from-violet-50 to-purple-50 rounded-lg md:rounded-xl mb-3 md:mb-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] md:text-xs text-gray-500">Total Kuota</p>
                        <p class="text-xl md:text-2xl font-bold text-violet-600">{{ $stats['total_kuota_mitra'] ?? 0 }}</p>
                    </div>
                    <div class="text-right text-xs md:text-sm">
                        <p><span class="text-green-600 font-semibold">{{ $stats['kuota_terisi'] ?? 0 }}</span> terisi</p>
                        <p><span class="text-gray-600 font-semibold">{{ $stats['kuota_tersisa'] ?? 0 }}</span> tersisa</p>
                    </div>
                </div>
                @php $pctKuota = ($stats['total_kuota_mitra'] ?? 0) > 0 ? round(($stats['kuota_terisi'] / $stats['total_kuota_mitra']) * 100) : 0; @endphp
                <div class="mt-2 md:mt-3">
                    <div class="h-1.5 md:h-2 bg-white rounded-full overflow-hidden">
                        <div class="h-full bg-violet-500 rounded-full transition-all" style="width: {{ $pctKuota }}%"></div>
                    </div>
                    <p class="text-[10px] md:text-xs text-gray-500 mt-1 text-right">{{ $pctKuota }}% terisi</p>
                </div>
            </div>

            <!-- Mitra Stats -->
            <div class="grid grid-cols-3 gap-2 md:gap-3">
                <div class="text-center p-2.5 md:p-3 bg-blue-50 rounded-lg md:rounded-xl">
                    <p class="text-lg md:text-xl font-bold text-blue-600">{{ $stats['mitra_aktif'] ?? 0 }}</p>
                    <p class="text-[10px] md:text-xs text-gray-500">Aktif</p>
                </div>
                <div class="text-center p-2.5 md:p-3 bg-green-50 rounded-lg md:rounded-xl">
                    <p class="text-lg md:text-xl font-bold text-green-600">{{ $stats['mitra_tersedia'] ?? 0 }}</p>
                    <p class="text-[10px] md:text-xs text-gray-500">Tersedia</p>
                </div>
                <div class="text-center p-2.5 md:p-3 bg-red-50 rounded-lg md:rounded-xl">
                    <p class="text-lg md:text-xl font-bold text-red-600">{{ $stats['mitra_penuh'] ?? 0 }}</p>
                    <p class="text-[10px] md:text-xs text-gray-500">Penuh</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Rankings Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-6">
    <!-- Top Mitra -->
    <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 md:p-5 border-b border-gray-100">
            <div class="flex items-center gap-2 md:gap-3">
                <div class="h-8 w-8 md:h-10 md:w-10 rounded-lg md:rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center">
                    <i class="fas fa-trophy text-white text-sm md:text-base"></i>
                </div>
                <h3 class="font-semibold text-gray-900 text-sm md:text-base">Top 5 Mitra</h3>
            </div>
        </div>
        <div class="divide-y divide-gray-50">
            @if(isset($stats['mitra_populer']) && $stats['mitra_populer']->count() > 0)
                @foreach($stats['mitra_populer'] as $index => $mitra)
                <div class="px-4 md:px-5 py-2.5 md:py-3 flex items-center gap-2 md:gap-3 hover:bg-gray-50 transition">
                    <span class="h-6 w-6 md:h-7 md:w-7 rounded-full flex items-center justify-center text-[10px] md:text-xs font-bold
                        {{ $index === 0 ? 'bg-amber-400 text-white' : ($index === 1 ? 'bg-gray-300 text-gray-700' : ($index === 2 ? 'bg-orange-300 text-white' : 'bg-gray-100 text-gray-600')) }}">
                        {{ $index + 1 }}
                    </span>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs md:text-sm font-medium text-gray-900 truncate">{{ $mitra->nama }}</p>
                    </div>
                    <span class="text-xs md:text-sm font-bold {{ $mitra->mahasiswa_terpilih_count >= $mitra->max_mahasiswa ? 'text-red-600' : 'text-blue-600' }}">
                        {{ $mitra->mahasiswa_terpilih_count }}/{{ $mitra->max_mahasiswa }}
                    </span>
                </div>
                @endforeach
            @else
                <div class="p-6 md:p-8 text-center text-gray-400">
                    <i class="fas fa-inbox text-2xl md:text-3xl mb-2"></i>
                    <p class="text-xs md:text-sm">Belum ada data</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Top Dospem -->
    <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 md:p-5 border-b border-gray-100">
            <div class="flex items-center gap-2 md:gap-3">
                <div class="h-8 w-8 md:h-10 md:w-10 rounded-lg md:rounded-xl bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center">
                    <i class="fas fa-medal text-white text-sm md:text-base"></i>
                </div>
                <h3 class="font-semibold text-gray-900 text-sm md:text-base">Top 5 Dospem</h3>
            </div>
        </div>
        <div class="divide-y divide-gray-50">
            @if(isset($stats['dospem_populer']) && $stats['dospem_populer']->count() > 0)
                @foreach($stats['dospem_populer'] as $index => $dospem)
                <div class="px-4 md:px-5 py-2.5 md:py-3 flex items-center gap-2 md:gap-3 hover:bg-gray-50 transition">
                    <span class="h-6 w-6 md:h-7 md:w-7 rounded-full flex items-center justify-center text-[10px] md:text-xs font-bold
                        {{ $index === 0 ? 'bg-emerald-500 text-white' : ($index === 1 ? 'bg-teal-400 text-white' : ($index === 2 ? 'bg-cyan-400 text-white' : 'bg-gray-100 text-gray-600')) }}">
                        {{ $index + 1 }}
                    </span>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs md:text-sm font-medium text-gray-900 truncate">{{ $dospem->name }}</p>
                    </div>
                    <span class="text-xs md:text-sm font-bold text-emerald-600">{{ $dospem->mahasiswa_bimbingan_count }}</span>
                </div>
                @endforeach
            @else
                <div class="p-6 md:p-8 text-center text-gray-400">
                    <i class="fas fa-inbox text-2xl md:text-3xl mb-2"></i>
                    <p class="text-xs md:text-sm">Belum ada data</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Distribution & Recent -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-6">
    <!-- Distribusi per Semester -->
    <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 md:p-5 border-b border-gray-100">
            <div class="flex items-center gap-2 md:gap-3">
                <div class="h-8 w-8 md:h-10 md:w-10 rounded-lg md:rounded-xl bg-gradient-to-br from-cyan-500 to-blue-500 flex items-center justify-center">
                    <i class="fas fa-chart-bar text-white text-sm md:text-base"></i>
                </div>
                <h3 class="font-semibold text-gray-900 text-sm md:text-base">Distribusi Semester</h3>
            </div>
        </div>
        <div class="p-4 md:p-5">
            @if(isset($stats['mahasiswa_per_semester']) && $stats['mahasiswa_per_semester']->count() > 0)
            <div class="space-y-2 md:space-y-3">
                @foreach($stats['mahasiswa_per_semester'] as $item)
                @php
                    $maxSem = $stats['mahasiswa_per_semester']->max('total');
                    $semPct = $maxSem > 0 ? ($item->total / $maxSem) * 100 : 0;
                @endphp
                <div class="flex items-center gap-2 md:gap-3">
                    <span class="text-[10px] md:text-xs w-12 md:w-16 text-gray-600">Sem {{ $item->semester }}</span>
                    <div class="flex-1">
                        <div class="h-5 md:h-6 bg-gray-100 rounded-md md:rounded-lg overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-cyan-500 to-blue-500 rounded-md md:rounded-lg flex items-center justify-end pr-1.5 md:pr-2 transition-all" style="width: {{ max($semPct, 15) }}%">
                                <span class="text-[9px] md:text-xs font-bold text-white">{{ $item->total }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-center text-gray-400 py-4 text-xs md:text-sm">Belum ada data</p>
            @endif
        </div>
    </div>

    <!-- Mahasiswa Terbaru -->
    <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 md:p-5 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-2 md:gap-3">
                <div class="h-8 w-8 md:h-10 md:w-10 rounded-lg md:rounded-xl bg-gradient-to-br from-pink-500 to-rose-500 flex items-center justify-center">
                    <i class="fas fa-user-plus text-white text-sm md:text-base"></i>
                </div>
                <h3 class="font-semibold text-gray-900 text-sm md:text-base">Mahasiswa Terbaru</h3>
            </div>
            <a href="{{ route('admin.kelola-akun') }}" class="text-[10px] md:text-xs text-pink-600 hover:text-pink-700 font-medium">
                Semua →
            </a>
        </div>
        <div class="divide-y divide-gray-50">
            @if(isset($stats['mahasiswa_terbaru']) && $stats['mahasiswa_terbaru']->count() > 0)
                @foreach($stats['mahasiswa_terbaru'] as $mhs)
                <div class="px-4 md:px-5 py-2.5 md:py-3 flex items-center gap-2 md:gap-3 hover:bg-gray-50 transition">
                    @if($mhs->photo && $mhs->google_linked)
                        <img src="{{ $mhs->photo }}" 
                             alt="{{ $mhs->name }}" 
                             class="h-7 w-7 md:h-9 md:w-9 rounded-full object-cover ring-2 ring-pink-200"
                             onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="h-7 w-7 md:h-9 md:w-9 rounded-full bg-gradient-to-br from-pink-500 to-rose-500 items-center justify-center text-white font-bold text-[10px] md:text-sm hidden">
                            {{ strtoupper(substr($mhs->name, 0, 1)) }}
                        </div>
                    @else
                        <div class="h-7 w-7 md:h-9 md:w-9 rounded-full bg-gradient-to-br from-pink-500 to-rose-500 flex items-center justify-center text-white font-bold text-[10px] md:text-sm">
                            {{ strtoupper(substr($mhs->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="text-xs md:text-sm font-medium text-gray-900 truncate">{{ $mhs->name }}</p>
                        <p class="text-[10px] md:text-xs text-gray-500">{{ $mhs->profilMahasiswa->nim ?? 'N/A' }}</p>
                    </div>
                    <span class="text-[10px] md:text-xs text-gray-400">{{ $mhs->created_at->diffForHumans() }}</span>
                </div>
                @endforeach
            @else
                <div class="p-6 md:p-8 text-center text-gray-400">
                    <i class="fas fa-inbox text-2xl md:text-3xl mb-2"></i>
                    <p class="text-xs md:text-sm">Belum ada</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Quick Actions & Activities -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
    <!-- Quick Actions -->
    <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 md:p-5 border-b border-gray-100">
            <div class="flex items-center gap-2 md:gap-3">
                <div class="h-8 w-8 md:h-10 md:w-10 rounded-lg md:rounded-xl bg-gradient-to-br from-gray-700 to-gray-800 flex items-center justify-center">
                    <i class="fas fa-bolt text-white text-sm md:text-base"></i>
                </div>
                <h3 class="font-semibold text-gray-900 text-sm md:text-base">Aksi Cepat</h3>
            </div>
        </div>
        <div class="p-3 md:p-4 space-y-1.5 md:space-y-2">
            <a href="{{ route('admin.kelola-data') }}" class="flex items-center justify-between p-2.5 md:p-3 bg-gray-50 hover:bg-blue-50 rounded-lg md:rounded-xl transition group">
                <div class="flex items-center gap-2 md:gap-3">
                    <i class="fas fa-cogs text-blue-500 text-sm md:text-base"></i>
                    <span class="text-xs md:text-sm font-medium text-gray-700">Kelola Data</span>
                </div>
                <i class="fas fa-chevron-right text-gray-300 group-hover:text-blue-500 transition text-xs"></i>
            </a>
            <a href="{{ route('admin.validation') }}" class="flex items-center justify-between p-2.5 md:p-3 bg-gray-50 hover:bg-purple-50 rounded-lg md:rounded-xl transition group">
                <div class="flex items-center gap-2 md:gap-3">
                    <i class="fas fa-clipboard-check text-purple-500 text-sm md:text-base"></i>
                    <span class="text-xs md:text-sm font-medium text-gray-700">Validasi</span>
                </div>
                @if(($stats['berkas_pending'] ?? 0) > 0)
                    <span class="bg-red-500 text-white text-[10px] md:text-xs px-1.5 md:px-2 py-0.5 rounded-full">{{ $stats['berkas_pending'] }}</span>
                @else
                    <i class="fas fa-chevron-right text-gray-300 group-hover:text-purple-500 transition text-xs"></i>
                @endif
            </a>
            <a href="{{ route('admin.kelola-akun') }}" class="flex items-center justify-between p-2.5 md:p-3 bg-gray-50 hover:bg-green-50 rounded-lg md:rounded-xl transition group">
                <div class="flex items-center gap-2 md:gap-3">
                    <i class="fas fa-users-cog text-green-500 text-sm md:text-base"></i>
                    <span class="text-xs md:text-sm font-medium text-gray-700">Kelola Akun</span>
                </div>
                <i class="fas fa-chevron-right text-gray-300 group-hover:text-green-500 transition text-xs"></i>
            </a>
            <a href="{{ route('admin.kelola-mitra') }}" class="flex items-center justify-between p-2.5 md:p-3 bg-gray-50 hover:bg-violet-50 rounded-lg md:rounded-xl transition group">
                <div class="flex items-center gap-2 md:gap-3">
                    <i class="fas fa-building text-violet-500 text-sm md:text-base"></i>
                    <span class="text-xs md:text-sm font-medium text-gray-700">Kelola Mitra</span>
                </div>
                <i class="fas fa-chevron-right text-gray-300 group-hover:text-violet-500 transition text-xs"></i>
            </a>
            <a href="{{ route('activity') }}" class="flex items-center justify-between p-2.5 md:p-3 bg-gray-50 hover:bg-indigo-50 rounded-lg md:rounded-xl transition group">
                <div class="flex items-center gap-2 md:gap-3">
                    <i class="fas fa-history text-indigo-500 text-sm md:text-base"></i>
                    <span class="text-xs md:text-sm font-medium text-gray-700">Log Aktivitas</span>
                </div>
                <i class="fas fa-chevron-right text-gray-300 group-hover:text-indigo-500 transition text-xs"></i>
            </a>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="lg:col-span-2 bg-white rounded-xl md:rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 md:p-5 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-2 md:gap-3">
                <div class="h-8 w-8 md:h-10 md:w-10 rounded-lg md:rounded-xl bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center">
                    <i class="fas fa-stream text-white text-sm md:text-base"></i>
                </div>
                <h3 class="font-semibold text-gray-900 text-sm md:text-base">Aktivitas Terbaru</h3>
            </div>
            <a href="{{ route('activity') }}" class="text-[10px] md:text-xs text-indigo-600 hover:text-indigo-700 font-medium">
                Semua →
            </a>
        </div>
        <div class="max-h-[280px] md:max-h-[350px] overflow-y-auto">
            @if(isset($stats['recent_activities']) && $stats['recent_activities']->count() > 0)
            <div class="divide-y divide-gray-50">
                @foreach($stats['recent_activities'] as $activity)
                <div class="px-4 md:px-5 py-2.5 md:py-3 flex items-start gap-2 md:gap-3 hover:bg-gray-50 transition">
                    <div class="h-6 w-6 md:h-8 md:w-8 rounded-full flex items-center justify-center flex-shrink-0
                        @if($activity->tipe === 'upload_dokumen') bg-blue-100
                        @elseif($activity->tipe === 'validasi_dokumen') bg-green-100
                        @elseif($activity->tipe === 'login') bg-purple-100
                        @elseif($activity->tipe === 'logout') bg-red-100
                        @else bg-gray-100 @endif">
                        <i class="fas text-[10px] md:text-xs
                            @if($activity->tipe === 'upload_dokumen') fa-upload text-blue-600
                            @elseif($activity->tipe === 'validasi_dokumen') fa-check text-green-600
                            @elseif($activity->tipe === 'login') fa-sign-in-alt text-purple-600
                            @elseif($activity->tipe === 'logout') fa-sign-out-alt text-red-600
                            @else fa-info text-gray-600 @endif"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs md:text-sm text-gray-900">
                            <span class="font-medium">{{ $activity->user->name ?? 'Unknown' }}</span>
                            @if($activity->tipe === 'upload_dokumen')
                                mengupload {{ $activity->pesan['document_type'] ?? 'dokumen' }}
                            @elseif($activity->tipe === 'validasi_dokumen')
                                memvalidasi dokumen
                            @elseif($activity->tipe === 'login')
                                login
                            @elseif($activity->tipe === 'logout')
                                logout
                            @else
                                {{ $activity->pesan['action'] ?? '' }}
                            @endif
                        </p>
                        <p class="text-[10px] md:text-xs text-gray-400 mt-0.5">{{ $activity->tanggal_dibuat->diffForHumans() }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="p-6 md:p-8 text-center text-gray-400">
                <i class="fas fa-inbox text-2xl md:text-3xl mb-2"></i>
                <p class="text-xs md:text-sm">Belum ada aktivitas</p>
            </div>
            @endif
        </div>
    </div>
</div>
