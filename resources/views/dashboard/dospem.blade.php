<!-- Dosen Pembimbing Dashboard -->
<div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6">
    <!-- Mahasiswa Bimbingan -->
    <div class="group bg-white overflow-hidden shadow-md md:shadow-lg rounded-xl md:rounded-2xl transform transition-all duration-300 hover:-translate-y-1 md:hover:-translate-y-2 hover:shadow-xl md:hover:shadow-2xl border border-gray-100">
        <div class="p-3 md:p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-20 md:w-32 h-20 md:h-32 bg-blue-50 rounded-full -mr-10 md:-mr-16 -mt-10 md:-mt-16 opacity-50"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-2 md:mb-4">
                    <div class="w-10 h-10 md:w-14 md:h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg md:rounded-xl flex items-center justify-center shadow-md md:shadow-lg">
                        <i class="fas fa-users text-lg md:text-2xl text-white"></i>
                    </div>
                    <div class="bg-blue-100 text-blue-600 text-[10px] md:text-xs font-bold px-2 md:px-3 py-0.5 md:py-1 rounded-full">Bimbingan</div>
                </div>
                <dt class="text-xs md:text-sm font-medium text-gray-500 mb-1 md:mb-2">Mahasiswa</dt>
                <dd class="text-xl md:text-3xl font-bold text-gray-900">{{ $stats['mahasiswa_bimbingan'] }}</dd>
                <p class="text-[10px] md:text-xs text-gray-400 mt-0.5 md:mt-1 hidden md:block">Mahasiswa Aktif</p>
            </div>
        </div>
    </div>

    <!-- Berkas Perlu Validasi -->
    <div class="group bg-white overflow-hidden shadow-md md:shadow-lg rounded-xl md:rounded-2xl transform transition-all duration-300 hover:-translate-y-1 md:hover:-translate-y-2 hover:shadow-xl md:hover:shadow-2xl border border-gray-100">
        <div class="p-3 md:p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-20 md:w-32 h-20 md:h-32 bg-yellow-50 rounded-full -mr-10 md:-mr-16 -mt-10 md:-mt-16 opacity-50"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-2 md:mb-4">
                    <div class="w-10 h-10 md:w-14 md:h-14 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg md:rounded-xl flex items-center justify-center shadow-md md:shadow-lg">
                        <i class="fas fa-clock text-lg md:text-2xl text-white"></i>
                    </div>
                    <div class="bg-yellow-100 text-yellow-700 text-[10px] md:text-xs font-bold px-2 md:px-3 py-0.5 md:py-1 rounded-full">Pending</div>
                </div>
                <dt class="text-xs md:text-sm font-medium text-gray-500 mb-1 md:mb-2">Validasi</dt>
                <dd class="text-xl md:text-3xl font-bold text-gray-900">{{ $stats['berkas_perlu_validasi'] }}</dd>
                <p class="text-[10px] md:text-xs text-gray-400 mt-0.5 md:mt-1 hidden md:block">Menunggu Validasi</p>
            </div>
        </div>
    </div>

    <!-- Berkas Tervalidasi -->
    <div class="group bg-white overflow-hidden shadow-md md:shadow-lg rounded-xl md:rounded-2xl transform transition-all duration-300 hover:-translate-y-1 md:hover:-translate-y-2 hover:shadow-xl md:hover:shadow-2xl border border-gray-100">
        <div class="p-3 md:p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-20 md:w-32 h-20 md:h-32 bg-green-50 rounded-full -mr-10 md:-mr-16 -mt-10 md:-mt-16 opacity-50"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-2 md:mb-4">
                    <div class="w-10 h-10 md:w-14 md:h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-lg md:rounded-xl flex items-center justify-center shadow-md md:shadow-lg">
                        <i class="fas fa-check-circle text-lg md:text-2xl text-white"></i>
                    </div>
                    <div class="bg-green-100 text-green-700 text-[10px] md:text-xs font-bold px-2 md:px-3 py-0.5 md:py-1 rounded-full">Valid</div>
                </div>
                <dt class="text-xs md:text-sm font-medium text-gray-500 mb-1 md:mb-2">Tervalidasi</dt>
                <dd class="text-xl md:text-3xl font-bold text-gray-900">{{ $stats['berkas_tervalidasi'] }}</dd>
                <p class="text-[10px] md:text-xs text-gray-400 mt-0.5 md:mt-1 hidden md:block">Selesai Validasi</p>
            </div>
        </div>
    </div>

    <!-- Total Mitra -->
    <div class="group bg-white overflow-hidden shadow-md md:shadow-lg rounded-xl md:rounded-2xl transform transition-all duration-300 hover:-translate-y-1 md:hover:-translate-y-2 hover:shadow-xl md:hover:shadow-2xl border border-gray-100">
        <div class="p-3 md:p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-20 md:w-32 h-20 md:h-32 bg-purple-50 rounded-full -mr-10 md:-mr-16 -mt-10 md:-mt-16 opacity-50"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-2 md:mb-4">
                    <div class="w-10 h-10 md:w-14 md:h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg md:rounded-xl flex items-center justify-center shadow-md md:shadow-lg">
                        <i class="fas fa-building text-lg md:text-2xl text-white"></i>
                    </div>
                    <div class="bg-purple-100 text-purple-600 text-[10px] md:text-xs font-bold px-2 md:px-3 py-0.5 md:py-1 rounded-full">Mitra</div>
                </div>
                <dt class="text-xs md:text-sm font-medium text-gray-500 mb-1 md:mb-2">Total Mitra</dt>
                <dd class="text-xl md:text-3xl font-bold text-gray-900">{{ $stats['total_mitra'] }}</dd>
                <p class="text-[10px] md:text-xs text-gray-400 mt-0.5 md:mt-1 hidden md:block">Mitra Tersedia</p>
            </div>
        </div>
    </div>
