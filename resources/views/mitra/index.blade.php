@extends('layouts.app')

@section('title', 'Instansi Mitra - SIPP PKL')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg p-6">
        <h1 class="text-2xl font-bold text-gray-900">Instansi Mitra</h1>
        <p class="text-gray-600 mt-2">Daftar instansi mitra PKL</p>
        
        <!-- Search Box -->
        <div class="mt-4">
            <form method="GET" action="{{ route('mitra') }}" class="flex gap-2">
                <div class="flex-1">
                    <input type="text" 
                           name="search" 
                           id="searchInput"
                           value="{{ request('search') }}"
                           placeholder="Cari berdasarkan nama, alamat, atau kontak..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    <i class="fas fa-search mr-2"></i>Cari
                </button>

                @if(request('sort') === 'ranking')
                    <a href="{{ request()->fullUrlWithQuery(['sort' => null]) }}" 
                       class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                        <i class="fas fa-font mr-2"></i>Urutkan Abjad
                    </a>
                @else
                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'ranking']) }}" 
                       class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">
                        <i class="fas fa-star mr-2"></i>Urutkan Peringkat
                    </a>
                @endif

                @if(request('search'))
                <a href="{{ route('mitra') }}" 
                   class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                    <i class="fas fa-times mr-2"></i>Reset
                </a>
                @endif
            </form>
        </div>
        
        @if(request('search'))
        <div class="mt-3">
            <p class="text-sm text-gray-600">
                Hasil pencarian untuk: <span class="font-medium">"{{ request('search') }}"</span>
            </p>
        </div>
        @endif
    </div>

    <!-- Mitra List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($mitra as $m)
        <div class="relative bg-white shadow rounded-lg p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group flex flex-col {{ $isRankingSort && isset($m->rank) && $m->rank <= 3 ? 'ring-2 ' . ($m->rank == 1 ? 'ring-yellow-400' : ($m->rank == 2 ? 'ring-gray-400' : 'ring-amber-600')) : '' }}">
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

                    <!-- Kriteria Penilaian -->
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <h4 class="text-sm font-medium text-gray-500 mb-2">Kriteria Penilaian</h4>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <i class="fas fa-road text-gray-400 mr-2 w-4 text-center"></i>
                                <p class="text-sm text-gray-600">Jarak: <span class="font-semibold">{{ $m->jarak }} km</span></p>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-money-bill-wave text-gray-400 mr-2 w-4 text-center"></i>
                                <p class="text-sm text-gray-600">Honor: <span class="font-semibold {{ $m->honor >= 4 ? 'text-green-600' : ($m->honor >= 3 ? 'text-blue-600' : 'text-orange-600') }}">{{ $m->honor_label }}</span></p>
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
                    
                    <div class="mt-4 flex items-center justify-end">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-users mr-1"></i>
                            <span class="font-medium">{{ $m->mahasiswa_count }}</span>
                            <span class="ml-1">mahasiswa</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pilih Mitra Button -->
            <div class="mt-4 pt-4 border-t border-gray-200">
                @if($profilMahasiswa && $profilMahasiswa->mitra_selected == $m->id)
                    <button disabled
                            class="w-full bg-green-100 text-green-800 py-2 px-4 rounded-lg cursor-not-allowed font-medium border-2 border-green-300">
                        <i class="fas fa-check-circle mr-2"></i>Telah dipilih
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
// Auto-focus search input when page loads
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.focus();
    }

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
@endsection