@extends('layouts.app')

@section('title', 'Kelola Mitra - SIP PKL')

@push('styles')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
@endpush

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
                    <option value="rekomendasi" {{ request('sort_by') == 'rekomendasi' ? 'selected' : '' }}>Rekomendasi</option>
                    <option value="nama" {{ request('sort_by', 'nama') == 'nama' ? 'selected' : '' }}>Nama</option>
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
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-gray-50 z-10" style="min-width: 280px; max-width: 280px;">Nama Mitra</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="min-width: 280px; max-width: 280px;">Alamat</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-36">Kontak</th>
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
                        <td class="px-4 py-4 sticky left-0 bg-white z-10 hover:bg-gray-50 transition-colors" style="min-width: 280px; max-width: 280px;">
                            <div class="flex items-center space-x-3">
                                @if($isRankingSort && isset($m->rank))
                                    <div class="h-10 w-10 rounded-lg flex items-center justify-center flex-shrink-0 font-bold text-sm border
                                        {{ $m->rank == 1 ? 'bg-yellow-100 text-yellow-800 border-yellow-300' :
                                           ($m->rank == 2 ? 'bg-gray-100 text-gray-800 border-gray-300' :
                                           ($m->rank == 3 ? 'bg-orange-100 text-orange-700 border-orange-200' :
                                           'bg-blue-100 text-blue-800 border-blue-200')) }}">
                                        {{ $m->rank }}
                                    </div>
                                @else
                                    <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center flex-shrink-0 shadow-sm">
                                        <i class="fas fa-building text-white text-sm"></i>
                                    </div>
                                @endif
                                <div class="min-w-0 flex-1">
                                    <div class="text-sm font-semibold text-gray-900 truncate" title="{{ $m->nama }}">
                                        {{ $m->nama }}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-0.5">
                                        <i class="fas fa-hashtag text-gray-400"></i> {{ $m->id }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4" style="min-width: 280px; max-width: 280px;">
                            <div class="text-sm text-gray-900 leading-relaxed">
                                <div class="line-clamp-2" title="{{ $m->alamat }}">
                                    <i class="fas fa-map-marker-alt text-gray-400 text-xs mr-1"></i>
                                    <span class="align-middle">{{ $m->alamat ?? '-' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-900 whitespace-nowrap">
                            {{ $m->kontak ?? 'N/A' }}
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-900 whitespace-nowrap">
                            {{ $m->jarak }} km
                        </td>
                        <td class="px-4 py-4 text-sm whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $m->honor >= 5 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
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
                            <button onclick="openEditModal({{ $m->id }}, '{{ addslashes($m->nama) }}', '{{ addslashes($m->alamat) }}', '{{ addslashes($m->kontak) }}', {{ $m->jarak ?? 0 }}, {{ $m->honor ?? 0 }}, {{ $m->fasilitas ?? 0 }}, {{ $m->kesesuaian_jurusan ?? 0 }}, {{ $m->tingkat_kebersihan ?? 0 }}, {{ $m->max_mahasiswa ?? 4 }}, '{{ $m->created_by }}')"
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
        @if(!$isRankingSort)
        <div class="px-6 py-3 border-t border-gray-200">
            {{ $mitra->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Create Mitra Modal -->
<div id="createModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-10 mx-auto p-6 border max-w-3xl shadow-lg rounded-md bg-white">
        <h3 class="text-lg font-semibold text-gray-900 mb-5 pb-3 border-b">Tambah Mitra Baru</h3>

        <form method="POST" action="{{ route('admin.create-mitra') }}">
            @csrf

            <!-- Nama Mitra -->
            <div class="mb-4">
                <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Mitra</label>
                <input type="text" id="nama" name="nama" required
                       class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 text-sm"
                       placeholder="Masukkan nama mitra">
            </div>

            <!-- Informasi Dasar -->
            <div class="mb-4 p-4 bg-gray-50 rounded-md border border-gray-200">
                <h4 class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-3">Informasi Dasar</h4>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                        <textarea id="alamat" name="alamat" rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 text-sm bg-white resize-none"
                                  placeholder="Masukkan alamat mitra"></textarea>
                    </div>
                    <div>
                        <label for="kontak" class="block text-sm font-medium text-gray-700 mb-1">Kontak</label>
                        <input type="text" id="kontak" name="kontak"
                               class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 text-sm bg-white"
                               placeholder="Masukkan kontak mitra">
                    </div>
                </div>
            </div>

            <!-- Kriteria Penilaian -->
            <div class="mb-4 p-4 bg-blue-50 rounded-md border border-blue-200">
                <h4 class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-3">Kriteria Penilaian</h4>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="jarak" class="block text-sm font-medium text-gray-700 mb-1">Jarak (km)</label>
                        <input type="text" id="jarak" name="jarak" required value="0" pattern="^\d+([,.]\d+)?$"
                               class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 text-sm bg-white"
                               placeholder="contoh: 6,6 atau 6.6">
                    </div>
                    <div>
                        <label for="honor" class="block text-sm font-medium text-gray-700 mb-1">Honor</label>
                        <select id="honor" name="honor" required class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 text-sm bg-white">
                            <option value="1">Tidak Ada</option>
                            <option value="5">Ada</option>
                        </select>
                    </div>
                    <div>
                        <label for="fasilitas" class="block text-sm font-medium text-gray-700 mb-1">Fasilitas</label>
                        <select id="fasilitas" name="fasilitas" required class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 text-sm bg-white">
                            <option value="1">Biasa saja</option>
                            <option value="2">Baik</option>
                            <option value="3">Bagus</option>
                            <option value="4">Sangat Bagus</option>
                            <option value="5">Luar Biasa</option>
                        </select>
                    </div>
                    <div>
                        <label for="kesesuaian_jurusan" class="block text-sm font-medium text-gray-700 mb-1">Kesesuaian Jurusan</label>
                        <select id="kesesuaian_jurusan" name="kesesuaian_jurusan" required class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 text-sm bg-white">
                            <option value="1">Biasa saja</option>
                            <option value="2">Baik</option>
                            <option value="3">Bagus</option>
                            <option value="4">Sangat Bagus</option>
                            <option value="5">Luar Biasa</option>
                        </select>
                    </div>
                    <div>
                        <label for="tingkat_kebersihan" class="block text-sm font-medium text-gray-700 mb-1">Tingkat Kebersihan</label>
                        <select id="tingkat_kebersihan" name="tingkat_kebersihan" required class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 text-sm bg-white">
                            <option value="1">Biasa saja</option>
                            <option value="2">Baik</option>
                            <option value="3">Bagus</option>
                            <option value="4">Sangat Bagus</option>
                            <option value="5">Luar Biasa</option>
                        </select>
                    </div>
                    <div>
                        <label for="max_mahasiswa" class="block text-sm font-medium text-gray-700 mb-1">Maksimal Mahasiswa</label>
                        <input type="number" id="max_mahasiswa" name="max_mahasiswa" required value="4" min="1" max="20"
                               class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 text-sm bg-white">
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-2 pt-3 border-t">
                <button type="button" onclick="closeCreateModal()"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 text-sm">
                    Batal
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Mitra Modal -->
<div id="editModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-10 mx-auto p-6 border max-w-3xl shadow-lg rounded-md bg-white">
        <h3 class="text-lg font-semibold text-gray-900 mb-5 pb-3 border-b">Edit Mitra</h3>

        <form id="editForm" method="POST">
            @csrf
            @method('PUT')

            <!-- Nama Mitra -->
            <div class="mb-4">
                <label for="edit_nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Mitra</label>
                <input type="text" id="edit_nama" name="nama" required
                       class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 text-sm">
            </div>

            <!-- Tampilan Toggle -->
            <div class="mb-4">
                <label for="edit_tampilan_selector" class="block text-sm font-medium text-gray-700 mb-1">Tampilan</label>
                <select id="edit_tampilan_selector" onchange="updateCreatedBy()" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 text-sm bg-white">
                    <option value="normal">Normal (Menampilkan Kriteria)</option>
                    <option value="user_added">Added by Mahasiswa (Menyembunyikan Kriteria)</option>
                </select>
                <input type="hidden" id="edit_created_by" name="created_by">
            </div>

            <!-- Informasi Dasar -->
            <div class="mb-4 p-4 bg-gray-50 rounded-md border border-gray-200">
                <h4 class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-3">Informasi Dasar</h4>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="edit_alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                        <textarea id="edit_alamat" name="alamat" rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 text-sm bg-white resize-none"></textarea>
                    </div>
                    <div>
                        <label for="edit_kontak" class="block text-sm font-medium text-gray-700 mb-1">Kontak</label>
                        <input type="text" id="edit_kontak" name="kontak"
                               class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 text-sm bg-white">
                    </div>
                </div>
            </div>

            <!-- Kriteria Penilaian -->
            <div class="mb-4 p-4 bg-blue-50 rounded-md border border-blue-200">
                <h4 class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-3">Kriteria Penilaian</h4>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="edit_jarak" class="block text-sm font-medium text-gray-700 mb-1">Jarak (km)</label>
                        <input type="text" id="edit_jarak" name="jarak" required pattern="^\d+([,.]\d+)?$"
                               class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 text-sm bg-white"
                               placeholder="contoh: 6,6 atau 6.6">
                    </div>
                    <div>
                        <label for="edit_honor" class="block text-sm font-medium text-gray-700 mb-1">Honor</label>
                        <select id="edit_honor" name="honor" required class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 text-sm bg-white">
                            <option value="1">Tidak Ada</option>
                            <option value="5">Ada</option>
                        </select>
                    </div>
                    <div>
                        <label for="edit_fasilitas" class="block text-sm font-medium text-gray-700 mb-1">Fasilitas</label>
                        <select id="edit_fasilitas" name="fasilitas" required class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 text-sm bg-white">
                            <option value="1">Biasa saja</option>
                            <option value="2">Baik</option>
                            <option value="3">Bagus</option>
                            <option value="4">Sangat Bagus</option>
                            <option value="5">Luar Biasa</option>
                        </select>
                    </div>
                    <div>
                        <label for="edit_kesesuaian_jurusan" class="block text-sm font-medium text-gray-700 mb-1">Kesesuaian Jurusan</label>
                        <select id="edit_kesesuaian_jurusan" name="kesesuaian_jurusan" required class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 text-sm bg-white">
                            <option value="1">Biasa saja</option>
                            <option value="2">Baik</option>
                            <option value="3">Bagus</option>
                            <option value="4">Sangat Bagus</option>
                            <option value="5">Luar Biasa</option>
                        </select>
                    </div>
                    <div>
                        <label for="edit_tingkat_kebersihan" class="block text-sm font-medium text-gray-700 mb-1">Tingkat Kebersihan</label>
                        <select id="edit_tingkat_kebersihan" name="tingkat_kebersihan" required class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 text-sm bg-white">
                            <option value="1">Biasa saja</option>
                            <option value="2">Baik</option>
                            <option value="3">Bagus</option>
                            <option value="4">Sangat Bagus</option>
                            <option value="5">Luar Biasa</option>
                        </select>
                    </div>
                    <div>
                        <label for="edit_max_mahasiswa" class="block text-sm font-medium text-gray-700 mb-1">Maksimal Mahasiswa</label>
                        <input type="number" id="edit_max_mahasiswa" name="max_mahasiswa" required value="4" min="1" max="20"
                               class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 text-sm bg-white">
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-2 pt-3 border-t">
                <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 text-sm">
                    Batal
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Konversi koma ke titik untuk input jarak
function normalizeDecimal(value) {
    return value.replace(',', '.');
}

// Handle form submit untuk create
document.addEventListener('DOMContentLoaded', function() {
    const createForm = document.querySelector('#createModal form');
    const editForm = document.querySelector('#editForm');

    if (createForm) {
        createForm.addEventListener('submit', function(e) {
            const jarakInput = document.getElementById('jarak');
            if (jarakInput) {
                jarakInput.value = normalizeDecimal(jarakInput.value);
            }
        });
    }

    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            const jarakInput = document.getElementById('edit_jarak');
            if (jarakInput) {
                jarakInput.value = normalizeDecimal(jarakInput.value);
            }
        });
    }
});

function openCreateModal() {
    document.getElementById('createModal').classList.remove('hidden');
}

function closeCreateModal() {
    document.getElementById('createModal').classList.add('hidden');
}

function openEditModal(id, nama, alamat, kontak, jarak, honor, fasilitas, kesesuaian_jurusan, tingkat_kebersihan, max_mahasiswa, created_by) {
    document.getElementById('editForm').action = `/admin/kelola-mitra/${id}`;
    document.getElementById('edit_nama').value = nama;
    document.getElementById('edit_alamat').value = alamat;
    document.getElementById('edit_kontak').value = kontak;
    // Tampilkan jarak dengan koma untuk user Indonesia
    document.getElementById('edit_jarak').value = jarak;
    document.getElementById('edit_honor').value = honor;
    document.getElementById('edit_fasilitas').value = fasilitas;
    document.getElementById('edit_kesesuaian_jurusan').value = kesesuaian_jurusan;
    document.getElementById('edit_tingkat_kebersihan').value = tingkat_kebersihan;
    document.getElementById('edit_max_mahasiswa').value = max_mahasiswa || 4;
    
    // Handle Tampilan Toggle
    const selector = document.getElementById('edit_tampilan_selector');
    const createdByInput = document.getElementById('edit_created_by');
    
    if (created_by && created_by !== 'null' && created_by !== '') {
        selector.value = 'user_added';
        createdByInput.value = created_by;
    } else {
        selector.value = 'normal';
        createdByInput.value = '';
    }
    
    document.getElementById('editModal').classList.remove('hidden');
}

function updateCreatedBy() {
    const selector = document.getElementById('edit_tampilan_selector');
    const createdByInput = document.getElementById('edit_created_by');
    
    if (selector.value === 'normal') {
        createdByInput.value = '';
    } else {
        // If switching to user_added, use current user ID if input is empty
        if (!createdByInput.value) {
            createdByInput.value = '{{ Auth::id() }}';
        }
    }
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
