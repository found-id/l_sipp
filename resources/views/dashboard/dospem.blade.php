<!-- Dosen Pembimbing Dashboard -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- Mahasiswa Bimbingan -->
    <div class="group bg-white overflow-hidden shadow-lg rounded-2xl transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl border border-gray-100">
        <div class="p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-full -mr-16 -mt-16 opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg transform group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                        <i class="fas fa-users text-2xl text-white"></i>
                    </div>
                    <div class="bg-blue-100 text-blue-600 text-xs font-bold px-3 py-1 rounded-full">Bimbingan</div>
                </div>
                <dt class="text-sm font-medium text-gray-500 mb-2">Mahasiswa Bimbingan</dt>
                <dd class="text-3xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors">{{ $stats['mahasiswa_bimbingan'] }}</dd>
                <p class="text-xs text-gray-400 mt-1">mahasiswa dibimbing</p>
            </div>
        </div>
    </div>

    <!-- Berkas Perlu Validasi -->
    <div class="group bg-white overflow-hidden shadow-lg rounded-2xl transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl border border-gray-100">
        <div class="p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-yellow-50 rounded-full -mr-16 -mt-16 opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center shadow-lg transform group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                        <i class="fas fa-clock text-2xl text-white"></i>
                    </div>
                    <div class="bg-yellow-100 text-yellow-700 text-xs font-bold px-3 py-1 rounded-full">Pending</div>
                </div>
                <dt class="text-sm font-medium text-gray-500 mb-2">Perlu Validasi</dt>
                <dd class="text-3xl font-bold text-gray-900 group-hover:text-yellow-600 transition-colors">{{ $stats['berkas_perlu_validasi'] }}</dd>
                <p class="text-xs text-gray-400 mt-1">berkas menunggu</p>
            </div>
        </div>
    </div>

    <!-- Berkas Tervalidasi -->
    <div class="group bg-white overflow-hidden shadow-lg rounded-2xl transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl border border-gray-100">
        <div class="p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-green-50 rounded-full -mr-16 -mt-16 opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg transform group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                        <i class="fas fa-check-circle text-2xl text-white"></i>
                    </div>
                    <div class="bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full">Valid</div>
                </div>
                <dt class="text-sm font-medium text-gray-500 mb-2">Tervalidasi</dt>
                <dd class="text-3xl font-bold text-gray-900 group-hover:text-green-600 transition-colors">{{ $stats['berkas_tervalidasi'] }}</dd>
                <p class="text-xs text-gray-400 mt-1">berkas selesai</p>
            </div>
        </div>
    </div>

    <!-- Total Mitra -->
    <div class="group bg-white overflow-hidden shadow-lg rounded-2xl transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl border border-gray-100">
        <div class="p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-purple-50 rounded-full -mr-16 -mt-16 opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg transform group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                        <i class="fas fa-building text-2xl text-white"></i>
                    </div>
                    <div class="bg-purple-100 text-purple-600 text-xs font-bold px-3 py-1 rounded-full">Mitra</div>
                </div>
                <dt class="text-sm font-medium text-gray-500 mb-2">Total Mitra</dt>
                <dd class="text-3xl font-bold text-gray-900 group-hover:text-purple-600 transition-colors">{{ $stats['total_mitra'] }}</dd>
                <p class="text-xs text-gray-400 mt-1">instansi tersedia</p>
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
        <ul class="divide-y divide-gray-200">
            @forelse($stats['mahasiswa_bimbingan_list'] as $profil)
            <li class="px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            @if($profil->user && $profil->user->photo && $profil->user->google_linked)
                                <img src="{{ $profil->user->photo }}"
                                     alt="{{ $profil->user->name }}"
                                     class="h-10 w-10 rounded-full object-cover"
                                     onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="h-10 w-10 rounded-full bg-gray-300 items-center justify-center hidden">
                                    <i class="fas fa-user text-gray-600"></i>
                                </div>
                            @else
                                <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                    <i class="fas fa-user text-gray-600"></i>
                                </div>
                            @endif
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">{{ $profil->user->name ?? 'N/A' }}</div>
                            <div class="text-sm text-gray-500">NIM: {{ $profil->nim ?? 'N/A' }} • Prodi: {{ $profil->prodi ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        @php
                            $completed = 0;
                            if($profil->user && $profil->user->khs()->tervalidasi()->exists()) $completed++;
                            if($profil->user && $profil->user->suratBalasan()->tervalidasi()->exists()) $completed++;
                            if($profil->user && $profil->user->laporanPkl()->tervalidasi()->exists()) $completed++;
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            @if($completed == 3) bg-green-100 text-green-800 @else bg-yellow-100 text-yellow-800 @endif">
                            <i class="fas fa-check mr-1"></i>
                            {{ $completed }}/3 berkas
                        </span>
                        <a href="{{ route('dospem.validation') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                            <i class="fas fa-eye mr-1"></i>
                            Detail
                        </a>
                    </div>
                </div>
            </li>
            @empty
            <li class="px-6 py-4 text-center text-gray-500">Tidak ada mahasiswa bimbingan</li>
            @endforelse
        </ul>
    </div>
</div>
