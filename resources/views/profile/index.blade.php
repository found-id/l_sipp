@extends('layouts.app')

@section('title', 'Profile - SIP PKL')

@section('content')
<div class="space-y-6">
    <!-- Success/Error Messages handled in layout -->

    <!-- Header with Minimalist Style -->
    <div class="bg-white shadow-sm rounded-2xl p-6 md:p-8 border border-gray-200">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
            <div class="flex flex-col sm:flex-row items-center sm:items-start text-center sm:text-left">
                <div class="relative flex-shrink-0">
                    <img src="{{ $user->profile_photo_url }}"
                         alt="{{ $user->name }}"
                         class="h-16 w-16 sm:h-20 sm:w-20 rounded-full object-cover border-4 border-white shadow-md"
                         referrerpolicy="no-referrer"
                         onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=f3f4f6&color=374151&size=200'">

                    <!-- Edit Photo Button -->
                    <button type="button" onclick="document.getElementById('photoInput').click()" class="absolute -bottom-1 -right-1 w-7 h-7 sm:w-8 sm:h-8 bg-white hover:bg-gray-50 rounded-full shadow-md flex items-center justify-center transition-all duration-200 border border-gray-200">
                        <i class="fas fa-camera text-gray-600 text-xs sm:text-sm"></i>
                    </button>
                </div>
                <div class="mt-3 sm:mt-0 sm:ml-5">
                    <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900">{{ $user->name }}</h1>
                    <p class="text-gray-500 text-sm sm:text-base mt-1 break-all">{{ $user->email }}</p>
                    <div class="mt-2">
                        <span class="inline-flex items-center px-2.5 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-medium bg-gray-100 text-gray-700 border border-gray-200">
                            <i class="fas
                                @if($user->role === 'admin') fa-shield-alt
                                @elseif($user->role === 'dospem') fa-chalkboard-teacher
                                @else fa-user-graduate @endif mr-1.5 sm:mr-2 text-gray-500"></i>
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>
                </div>
            </div>
            <a href="{{ route('profile.edit') }}" class="bg-white text-gray-700 px-4 sm:px-6 py-2 sm:py-2.5 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md font-medium border border-gray-300 hover:bg-gray-50 text-sm sm:text-base self-center sm:self-start whitespace-nowrap">
                <i class="fas fa-edit mr-1.5 sm:mr-2"></i>Edit Profile
            </a>
        </div>
    </div>

    <!-- Hidden File Input for Photo Upload -->
    <form id="photoUploadForm" action="{{ route('profile.photo.upload') }}" method="POST" enctype="multipart/form-data" style="display: none;">
        @csrf
        <input type="file" id="photoInput" name="profile_photo" accept="image/*" onchange="document.getElementById('photoUploadForm').submit()">
    </form>

    <!-- Profile Information -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Basic Information -->
        <div class="bg-white shadow-sm rounded-2xl p-6 border border-gray-200">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-id-card text-gray-600 text-lg"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 ml-3">Informasi Akun</h3>
            </div>

            <div class="grid grid-cols-1 gap-4">
                <div class="bg-gray-50 p-5 rounded-xl border border-gray-200">
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
                            <div class="w-6 h-6 bg-gray-200 rounded-md flex items-center justify-center mr-3 mt-1 flex-shrink-0">
                                <i class="fas fa-envelope text-gray-600 text-xs"></i>
                            </div>
                        @endif
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-700">Email</p>
                            <p class="text-base text-gray-900 font-medium mt-1 break-all">{{ $user->google_linked ? $user->google_email : $user->email }}</p>
                            @if($user->google_linked)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700 mt-2 border border-blue-100">
                                    <i class="fas fa-check-circle mr-1"></i>Terhubung dengan Google
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 p-5 rounded-xl border border-gray-200">
                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-gray-200 rounded-md flex items-center justify-center mr-3 mt-1 flex-shrink-0">
                            <i class="fas fa-calendar text-gray-600 text-xs"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-700">Bergabung Sejak</p>
                            <p class="text-base text-gray-900 font-medium mt-1">{{ $user->created_at->format('d M Y') }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $user->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>

                @if($user->role === 'dospem' && $user->dospem && $user->dospem->nip)
                <div class="bg-gray-50 p-5 rounded-xl border border-gray-200">
                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-gray-200 rounded-md flex items-center justify-center mr-3 mt-1 flex-shrink-0">
                            <i class="fas fa-id-card text-gray-600 text-xs"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-700">NIP</p>
                            <p class="text-base text-gray-900 font-medium mt-1">{{ $user->dospem->nip }}</p>
                        </div>
                    </div>
                </div>
                @endif
                
                @if($user->role === 'dospem' && $user->dospem && $user->dospem->no_telepon)
                <div class="bg-gray-50 p-5 rounded-xl border border-gray-200">
                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-gray-200 rounded-md flex items-center justify-center mr-3 mt-1 flex-shrink-0">
                            <i class="fas fa-phone text-gray-600 text-xs"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-700">Nomor Telepon</p>
                            <p class="text-base text-gray-900 font-medium mt-1">+62{{ $user->dospem->no_telepon }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Dosen Pembimbing Info (for Mahasiswa) -->
        @if($user->role === 'mahasiswa' && $dosenPembimbing)
        <div class="bg-white shadow-sm rounded-2xl p-6 border border-gray-200">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chalkboard-teacher text-gray-600 text-lg"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 ml-3">Dosen Pembimbing</h3>
            </div>
            <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                <div class="flex items-center">
                    <div class="h-16 w-16 rounded-full bg-white border border-gray-200 flex items-center justify-center shadow-sm">
                        <i class="fas fa-chalkboard-teacher text-gray-400 text-2xl"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <div class="text-lg font-bold text-gray-900">{{ $dosenPembimbing->name }}</div>
                        @if($dosenPembimbing->dospem && $dosenPembimbing->dospem->nip)
                        <div class="text-sm text-gray-700 mt-1">NIP: {{ $dosenPembimbing->dospem->nip }}</div>
                        @endif
                        @if($dosenPembimbing->dospem && $dosenPembimbing->dospem->no_telepon)
                        <div class="flex items-center text-sm text-gray-700 mt-1">
                            <i class="fas fa-phone text-gray-400 mr-2"></i>
                            <span>+62{{ $dosenPembimbing->dospem->no_telepon }}</span>
                            <a href="https://wa.me/62{{ $dosenPembimbing->dospem->no_telepon }}" target="_blank" 
                               class="ml-2 inline-flex items-center text-green-600 hover:text-green-700">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        </div>
                        @endif
                        <div class="text-sm text-gray-500 mt-1">Dosen Pembimbing PKL Anda</div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Biodata Information (for Mahasiswa) -->
    @if($user->role === 'mahasiswa' && $profil)
    <div class="bg-white shadow-sm rounded-2xl p-6 border border-gray-200">
        <div class="flex items-center mb-6">
            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-user-graduate text-gray-600 text-lg"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 ml-3">Biodata Mahasiswa</h3>
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
    <div class="bg-white shadow-sm rounded-2xl p-6 border border-gray-200">
        <div class="flex items-center mb-6">
            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-bolt text-gray-600 text-lg"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 ml-3">Aksi Cepat</h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('mahasiswa.hasil-penilaian') }}" class="group bg-white p-6 rounded-xl hover:shadow-md transition-all duration-300 border border-gray-200 hover:border-gray-300">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center group-hover:bg-blue-100 transition-colors duration-300">
                        <i class="fas fa-graduation-cap text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h4 class="font-bold text-gray-900 group-hover:text-blue-600 transition-colors">Hasil Penilaian</h4>
                        <p class="text-sm text-gray-600 mt-0.5">Lihat hasil penilaian PKL</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('profile.settings') }}" class="group bg-white p-6 rounded-xl hover:shadow-md transition-all duration-300 border border-gray-200 hover:border-gray-300">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gray-50 rounded-lg flex items-center justify-center group-hover:bg-gray-100 transition-colors duration-300">
                        <i class="fas fa-cog text-gray-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h4 class="font-bold text-gray-900 group-hover:text-gray-600 transition-colors">Pengaturan</h4>
                        <p class="text-sm text-gray-600 mt-0.5">Kelola akun dan keamanan</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('activity') }}" class="group bg-white p-6 rounded-xl hover:shadow-md transition-all duration-300 border border-gray-200 hover:border-gray-300">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center group-hover:bg-green-100 transition-colors duration-300">
                        <i class="fas fa-history text-green-600 text-xl"></i>
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
