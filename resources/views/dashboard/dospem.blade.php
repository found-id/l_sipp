<!-- Dosen Pembimbing Dashboard -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- Mahasiswa Bimbingan -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-users text-2xl text-blue-600"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Mahasiswa Bimbingan</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ $stats['mahasiswa_bimbingan'] }} mahasiswa</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Berkas Perlu Validasi -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-clock text-2xl text-yellow-600"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Perlu Validasi</dt>
                        <dd class="text-lg font-medium text-yellow-600">{{ $stats['berkas_perlu_validasi'] }} berkas</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Berkas Tervalidasi -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-2xl text-green-600"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Tervalidasi</dt>
                        <dd class="text-lg font-medium text-green-600">{{ $stats['berkas_tervalidasi'] }} berkas</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Mitra -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-building text-2xl text-purple-600"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Mitra</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ $stats['total_mitra'] }} mitra</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mahasiswa Bimbingan List -->
<div class="mt-8">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Mahasiswa Bimbingan</h3>
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
            @forelse($stats['mahasiswa_bimbingan_list'] as $profil)
            <li class="px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                <i class="fas fa-user text-gray-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">{{ $profil->user->name }}</div>
                            <div class="text-sm text-gray-500">NIM: {{ $profil->nim ?? 'N/A' }} • Prodi: {{ $profil->prodi }}</div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        @php
                            $completed = 0;
                            if($profil->user->khs()->tervalidasi()->exists()) $completed++;
                            if($profil->user->suratBalasan()->tervalidasi()->exists()) $completed++;
                            if($profil->user->laporanPkl()->tervalidasi()->exists()) $completed++;
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

<!-- Quick Actions -->
<div class="mt-8">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Aksi Cepat</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('dospem.validation') }}" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <i class="fas fa-clipboard-check text-2xl text-blue-600 mr-4"></i>
                <div>
                    <h4 class="font-medium text-gray-900">Validasi Berkas</h4>
                    <p class="text-sm text-gray-600">Validasi dokumen mahasiswa</p>
                </div>
            </div>
        </a>

        <a href="{{ route('mitra') }}" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <i class="fas fa-building text-2xl text-green-600 mr-4"></i>
                <div>
                    <h4 class="font-medium text-gray-900">Instansi Mitra</h4>
                    <p class="text-sm text-gray-600">Lihat daftar instansi mitra</p>
                </div>
            </div>
        </a>

        <a href="#" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <i class="fas fa-list text-2xl text-purple-600 mr-4"></i>
                <div>
                    <h4 class="font-medium text-gray-900">Laporan</h4>
                    <p class="text-sm text-gray-600">Lihat laporan bimbingan</p>
                </div>
            </div>
        </a>
    </div>
</div>
