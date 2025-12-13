@extends('layouts.app')

@section('title', 'Instansi Mitra - SIP PKL')

@section('content')
<style>
/* Modal centering - Desktop (default) */
#addMitraModal .modal-dialog-scale {
    transform: translate(-50%, -50%);
}

/* Scale down modal on mobile */
@media (max-width: 768px) {
    #addMitraModal .modal-dialog-scale {
        transform: translate(-50%, -50%) scale(0.75);
    }
}
</style>
<div class="space-y-4 md:space-y-6">
    <!-- Header -->
    <div class="bg-white shadow-sm rounded-xl p-4 md:p-6 border border-gray-100">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 md:gap-4">
            <div>
                <h1 class="text-xl md:text-2xl font-bold text-gray-900 flex items-center gap-2 md:gap-3">
                    <div class="w-9 h-9 md:w-10 md:h-10 bg-blue-50 rounded-lg flex items-center justify-center border border-blue-100">
                        <i class="fas fa-building text-blue-600 text-sm md:text-base"></i>
                    </div>
                    Instansi Mitra
                </h1>
                <p class="text-gray-500 mt-0.5 md:mt-1 ml-11 md:ml-14 text-sm hidden md:block">Pilih instansi mitra terbaik untuk PKL Anda</p>
            </div>
            @if($profilMahasiswa && $profilMahasiswa->mitra_selected)
                @php
                    $selectedMitra = \App\Models\Mitra::find($profilMahasiswa->mitra_selected);
                @endphp
                @if($selectedMitra)
                <div class="bg-green-50 border border-green-200 rounded-lg px-3 md:px-4 py-2 md:py-3 flex items-center gap-2 md:gap-3">
                    <div class="w-6 h-6 md:w-8 md:h-8 bg-green-100 rounded-full flex items-center justify-center text-green-600">
                        <i class="fas fa-check text-xs md:text-sm"></i>
                    </div>
                    <div>
                        <p class="text-[10px] md:text-xs text-green-700 font-semibold uppercase tracking-wide">Mitra Terpilih</p>
                        <p class="font-medium text-gray-900 text-xs md:text-base truncate max-w-[150px] md:max-w-none">{{ $selectedMitra->nama }}</p>
                    </div>
                </div>
                @endif
            @endif
        </div>

        <!-- Search Box -->
        <div class="mt-4 md:mt-6">
            <form method="GET" action="{{ route('mitra') }}" class="flex flex-col gap-2 md:gap-3">
                <div class="flex flex-col md:flex-row gap-2 md:gap-3">
                    <div class="flex-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text"
                               name="search"
                               id="searchInput"
                               value="{{ request('search') }}"
                               placeholder="Cari nama, alamat..."
                               class="w-full pl-10 pr-4 py-2 md:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition-shadow">
                    </div>
                    
                    <div class="relative min-w-0 md:min-w-[200px]">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-sort text-gray-400"></i>
                        </div>
                        @php $currentSort = request('sort', 'ranking'); @endphp
                        <select name="sort" onchange="this.form.submit()" 
                                class="w-full pl-10 pr-8 py-2 md:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm appearance-none bg-white cursor-pointer">
                            <option value="ranking" {{ $currentSort == 'ranking' ? 'selected' : '' }}>Rekomendasi</option>
                            <option value="terbaru" {{ $currentSort == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                            <option value="abjad" {{ $currentSort == 'abjad' ? 'selected' : '' }}>Abjad (A-Z)</option>
                            <option value="jarak" {{ $currentSort == 'jarak' ? 'selected' : '' }}>Jarak</option>
                            <option value="honor" {{ $currentSort == 'honor' ? 'selected' : '' }}>Honor</option>
                            <option value="fasilitas" {{ $currentSort == 'fasilitas' ? 'selected' : '' }}>Fasilitas</option>
                            <option value="kesesuaian" {{ $currentSort == 'kesesuaian' ? 'selected' : '' }}>Kesesuaian</option>
                            <option value="kebersihan" {{ $currentSort == 'kebersihan' ? 'selected' : '' }}>Kebersihan</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                            class="flex-1 md:flex-none px-4 md:px-6 py-2 md:py-2.5 bg-gray-900 text-white rounded-lg hover:bg-black font-medium text-sm transition-colors shadow-sm">
                        Cari
                    </button>
                    
                    <button type="button" onclick="openAddMitraModal()"
                            class="px-4 md:px-6 py-2 md:py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium text-sm transition-colors shadow-sm flex items-center justify-center">
                        <i class="fas fa-plus md:mr-2"></i>
                        <span class="hidden md:inline">Tambah</span>
                    </button>

                    @if(request('search'))
                    <a href="{{ route('mitra') }}"
                       class="px-4 py-2 md:py-2.5 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-colors text-center">
                        <i class="fas fa-times"></i>
                    </a>
                    @endif
                </div>
            </form>
        </div>

        @if(request('search'))
        <div class="mt-4 pt-4 border-t border-gray-100">
            <p class="text-sm text-gray-500">
                Hasil pencarian untuk: <span class="font-medium text-gray-900">"{{ request('search') }}"</span>
            </p>
        </div>
        @endif
    </div>

    <!-- Mitra List -->
    <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-6">
        @forelse($mitra as $m)
        <div class="relative bg-white shadow-sm rounded-xl border border-gray-200 p-3 md:p-6 hover:shadow-md hover:border-blue-300 transition-all duration-300 group flex flex-col
            {{ $profilMahasiswa && $profilMahasiswa->mitra_selected == $m->id ? 'ring-2 ring-green-500 border-green-500 bg-green-50/30' : '' }}">

            @if($profilMahasiswa && $profilMahasiswa->mitra_selected == $m->id)
            <div class="absolute -top-2 -left-2 md:-top-3 md:-left-3 bg-green-600 text-white px-2 md:px-3 py-0.5 md:py-1 rounded-full shadow-sm flex items-center gap-1 text-[10px] md:text-xs font-bold">
                <i class="fas fa-check"></i>
                <span class="hidden md:inline">Pilihan Anda</span>
            </div>
            @endif
            
            @if($isRankingSort && isset($m->rank))
            <div class="absolute top-2 right-2 md:top-4 md:right-4 w-6 h-6 md:w-8 md:h-8 rounded-full flex items-center justify-center font-bold text-[10px] md:text-sm border
                {{ $m->rank == 1 ? 'bg-yellow-50 text-yellow-700 border-yellow-200' :
                   ($m->rank == 2 ? 'bg-gray-50 text-gray-700 border-gray-200' :
                   ($m->rank == 3 ? 'bg-orange-50 text-orange-700 border-orange-200' :
                   'bg-blue-50 text-blue-700 border-blue-200')) }}">
                {{ $m->rank }}
            </div>
            @endif

            <div class="flex items-start flex-1 mb-2 md:mb-4">
                <div class="flex-shrink-0 hidden md:block">
                    <div class="h-12 w-12 rounded-lg bg-gray-50 flex items-center justify-center border border-gray-100 group-hover:bg-blue-50 group-hover:border-blue-100 transition-colors">
                        <i class="fas fa-building text-gray-400 group-hover:text-blue-500 transition-colors"></i>
                    </div>
                </div>
                <div class="md:ml-4 flex-1 pr-6 md:pr-8">
                    <h3 class="text-sm md:text-lg font-bold text-gray-900 group-hover:text-blue-600 transition-colors line-clamp-2">{{ $m->nama }}</h3>
                    
                    @if($m->alamat)
                    <div class="mt-1 flex flex-col gap-y-0.5 md:gap-y-1 text-xs md:text-sm text-gray-500">
                        <div class="flex items-start">
                            <i class="fas fa-map-marker-alt text-gray-400 mr-1 md:mr-1.5 mt-0.5 text-[10px] md:text-xs"></i>
                            <span class="line-clamp-1">{{ $m->alamat }}</span>
                        </div>
                        @if($m->kontak)
                        <div class="flex items-center hidden md:flex">
                            <i class="fas fa-phone text-gray-400 mr-1.5 text-xs"></i>
                            <span class="truncate">{{ $m->kontak }}</span>
                        </div>
                        @endif
                        @if($m->created_by)
                        <div class="flex items-center mt-1">
                            <i class="fas fa-user-plus text-blue-400 mr-1 md:mr-1.5 text-[10px] md:text-xs"></i>
                            <span class="text-blue-600 text-[10px] md:text-xs">
                                Dibuat oleh {{ $m->creator ? $m->creator->name : $m->created_by }}
                            </span>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
            
            <div class="space-y-2 md:space-y-3 mb-2 md:mb-4">
                <!-- Kriteria Penilaian - Hidden if numeric criteria are missing/null -->
                @if($m->honor !== null)
                <div class="border-t border-b border-gray-100 py-3 md:py-4">
                    <div class="space-y-2 text-xs md:text-sm">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center text-gray-500">
                                <i class="fas fa-money-bill-wave w-5 text-center mr-2 text-green-500"></i>
                                <span>Honor</span>
                            </div>
                            <span class="font-semibold {{ $m->honor >= 5 ? 'text-green-600' : 'text-gray-700' }}">{{ $m->honor_label }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center text-gray-500">
                                <i class="fas fa-star w-5 text-center mr-2 text-yellow-500"></i>
                                <span>Fasilitas</span>
                            </div>
                            <span class="font-semibold {{ $m->fasilitas >= 4 ? 'text-green-600' : 'text-gray-700' }}">{{ $m->fasilitas_label }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center text-gray-500">
                                <i class="fas fa-road w-5 text-center mr-2 text-blue-500"></i>
                                <span>Jarak</span>
                            </div>
                            <span class="font-semibold text-gray-700">{{ $m->jarak }} km</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center text-gray-500">
                                <i class="fas fa-user-check w-5 text-center mr-2 text-indigo-500"></i>
                                <span>Kesesuaian</span>
                            </div>
                            <span class="font-semibold {{ $m->kesesuaian_jurusan >= 4 ? 'text-green-600' : 'text-gray-700' }}">{{ $m->kesesuaian_jurusan_label }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center text-gray-500">
                                <i class="fas fa-broom w-5 text-center mr-2 text-purple-500"></i>
                                <span>Kebersihan</span>
                            </div>
                            <span class="font-semibold {{ $m->tingkat_kebersihan >= 4 ? 'text-green-600' : 'text-gray-700' }}">{{ $m->tingkat_kebersihan_label }}</span>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Jumlah Mahasiswa & Kuota -->
            <div class="mt-auto pt-2 md:pt-4 border-t border-gray-100">
                @php
                    $sisaKuota = $m->max_mahasiswa - $m->mahasiswa_count;
                    $persentaseTerisi = $m->max_mahasiswa > 0 ? ($m->mahasiswa_count / $m->max_mahasiswa) * 100 : 0;
                    $kuotaPenuh = $m->mahasiswa_count >= $m->max_mahasiswa;
                @endphp
                <div class="space-y-1.5 md:space-y-2">
                    <div class="flex justify-between items-center text-[10px] md:text-xs">
                        <span class="text-gray-500">Kuota</span>
                        <span class="font-medium text-gray-700">{{ $m->mahasiswa_count }}/{{ $m->max_mahasiswa }}</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-1 md:h-1.5 overflow-hidden">
                        <div class="h-1 md:h-1.5 rounded-full transition-all duration-300 {{ $kuotaPenuh ? 'bg-red-500' : ($persentaseTerisi > 80 ? 'bg-yellow-500' : 'bg-blue-500') }}"
                             style="width: {{ min($persentaseTerisi, 100) }}%"></div>
                    </div>
                    
                    @if(!$kuotaPenuh && $sisaKuota <= 2)
                        <p class="text-[9px] md:text-xs text-gray-500 font-medium flex items-center justify-center bg-gray-50 py-0.5 md:py-1 rounded">
                            <i class="fas fa-info-circle mr-1"></i>Sisa {{ $sisaKuota }}
                        </p>
                    @endif
                </div>

                <!-- Pilih Mitra Button -->
                <div class="mt-2 md:mt-4">
                    @if($profilMahasiswa && $profilMahasiswa->mitra_selected == $m->id)
                        <button disabled
                                class="w-full bg-green-600 text-white py-1.5 md:py-2 px-3 md:px-4 rounded-lg cursor-not-allowed font-medium text-[10px] md:text-sm shadow-sm opacity-90">
                            <i class="fas fa-check mr-1 md:mr-2"></i>Dipilih
                        </button>
                    @elseif($kuotaPenuh)
                        <button disabled
                                class="w-full bg-gray-100 text-gray-400 py-1.5 md:py-2 px-3 md:px-4 rounded-lg cursor-not-allowed font-medium text-[10px] md:text-sm border border-gray-200">
                            <i class="fas fa-lock mr-1 md:mr-2"></i>Penuh
                        </button>
                    @else
                        <button onclick="selectMitra({{ $m->id }}, '{{ $m->nama }}')"
                                class="w-full bg-white border border-blue-600 text-blue-600 py-1.5 md:py-2 px-3 md:px-4 rounded-lg hover:bg-blue-50 transition-colors duration-200 font-medium text-[10px] md:text-sm">
                            Pilih
                        </button>
                    @endif
                </div>
            </div>
            

        </div>
        @empty
        <div class="col-span-full">
            <div class="bg-white shadow-sm rounded-xl p-12 text-center border border-gray-200">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-search text-3xl text-gray-300"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ditemukan</h3>
                <p class="text-gray-500 mb-6">Tidak ada instansi mitra yang sesuai dengan pencarian Anda.</p>
                <a href="{{ route('mitra') }}" class="inline-flex items-center px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-black transition-colors text-sm font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Lihat Semua Mitra
                </a>
            </div>
        </div>
        @endforelse
    </div>
</div>

<!-- Modal Konfirmasi Penggantian Mitra - Will be moved to body via JavaScript -->
<div id="modalPenggantianMitra" class="fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full hidden backdrop-blur-sm flex items-center justify-center p-4" style="z-index: 9999;">
    <div class="relative bg-white w-full max-w-lg shadow-xl rounded-2xl mx-auto">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-900">Konfirmasi Penggantian Mitra</h3>
            <p class="text-sm text-gray-500 mt-1">Anda sudah memilih mitra. Mengapa ingin mengganti?</p>
        </div>

        <div class="p-6 space-y-4">
            <label class="flex items-start p-4 border border-gray-200 rounded-xl cursor-pointer hover:border-blue-300 hover:bg-blue-50 transition-all group">
                <input type="radio" name="jenis_alasan" value="ditolak" class="mt-1 mr-3 text-blue-600 focus:ring-blue-500" required>
                <div>
                    <div class="font-semibold text-gray-900 group-hover:text-blue-700">Ditolak Instansi</div>
                    <div class="text-sm text-gray-500 mt-0.5">Saya mengajukan lamaran tetapi ditolak oleh instansi tersebut</div>
                </div>
            </label>

            <label class="flex items-start p-4 border border-gray-200 rounded-xl cursor-pointer hover:border-blue-300 hover:bg-blue-50 transition-all group">
                <input type="radio" name="jenis_alasan" value="alasan_tertentu" class="mt-1 mr-3 text-blue-600 focus:ring-blue-500">
                <div class="flex-1">
                    <div class="font-semibold text-gray-900 group-hover:text-blue-700">Alasan Lainnya</div>
                    <div class="text-sm text-gray-500 mt-0.5 mb-2">Ada kendala atau alasan lain</div>
                    <textarea id="alasan_tertentu" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white"
                              rows="2" placeholder="Jelaskan alasan Anda..." disabled></textarea>
                </div>
            </label>

            <label class="flex items-start p-4 border border-gray-200 rounded-xl cursor-pointer hover:border-blue-300 hover:bg-blue-50 transition-all group">
                <input type="radio" name="jenis_alasan" value="pilihan_pribadi" class="mt-1 mr-3 text-blue-600 focus:ring-blue-500">
                <div class="flex-1">
                    <div class="font-semibold text-gray-900 group-hover:text-blue-700">Pilihan Pribadi</div>
                    <div class="text-sm text-gray-500 mt-0.5 mb-2">Saya berubah pikiran atas keinginan sendiri</div>
                    <textarea id="alasan_pribadi" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white"
                              rows="2" placeholder="Jelaskan alasan Anda..." disabled></textarea>
                </div>
            </label>
        </div>

        <div class="p-6 border-t border-gray-100 bg-gray-50 rounded-b-2xl flex justify-end space-x-3">
            <button type="button" onclick="closeModalPenggantian()"
                    class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium text-sm shadow-sm">
                Batal
            </button>
            <button type="button" onclick="konfirmasiPenggantian()"
                    class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium text-sm shadow-md">
                Konfirmasi
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
            
            // Focus textarea if enabled
            if (this.value === 'alasan_tertentu') {
                document.getElementById('alasan_tertentu').focus();
            } else if (this.value === 'pilihan_pribadi') {
                document.getElementById('alasan_pribadi').focus();
            }
        });
    });
    
    // Move modals to body to escape transform stacking context
    const modalPenggantian = document.getElementById('modalPenggantianMitra');
    const addMitraModal = document.getElementById('addMitraModal');
    
    if (modalPenggantian) {
        document.body.appendChild(modalPenggantian);
    }
    if (addMitraModal) {
        document.body.appendChild(addMitraModal);
    }
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
        document.body.style.overflow = 'hidden';
    } else {
        // Langsung pilih mitra
        kirimPilihanMitra(mitraId, mitraName, null, null);
    }
}

