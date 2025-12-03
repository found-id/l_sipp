@extends('layouts.app')

@section('title', 'Instansi Mitra - SIP PKL')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 shadow-lg rounded-xl p-6 text-white">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold flex items-center gap-3">
                    <i class="fas fa-building"></i>
                    Instansi Mitra PKL
                </h1>
                <p class="text-blue-100 mt-2">Pilih instansi mitra terbaik untuk PKL Anda</p>
            </div>
            @if($profilMahasiswa && $profilMahasiswa->mitra_selected)
                @php
                    $selectedMitra = \App\Models\Mitra::find($profilMahasiswa->mitra_selected);
                @endphp
                @if($selectedMitra)
                <div class="bg-white/10 backdrop-blur-sm border-2 border-white/30 rounded-lg px-4 py-3">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-green-300"></i>
                        <div>
                            <p class="text-xs text-blue-100 uppercase tracking-wide">Mitra Terpilih</p>
                            <p class="font-semibold text-white">{{ $selectedMitra->nama }}</p>
                        </div>
                    </div>
                </div>
                @endif
            @endif
        </div>

        <!-- Search Box -->
        <div>
            <form method="GET" action="{{ route('mitra') }}" class="flex gap-2">
                <div class="flex-1">
                    <input type="text"
                           name="search"
                           id="searchInput"
                           value="{{ request('search') }}"
                           placeholder="Cari berdasarkan nama, alamat, atau kontak..."
                           class="w-full px-4 py-3 border-0 rounded-lg focus:ring-2 focus:ring-white text-gray-900 placeholder-gray-400">
                </div>
                <button type="submit"
                        class="px-6 py-3 bg-white text-blue-600 rounded-lg hover:bg-blue-50 font-medium transition-all hover:scale-105">
                    <i class="fas fa-search mr-2"></i>Cari
                </button>

                <div class="relative">
                    <select name="sort" onchange="this.form.submit()" 
                            class="appearance-none px-6 py-3 pr-10 bg-white text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-all focus:outline-none focus:ring-2 focus:ring-white cursor-pointer">
                        <option value="" {{ request('sort') == '' ? 'selected' : '' }}>Abjad (A-Z)</option>
                        <option value="ranking" {{ request('sort') == 'ranking' ? 'selected' : '' }}>Rekomendasi Sistem</option>
                        <option value="jarak" {{ request('sort') == 'jarak' ? 'selected' : '' }}>Jarak Terdekat</option>
                        <option value="honor" {{ request('sort') == 'honor' ? 'selected' : '' }}>Honor Tertinggi</option>
                        <option value="fasilitas" {{ request('sort') == 'fasilitas' ? 'selected' : '' }}>Fasilitas Terbaik</option>
                        <option value="kesesuaian" {{ request('sort') == 'kesesuaian' ? 'selected' : '' }}>Kesesuaian Jurusan</option>
                        <option value="kebersihan" {{ request('sort') == 'kebersihan' ? 'selected' : '' }}>Kebersihan Terbaik</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-700">
                        <i class="fas fa-sort"></i>
                    </div>
                </div>

                @if(request('search'))
                <a href="{{ route('mitra') }}"
                   class="px-4 py-3 bg-white/20 backdrop-blur-sm text-white rounded-lg hover:bg-white/30 transition-all">
                    <i class="fas fa-times"></i>
                </a>
                @endif
            </form>
        </div>

        @if(request('search'))
        <div class="mt-3">
            <p class="text-sm text-blue-100">
                Hasil pencarian untuk: <span class="font-medium text-white">"{{ request('search') }}"</span>
            </p>
        </div>
        @endif
    </div>

    <!-- Mitra List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($mitra as $m)
        <div class="relative bg-white shadow rounded-lg p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group flex flex-col
            {{ $profilMahasiswa && $profilMahasiswa->mitra_selected == $m->id ? 'ring-4 ring-green-400 shadow-xl' : '' }}
            {{ $isRankingSort && isset($m->rank) && $m->rank <= 3 ? 'ring-2 ' . ($m->rank == 1 ? 'ring-yellow-400' : ($m->rank == 2 ? 'ring-gray-400' : 'ring-amber-600')) : '' }}">

            @if($profilMahasiswa && $profilMahasiswa->mitra_selected == $m->id)
            <div class="absolute -top-3 -left-3 bg-green-500 text-white px-3 py-1 rounded-full shadow-lg flex items-center gap-1 text-xs font-bold">
                <i class="fas fa-check-circle"></i>
                <span>Pilihan Anda</span>
            </div>
            @endif
            @if($isRankingSort && isset($m->rank))
            <div class="absolute -top-3 -right-3 w-12 h-12 rounded-full flex items-center justify-center font-bold text-lg shadow-lg
                {{ $m->rank == 1 ? 'bg-gradient-to-br from-yellow-400 to-yellow-600 text-white' :
                   ($m->rank == 2 ? 'bg-gradient-to-br from-gray-300 to-gray-500 text-white' :
                   ($m->rank == 3 ? 'bg-gradient-to-br from-amber-600 to-amber-800 text-white' :
                   'bg-gradient-to-br from-blue-500 to-blue-700 text-white')) }}">
                @if($m->rank == 1)
                    <i class="fas fa-trophy"></i>
                @elseif($m->rank == 2)
                    <i class="fas fa-medal"></i>
                @elseif($m->rank == 3)
                    <i class="fas fa-award"></i>
                @else
                    {{ $m->rank }}
                @endif
            </div>
            @endif
            <div class="flex items-start flex-1">
                <div class="flex-shrink-0">
                    <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center group-hover:bg-blue-200 transition-colors duration-300">
                        <i class="fas fa-building text-blue-600"></i>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-lg font-medium text-gray-900 group-hover:text-blue-600 transition-colors duration-300">{{ $m->nama }}</h3>
                    
                    @if($m->alamat)
                    <div class="mt-2">
                        <div class="flex items-start">
                            <i class="fas fa-map-marker-alt text-gray-400 mr-2 mt-1"></i>
                            <p class="text-sm text-gray-600">{{ $m->alamat }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($m->kontak)
                    <div class="mt-2">
                        <div class="flex items-center">
                            <i class="fas fa-phone text-gray-400 mr-2"></i>
                            <p class="text-sm text-gray-600">{{ $m->kontak }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Kriteria Penilaian - Hidden for user-added mitra -->
                    @if(!$m->created_by)
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <h4 class="text-sm font-medium text-gray-500 mb-2">Kriteria Penilaian</h4>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <i class="fas fa-road text-gray-400 mr-2 w-4 text-center"></i>
                                <p class="text-sm text-gray-600">Jarak: <span class="font-semibold">{{ $m->jarak }} km</span></p>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-money-bill-wave text-gray-400 mr-2 w-4 text-center"></i>
                                <p class="text-sm text-gray-600">Honor: <span class="font-semibold {{ $m->honor >= 5 ? 'text-green-600' : 'text-red-600' }}">{{ $m->honor_label }}</span></p>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-couch text-gray-400 mr-2 w-4 text-center"></i>
                                <p class="text-sm text-gray-600">Fasilitas: <span class="font-semibold {{ $m->fasilitas >= 4 ? 'text-green-600' : ($m->fasilitas >= 3 ? 'text-blue-600' : 'text-orange-600') }}">{{ $m->fasilitas_label }}</span></p>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-graduation-cap text-gray-400 mr-2 w-4 text-center"></i>
                                <p class="text-sm text-gray-600">Kesesuaian Jurusan: <span class="font-semibold {{ $m->kesesuaian_jurusan >= 4 ? 'text-green-600' : ($m->kesesuaian_jurusan >= 3 ? 'text-blue-600' : 'text-orange-600') }}">{{ $m->kesesuaian_jurusan_label }}</span></p>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-broom text-gray-400 mr-2 w-4 text-center"></i>
                                <p class="text-sm text-gray-600">Tingkat Kebersihan: <span class="font-semibold {{ $m->tingkat_kebersihan >= 4 ? 'text-green-600' : ($m->tingkat_kebersihan >= 3 ? 'text-blue-600' : 'text-orange-600') }}">{{ $m->tingkat_kebersihan_label }}</span></p>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    @if($m->created_by)
                    <div class="mt-3 pt-3 border-t border-gray-100">
                        <p class="text-xs text-gray-500 italic">
                            <i class="fas fa-user-edit mr-1"></i>Ditambahkan oleh: {{ $m->created_by }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Jumlah Mahasiswa & Kuota -->
            <div class="mt-auto pt-4">
                @php
                    $sisaKuota = $m->max_mahasiswa - $m->mahasiswa_count;
                    $persentaseTerisi = $m->max_mahasiswa > 0 ? ($m->mahasiswa_count / $m->max_mahasiswa) * 100 : 0;
                    $kuotaPenuh = $m->mahasiswa_count >= $m->max_mahasiswa;
                @endphp
                <div class="space-y-2">
                    <!-- Jumlah mahasiswa - rata kanan dan kecil -->
                    <div class="flex justify-end">
                        <span class="text-xs text-gray-500">
                            {{ $m->mahasiswa_count }}/{{ $m->max_mahasiswa }}
                        </span>
                    </div>
                    <!-- Progress Bar - warna abu-abu -->
                    <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                        <div class="h-2 rounded-full transition-all duration-300 bg-gray-400"
                             style="width: {{ min($persentaseTerisi, 100) }}%"></div>
                    </div>
                    @if($kuotaPenuh)
                        <p class="text-xs text-gray-500 font-medium text-center">
                            <i class="fas fa-lock mr-1"></i>Kuota penuh
                        </p>
                    @elseif($sisaKuota == 1)
                        <p class="text-xs text-yellow-600 font-medium text-center">
                            <i class="fas fa-exclamation-triangle mr-1"></i>Sisa 1 kuota
                        </p>
                    @elseif($m->mahasiswa_count >= 2)
                        <p class="text-xs text-gray-500 text-center">
                            Sisa {{ $sisaKuota }} kuota tersedia
                        </p>
                    @endif
                </div>
            </div>

            <!-- Pilih Mitra Button -->
            <div class="mt-4 pt-4 border-t border-gray-200">
                @if($profilMahasiswa && $profilMahasiswa->mitra_selected == $m->id)
                    <button disabled
                            class="w-full bg-green-100 text-green-800 py-2 px-4 rounded-lg cursor-not-allowed font-medium border-2 border-green-300">
                        <i class="fas fa-check-circle mr-2"></i>Telah dipilih
                    </button>
                @elseif($kuotaPenuh)
                    <button disabled
                            class="w-full bg-gray-100 text-gray-500 py-2 px-4 rounded-lg cursor-not-allowed font-medium border-2 border-gray-200">
                        <i class="fas fa-lock mr-2"></i>Kuota Penuh
                    </button>
                @else
                    <button onclick="selectMitra({{ $m->id }}, '{{ $m->nama }}')"
                            class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200 font-medium">
                        <i class="fas fa-check mr-2"></i>Pilih Instansi Mitra
                    </button>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="bg-white shadow rounded-lg p-8 text-center">
                @if(request('search'))
                    <i class="fas fa-search text-4xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ditemukan</h3>
                    <p class="text-gray-600">Tidak ada instansi mitra yang sesuai dengan pencarian "<span class="font-medium">{{ request('search') }}</span>".</p>
                    <a href="{{ route('mitra') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Lihat Semua Mitra
                    </a>
                @else
                    <i class="fas fa-building text-4xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Mitra</h3>
                    <p class="text-gray-600">Belum ada instansi mitra yang terdaftar.</p>
                @endif
            </div>
        </div>
        @endforelse
    </div>
</div>

<!-- Modal Konfirmasi Penggantian Mitra -->
<div id="modalPenggantianMitra" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-6 border w-full max-w-lg shadow-lg rounded-lg bg-white">
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Penggantian Instansi Mitra</h3>
            <p class="text-sm text-gray-600 mt-2">Anda sudah memilih instansi mitra sebelumnya. Mohon pilih alasan penggantian:</p>
        </div>

        <div class="space-y-3 mb-6">
            <label class="flex items-start p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                <input type="radio" name="jenis_alasan" value="ditolak" class="mt-1 mr-3" required>
                <div>
                    <div class="font-semibold text-gray-900">Saya tidak diterima dari instansi tersebut</div>
                    <div class="text-sm text-gray-600">Saya ditolak oleh instansi mitra sebelumnya</div>
                </div>
            </label>

            <label class="flex items-start p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                <input type="radio" name="jenis_alasan" value="alasan_tertentu" class="mt-1 mr-3">
                <div class="flex-1">
                    <div class="font-semibold text-gray-900">Karena suatu alasan</div>
                    <textarea id="alasan_tertentu" class="mt-2 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm"
                              rows="2" placeholder="Jelaskan alasan Anda..." disabled></textarea>
                </div>
            </label>

            <label class="flex items-start p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                <input type="radio" name="jenis_alasan" value="pilihan_pribadi" class="mt-1 mr-3">
                <div class="flex-1">
                    <div class="font-semibold text-gray-900">Atas pilihan pribadi</div>
                    <textarea id="alasan_pribadi" class="mt-2 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm"
                              rows="2" placeholder="Jelaskan alasan Anda..." disabled></textarea>
                </div>
            </label>
        </div>

        <div class="flex justify-end space-x-3">
            <button type="button" onclick="closeModalPenggantian()"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                Batal
            </button>
            <button type="button" onclick="konfirmasiPenggantian()"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-check mr-2"></i>Konfirmasi
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enable/disable textarea based on radio selection
    const radioButtons = document.querySelectorAll('input[name="jenis_alasan"]');
    radioButtons.forEach(radio => {
        radio.addEventListener('change', function() {
            document.getElementById('alasan_tertentu').disabled = this.value !== 'alasan_tertentu';
            document.getElementById('alasan_pribadi').disabled = this.value !== 'pilihan_pribadi';
        });
    });
});

let selectedMitraId = null;
let selectedMitraName = null;
const currentMitraId = {{ $profilMahasiswa && $profilMahasiswa->mitra_selected ? $profilMahasiswa->mitra_selected : 'null' }};

// Function to select mitra and redirect to documents page
function selectMitra(mitraId, mitraName) {
    selectedMitraId = mitraId;
    selectedMitraName = mitraName;

    // Jika user sudah punya mitra sebelumnya dan bukan mitra yang sama
    if (currentMitraId && currentMitraId !== mitraId) {
        // Tampilkan modal konfirmasi
        document.getElementById('modalPenggantianMitra').classList.remove('hidden');
    } else {
        // Langsung pilih mitra
        kirimPilihanMitra(mitraId, mitraName, null, null);
    }
}

function closeModalPenggantian() {
    document.getElementById('modalPenggantianMitra').classList.add('hidden');
    // Reset form
    document.querySelectorAll('input[name="jenis_alasan"]').forEach(radio => radio.checked = false);
    document.getElementById('alasan_tertentu').value = '';
    document.getElementById('alasan_pribadi').value = '';
    document.getElementById('alasan_tertentu').disabled = true;
    document.getElementById('alasan_pribadi').disabled = true;
}

function konfirmasiPenggantian() {
    const jenisAlasan = document.querySelector('input[name="jenis_alasan"]:checked');

    if (!jenisAlasan) {
        alert('Mohon pilih alasan penggantian instansi mitra');
        return;
    }

    let alasanLengkap = null;
    if (jenisAlasan.value === 'alasan_tertentu') {
        alasanLengkap = document.getElementById('alasan_tertentu').value.trim();
        if (!alasanLengkap) {
            alert('Mohon jelaskan alasan Anda');
            return;
        }
    } else if (jenisAlasan.value === 'pilihan_pribadi') {
        alasanLengkap = document.getElementById('alasan_pribadi').value.trim();
        if (!alasanLengkap) {
            alert('Mohon jelaskan alasan Anda');
            return;
        }
    }

    closeModalPenggantian();
    kirimPilihanMitra(selectedMitraId, selectedMitraName, jenisAlasan.value, alasanLengkap);
}

function kirimPilihanMitra(mitraId, mitraName, jenisAlasan, alasanLengkap) {
    // Show loading state (find the button that was clicked)
    const buttons = document.querySelectorAll('button[onclick*="selectMitra"]');
    let targetButton = null;
    buttons.forEach(btn => {
        if (btn.getAttribute('onclick').includes(`selectMitra(${mitraId},`)) {
            targetButton = btn;
        }
    });

    if (targetButton) {
        const originalText = targetButton.innerHTML;
        targetButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
        targetButton.disabled = true;
    }

    // Send AJAX request to save selected mitra
    const requestBody = {
        mitra_id: mitraId
    };

    if (jenisAlasan) {
        requestBody.jenis_alasan = jenisAlasan;
        requestBody.alasan_lengkap = alasanLengkap;
    }

    fetch('{{ route("documents.select-mitra") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(requestBody)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Redirect to documents page with success message
            window.location.href = '{{ route("documents.index") }}?tab=surat-balasan&mitra_selected=' + mitraId + '&success=' + encodeURIComponent('Instansi mitra "' + mitraName + '" berhasil dipilih!');
        } else {
            alert('Terjadi kesalahan: ' + (data.message || 'Gagal memilih instansi mitra'));
            if (targetButton) {
                targetButton.innerHTML = originalText;
                targetButton.disabled = false;
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memilih instansi mitra');
        if (targetButton) {
            targetButton.innerHTML = originalText;
            targetButton.disabled = false;
        }
    });
}
</script>

<!-- Floating Action Button -->
<button onclick="openAddMitraModal()" class="fixed bottom-8 right-8 bg-blue-600 hover:bg-blue-700 text-white rounded-full p-4 shadow-lg transition-transform hover:scale-110 focus:outline-none focus:ring-4 focus:ring-blue-300 z-50 flex items-center justify-center w-16 h-16">
    <i class="fas fa-plus text-2xl"></i>
</button>

<!-- Add Mitra Modal -->
<div id="addMitraModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeAddMitraModal()"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-building text-blue-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Tambah Tempat Magang Baru
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 mb-4">
                                Jika Anda tidak menemukan tempat magang yang dicari, silakan tambahkan di sini.
                            </p>
                            <form action="{{ route('mitra.store') }}" method="POST" id="addMitraForm">
                                @csrf
                                <div class="space-y-4">
                                    <!-- Nama Instansi -->
                                    <div>
                                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">
                                            Nama Instansi <span class="text-red-500">*</span>
                                        </label>
                                        <input 
                                            type="text" 
                                            name="nama" 
                                            id="nama" 
                                            required 
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                                            placeholder="Contoh: PT. Teknologi Indonesia"
                                        >
                                    </div>
                                    
                                    <!-- Divider -->
                                    <div class="border-t pt-3">
                                        <p class="text-xs text-gray-500 mb-3 flex items-center">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Field di bawah ini opsional
                                        </p>
                                        
                                        <!-- Alamat -->
                                        <div class="mb-3">
                                            <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">
                                                Alamat
                                            </label>
                                            <textarea 
                                                name="alamat" 
                                                id="alamat" 
                                                rows="2" 
                                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                                                placeholder="Alamat lengkap (opsional)"
                                            ></textarea>
                                        </div>
                                        
                                        <!-- Kontak -->
                                        <div>
                                            <label for="kontak" class="block text-sm font-medium text-gray-700 mb-1">
                                                Kontak
                                            </label>
                                            <input 
                                                type="text" 
                                                name="kontak" 
                                                id="kontak" 
                                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                                                placeholder="Email/No. HP (opsional)"
                                            >
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="document.getElementById('addMitraForm').submit()" class="w-full inline-flex items-center justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    <i class="fas fa-save mr-2"></i>Simpan
                </button>
                <button type="button" onclick="closeAddMitraModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function openAddMitraModal() {
    document.getElementById('addMitraModal').classList.remove('hidden');
}

function closeAddMitraModal() {
    document.getElementById('addMitraModal').classList.add('hidden');
    // Reset form
    document.getElementById('addMitraForm').reset();
}
</script>

@endsection