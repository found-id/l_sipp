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
        <div class="bg-white shadow rounded-lg p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group">
            <div class="flex items-start">
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
                    
                    <div class="mt-4 flex items-center justify-between">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check mr-1"></i>
                            Mitra Aktif
                        </span>
                        
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-users mr-1"></i>
                            <span class="font-medium">{{ $m->total_applications }}</span>
                            <span class="ml-1">mahasiswa</span>
                        </div>
                    </div>
                    
                    <!-- Pilih Mitra Button -->
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <button onclick="selectMitra({{ $m->id }}, '{{ $m->nama }}')" 
                                class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200 font-medium">
                            <i class="fas fa-check mr-2"></i>Pilih Instansi Mitra
                        </button>
                    </div>
                </div>
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

<script>
// Auto-focus search input when page loads
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.focus();
    }
});

// Function to select mitra and redirect to documents page
function selectMitra(mitraId, mitraName) {
    // Show loading state
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
    button.disabled = true;
    
    // Send AJAX request to save selected mitra
    fetch('{{ route("documents.select-mitra") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            mitra_id: mitraId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Redirect to documents page with success message
            window.location.href = '{{ route("documents.index") }}?tab=surat-balasan&mitra_selected=' + mitraId + '&success=' + encodeURIComponent('Instansi mitra "' + mitraName + '" berhasil dipilih!');
        } else {
            alert('Terjadi kesalahan: ' + (data.message || 'Gagal memilih instansi mitra'));
            button.innerHTML = originalText;
            button.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memilih instansi mitra');
        button.innerHTML = originalText;
        button.disabled = false;
    });
}
</script>
@endsection