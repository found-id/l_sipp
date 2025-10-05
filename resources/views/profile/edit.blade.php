@extends('layouts.app')

@section('title', 'Edit Profile - SIPP PKL')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Profile</h1>
                <p class="text-gray-600 mt-2">Lengkapi informasi pribadi Anda</p>
            </div>
            <a href="{{ route('profile.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Edit Form -->
    <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <!-- Basic Information -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Dasar</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           readonly>
                    <p class="mt-1 text-xs text-gray-500">Nama tidak dapat diubah</p>
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           readonly>
                    <p class="mt-1 text-xs text-gray-500">Email tidak dapat diubah</p>
                </div>
                
            </div>
        </div>

        @if($user->role === 'mahasiswa')
        <!-- Biodata Information -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Biodata Mahasiswa</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nim" class="block text-sm font-medium text-gray-700">NIM</label>
                    <input type="text" id="nim" name="nim" value="{{ old('nim', $profil->nim ?? '') }}"
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           readonly>
                    <p class="mt-1 text-xs text-gray-500">NIM tidak dapat diubah</p>
                </div>
                
                <div>
                    <label for="prodi" class="block text-sm font-medium text-gray-700">Program Studi</label>
                    <input type="text" id="prodi" name="prodi" value="{{ old('prodi', $profil->prodi ?? '') }}" required
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div>
                    <label for="semester" class="block text-sm font-medium text-gray-700">Semester</label>
                    <input type="number" id="semester" name="semester" value="{{ old('semester', $profil->semester ?? 5) }}" required
                           min="1" max="14"
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div>
                    <label for="no_whatsapp" class="block text-sm font-medium text-gray-700">No. WhatsApp</label>
                    <input type="text" id="no_whatsapp" name="no_whatsapp" value="{{ old('no_whatsapp', $profil->no_whatsapp ?? '') }}"
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div>
                    <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                    <select id="jenis_kelamin" name="jenis_kelamin"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="L" {{ old('jenis_kelamin', $profil->jenis_kelamin ?? '') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin', $profil->jenis_kelamin ?? '') === 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                
                <div>
                    <label for="ipk" class="block text-sm font-medium text-gray-700">IPK</label>
                    <input type="number" id="ipk" name="ipk" value="{{ old('ipk', $profil->ipk ?? '') }}"
                           step="0.01" min="0" max="4.0"
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div>
                    <label for="id_dospem" class="block text-sm font-medium text-gray-700">Dosen Pembimbing</label>
                    <select id="id_dospem" name="id_dospem"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Pilih Dosen Pembimbing --</option>
                        @foreach($dosenPembimbingList as $dosen)
                            <option value="{{ $dosen->id }}" {{ old('id_dospem', $profil->id_dospem ?? '') == $dosen->id ? 'selected' : '' }}>
                                {{ $dosen->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Checkboxes -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Konfirmasi Persyaratan</h3>
            <div class="space-y-4">
                <div class="flex items-start">
                    <input type="checkbox" id="cek_min_semester" name="cek_min_semester" value="1" 
                           {{ old('cek_min_semester', $profil->cek_min_semester ?? false) ? 'checked' : '' }}
                           class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="cek_min_semester" class="ml-3 text-sm text-gray-700">
                        Telah menempuh minimal 4 semester (D-3) atau 5 semester (D-4)
                    </label>
                </div>
                
                <div class="flex items-start">
                    <input type="checkbox" id="cek_ipk_nilaisks" name="cek_ipk_nilaisks" value="1"
                           {{ old('cek_ipk_nilaisks', $profil->cek_ipk_nilaisks ?? false) ? 'checked' : '' }}
                           class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="cek_ipk_nilaisks" class="ml-3 text-sm text-gray-700">
                        IPK tidak di bawah 2,50, tanpa nilai E, nilai D maksimal 9 SKS
                    </label>
                </div>
                
                <div class="flex items-start">
                    <input type="checkbox" id="cek_valid_biodata" name="cek_valid_biodata" value="1"
                           {{ old('cek_valid_biodata', $profil->cek_valid_biodata ?? false) ? 'checked' : '' }}
                           class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="cek_valid_biodata" class="ml-3 text-sm text-gray-700">
                        Biodata yang saya masukkan valid dan dapat dipertanggungjawabkan
                    </label>
                </div>
            </div>
        </div>
        @endif

        <!-- Submit Button -->
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <i class="fas fa-save mr-2"></i>Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
