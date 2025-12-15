@extends('layouts.app')

@section('title', 'Jadwal Seminar - SIP PKL')

@section('content')
<div class="space-y-6">
    <!-- Header with Minimalist Style -->
    <div class="bg-white shadow-sm rounded-2xl p-8 border border-gray-200">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-16 h-16 bg-blue-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-4xl text-blue-600"></i>
                </div>
            </div>
            <div class="ml-5">
                <h1 class="text-3xl font-bold text-gray-900">Jadwal Seminar PKL</h1>
                <p class="text-gray-500 mt-1">Lihat jadwal seminar PKL yang telah dipublikasikan</p>
            </div>
        </div>
    </div>

    <!-- Jadwal List -->
    @if($jadwal->count() > 0)
        <div class="space-y-6">
            @foreach($jadwal as $j)
            <div class="bg-white shadow-sm rounded-2xl overflow-hidden border border-gray-200 hover:shadow-md transition-all duration-300">
                <!-- Card Header -->
                <div class="bg-gray-50 px-6 py-5 border-b border-gray-200">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-white border border-gray-200 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-calendar-check text-blue-600"></i>
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
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-200">
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
                            @php
                                $fileExists = \Storage::disk('public')->exists($j->lokasi_file);
                            @endphp
                            <div class="bg-white p-6 rounded-xl border border-gray-200" id="image-container-{{ $j->id }}">
                                <div class="flex justify-between items-center mb-4">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-2">
                                            <i class="fas fa-image text-gray-600 text-sm"></i>
                                        </div>
                                        <h4 class="text-sm font-semibold text-gray-900">Pratinjau Gambar</h4>
                                    </div>
                                    <a href="{{ $url }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200 text-sm font-medium" id="view-full-btn-{{ $j->id }}">
                                        <i class="fas fa-external-link-alt mr-2"></i>
                                        Lihat Penuh
                                    </a>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100" id="image-wrapper-{{ $j->id }}">
                                    <!-- Loading Indicator -->
                                    <div id="loading-{{ $j->id }}" class="flex flex-col items-center justify-center py-12">
                                        <div class="animate-spin rounded-full h-12 w-12 border-4 border-blue-200 border-t-blue-600 mb-4"></div>
                                        <p class="text-gray-500 text-sm">Memuat gambar...</p>
                                    </div>
                                    <!-- Image -->
                                    <img src="{{ $url }}"
                                         alt="Jadwal"
                                         class="max-w-full h-auto rounded-lg mx-auto block shadow-sm hidden"
                                         id="image-{{ $j->id }}"
                                         onload="handleImageLoad({{ $j->id }})"
                                         onerror="handleImageError(this, {{ $j->id }})">
                                </div>
                                <div id="error-message-{{ $j->id }}" class="hidden text-center py-12">
                                    <div class="inline-flex items-center justify-center w-16 h-16 bg-red-50 rounded-full mb-4">
                                        <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
                                    </div>
                                    <p class="text-gray-900 font-semibold text-lg">Jadwal seminar ini telah dihapus atau hilang.</p>
                                    <p class="text-gray-500 text-sm mt-2">Silahkan hubungi Admin untuk informasi lebih lanjut</p>
                                </div>
                            </div>
                        @elseif($ext === 'pdf')
                            @php
                                $fileExists = \Storage::disk('public')->exists($j->lokasi_file);
                            @endphp
                            <div class="bg-white p-6 rounded-xl border border-gray-200" id="pdf-container-{{ $j->id }}">
                                <div class="flex justify-between items-center mb-4">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-2">
                                            <i class="fas fa-file-pdf text-red-500 text-sm"></i>
                                        </div>
                                        <h4 class="text-sm font-semibold text-gray-900">Pratinjau Dokumen PDF</h4>
                                    </div>
                                    <a href="{{ $url }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200 text-sm font-medium">
                                        <i class="fas fa-external-link-alt mr-2"></i>
                                        Lihat Penuh
                                    </a>
                                </div>
                                
                                <!-- Desktop: Show iframe preview -->
                                <div class="hidden md:block bg-gray-50 rounded-lg border border-gray-200 overflow-hidden">
                                    <iframe src="{{ $url }}"
                                            class="w-full h-96 border-0"
                                            frameborder="0"
                                            onload="checkPdfLoaded(this, {{ $j->id }})"
                                            id="pdf-iframe-{{ $j->id }}"></iframe>
                                </div>
                                
                                <!-- Mobile: Show card with open button -->
                                <div class="md:hidden bg-gradient-to-br from-red-50 to-orange-50 rounded-xl border border-red-100 p-6 text-center">
                                    <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-full shadow-sm mb-4">
                                        <i class="fas fa-file-pdf text-red-500 text-3xl"></i>
                                    </div>
                                    <p class="text-gray-700 font-medium mb-2">{{ basename($j->lokasi_file) }}</p>
                                    <p class="text-gray-500 text-sm mb-4">Ketuk tombol di bawah untuk membuka dokumen PDF</p>
                                    <a href="{{ $url }}" target="_blank" class="inline-flex items-center justify-center w-full px-6 py-3 bg-red-500 text-white rounded-xl hover:bg-red-600 transition-all duration-200 font-medium shadow-sm">
                                        <i class="fas fa-external-link-alt mr-2"></i>
                                        Buka Dokumen PDF
                                    </a>
                                </div>
                                
                                <div id="pdf-error-message-{{ $j->id }}" class="hidden text-center py-12">
                                    <div class="inline-flex items-center justify-center w-16 h-16 bg-red-50 rounded-full mb-4">
                                        <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
                                    </div>
                                    <p class="text-gray-900 font-semibold text-lg">Jadwal seminar ini telah dihapus atau hilang.</p>
                                    <p class="text-gray-500 text-sm mt-2">Silahkan hubungi Admin untuk informasi lebih lanjut</p>
                                </div>
                            </div>
                        @else
                            <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 bg-white border border-gray-200 rounded-xl flex items-center justify-center mr-4">
                                            <i class="fas fa-file-excel text-green-600 text-xl"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-semibold text-gray-900">Berkas {{ strtoupper($ext) }}</h4>
                                            <p class="text-xs text-gray-600 mt-0.5">Pratinjau tidak tersedia, silakan unduh</p>
                                        </div>
                                    </div>
                                    <a href="{{ $url }}" target="_blank" class="inline-flex items-center px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200 font-medium">
                                        <i class="fas fa-download mr-2"></i>
                                        Unduh Berkas
                                    </a>
                                </div>
                            </div>
                        @endif
                    @elseif($j->jenis === 'link' && $j->url_eksternal)
                        <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-white border border-gray-200 rounded-xl flex items-center justify-center mr-4">
                                        <i class="fas fa-link text-blue-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-semibold text-gray-900">Tautan Eksternal</h4>
                                        <p class="text-xs text-gray-600 mt-0.5">Konten dihosting di layanan eksternal</p>
                                    </div>
                                </div>
                                <a href="{{ $j->url_eksternal }}" target="_blank" class="inline-flex items-center px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200 font-medium">
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

        <!-- Paginatin -->
        <div class="mt-6">
            {{ $jadwal->links() }}
        </div>
    @else
        <div class="bg-white shadow-sm rounded-2xl p-12 text-center border border-gray-200">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-50 rounded-full mb-6">
                <i class="fas fa-calendar-times text-4xl text-gray-400"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-3">Belum Ada Jadwal</h3>
            <p class="text-gray-600 text-lg">Belum ada jadwal seminar yang dipublikasikan saat ini.</p>
            <p class="text-gray-500 text-sm mt-2">Silakan cek kembali nanti atau hubungi admin untuk informasi lebih lanjut.</p>
        </div>
    @endif
</div>

<script>
    // Handle successful image load
    function handleImageLoad(jadwalId) {
        // Hide loading indicator
        const loading = document.getElementById('loading-' + jadwalId);
        if (loading) {
            loading.classList.add('hidden');
        }
        
        // Show the image
        const img = document.getElementById('image-' + jadwalId);
        if (img) {
            img.classList.remove('hidden');
        }
    }

    // Handle image loading error
    function handleImageError(img, jadwalId) {
        // Hide loading indicator
        const loading = document.getElementById('loading-' + jadwalId);
        if (loading) {
            loading.classList.add('hidden');
        }
        
        // Hide the image
        img.classList.add('hidden');
        
        // Hide the image wrapper
        const wrapper = document.getElementById('image-wrapper-' + jadwalId);
        if (wrapper) {
            wrapper.classList.add('hidden');
        }
        
        // Show error message
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
