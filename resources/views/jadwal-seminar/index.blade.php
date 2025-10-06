@extends('layouts.app')

@section('title', 'Jadwal Seminar - SIPP PKL')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg p-6">
        <h1 class="text-2xl font-bold text-gray-900">Jadwal Seminar</h1>
        <p class="text-gray-600 mt-2">Jadwal seminar PKL yang telah dipublikasikan</p>
    </div>

    <!-- Jadwal List -->
    @if($jadwal->count() > 0)
        <div class="space-y-4">
            @foreach($jadwal as $j)
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $j->judul }}</h3>
                        @if($j->subjudul)
                            <p class="text-sm text-gray-600 mt-1">{{ $j->subjudul }}</p>
                        @endif
                        <p class="text-xs text-gray-500 mt-2">
                            Dipublikasikan: {{ $j->tanggal_publikasi->format('d M Y H:i') }} 
                            oleh {{ $j->pembuat->name }}
                        </p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Aktif
                    </span>
                </div>
                
                @if($j->jenis === 'file' && $j->lokasi_file)
                    @php
                        $ext = strtolower(pathinfo($j->lokasi_file, PATHINFO_EXTENSION));
                        $url = Storage::url($j->lokasi_file);
                    @endphp
                    
                    @if(in_array($ext, ['jpg', 'jpeg', 'png']))
                        <img src="{{ $url }}" alt="Jadwal" class="max-w-full h-auto border border-gray-200 rounded-lg">
                    @elseif($ext === 'pdf')
                        <embed src="{{ $url }}" type="application/pdf" class="w-full h-96 border border-gray-200 rounded-lg">
                    @else
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <a href="{{ $url }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                <i class="fas fa-download mr-2"></i>
                                Unduh Berkas ({{ strtoupper($ext) }})
                            </a>
                            <p class="text-sm text-gray-500 mt-2">Pratinjau Excel tidak didukung di browser. Silakan unduh.</p>
                        </div>
                    @endif
                @elseif($j->jenis === 'link' && $j->url_eksternal)
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <a href="{{ $j->url_eksternal }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                            <i class="fas fa-external-link-alt mr-2"></i>
                            Buka Tautan Jadwal
                        </a>
                        <p class="text-sm text-gray-500 mt-2">Konten dihosting di layanan eksternal.</p>
                    </div>
                @endif
            </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-6">
            {{ $jadwal->links() }}
        </div>
    @else
        <div class="bg-white shadow rounded-lg p-8 text-center">
            <i class="fas fa-calendar-times text-4xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Jadwal</h3>
            <p class="text-gray-600">Belum ada jadwal seminar yang dipublikasikan.</p>
        </div>
    @endif
</div>
@endsection