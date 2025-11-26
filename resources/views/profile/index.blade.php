@extends('layouts.app')

@section('title', 'Profile - SIP PKL')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Banner with Profile Photo Overlay -->
    <div class="relative bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
        <!-- Banner Image/Gradient -->
        <div class="h-64 bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-600 relative overflow-hidden">
            <!-- Decorative Pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 right-0 w-96 h-96 bg-white rounded-full -mr-48 -mt-48"></div>
                <div class="absolute bottom-0 left-0 w-64 h-64 bg-white rounded-full -ml-32 -mb-32"></div>
            </div>

            <!-- Edit Profile Button -->
            <div class="absolute bottom-6 right-6">
                <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-6 py-3 bg-gray-900 hover:bg-gray-800 text-white font-semibold rounded-full transition-all duration-200 shadow-lg hover:shadow-xl border-2 border-white">
                    <i class="fas fa-edit mr-2"></i>Edit profil
                </a>
            </div>
        </div>

        <!-- Profile Photo - Overlapping Banner -->
        <div class="absolute left-8 -bottom-16">
            <div class="relative">
                <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-xl" referrerpolicy="no-referrer">
                <!-- Online Status Indicator (optional) -->
                <div class="absolute bottom-2 right-2 w-5 h-5 bg-green-500 rounded-full border-4 border-white"></div>
            </div>
        </div>

        <!-- User Info Section Below Banner -->
        <div class="pt-20 pb-6 px-8">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $user->name }}</h1>
                    <p class="text-gray-600 mt-1">{{ $user->email }}</p>
                    <div class="mt-3 flex items-center gap-3">
                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold
                            @if($user->role === 'admin') bg-red-100 text-red-700
                            @elseif($user->role === 'dospem') bg-purple-100 text-purple-700
                            @else bg-blue-100 text-blue-700 @endif">
                            <i class="fas
                                @if($user->role === 'admin') fa-shield-alt
                                @elseif($user->role === 'dospem') fa-chalkboard-teacher
                                @else fa-user-graduate @endif mr-2"></i>
                            {{ ucfirst($user->role) }}
                        </span>
                        @if($user->google_linked)
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                            <svg class="w-4 h-4 mr-1" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                            </svg>
                            Terhubung dengan Google
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Content Cards -->
    <div class="space-y-6">
        <!-- Basic Information -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-info-circle text-blue-600 text-lg"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-900 ml-3">Informasi Akun</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <div class="flex items-center text-sm text-gray-600 mb-1">
                        <i class="fas fa-envelope w-5"></i>
                        <span class="ml-2">Email</span>
                    </div>
                    <p class="text-gray-900 font-medium ml-7">{{ $user->email }}</p>
                </div>

                <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <div class="flex items-center text-sm text-gray-600 mb-1">
                        <i class="fas fa-calendar w-5"></i>
                        <span class="ml-2">Bergabung Sejak</span>
                    </div>
                    <p class="text-gray-900 font-medium ml-7">{{ $user->created_at->format('d M Y') }}</p>
                </div>

                @if($user->role === 'dospem' && $user->dospem && $user->dospem->nip)
                <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <div class="flex items-center text-sm text-gray-600 mb-1">
                        <i class="fas fa-id-card w-5"></i>
                        <span class="ml-2">NIP</span>
                    </div>
                    <p class="text-gray-900 font-medium ml-7">{{ $user->dospem->nip }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Biodata Mahasiswa -->
        @if($user->role === 'mahasiswa' && $profil)
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-user-graduate text-green-600 text-lg"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-900 ml-3">Biodata Mahasiswa</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <div class="flex items-center text-sm text-gray-600 mb-1">
                        <i class="fas fa-id-card w-5"></i>
                        <span class="ml-2">NIM</span>
                    </div>
                    <p class="text-gray-900 font-medium ml-7">{{ $profil->nim ?? 'Belum diisi' }}</p>
                </div>

                <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <div class="flex items-center text-sm text-gray-600 mb-1">
                        <i class="fas fa-graduation-cap w-5"></i>
                        <span class="ml-2">Program Studi</span>
                    </div>
                    <p class="text-gray-900 font-medium ml-7">{{ $profil->prodi ?: 'Belum diisi' }}</p>
                </div>

                <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <div class="flex items-center text-sm text-gray-600 mb-1">
                        <i class="fas fa-calendar-alt w-5"></i>
                        <span class="ml-2">Semester</span>
                    </div>
                    <p class="text-gray-900 font-medium ml-7">{{ $profil->semester ?: 'Belum diisi' }}</p>
                </div>

                <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <div class="flex items-center text-sm text-gray-600 mb-1">
                        <i class="fab fa-whatsapp w-5"></i>
                        <span class="ml-2">WhatsApp</span>
                    </div>
                    <p class="text-gray-900 font-medium ml-7">
                        @if($profil->no_whatsapp)
                            +62{{ $profil->no_whatsapp }}
                        @else
                            Belum diisi
                        @endif
                    </p>
                </div>

                <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <div class="flex items-center text-sm text-gray-600 mb-1">
                        <i class="fas fa-venus-mars w-5"></i>
                        <span class="ml-2">Jenis Kelamin</span>
                    </div>
                    <p class="text-gray-900 font-medium ml-7">
                        @if($profil->jenis_kelamin === 'L') Laki-laki
                        @elseif($profil->jenis_kelamin === 'P') Perempuan
                        @else Belum diisi @endif
                    </p>
                </div>

                <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <div class="flex items-center text-sm text-gray-600 mb-1">
                        <i class="fas fa-chart-line w-5"></i>
                        <span class="ml-2">IPK</span>
                    </div>
                    <p class="text-gray-900 font-medium ml-7">{{ $profil->ipk ?: 'Belum diisi' }}</p>
                </div>
            </div>

            <!-- Persyaratan -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">
                    <i class="fas fa-check-square text-gray-400 mr-2"></i>Persyaratan yang Disetujui
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center">
                        <input type="checkbox" {{ $profil->cek_min_semester ? 'checked' : '' }} disabled class="h-4 w-4 text-green-600 rounded">
                        <label class="ml-3 text-sm text-gray-700">Telah menempuh minimal 4 semester (D-3) atau 5 semester (D-4)</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" {{ $profil->cek_ipk_nilaisks ? 'checked' : '' }} disabled class="h-4 w-4 text-green-600 rounded">
                        <label class="ml-3 text-sm text-gray-700">IPK tidak di bawah 2,50, tanpa nilai E, nilai D maksimal 9 SKS</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" {{ $profil->cek_valid_biodata ? 'checked' : '' }} disabled class="h-4 w-4 text-green-600 rounded">
                        <label class="ml-3 text-sm text-gray-700">Biodata yang saya masukkan valid dan dapat dipertanggungjawabkan</label>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Dosen Pembimbing (for Mahasiswa) -->
        @if($user->role === 'mahasiswa' && $dosenPembimbing)
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chalkboard-teacher text-purple-600 text-lg"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-900 ml-3">Dosen Pembimbing</h2>
            </div>

            <div class="flex items-center p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl border border-purple-100">
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center shadow-lg">
                    <i class="fas fa-chalkboard-teacher text-white text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-bold text-gray-900">{{ $dosenPembimbing->name }}</h3>
                    @if($dosenPembimbing->dospem && $dosenPembimbing->dospem->nip)
                    <p class="text-sm text-gray-600 mt-1">NIP: {{ $dosenPembimbing->dospem->nip }}</p>
                    @endif
                    <p class="text-sm text-gray-500 mt-1">{{ $dosenPembimbing->email }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Quick Actions -->
        @if($user->role === 'mahasiswa')
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-bolt text-orange-600 text-lg"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-900 ml-3">Aksi Cepat</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('mahasiswa.hasil-penilaian') }}" class="group p-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-100 hover:border-blue-300 hover:shadow-md transition-all">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-graduation-cap text-white"></i>
                        </div>
                        <div class="ml-3">
                            <h4 class="font-semibold text-gray-900">Hasil Penilaian</h4>
                            <p class="text-xs text-gray-600">Lihat nilai PKL</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('profile.settings') }}" class="group p-4 bg-gradient-to-br from-gray-50 to-slate-50 rounded-xl border border-gray-200 hover:border-gray-400 hover:shadow-md transition-all">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-gray-500 to-slate-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-cog text-white"></i>
                        </div>
                        <div class="ml-3">
                            <h4 class="font-semibold text-gray-900">Pengaturan</h4>
                            <p class="text-xs text-gray-600">Kelola akun</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('activity') }}" class="group p-4 bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl border border-green-100 hover:border-green-300 hover:shadow-md transition-all">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-history text-white"></i>
                        </div>
                        <div class="ml-3">
                            <h4 class="font-semibold text-gray-900">Log Aktivitas</h4>
                            <p class="text-xs text-gray-600">Riwayat aktivitas</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
