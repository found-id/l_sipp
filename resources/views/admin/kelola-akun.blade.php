@extends('layouts.app')

@section('title', 'Kelola Akun - SIP PKL')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Kelola Akun</h1>
                <p class="text-gray-600 mt-2">Kelola user dan role dalam sistem</p>
            </div>
            <button onclick="openCreateModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i>Tambah User
            </button>
        </div>
        
        <!-- Search and Sort -->
        <div class="flex flex-col sm:flex-row gap-4 mb-6">
            <!-- Search -->
            <div class="flex-1">
                <form method="GET" class="flex">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nama, email..."
                           class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <button type="submit" class="ml-2 bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                        <i class="fas fa-search"></i>
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admin.kelola-akun') }}" class="ml-2 bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </form>
            </div>

            <!-- Sort and Per Page -->
            <div class="flex gap-2">
                <select name="sort_by" onchange="updateSort()" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500">
                    <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Nama</option>
                    <option value="email" {{ request('sort_by') == 'email' ? 'selected' : '' }}>Email</option>
                    <option value="role" {{ request('sort_by') == 'role' ? 'selected' : '' }}>Role</option>
                    <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Tanggal Dibuat</option>
                </select>
                <select name="sort_order" onchange="updateSort()" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500">
                    <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>A-Z</option>
                    <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Z-A</option>
                </select>
                <select name="per_page" onchange="updatePerPage()" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500">
                    <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15 per halaman</option>
                    <option value="30" {{ request('per_page') == 30 ? 'selected' : '' }}>30 per halaman</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 per halaman</option>
                    <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>Semua</option>
                </select>
            </div>
        </div>

        <!-- Bulk Actions -->
        <div id="bulkActions" class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg hidden">
            <div class="flex flex-wrap items-center gap-3">
                <span class="font-medium text-gray-700">
                    <span id="selectedCount">0</span> akun dipilih
                </span>
                <button onclick="bulkDelete()" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 text-sm">
                    <i class="fas fa-trash mr-2"></i>Hapus Terpilih
                </button>
                <button onclick="openBulkEditDospemModal()" class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 text-sm">
                    <i class="fas fa-user-edit mr-2"></i>Edit Dosen Pembimbing
                </button>
                <button onclick="bulkResetDocuments()" class="bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700 text-sm">
                    <i class="fas fa-redo mr-2"></i>Reset Data Pemberkasan
                </button>
                <button onclick="clearSelection()" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 text-sm">
                    <i class="fas fa-times mr-2"></i>Batal
                </button>
            </div>
        </div>

        @if($showAll)
        <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg">
            <p class="text-sm text-green-800">
                <i class="fas fa-info-circle mr-2"></i>
                Menampilkan <span class="font-semibold">semua {{ $users->count() }} akun</span>
            </p>
        </div>
        @endif
    </div>

    <!-- Users Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto relative">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left w-12 sticky left-0 bg-gray-50 z-10">
                            <input type="checkbox" id="selectAll" onchange="toggleSelectAll()"
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-56 sticky left-12 bg-gray-50 z-10">User</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-48">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Role</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">NIM/NIDN</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-48">Dosen Pembimbing</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-40 sticky right-0 bg-gray-50 z-10 shadow-[-4px_0_6px_-1px_rgba(0,0,0,0.1)]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-4 whitespace-nowrap sticky left-0 bg-white z-10 hover:bg-gray-50 transition-colors">
                            <input type="checkbox" class="user-checkbox w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                   value="{{ $user->id }}" onchange="updateBulkActions()">
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap sticky left-12 bg-white z-10 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center">
                                @if($user->photo)
                                    @if($user->google_linked)
                                        @php
                                            $photoUrl = $user->photo;
                                            if (str_contains($photoUrl, 'googleusercontent.com')) {
                                                $photoUrl = preg_replace('/=s\d+-c/', '', $photoUrl);
                                                $photoUrl .= '=s96-c';
                                            }
                                        @endphp
                                        <img src="{{ $photoUrl }}" alt="Profile" class="h-10 w-10 rounded-full object-cover" referrerpolicy="no-referrer" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center" style="display: none;">
                                            <i class="fas fa-user text-gray-600"></i>
                                        </div>
                                    @else
                                        <img src="{{ asset('storage/' . $user->photo) }}" alt="Profile" class="h-10 w-10 rounded-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center" style="display: none;">
                                            <i class="fas fa-user text-gray-600"></i>
                                        </div>
                                    @endif
                                @else
                                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                        <i class="fas fa-user text-gray-600"></i>
                                    </div>
                                @endif
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900 flex items-center">
                                        {{ $user->name }}
                                        @if($user->google_linked)
                                            <span class="ml-2" title="Akun Google">
                                                <svg class="w-4 h-4 inline" viewBox="0 0 24 24">
                                                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                                </svg>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-500">ID: {{ $user->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $user->google_linked ? $user->google_email : $user->email }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($user->role === 'admin') bg-red-100 text-red-800
                                @elseif($user->role === 'dospem') bg-purple-100 text-purple-800
                                @else bg-green-100 text-green-800 @endif">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $user->profilMahasiswa->nim ?? 'N/A' }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $user->profilMahasiswa->dosenPembimbing->name ?? 'N/A' }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-center sticky right-0 bg-white z-10 hover:bg-gray-50 transition-colors shadow-[-4px_0_6px_-1px_rgba(0,0,0,0.1)]">
                            <button onclick="openEditModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}', '{{ $user->role }}', '{{ $user->profilMahasiswa->nim ?? '' }}', '{{ $user->profilMahasiswa->prodi ?? '' }}', '{{ $user->profilMahasiswa->semester ?? '' }}', '{{ $user->profilMahasiswa->id_dospem ?? '' }}')"
                                    class="text-blue-600 hover:text-blue-900 mr-2">
                                <i class="fas fa-edit mr-1"></i>Edit
                            </button>
                            <button onclick="deleteUser({{ $user->id }})"
                                    class="text-red-600 hover:text-red-900">
                                <i class="fas fa-trash mr-1"></i>Hapus
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">Tidak ada user</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(!$showAll && method_exists($users, 'hasPages') && $users->hasPages())
        <div class="px-6 py-3 border-t border-gray-200">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Create User Modal -->
