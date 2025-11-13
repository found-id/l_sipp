@extends('layouts.app')

@section('title', 'Log Aktivitas - SIPP PKL')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Log Aktivitas</h1>
                <p class="text-gray-600 mt-2">Riwayat aktivitas dalam sistem</p>
            </div>
            @if(auth()->user()->role === 'admin')
                <button onclick="confirmClearActivities()" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    <i class="fas fa-trash mr-2"></i>
                    Bersihkan Semua Aktivitas
                </button>
            @endif
        </div>
    </div>

    <!-- Search and Sort -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari Aktivitas</label>
                <form method="GET" class="flex">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari nama user, mahasiswa, atau tipe..."
                           class="flex-1 px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-r-md hover:bg-indigo-700">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
            
            <!-- Sort By -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Urutkan Berdasarkan</label>
                <select name="sort_by" onchange="updateSort()" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="tanggal_dibuat" {{ request('sort_by') == 'tanggal_dibuat' ? 'selected' : '' }}>Tanggal</option>
                    <option value="tipe" {{ request('sort_by') == 'tipe' ? 'selected' : '' }}>Tipe Aktivitas</option>
                </select>
            </div>
            
            <!-- Sort Order -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Urutan</label>
                <select name="sort_order" onchange="updateSort()" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Terbaru</option>
                    <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Terlama</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Activities List -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Daftar Aktivitas</h3>
        </div>
        
        <div class="divide-y divide-gray-200">
            @forelse($activities as $activity)
            <div class="px-6 py-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 rounded-full flex items-center justify-center
                            @if($activity->tipe === 'upload_dokumen') bg-blue-100
                            @elseif($activity->tipe === 'validasi_dokumen') bg-green-100
                            @elseif($activity->tipe === 'login') bg-purple-100
                            @elseif($activity->tipe === 'logout') bg-red-100
                            @else bg-gray-100 @endif">
                            <i class="fas 
                                @if($activity->tipe === 'upload_dokumen') fa-upload
                                @elseif($activity->tipe === 'validasi_dokumen') fa-check
                                @elseif($activity->tipe === 'login') fa-sign-in-alt
                                @elseif($activity->tipe === 'logout') fa-sign-out-alt
                                @else fa-info @endif
                                @if($activity->tipe === 'upload_dokumen') text-blue-600
                                @elseif($activity->tipe === 'validasi_dokumen') text-green-600
                                @elseif($activity->tipe === 'login') text-purple-600
                                @elseif($activity->tipe === 'logout') text-red-600
                                @else text-gray-600 @endif"></i>
                        </div>
                    </div>
                    
                    <div class="ml-4 flex-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    @if($activity->tipe === 'upload_dokumen')
                                        {{ $activity->mahasiswa->name ?? $activity->user->name }} mengupload {{ $activity->pesan['document_type'] ?? 'dokumen' }}
                                    @elseif($activity->tipe === 'validasi_dokumen')
                                        {{ $activity->user->name }} memvalidasi dokumen {{ $activity->pesan['document_type'] ?? '' }} milik {{ $activity->mahasiswa->name ?? '' }}
                                    @elseif($activity->tipe === 'login')
                                        {{ $activity->pesan['message'] ?? ($activity->user->name . ' melakukan login') }}
                                    @elseif($activity->tipe === 'logout')
                                        {{ $activity->pesan['message'] ?? ($activity->user->name . ' melakukan logout') }}
                                    @elseif($activity->tipe === 'register')
                                        {{ $activity->pesan['message'] ?? ($activity->user->name . ' melakukan registrasi') }}
                                    @else
                                        {{ $activity->pesan['message'] ?? ($activity->pesan['action'] ?? 'Aktivitas') }}
                                    @endif
                                </p>
                                
                                @if($activity->pesan['catatan'] ?? false)
                                    <p class="text-sm text-gray-600 mt-1">
                                        <strong>Catatan:</strong> {{ $activity->pesan['catatan'] }}
                                    </p>
                                @endif
                                
                                @if($activity->pesan['file_name'] ?? false)
                                    <p class="text-sm text-gray-500 mt-1">
                                        <i class="fas fa-file mr-1"></i>{{ $activity->pesan['file_name'] }}
                                    </p>
                                @endif
                            </div>
                            
                            <div class="text-right">
                                <p class="text-xs text-gray-500">
                                    {{ $activity->tanggal_dibuat->format('d M Y H:i') }}
                                </p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($activity->tipe === 'upload_dokumen') bg-blue-100 text-blue-800
                                    @elseif($activity->tipe === 'validasi_dokumen') bg-green-100 text-green-800
                                    @elseif($activity->tipe === 'login') bg-purple-100 text-purple-800
                                    @elseif($activity->tipe === 'logout') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $activity->tipe)) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="px-6 py-8 text-center text-gray-500">
                <i class="fas fa-history text-4xl text-gray-300 mb-4"></i>
                <p>Tidak ada aktivitas</p>
            </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        @if($activities instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="px-6 py-3 border-t border-gray-200">
            {{ $activities->links() }}
        </div>
        @endif
    </div>
</div>

<script>
function updateSort() {
    const sortBy = document.querySelector('select[name="sort_by"]').value;
    const sortOrder = document.querySelector('select[name="sort_order"]').value;
    const search = new URLSearchParams(window.location.search);

    search.set('sort_by', sortBy);
    search.set('sort_order', sortOrder);

    window.location.href = window.location.pathname + '?' + search.toString();
}

function confirmClearActivities() {
    if (!confirm('Apakah Anda yakin ingin menghapus SEMUA log aktivitas? Tindakan ini tidak dapat dibatalkan!')) {
        return;
    }

    // Show loading state
    const button = event.target.closest('button');
    const originalContent = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menghapus...';
    button.disabled = true;

    fetch('{{ route("activity.clear") }}', {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            window.location.reload();
        } else {
            alert('Gagal menghapus aktivitas: ' + data.message);
            button.innerHTML = originalContent;
            button.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menghapus aktivitas');
        button.innerHTML = originalContent;
        button.disabled = false;
    });
}
</script>
@endsection