</div>

<!-- Mahasiswa Bimbingan List -->
<div class="mt-8">
    <div class="bg-white shadow-lg rounded-2xl border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-5 border-b border-gray-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-users text-white"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Mahasiswa Bimbingan</h3>
                    <p class="text-xs text-gray-600">Daftar mahasiswa yang Anda bimbing</p>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">
                            No
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Mahasiswa
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                            Semester
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">
                            IPK
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-40">
                            Status
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($stats['mahasiswa_bimbingan_list'] as $index => $profil)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    @if($profil->user && $profil->user->photo && $profil->user->google_linked)
                                        <img src="{{ $profil->user->photo }}"
                                             alt="{{ $profil->user->name }}"
                                             class="h-10 w-10 rounded-full object-cover"
                                             onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="h-10 w-10 rounded-full bg-blue-100 items-center justify-center hidden">
                                            <i class="fas fa-user text-blue-600"></i>
                                        </div>
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            <i class="fas fa-user text-blue-600"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $profil->user->name ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">NIM: {{ $profil->nim ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">
                                <i class="fas fa-graduation-cap mr-1"></i>
                                Semester {{ $profil->semester ?? '-' }}
                            </span>
                        <td class="px-4 py-4 whitespace-nowrap">
                            @if($profil->ipk_transkrip)
                                <span class="text-sm font-semibold {{ $profil->ipk_transkrip >= 3.0 ? 'text-green-600' : 'text-orange-600' }}">
                                    {{ number_format($profil->ipk_transkrip, 2) }}
                                </span>
                            @else
                                <span class="text-sm text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            @php
                                $user = $profil->user;

                                // Pemberkasan Kelayakan (KHS Files)
                                $kelayakanStatus = 'incomplete';
                                if ($user) {
                                    $khsCount = $user->khs()->count();
                                    if ($khsCount > 0) {
                                        $hasValidated = $user->khs()->where('status_validasi', 'tervalidasi')->exists();
                                        $kelayakanStatus = $hasValidated ? 'validated' : 'complete';
                                    }
                                }

                                // Dokumen Pendukung (Google Drive)
                                $dokumenStatus = 'incomplete';
                                $hasPkkmb = !empty($profil->gdrive_pkkmb ?? '');
                                $hasEcourse = !empty($profil->gdrive_ecourse ?? '');
                                if ($hasPkkmb && $hasEcourse) {
                                    $statusDokPendukung = $profil->status_dokumen_pendukung ?? 'menunggu';
                                    $dokumenStatus = $statusDokPendukung === 'tervalidasi' ? 'validated' : 'complete';
                                }

                                // Instansi Mitra (Surat Balasan)
                                $mitraStatus = 'incomplete';
                                if ($user) {
                                    $suratBalasan = $user->suratBalasan()->latest()->first();
                                    if ($suratBalasan) {
                                        $mitraStatus = $suratBalasan->status_validasi === 'tervalidasi' ? 'validated' : 'complete';
                                    }
                                }

                                // Pemberkasan Akhir (Laporan PKL)
                                $akhirStatus = 'incomplete';
                                if ($user) {
                                    $laporan = $user->laporanPkl()->latest()->first();
                                    if ($laporan) {
                                        $akhirStatus = $laporan->status_validasi === 'tervalidasi' ? 'validated' : 'complete';
                                    }
                                }
                            @endphp
                            <div class="flex items-center space-x-2">
                                <!-- Pemberkasan Kelayakan -->
                                <i class="fas fa-file-alt text-lg @if($kelayakanStatus === 'validated') text-blue-600 @elseif($kelayakanStatus === 'complete') text-green-600 @else text-gray-400 @endif" title="Pemberkasan Kelayakan"></i>
                                <!-- Dokumen Pendukung -->
                                <i class="fab fa-google-drive text-lg @if($dokumenStatus === 'validated') text-blue-600 @elseif($dokumenStatus === 'complete') text-green-600 @else text-gray-400 @endif" title="Dokumen Pendukung"></i>
                                <!-- Instansi Mitra -->
                                <i class="fas fa-envelope text-lg @if($mitraStatus === 'validated') text-blue-600 @elseif($mitraStatus === 'complete') text-green-600 @else text-gray-400 @endif" title="Instansi Mitra"></i>
                                <!-- Pemberkasan Akhir -->
                                <i class="fas fa-book text-lg @if($akhirStatus === 'validated') text-blue-600 @elseif($akhirStatus === 'complete') text-green-600 @else text-gray-400 @endif" title="Pemberkasan Akhir"></i>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-users text-gray-300 text-4xl mb-3"></i>
                            <p>Tidak ada mahasiswa bimbingan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
