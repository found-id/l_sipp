@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Validasi Berkas Mahasiswa</h1>
                <p class="text-gray-600 mt-2">Validasi berkas dari seluruh mahasiswa</p>
            </div>
        </div>
    </div>

    <!-- KHS Section -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Kartu Hasil Studi (KHS)</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mahasiswa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dosen Pembimbing</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Upload</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($khs as $k)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                    <i class="fas fa-user text-gray-600"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $k->mahasiswa->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $k->mahasiswa->profilMahasiswa->nim ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $k->mahasiswa->profilMahasiswa->dosenPembimbing->name ?? 'Belum ditentukan' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($k->status_validasi === 'menunggu') bg-yellow-100 text-yellow-800
                                @elseif($k->status_validasi === 'tervalidasi') bg-green-100 text-green-800
                                @elseif($k->status_validasi === 'belum_valid') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $k->status_validasi)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $k->created_at->format('d M Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <button onclick="openBiodataModal('{{ $k->mahasiswa->name }}', '{{ $k->mahasiswa->profilMahasiswa->nim ?? 'N/A' }}', '{{ $k->mahasiswa->profilMahasiswa->prodi ?? 'N/A' }}', '{{ $k->mahasiswa->profilMahasiswa->semester ?? 'N/A' }}', '{{ $k->mahasiswa->profilMahasiswa->dosenPembimbing->name ?? 'Belum ditentukan' }}')" 
                                    class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-user mr-1"></i>Lihat Biodata
                            </button>
                            @if($k->status_validasi === 'menunggu')
                                <button onclick="validateKhs({{ $k->id }}, 'tervalidasi')" 
                                        class="text-green-600 hover:text-green-900">
                                    <i class="fas fa-check mr-1"></i>Validasi
                                </button>
                                <button onclick="validateKhs({{ $k->id }}, 'belum_valid')" 
                                        class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-times mr-1"></i>Tolak
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada KHS</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Surat Balasan Section -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Surat Balasan</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mahasiswa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dosen Pembimbing</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Upload</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($suratBalasan as $sb)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                    <i class="fas fa-user text-gray-600"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $sb->mahasiswa->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $sb->mahasiswa->profilMahasiswa->nim ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $sb->mahasiswa->profilMahasiswa->dosenPembimbing->name ?? 'Belum ditentukan' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($sb->status_validasi === 'menunggu') bg-yellow-100 text-yellow-800
                                @elseif($sb->status_validasi === 'tervalidasi') bg-green-100 text-green-800
                                @elseif($sb->status_validasi === 'belum_valid') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $sb->status_validasi)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $sb->created_at->format('d M Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <button onclick="openBiodataModal('{{ $sb->mahasiswa->name }}', '{{ $sb->mahasiswa->profilMahasiswa->nim ?? 'N/A' }}', '{{ $sb->mahasiswa->profilMahasiswa->prodi ?? 'N/A' }}', '{{ $sb->mahasiswa->profilMahasiswa->semester ?? 'N/A' }}', '{{ $sb->mahasiswa->profilMahasiswa->dosenPembimbing->name ?? 'Belum ditentukan' }}')" 
                                    class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-user mr-1"></i>Lihat Biodata
                            </button>
                            @if($sb->status_validasi === 'menunggu')
                                <button onclick="validateSuratBalasan({{ $sb->id }}, 'tervalidasi')" 
                                        class="text-green-600 hover:text-green-900">
                                    <i class="fas fa-check mr-1"></i>Validasi
                                </button>
                                <button onclick="validateSuratBalasan({{ $sb->id }}, 'belum_valid')" 
                                        class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-times mr-1"></i>Tolak
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada Surat Balasan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Laporan PKL Section -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Laporan PKL</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mahasiswa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dosen Pembimbing</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Upload</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($laporanPkl as $lp)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                    <i class="fas fa-user text-gray-600"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $lp->mahasiswa->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $lp->mahasiswa->profilMahasiswa->nim ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $lp->mahasiswa->profilMahasiswa->dosenPembimbing->name ?? 'Belum ditentukan' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($lp->status_validasi === 'menunggu') bg-yellow-100 text-yellow-800
                                @elseif($lp->status_validasi === 'tervalidasi') bg-green-100 text-green-800
                                @elseif($lp->status_validasi === 'belum_valid') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $lp->status_validasi)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $lp->created_at->format('d M Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <button onclick="openBiodataModal('{{ $lp->mahasiswa->name }}', '{{ $lp->mahasiswa->profilMahasiswa->nim ?? 'N/A' }}', '{{ $lp->mahasiswa->profilMahasiswa->prodi ?? 'N/A' }}', '{{ $lp->mahasiswa->profilMahasiswa->semester ?? 'N/A' }}', '{{ $lp->mahasiswa->profilMahasiswa->dosenPembimbing->name ?? 'Belum ditentukan' }}')" 
                                    class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-user mr-1"></i>Lihat Biodata
                            </button>
                            @if($lp->status_validasi === 'menunggu')
                                <button onclick="validateLaporan({{ $lp->id }}, 'tervalidasi')" 
                                        class="text-green-600 hover:text-green-900">
                                    <i class="fas fa-check mr-1"></i>Validasi
                                </button>
                                <button onclick="validateLaporan({{ $lp->id }}, 'belum_valid')" 
                                        class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-times mr-1"></i>Tolak
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada Laporan PKL</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Biodata Modal -->
<div id="biodataModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Biodata Mahasiswa</h3>
                <button onclick="closeBiodataModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama</label>
                    <p id="biodataNama" class="mt-1 text-sm text-gray-900"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">NIM</label>
                    <p id="biodataNim" class="mt-1 text-sm text-gray-900"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Program Studi</label>
                    <p id="biodataProdi" class="mt-1 text-sm text-gray-900"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Semester</label>
                    <p id="biodataSemester" class="mt-1 text-sm text-gray-900"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Dosen Pembimbing</label>
                    <p id="biodataDospem" class="mt-1 text-sm text-gray-900"></p>
                </div>
            </div>
            <div class="mt-6 flex justify-end">
                <button onclick="closeBiodataModal()" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function openBiodataModal(nama, nim, prodi, semester, dospem) {
    document.getElementById('biodataNama').textContent = nama;
    document.getElementById('biodataNim').textContent = nim;
    document.getElementById('biodataProdi').textContent = prodi;
    document.getElementById('biodataSemester').textContent = semester;
    document.getElementById('biodataDospem').textContent = dospem;
    document.getElementById('biodataModal').classList.remove('hidden');
}

function closeBiodataModal() {
    document.getElementById('biodataModal').classList.add('hidden');
}

function validateKhs(id, status) {
    if (confirm('Apakah Anda yakin ingin ' + (status === 'tervalidasi' ? 'memvalidasi' : 'menolak') + ' KHS ini?')) {
        fetch(`/admin/validation/khs/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Gagal memvalidasi KHS');
            }
        });
    }
}

function validateSuratBalasan(id, status) {
    if (confirm('Apakah Anda yakin ingin ' + (status === 'tervalidasi' ? 'memvalidasi' : 'menolak') + ' Surat Balasan ini?')) {
        fetch(`/admin/validation/surat-balasan/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Gagal memvalidasi Surat Balasan');
            }
        });
    }
}

function validateLaporan(id, status) {
    if (confirm('Apakah Anda yakin ingin ' + (status === 'tervalidasi' ? 'memvalidasi' : 'menolak') + ' Laporan PKL ini?')) {
        fetch(`/admin/validation/laporan/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Gagal memvalidasi Laporan PKL');
            }
        });
    }
}
</script>
@endsection