<div id="createModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Tambah User Baru</h3>
            <form method="POST" action="{{ route('admin.create-user') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                        <input type="text" id="name" name="name" required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="email" name="email" required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" id="password" name="password" required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                        <select id="role" name="role" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih Role --</option>
                            <option value="mahasiswa">Mahasiswa</option>
                            <option value="dospem">Dosen Pembimbing</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    
                    <div id="mahasiswa-fields" class="hidden space-y-4">
                        <div>
                            <label for="nim" class="block text-sm font-medium text-gray-700">NIM</label>
                            <input type="text" id="nim" name="nim"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label for="id_dospem" class="block text-sm font-medium text-gray-700">Dosen Pembimbing</label>
                            <select id="id_dospem" name="id_dospem"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Pilih Dosen Pembimbing --</option>
                                @foreach(\App\Models\User::dosenPembimbing()->get() as $dosen)
                                    <option value="{{ $dosen->id }}">{{ $dosen->name }}</option>
                                @endforeach
                            </select>
                        </div>
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

<!-- Edit User Modal -->
<div id="editModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-4/5 max-w-4xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Akun</h3>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-4">
                    <div>
                        <label for="edit_name" class="block text-sm font-medium text-gray-700">Nama</label>
                        <input type="text" id="edit_name" name="name" required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="edit_email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="edit_email" name="email" required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="edit_password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" id="edit_password" name="password" placeholder="Kosongkan bila tidak diubah"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="edit_role" class="block text-sm font-medium text-gray-700">Role</label>
                        <select id="edit_role" name="role" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="mahasiswa">Mahasiswa</option>
                            <option value="dospem">Dosen Pembimbing</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    </div>
                    
                    <!-- Right Column - Mahasiswa Fields -->
                    <div id="mahasiswa_fields" class="space-y-4 hidden md:block">
                        <div>
                            <label for="edit_nim" class="block text-sm font-medium text-gray-700">NIM (mahasiswa)</label>
                            <input type="text" id="edit_nim" name="nim"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label for="edit_prodi" class="block text-sm font-medium text-gray-700">Prodi (mahasiswa)</label>
                            <input type="text" id="edit_prodi" name="prodi" value="Teknologi Informasi"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label for="edit_semester" class="block text-sm font-medium text-gray-700">Semester (mahasiswa)</label>
                            <input type="number" id="edit_semester" name="semester" value="5" min="1" max="14"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label for="edit_dospem_id" class="block text-sm font-medium text-gray-700">Dospem (mahasiswa)</label>
                            <select id="edit_dospem_id" name="dospem_id"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">— tidak ditetapkan —</option>
                                @foreach($dospems as $dospem)
                                    <option value="{{ $dospem->id }}">{{ $dospem->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeEditModal()" 
                            class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        Tutup
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center">
                        <i class="fas fa-pencil-alt mr-2"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Edit Dospem Modal -->
<div id="bulkEditDospemModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Dosen Pembimbing</h3>
            <form id="bulkEditDospemForm" method="POST" action="{{ route('admin.bulk-edit-dospem') }}">
                @csrf
                <input type="hidden" name="user_ids" id="bulkEditUserIds">
                <div class="space-y-4">
                    <div>
                        <label for="bulk_dospem_id" class="block text-sm font-medium text-gray-700">Pilih Dosen Pembimbing</label>
                        <select id="bulk_dospem_id" name="dospem_id" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih Dosen Pembimbing --</option>
                            @foreach($dospems as $dospem)
                                <option value="{{ $dospem->id }}">{{ $dospem->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <p class="text-sm text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Dosen pembimbing akan diterapkan ke <span id="bulkEditCount" class="font-semibold">0</span> mahasiswa yang dipilih
                    </p>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeBulkEditDospemModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                        <i class="fas fa-save mr-2"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Orphaned Profil Mahasiswa Section -->
    @if($orphanedProfils->count() > 0)
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h2 class="text-xl font-bold text-red-900">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Profil Mahasiswa Orphaned
                </h2>
                <p class="text-gray-600 mt-1">Profil mahasiswa yang tidak terkait dengan user aktif ({{ $orphanedProfils->count() }} ditemukan)</p>
            </div>
            <form action="{{ route('admin.bulk-delete-orphaned-profils') }}" method="POST"
                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus SEMUA profil orphaned? Tindakan ini tidak dapat dibatalkan!')">
                @csrf
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                    <i class="fas fa-trash-alt mr-2"></i>Hapus Semua ({{ $orphanedProfils->count() }})
                </button>
            </form>
        </div>

        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">
                        Profil di bawah ini tidak memiliki relasi dengan user yang aktif. Ini mungkin terjadi karena kesalahan saat menghapus user atau masalah database. Disarankan untuk menghapus profil orphaned ini.
                    </p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID Mahasiswa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIM</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prodi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Semester</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dosen Pembimbing</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($orphanedProfils as $profil)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded">{{ $profil->id_mahasiswa }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $profil->nim ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $profil->prodi ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $profil->semester ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $profil->dosenPembimbing->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <form action="{{ route('admin.delete-orphaned-profil', ['id' => $profil->id_mahasiswa]) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus profil ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash-alt mr-1"></i>Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

<script>
function updatePerPage() {
    const perPage = document.querySelector('select[name="per_page"]').value;
    const sortBy = document.querySelector('select[name="sort_by"]').value;
    const sortOrder = document.querySelector('select[name="sort_order"]').value;
    const search = new URLSearchParams(window.location.search).get('search') || '';

    const url = new URL(window.location);
    url.searchParams.set('per_page', perPage);
    url.searchParams.set('sort_by', sortBy);
    url.searchParams.set('sort_order', sortOrder);
    if (search) url.searchParams.set('search', search);

    window.location.href = url.toString();
}

function updateSort() {
    const sortBy = document.querySelector('select[name="sort_by"]').value;
    const sortOrder = document.querySelector('select[name="sort_order"]').value;
    const perPage = document.querySelector('select[name="per_page"]').value;
    const search = new URLSearchParams(window.location.search).get('search') || '';

    const url = new URL(window.location);
    url.searchParams.set('sort_by', sortBy);
    url.searchParams.set('sort_order', sortOrder);
    url.searchParams.set('per_page', perPage);
    if (search) url.searchParams.set('search', search);

    window.location.href = url.toString();
}

// Bulk Actions Functions
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.user-checkbox');
    checkboxes.forEach(cb => cb.checked = selectAll.checked);
    updateBulkActions();
}

function updateBulkActions() {
    const checkboxes = document.querySelectorAll('.user-checkbox:checked');
    const count = checkboxes.length;
    document.getElementById('selectedCount').textContent = count;
    document.getElementById('bulkActions').classList.toggle('hidden', count === 0);
}

function getSelectedIds() {
    const checkboxes = document.querySelectorAll('.user-checkbox:checked');
    return Array.from(checkboxes).map(cb => cb.value);
}

function clearSelection() {
    document.getElementById('selectAll').checked = false;
    document.querySelectorAll('.user-checkbox').forEach(cb => cb.checked = false);
    updateBulkActions();
}

function bulkDelete() {
    const ids = getSelectedIds();
    if (ids.length === 0) {
        alert('Pilih minimal 1 akun untuk dihapus!');
        return;
    }

    if (!confirm(`Apakah Anda yakin ingin menghapus ${ids.length} akun? Data yang terkait juga akan dihapus!`)) {
        return;
    }

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("admin.bulk-delete-users") }}';

    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = '{{ csrf_token() }}';
    form.appendChild(csrfInput);

    ids.forEach(id => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'user_ids[]';
        input.value = id;
        form.appendChild(input);
    });

    document.body.appendChild(form);
    form.submit();
}

function openBulkEditDospemModal() {
    const ids = getSelectedIds();
    if (ids.length === 0) {
        alert('Pilih minimal 1 mahasiswa untuk edit dosen pembimbing!');
        return;
    }

    document.getElementById('bulkEditUserIds').value = JSON.stringify(ids);
    document.getElementById('bulkEditCount').textContent = ids.length;
    document.getElementById('bulkEditDospemModal').classList.remove('hidden');
}

function closeBulkEditDospemModal() {
    document.getElementById('bulkEditDospemModal').classList.add('hidden');
}

// Update form submission to convert JSON array to proper array format
document.getElementById('bulkEditDospemForm').addEventListener('submit', function(e) {
    const ids = JSON.parse(document.getElementById('bulkEditUserIds').value);
    document.getElementById('bulkEditUserIds').remove();

    ids.forEach(id => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'user_ids[]';
        input.value = id;
        this.appendChild(input);
    });
});

function bulkResetDocuments() {
    const ids = getSelectedIds();
    if (ids.length === 0) {
        alert('Pilih minimal 1 mahasiswa untuk reset data pemberkasan!');
        return;
    }

    if (!confirm(`Apakah Anda yakin ingin mereset data pemberkasan untuk ${ids.length} mahasiswa? Semua dokumen dan data terkait akan dihapus!`)) {
        return;
    }

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("admin.bulk-reset-documents") }}';

    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = '{{ csrf_token() }}';
    form.appendChild(csrfInput);

    ids.forEach(id => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'user_ids[]';
        input.value = id;
        form.appendChild(input);
    });

    document.body.appendChild(form);
    form.submit();
}

