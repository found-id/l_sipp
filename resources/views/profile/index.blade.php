@extends('layouts.app')

@section('title', 'Profile - SIPP PKL')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Profile</h1>
                <p class="text-gray-600 mt-2">Informasi akun dan biodata Anda</p>
            </div>
            <a href="{{ route('profile.edit') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                <i class="fas fa-edit mr-2"></i>Edit Profile
            </a>
        </div>
    </div>

    <!-- Profile Information -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Basic Information -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center mb-6">
                @if($user->photo && $user->google_linked)
                    <img src="{{ $user->photo }}" alt="Profile" class="h-16 w-16 rounded-full object-cover">
                @elseif($user->photo && !$user->google_linked)
                    <img src="{{ asset('storage/' . $user->photo) }}" alt="Profile" class="h-16 w-16 rounded-full object-cover">
                @else
                    <div class="h-16 w-16 rounded-full bg-gray-500 flex items-center justify-center">
                        <i class="fas fa-user text-white text-2xl"></i>
                    </div>
                @endif
                <div class="ml-4">
                    <h3 class="text-xl font-semibold text-gray-900">{{ $user->name }}</h3>
                    <p class="text-sm text-gray-600">{{ $user->email }}</p>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium mt-2
                        @if($user->role === 'admin') bg-red-100 text-red-800
                        @elseif($user->role === 'dospem') bg-purple-100 text-purple-800
                        @else bg-green-100 text-green-800 @endif">
                        <i class="fas 
                            @if($user->role === 'admin') fa-shield-alt
                            @elseif($user->role === 'dospem') fa-chalkboard-teacher
                            @else fa-user-graduate @endif mr-1"></i>
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-center">
                        @if($user->google_linked)
                            <svg class="w-4 h-4 text-gray-400 mr-3" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                            </svg>
                        @else
                            <i class="fas fa-envelope text-gray-400 mr-3"></i>
                        @endif
                        <div>
                            <p class="text-sm font-medium text-gray-700">Email</p>
                            <p class="text-sm text-gray-900">{{ $user->google_linked ? $user->google_email : $user->email }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-calendar text-gray-400 mr-3"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-700">Bergabung Sejak</p>
                            <p class="text-sm text-gray-900">{{ $user->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dosen Pembimbing Info (for Mahasiswa) -->
        @if($user->role === 'mahasiswa' && $dosenPembimbing)
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Dosen Pembimbing</h3>
            <div class="flex items-center">
                <div class="h-12 w-12 rounded-full bg-purple-100 flex items-center justify-center">
                    <i class="fas fa-chalkboard-teacher text-purple-600"></i>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">{{ $dosenPembimbing->name }}</div>
                    <div class="text-sm text-gray-500">Dosen Pembimbing Anda</div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Biodata Information (for Mahasiswa) -->
    @if($user->role === 'mahasiswa' && $profil)
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Biodata Mahasiswa</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">NIM</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $profil->nim ?? 'Belum diisi' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Program Studi</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $profil->prodi ?: 'Belum diisi' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Semester</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $profil->semester ?: 'Belum diisi' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">No. WhatsApp</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $profil->no_whatsapp ?? 'Belum diisi' }}</p>
                </div>
            </div>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                    <p class="mt-1 text-sm text-gray-900">
                        @if($profil->jenis_kelamin === 'L') Laki-laki
                        @elseif($profil->jenis_kelamin === 'P') Perempuan
                        @else Belum diisi @endif
                    </p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">IPK</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $profil->ipk ?: 'Belum diisi' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status Validasi</label>
                    <div class="mt-1 space-y-2">
                        <div class="flex items-center">
                            <input type="checkbox" {{ $profil->cek_min_semester ? 'checked' : '' }} disabled class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label class="ml-2 text-sm text-gray-700">Telah menempuh minimal 4 semester (D-3) atau 5 semester (D-4)</label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" {{ $profil->cek_ipk_nilaisks ? 'checked' : '' }} disabled class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label class="ml-2 text-sm text-gray-700">IPK tidak di bawah 2,50, tanpa nilai E, nilai D maksimal 9 SKS</label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" {{ $profil->cek_valid_biodata ? 'checked' : '' }} disabled class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label class="ml-2 text-sm text-gray-700">Biodata yang saya masukkan valid dan dapat dipertanggungjawabkan</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Quick Actions -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Aksi Cepat</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('mahasiswa.hasil-penilaian') }}" class="bg-blue-50 p-4 rounded-lg hover:bg-blue-100 transition-colors">
                <div class="flex items-center">
                    <i class="fas fa-graduation-cap text-blue-600 mr-3"></i>
                    <div>
                        <h4 class="font-medium text-gray-900">Hasil Penilaian</h4>
                        <p class="text-sm text-gray-600">Lihat hasil penilaian PKL</p>
                    </div>
                </div>
            </a>
            
            <a href="{{ route('profile.settings') }}" class="bg-gray-50 p-4 rounded-lg hover:bg-gray-100 transition-colors">
                <div class="flex items-center">
                    <i class="fas fa-cog text-gray-600 mr-3"></i>
                    <div>
                        <h4 class="font-medium text-gray-900">Pengaturan</h4>
                        <p class="text-sm text-gray-600">Kelola akun dan keamanan</p>
                    </div>
                </div>
            </a>
            
            <a href="{{ route('activity') }}" class="bg-green-50 p-4 rounded-lg hover:bg-green-100 transition-colors">
                <div class="flex items-center">
                    <i class="fas fa-history text-green-600 mr-3"></i>
                    <div>
                        <h4 class="font-medium text-gray-900">Log Aktivitas</h4>
                        <p class="text-sm text-gray-600">Lihat riwayat aktivitas</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
