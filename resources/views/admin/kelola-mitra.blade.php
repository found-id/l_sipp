@extends('layouts.app')

@section('title', 'Kelola Mitra - SIPP PKL')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Kelola Mitra</h1>
                <p class="text-gray-600 mt-2">Kelola data mitra PKL</p>
            </div>
            <button onclick="openCreateModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i>Tambah Mitra
            </button>
        </div>
    </div>

    <!-- Search and Sort -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari Mitra</label>
                <form method="GET" class="flex">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari nama, alamat, atau kontak..."
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
                    <option value="nama" {{ request('sort_by') == 'nama' ? 'selected' : '' }}>Nama</option>
                    <option value="alamat" {{ request('sort_by') == 'alamat' ? 'selected' : '' }}>Alamat</option>
                    <option value="kontak" {{ request('sort_by') == 'kontak' ? 'selected' : '' }}>Kontak</option>
                    <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Tanggal Dibuat</option>
                </select>
            </div>
            
            <!-- Sort Order -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Urutan</label>
                <select name="sort_order" onchange="updateSort()" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>A-Z</option>
                    <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Z-A</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Mitra Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto relative">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-80 sticky left-0 bg-gray-50 z-10">Nama Mitra</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-64">Alamat</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Kontak</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Jarak</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-28">Honor</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-28">Fasilitas</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Kesesuaian</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-28">Kebersihan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-28">Dibuat</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-40 sticky right-0 bg-gray-50 z-10 shadow-[-4px_0_6px_-1px_rgba(0,0,0,0.1)]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($mitra as $m)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-4 sticky left-0 bg-white z-10 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-building text-blue-600"></i>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">{{ Str::limit($m->nama, 50) }}</div>
                                    <div class="text-xs text-gray-500">ID: {{ $m->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-900">
                            <div class="max-w-xs truncate" title="{{ $m->alamat }}">{{ $m->alamat ?? 'N/A' }}</div>
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-900 whitespace-nowrap">
                            {{ $m->kontak ?? 'N/A' }}
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-900 whitespace-nowrap">
                            {{ $m->jarak }} km
                        </td>
                        <td class="px-4 py-4 text-sm whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $m->honor >= 4 ? 'bg-green-100 text-green-800' : ($m->honor >= 3 ? 'bg-blue-100 text-blue-800' : 'bg-orange-100 text-orange-800') }}">
                                {{ $m->honor_label }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-sm whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $m->fasilitas >= 4 ? 'bg-green-100 text-green-800' : ($m->fasilitas >= 3 ? 'bg-blue-100 text-blue-800' : 'bg-orange-100 text-orange-800') }}">
                                {{ $m->fasilitas_label }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-sm whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $m->kesesuaian_jurusan >= 4 ? 'bg-green-100 text-green-800' : ($m->kesesuaian_jurusan >= 3 ? 'bg-blue-100 text-blue-800' : 'bg-orange-100 text-orange-800') }}">
                                {{ $m->kesesuaian_jurusan_label }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-sm whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $m->tingkat_kebersihan >= 4 ? 'bg-green-100 text-green-800' : ($m->tingkat_kebersihan >= 3 ? 'bg-blue-100 text-blue-800' : 'bg-orange-100 text-orange-800') }}">
                                {{ $m->tingkat_kebersihan_label }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-500 whitespace-nowrap">
                            {{ $m->created_at->format('d M Y') }}
                        </td>
                        <td class="px-4 py-4 text-sm font-medium whitespace-nowrap text-center sticky right-0 bg-white z-10 hover:bg-gray-50 transition-colors shadow-[-4px_0_6px_-1px_rgba(0,0,0,0.1)]">
                            <button onclick="openEditModal({{ $m->id }}, '{{ addslashes($m->nama) }}', '{{ addslashes($m->alamat) }}', '{{ addslashes($m->kontak) }}', {{ $m->jarak ?? 0 }}, {{ $m->honor ?? 0 }}, {{ $m->fasilitas ?? 0 }}, {{ $m->kesesuaian_jurusan ?? 0 }}, {{ $m->tingkat_kebersihan ?? 0 }}, {{ $m->max_mahasiswa ?? 4 }})"
                                    class="text-blue-600 hover:text-blue-900 mr-3 inline-flex items-center">
                                <i class="fas fa-edit mr-1"></i>Edit
                            </button>
                            <button onclick="deleteMitra({{ $m->id }})"
                                    class="text-red-600 hover:text-red-900 inline-flex items-center">
                                <i class="fas fa-trash mr-1"></i>Hapus
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl text-gray-300 mb-2"></i>
                            <p>Tidak ada mitra</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-3 border-t border-gray-200">
            {{ $mitra->links() }}
        </div>
    </div>
</div>

<!-- Create Mitra Modal -->
<div id="createModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Tambah Mitra Baru</h3>
            <form method="POST" action="{{ route('admin.create-mitra') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700">Nama Mitra</label>
                        <input type="text" id="nama" name="nama" required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Masukkan nama mitra">
                    </div>
                    
                    <div>
                        <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                        <textarea id="alamat" name="alamat" rows="3"
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Masukkan alamat mitra"></textarea>
                    </div>
                    
                    <div>
                        <label for="kontak" class="block text-sm font-medium text-gray-700">Kontak</label>
                        <input type="text" id="kontak" name="kontak"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Masukkan kontak mitra">
                    </div>

                    {{-- SAW Criteria Fields --}}
                    <div>
                        <label for="jarak" class="block text-sm font-medium text-gray-700">Jarak (km)</label>
                        <input type="number" id="jarak" name="jarak" required value="0"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="honor" class="block text-sm font-medium text-gray-700">Honor</label>
                        <select id="honor" name="honor" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="1">Biasa saja</option>
                            <option value="2">Baik</option>
                            <option value="3">Bagus</option>
                            <option value="4">Sangat Bagus</option>
                            <option value="5">Luar Biasa</option>
                        </select>
                    </div>
                    <div>
                        <label for="fasilitas" class="block text-sm font-medium text-gray-700">Fasilitas</label>
                        <select id="fasilitas" name="fasilitas" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="1">Biasa saja</option>
                            <option value="2">Baik</option>
                            <option value="3">Bagus</option>
                            <option value="4">Sangat Bagus</option>
                            <option value="5">Luar Biasa</option>
                        </select>
                    </div>
                    <div>
                        <label for="kesesuaian_jurusan" class="block text-sm font-medium text-gray-700">Kesesuaian Jurusan</label>
                        <select id="kesesuaian_jurusan" name="kesesuaian_jurusan" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="1">Biasa saja</option>
                            <option value="2">Baik</option>
                            <option value="3">Bagus</option>
                            <option value="4">Sangat Bagus</option>
                            <option value="5">Luar Biasa</option>
                        </select>
                    </div>
                    <div>
                        <label for="tingkat_kebersihan" class="block text-sm font-medium text-gray-700">Tingkat Kebersihan</label>
                        <select id="tingkat_kebersihan" name="tingkat_kebersihan" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="1">Biasa saja</option>
                            <option value="2">Baik</option>
                            <option value="3">Bagus</option>
                            <option value="4">Sangat Bagus</option>
                            <option value="5">Luar Biasa</option>
                        </select>
                    </div>
                    <div>
                        <label for="max_mahasiswa" class="block text-sm font-medium text-gray-700">Maksimal Mahasiswa</label>
                        <input type="number" id="max_mahasiswa" name="max_mahasiswa" required value="4" min="1" max="20"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Maksimal mahasiswa (default: 4)">
                        <p class="mt-1 text-xs text-gray-500">Jumlah maksimal mahasiswa yang dapat memilih mitra ini</p>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeCreateModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Mitra Modal -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Mitra</h3>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label for="edit_nama" class="block text-sm font-medium text-gray-700">Nama Mitra</label>
                        <input type="text" id="edit_nama" name="nama" required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="edit_alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                        <textarea id="edit_alamat" name="alamat" rows="3"
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                    
                    <div>
                        <label for="edit_kontak" class="block text-sm font-medium text-gray-700">Kontak</label>
                        <input type="text" id="edit_kontak" name="kontak"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    {{-- SAW Criteria Fields --}}
                    <div>
                        <label for="edit_jarak" class="block text-sm font-medium text-gray-700">Jarak (km)</label>
                        <input type="number" id="edit_jarak" name="jarak" required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="edit_honor" class="block text-sm font-medium text-gray-700">Honor</label>
                        <select id="edit_honor" name="honor" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="1">Biasa saja</option>
                            <option value="2">Baik</option>
                            <option value="3">Bagus</option>
                            <option value="4">Sangat Bagus</option>
                            <option value="5">Luar Biasa</option>
                        </select>
                    </div>
                    <div>
                        <label for="edit_fasilitas" class="block text-sm font-medium text-gray-700">Fasilitas</label>
                        <select id="edit_fasilitas" name="fasilitas" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="1">Biasa saja</option>
                            <option value="2">Baik</option>
                            <option value="3">Bagus</option>
                            <option value="4">Sangat Bagus</option>
                            <option value="5">Luar Biasa</option>
                        </select>
                    </div>
                    <div>
                        <label for="edit_kesesuaian_jurusan" class="block text-sm font-medium text-gray-700">Kesesuaian Jurusan</label>
                        <select id="edit_kesesuaian_jurusan" name="kesesuaian_jurusan" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="1">Biasa saja</option>
                            <option value="2">Baik</option>
                            <option value="3">Bagus</option>
                            <option value="4">Sangat Bagus</option>
                            <option value="5">Luar Biasa</option>
                        </select>
                    </div>
                    <div>
                        <label for="edit_tingkat_kebersihan" class="block text-sm font-medium text-gray-700">Tingkat Kebersihan</label>
                        <select id="edit_tingkat_kebersihan" name="tingkat_kebersihan" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="1">Biasa saja</option>
                            <option value="2">Baik</option>
                            <option value="3">Bagus</option>
                            <option value="4">Sangat Bagus</option>
                            <option value="5">Luar Biasa</option>
                        </select>
                    </div>
                    <div>
                        <label for="edit_max_mahasiswa" class="block text-sm font-medium text-gray-700">Maksimal Mahasiswa</label>
                        <input type="number" id="edit_max_mahasiswa" name="max_mahasiswa" required value="4" min="1" max="20"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Maksimal mahasiswa (default: 4)">
                        <p class="mt-1 text-xs text-gray-500">Jumlah maksimal mahasiswa yang dapat memilih mitra ini</p>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeEditModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openCreateModal() {
    document.getElementById('createModal').classList.remove('hidden');
}

function closeCreateModal() {
    document.getElementById('createModal').classList.add('hidden');
}

function openEditModal(id, nama, alamat, kontak, jarak, honor, fasilitas, kesesuaian_jurusan, tingkat_kebersihan, max_mahasiswa) {
    document.getElementById('editForm').action = `/admin/kelola-mitra/${id}`;
    document.getElementById('edit_nama').value = nama;
    document.getElementById('edit_alamat').value = alamat;
    document.getElementById('edit_kontak').value = kontak;
    document.getElementById('edit_jarak').value = jarak;
    document.getElementById('edit_honor').value = honor;
    document.getElementById('edit_fasilitas').value = fasilitas;
    document.getElementById('edit_kesesuaian_jurusan').value = kesesuaian_jurusan;
    document.getElementById('edit_tingkat_kebersihan').value = tingkat_kebersihan;
    document.getElementById('edit_max_mahasiswa').value = max_mahasiswa || 4;
    document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

function deleteMitra(id) {
    if (confirm('Apakah Anda yakin ingin menghapus mitra ini?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/kelola-mitra/${id}`;
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        
        const tokenInput = document.createElement('input');
        tokenInput.type = 'hidden';
        tokenInput.name = '_token';
        tokenInput.value = '{{ csrf_token() }}';
        
        form.appendChild(methodInput);
        form.appendChild(tokenInput);
        document.body.appendChild(form);
        form.submit();
    }
}

function updateSort() {
    const sortBy = document.querySelector('select[name="sort_by"]').value;
    const sortOrder = document.querySelector('select[name="sort_order"]').value;
    const search = new URLSearchParams(window.location.search);
    
    search.set('sort_by', sortBy);
    search.set('sort_order', sortOrder);
    
    window.location.href = window.location.pathname + '?' + search.toString();
}
</script>
@endsection
