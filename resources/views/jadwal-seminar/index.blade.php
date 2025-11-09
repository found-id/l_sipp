@extends('layouts.app')

@section('title', 'Jadwal Seminar - SIPP PKL')

@section('content')
<div class="space-y-6">
    <!-- Header with Gradient -->
    <div class="relative overflow-hidden bg-gradient-to-br from-purple-600 via-indigo-600 to-blue-600 shadow-2xl rounded-2xl p-8">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="relative z-10">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-4xl text-white"></i>
                    </div>
                </div>
                <div class="ml-5">
                    <h1 class="text-3xl font-bold text-white">Jadwal Seminar PKL</h1>
                    <p class="text-purple-100 mt-1">Lihat jadwal seminar PKL yang telah dipublikasikan</p>
                </div>
            </div>
        </div>
        <!-- Decorative circles -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mr-32 -mt-32"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full -ml-24 -mb-24"></div>
    </div>

    <!-- Jadwal List -->
    @if($jadwal->count() > 0)
        <div class="space-y-6">
            @foreach($jadwal as $j)
            <div class="bg-white shadow-lg rounded-2xl overflow-hidden border border-gray-100 hover:shadow-xl transition-all duration-300">
                <!-- Card Header -->
                <div class="bg-gradient-to-r from-purple-50 to-indigo-50 px-6 py-5 border-b border-gray-200">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-calendar-check text-white"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">{{ $j->judul }}</h3>
                                    @if($j->subjudul)
                                        <p class="text-sm text-gray-600 mt-0.5">{{ $j->subjudul }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="ml-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-700">
                                <i class="fas fa-clock mr-1"></i>
                                {{ $j->created_at->format('d M Y') }}
                            </span>
                        </div>
                    </div>
                    <div class="mt-3 flex items-center text-sm text-gray-500">
                        <i class="fas fa-user mr-2"></i>
                        <span>Dipublikasikan oleh <span class="font-medium text-gray-700">{{ $j->pembuat->name }}</span> pada {{ $j->created_at->format('d M Y H:i') }}</span>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="p-6">
                    @if($j->jenis === 'file' && $j->lokasi_file)
                        @php
                            $ext = strtolower(pathinfo($j->lokasi_file, PATHINFO_EXTENSION));
                            $filename = basename($j->lokasi_file);
                            $url = route('jadwal.file', ['filename' => $filename]);
                        @endphp

                        @if(in_array($ext, ['jpg', 'jpeg', 'png']))
                            <div class="bg-white p-6 rounded-xl border-2 border-gray-200" id="image-container-{{ $j->id }}">
                                <div class="flex justify-between items-center mb-4">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center mr-2">
                                            <i class="fas fa-image text-white text-sm"></i>
                                        </div>
                                        <h4 class="text-sm font-semibold text-gray-900">Pratinjau Gambar</h4>
                                    </div>
                                    <a href="{{ $url }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-md hover:shadow-lg text-sm font-medium">
                                        <i class="fas fa-external-link-alt mr-2"></i>
                                        Lihat Penuh
                                    </a>
                                </div>
                                <div class="bg-white rounded-lg p-4 shadow-inner">
                                    <img src="{{ $url }}"
                                         alt="Jadwal"
                                         class="max-w-full h-auto rounded-lg mx-auto block shadow-lg"
                                         onerror="handleImageError(this, {{ $j->id }})">
                                </div>
                                <div id="error-message-{{ $j->id }}" class="hidden text-center py-12">
                                    <div class="inline-flex items-center justify-center w-16 h-16 bg-yellow-100 rounded-full mb-4">
                                        <i class="fas fa-exclamation-triangle text-yellow-600 text-2xl"></i>
                                    </div>
                                    <p class="text-gray-900 font-semibold text-lg">Jadwal seminar ini telah dihapus atau hilang.</p>
                                    <p class="text-gray-500 text-sm mt-2">Silahkan hubungi Admin untuk informasi lebih lanjut</p>
                                </div>
                            </div>
                        @elseif($ext === 'pdf')
                            <div class="bg-white p-6 rounded-xl border-2 border-gray-200" id="pdf-container-{{ $j->id }}">
                                <div class="flex justify-between items-center mb-4">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-red-500 rounded-lg flex items-center justify-center mr-2">
                                            <i class="fas fa-file-pdf text-white text-sm"></i>
                                        </div>
                                        <h4 class="text-sm font-semibold text-gray-900">Pratinjau Dokumen PDF</h4>
                                    </div>
                                    <a href="{{ $url }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg hover:from-red-700 hover:to-red-800 transition-all duration-200 shadow-md hover:shadow-lg text-sm font-medium">
                                        <i class="fas fa-file-pdf mr-2"></i>
                                        Lihat Penuh
                                    </a>
                                </div>
                                <div class="bg-white rounded-lg shadow-inner overflow-hidden">
                                    <iframe src="{{ $url }}"
                                            class="w-full h-96 border-0"
                                            frameborder="0"
                                            onload="checkPdfLoaded(this, {{ $j->id }})"
                                            id="pdf-iframe-{{ $j->id }}"></iframe>
                                </div>
                                <div id="pdf-error-message-{{ $j->id }}" class="hidden text-center py-12">
                                    <div class="inline-flex items-center justify-center w-16 h-16 bg-yellow-100 rounded-full mb-4">
                                        <i class="fas fa-exclamation-triangle text-yellow-600 text-2xl"></i>
                                    </div>
                                    <p class="text-gray-900 font-semibold text-lg">Jadwal seminar ini telah dihapus atau hilang.</p>
                                    <p class="text-gray-500 text-sm mt-2">Silahkan hubungi Admin untuk informasi lebih lanjut</p>
                                </div>
                            </div>
                        @else
                            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-6 rounded-xl border-2 border-blue-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mr-4">
                                            <i class="fas fa-file-excel text-white text-xl"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-semibold text-gray-900">Berkas {{ strtoupper($ext) }}</h4>
                                            <p class="text-xs text-gray-600 mt-0.5">Pratinjau tidak tersedia, silakan unduh</p>
                                        </div>
                                    </div>
                                    <a href="{{ $url }}" target="_blank" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-md hover:shadow-lg font-medium">
                                        <i class="fas fa-download mr-2"></i>
                                        Unduh Berkas
                                    </a>
                                </div>
                            </div>
                        @endif
                    @elseif($j->jenis === 'link' && $j->url_eksternal)
                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 p-6 rounded-xl border-2 border-green-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center mr-4">
                                        <i class="fas fa-link text-white text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-semibold text-gray-900">Tautan Eksternal</h4>
                                        <p class="text-xs text-gray-600 mt-0.5">Konten dihosting di layanan eksternal</p>
                                    </div>
                                </div>
                                <a href="{{ $j->url_eksternal }}" target="_blank" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition-all duration-200 shadow-md hover:shadow-lg font-medium">
                                    <i class="fas fa-external-link-alt mr-2"></i>
                                    Buka Tautan
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $jadwal->links() }}
        </div>
    @else
        <div class="bg-white shadow-lg rounded-2xl p-12 text-center border border-gray-100">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-purple-100 to-indigo-100 rounded-full mb-6">
                <i class="fas fa-calendar-times text-4xl text-purple-600"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-3">Belum Ada Jadwal</h3>
            <p class="text-gray-600 text-lg">Belum ada jadwal seminar yang dipublikasikan saat ini.</p>
            <p class="text-gray-500 text-sm mt-2">Silakan cek kembali nanti atau hubungi admin untuk informasi lebih lanjut.</p>
        </div>
    @endif
</div>

<script>
    // Handle image loading error
    function handleImageError(img, jadwalId) {
        img.style.display = 'none';
        document.getElementById('error-message-' + jadwalId).classList.remove('hidden');
    }

    // Check if PDF loaded successfully
    function checkPdfLoaded(iframe, jadwalId) {
        try {
            // Try to access iframe content
            fetch(iframe.src, { method: 'HEAD' })
                .then(response => {
                    if (!response.ok || response.status === 404) {
                        // File not found or error
                        iframe.style.display = 'none';
                        document.getElementById('pdf-error-message-' + jadwalId).classList.remove('hidden');
                    }
                })
                .catch(error => {
                    // Network error or file not accessible
                    iframe.style.display = 'none';
                    document.getElementById('pdf-error-message-' + jadwalId).classList.remove('hidden');
                });
        } catch (e) {
            // Error accessing iframe
            iframe.style.display = 'none';
            document.getElementById('pdf-error-message-' + jadwalId).classList.remove('hidden');
        }
    }
</script>
@endsection
