@extends('layouts.app')

@section('title', 'Profile - SIP PKL')

@section('content')
<div class="space-y-6">
    <!-- Header with Gradient -->
    <div class="relative overflow-hidden bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-600 shadow-2xl rounded-2xl p-8">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="relative z-10">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    @if($user->photo && $user->google_linked)
                        @php
                            $photoUrl = $user->photo;
                            // Tambahkan parameter ukuran untuk Google photos
                            if (str_contains($photoUrl, 'googleusercontent.com')) {
                                $photoUrl = preg_replace('/=s\d+-c/', '', $photoUrl);
                                $photoUrl .= '=s200-c';
                            }
                        @endphp
                        <img src="{{ $photoUrl }}" alt="Profile" class="h-20 w-20 rounded-full object-cover border-4 border-white/30 shadow-xl" referrerpolicy="no-referrer" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="h-20 w-20 rounded-full bg-white/20 backdrop-blur-sm border-4 border-white/30 flex items-center justify-center shadow-xl" style="display: none;">
                            <i class="fas fa-user text-white text-3xl"></i>
                        </div>
                    @elseif($user->photo && !$user->google_linked)
                        <img src="{{ asset('storage/' . $user->photo) }}" alt="Profile" class="h-20 w-20 rounded-full object-cover border-4 border-white/30 shadow-xl" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="h-20 w-20 rounded-full bg-white/20 backdrop-blur-sm border-4 border-white/30 flex items-center justify-center shadow-xl" style="display: none;">
                            <i class="fas fa-user text-white text-3xl"></i>
                        </div>
                    @else
                        <div class="h-20 w-20 rounded-full bg-white/20 backdrop-blur-sm border-4 border-white/30 flex items-center justify-center shadow-xl">
                            <i class="fas fa-user text-white text-3xl"></i>
                        </div>
                    @endif
                    <div class="ml-5">
                        <h1 class="text-3xl font-bold text-white">{{ $user->name }}</h1>
                        <p class="text-blue-100 mt-1">{{ $user->email }}</p>
                        <div class="mt-2">
                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold
                                @if($user->role === 'admin') bg-red-500/90 text-white
                                @elseif($user->role === 'dospem') bg-purple-500/90 text-white
                                @else bg-green-500/90 text-white @endif backdrop-blur-sm shadow-lg">
                                <i class="fas
                                    @if($user->role === 'admin') fa-shield-alt
                                    @elseif($user->role === 'dospem') fa-chalkboard-teacher
                                    @else fa-user-graduate @endif mr-2"></i>
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>
                    </div>
                </div>
                <a href="{{ route('profile.edit') }}" class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white px-6 py-3 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl font-semibold border border-white/30">
                    <i class="fas fa-edit mr-2"></i>Edit Profile
                </a>
            </div>
        </div>
        <!-- Decorative circles -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mr-32 -mt-32"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full -ml-24 -mb-24"></div>
    </div>

    <!-- Profile Information -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Basic Information -->
        <div class="bg-white shadow-lg rounded-2xl p-6 border border-gray-100">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-id-card text-white text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 ml-3">Informasi Akun</h3>
            </div>

            <div class="grid grid-cols-1 gap-4">
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-5 rounded-xl border border-blue-100">
                    <div class="flex items-start">
                        @if($user->google_linked)
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 mr-3 mt-1" viewBox="0 0 24 24">
                                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                </svg>
                            </div>
                        @else
                            <div class="w-6 h-6 bg-blue-500 rounded-lg flex items-center justify-center mr-3 mt-1 flex-shrink-0">
                                <i class="fas fa-envelope text-white text-xs"></i>
                            </div>
                        @endif
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-700">Email</p>
                            <p class="text-base text-gray-900 font-medium mt-1 break-all">{{ $user->google_linked ? $user->google_email : $user->email }}</p>
                            @if($user->google_linked)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-700 mt-2">
                                    <i class="fas fa-check-circle mr-1"></i>Terhubung dengan Google
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-50 to-pink-50 p-5 rounded-xl border border-purple-100">
                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-purple-500 rounded-lg flex items-center justify-center mr-3 mt-1 flex-shrink-0">
                            <i class="fas fa-calendar text-white text-xs"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-700">Bergabung Sejak</p>
                            <p class="text-base text-gray-900 font-medium mt-1">{{ $user->created_at->format('d M Y') }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $user->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>

                @if($user->role === 'dospem' && $user->dospem && $user->dospem->nip)
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 p-5 rounded-xl border border-green-100">
                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-green-500 rounded-lg flex items-center justify-center mr-3 mt-1 flex-shrink-0">
                            <i class="fas fa-id-card text-white text-xs"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-700">NIP</p>
                            <p class="text-base text-gray-900 font-medium mt-1">{{ $user->dospem->nip }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Dosen Pembimbing Info (for Mahasiswa) -->
        @if($user->role === 'mahasiswa' && $dosenPembimbing)
        <div class="bg-white shadow-lg rounded-2xl p-6 border border-gray-100">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chalkboard-teacher text-white text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 ml-3">Dosen Pembimbing</h3>
            </div>
            <div class="bg-gradient-to-br from-purple-50 to-pink-50 p-6 rounded-xl border border-purple-100">
                <div class="flex items-center">
                    <div class="h-16 w-16 rounded-full bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center shadow-lg">
                        <i class="fas fa-chalkboard-teacher text-white text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <div class="text-lg font-bold text-gray-900">{{ $dosenPembimbing->name }}</div>
                        @if($dosenPembimbing->dospem && $dosenPembimbing->dospem->nip)
                        <div class="text-sm text-gray-700 mt-1">NIP: {{ $dosenPembimbing->dospem->nip }}</div>
                        @endif
                        <div class="text-sm text-gray-600 mt-1">Dosen Pembimbing PKL Anda</div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Biodata Information (for Mahasiswa) -->
    @if($user->role === 'mahasiswa' && $profil)
    <div class="bg-white shadow-lg rounded-2xl p-6 border border-gray-100">
        <div class="flex items-center mb-6">
            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-user-graduate text-white text-xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 ml-3">Biodata Mahasiswa</h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div class="bg-gray-50 p-5 rounded-xl border border-gray-200">
                    <label class="block text-sm font-semibold text-gray-700">
                        <i class="fas fa-id-card text-gray-400 mr-2"></i>NIM
                    </label>
                    <p class="mt-2 text-base text-gray-900 font-medium">{{ $profil->nim ?? 'Belum diisi' }}</p>
                </div>

                <div class="bg-gray-50 p-5 rounded-xl border border-gray-200">
                    <label class="block text-sm font-semibold text-gray-700">
                        <i class="fas fa-graduation-cap text-gray-400 mr-2"></i>Program Studi
                    </label>
                    <p class="mt-2 text-base text-gray-900 font-medium">{{ $profil->prodi ?: 'Belum diisi' }}</p>
                </div>

                <div class="bg-gray-50 p-5 rounded-xl border border-gray-200">
                    <label class="block text-sm font-semibold text-gray-700">
                        <i class="fas fa-calendar-alt text-gray-400 mr-2"></i>Semester
                    </label>
                    <p class="mt-2 text-base text-gray-900 font-medium">{{ $profil->semester ?: 'Belum diisi' }}</p>
                </div>

                <div class="bg-gray-50 p-5 rounded-xl border border-gray-200">
                    <label class="block text-sm font-semibold text-gray-700">
                        <i class="fab fa-whatsapp text-gray-400 mr-2"></i>No. WhatsApp
                    </label>
                    <p class="mt-2 text-base text-gray-900 font-medium">
                        @if($profil->no_whatsapp)
                            +62{{ $profil->no_whatsapp }}
                        @else
                            Belum diisi
                        @endif
                    </p>
                </div>
            </div>

            <div class="space-y-4">
                <div class="bg-gray-50 p-5 rounded-xl border border-gray-200">
                    <label class="block text-sm font-semibold text-gray-700">
                        <i class="fas fa-venus-mars text-gray-400 mr-2"></i>Jenis Kelamin
                    </label>
                    <p class="mt-2 text-base text-gray-900 font-medium">
                        @if($profil->jenis_kelamin === 'L') Laki-laki
                        @elseif($profil->jenis_kelamin === 'P') Perempuan
                        @else Belum diisi @endif
                    </p>
                </div>

                <div class="bg-gray-50 p-5 rounded-xl border border-gray-200">
                    <label class="block text-sm font-semibold text-gray-700">
                        <i class="fas fa-chart-line text-gray-400 mr-2"></i>IPK
                    </label>
                    <p class="mt-2 text-base text-gray-900 font-medium">{{ $profil->ipk ?: 'Belum diisi' }}</p>
                </div>

                <div class="bg-gray-50 p-5 rounded-xl border border-gray-200">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                        <i class="fas fa-check-square text-gray-400 mr-2"></i>Persyaratan yang Disetujui
                    </label>
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" {{ $profil->cek_min_semester ? 'checked' : '' }} disabled class="h-5 w-5 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                            </div>
                            <label class="ml-3 text-sm text-gray-700">Telah menempuh minimal 4 semester (D-3) atau 5 semester (D-4)</label>
                        </div>

                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" {{ $profil->cek_ipk_nilaisks ? 'checked' : '' }} disabled class="h-5 w-5 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                            </div>
                            <label class="ml-3 text-sm text-gray-700">IPK tidak di bawah 2,50, tanpa nilai E, nilai D maksimal 9 SKS</label>
                        </div>

                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" {{ $profil->cek_valid_biodata ? 'checked' : '' }} disabled class="h-5 w-5 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                            </div>
                            <label class="ml-3 text-sm text-gray-700">Biodata yang saya masukkan valid dan dapat dipertanggungjawabkan</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Quick Actions (Only for Mahasiswa) -->
    @if($user->role === 'mahasiswa')
    <div class="bg-white shadow-lg rounded-2xl p-6 border border-gray-100">
        <div class="flex items-center mb-6">
            <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-bolt text-white text-xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 ml-3">Aksi Cepat</h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('mahasiswa.hasil-penilaian') }}" class="group bg-gradient-to-br from-blue-50 to-indigo-50 p-6 rounded-xl hover:shadow-lg transition-all duration-300 border-2 border-blue-100 hover:border-blue-300">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-graduation-cap text-white text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h4 class="font-bold text-gray-900 group-hover:text-blue-600 transition-colors">Hasil Penilaian</h4>
                        <p class="text-sm text-gray-600 mt-0.5">Lihat hasil penilaian PKL</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('profile.settings') }}" class="group bg-gradient-to-br from-gray-50 to-slate-50 p-6 rounded-xl hover:shadow-lg transition-all duration-300 border-2 border-gray-200 hover:border-gray-400">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-gray-500 to-slate-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-cog text-white text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h4 class="font-bold text-gray-900 group-hover:text-gray-600 transition-colors">Pengaturan</h4>
                        <p class="text-sm text-gray-600 mt-0.5">Kelola akun dan keamanan</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('activity') }}" class="group bg-gradient-to-br from-green-50 to-emerald-50 p-6 rounded-xl hover:shadow-lg transition-all duration-300 border-2 border-green-100 hover:border-green-300">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-history text-white text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h4 class="font-bold text-gray-900 group-hover:text-green-600 transition-colors">Log Aktivitas</h4>
                        <p class="text-sm text-gray-600 mt-0.5">Lihat riwayat aktivitas</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
    @endif
</div>
@endsection