function openCreateModal() {
    document.getElementById('createModal').classList.remove('hidden');
}

function closeCreateModal() {
    document.getElementById('createModal').classList.add('hidden');
}

function openEditModal(id, name, email, role, nim, prodi, semester, idDospem) {
    document.getElementById('editForm').action = `/admin/kelola-akun/${id}`;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_role').value = role;
    document.getElementById('edit_nim').value = nim || '';
    document.getElementById('edit_prodi').value = prodi || 'Teknologi Informasi';
    document.getElementById('edit_semester').value = semester || 5;
    document.getElementById('edit_dospem_id').value = idDospem || '';
    
    // Show/hide mahasiswa fields based on role
    toggleMahasiswaFields(role);
    
    document.getElementById('editModal').classList.remove('hidden');
}

function toggleMahasiswaFields(role) {
    const mahasiswaFields = document.getElementById('mahasiswa_fields');
    if (role === 'mahasiswa') {
        mahasiswaFields.classList.remove('hidden');
        mahasiswaFields.classList.add('md:block');
    } else {
        mahasiswaFields.classList.add('hidden');
        mahasiswaFields.classList.remove('md:block');
    }
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

// Add event listener for role change
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('edit_role');
    if (roleSelect) {
        roleSelect.addEventListener('change', function() {
            toggleMahasiswaFields(this.value);
        });
    }
});

function deleteUser(id) {
    if (confirm('Apakah Anda yakin ingin menghapus user ini?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/kelola-akun/${id}`;
        
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

// Show/hide mahasiswa fields based on role
document.getElementById('role').addEventListener('change', function() {
    const mahasiswaFields = document.getElementById('mahasiswa-fields');
    if (this.value === 'mahasiswa') {
        mahasiswaFields.classList.remove('hidden');
    } else {
        mahasiswaFields.classList.add('hidden');
    }
});
</script>
@endsection