function closeModalPenggantian() {
    document.getElementById('modalPenggantianMitra').classList.add('hidden');
    document.body.style.overflow = 'auto';
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



<!-- Add Mitra Modal - Will be moved to body via JavaScript -->
<div id="addMitraModal" class="fixed hidden overflow-y-auto backdrop-blur-sm" style="z-index: 9999; top: -50%; left: -50%; width: 200%; height: 200%;" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Background overlay - 2x size to cover full screen -->
    <div class="absolute inset-0 bg-gray-900 bg-opacity-50 transition-opacity" aria-hidden="true" onclick="closeAddMitraModal()"></div>
    
    <!-- Modal container - centered at 50% of 200% = actual center -->
    <div class="absolute flex items-center justify-center modal-dialog-scale" style="top: 50%; left: 50%; width: 100vw; height: 100vh;">
        <div class="relative bg-white rounded-2xl text-left overflow-hidden shadow-xl w-full max-w-lg mx-4">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-50 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-building text-blue-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">
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
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                                            placeholder="Contoh: PT. Teknologi Indonesia"
                                        >
                                    </div>
                                    
                                    <!-- Divider -->
                                    <div class="border-t border-gray-100 pt-3">
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
                                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
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
                                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
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
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                <button type="button" onclick="document.getElementById('addMitraForm').submit()" class="w-full inline-flex items-center justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    <i class="fas fa-save mr-2"></i>Simpan
                </button>
                <button type="button" onclick="closeAddMitraModal()" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function openAddMitraModal() {
    document.getElementById('addMitraModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeAddMitraModal() {
    document.getElementById('addMitraModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    // Reset form
    document.getElementById('addMitraForm').reset();
}
</script>

@endsection