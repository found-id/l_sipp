@extends('layouts.app')

@section('title', 'Kelola Jadwal Seminar - SIP PKL')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Kelola Jadwal Seminar</h1>
                <p class="text-gray-600 mt-2">Kelola publikasi jadwal seminar PKL</p>
            </div>
            <a href="{{ route('admin.jadwal-seminar.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i>Publikasikan Jadwal
            </a>
        </div>
    </div>

    <!-- Jadwal List -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Daftar Publikasi</h3>
        </div>
        
        @if($jadwal->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($jadwal as $j)
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-2">
                                <h4 class="text-lg font-medium text-gray-900">{{ $j->judul }}</h4>
                                @if($j->status_aktif)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Nonaktif
                                    </span>
                                @endif
                            </div>
                            
                            @if($j->subjudul)
                                <p class="text-sm text-gray-600 mb-2">{{ $j->subjudul }}</p>
                            @endif
                            
                            <div class="flex items-center space-x-4 text-sm text-gray-500">
                                <span>{{ $j->created_at->format('d M Y H:i') }}</span>
                                <span>•</span>
                                <span>Tipe: {{ ucfirst($j->jenis) }}</span>
                                <span>•</span>
                                <span>Oleh: {{ $j->pembuat->name }}</span>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('jadwal-seminar') }}" class="bg-gray-100 text-gray-700 px-3 py-1 rounded-md hover:bg-gray-200 text-sm">
                                <i class="fas fa-eye mr-1"></i>Lihat
                            </a>
                            
                            <form method="POST" action="{{ route('admin.jadwal-seminar.toggle', $j->id) }}" class="inline">
                                @csrf
                                <button type="submit" class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-md hover:bg-yellow-200 text-sm">
                                    <i class="fas fa-lock mr-1"></i>
                                    {{ $j->status_aktif ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                            
                            <form method="POST" action="{{ route('admin.jadwal-seminar.destroy', $j->id) }}" class="inline" onsubmit="return confirm('Hapus publikasi ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-100 text-red-700 px-3 py-1 rounded-md hover:bg-red-200 text-sm">
                                    <i class="fas fa-trash mr-1"></i>Hapus
                                </button>
                            </form>
                            
                            @if($j->jenis === 'file' && $j->lokasi_file)
                                <a href="{{ Storage::url($j->lokasi_file) }}" target="_blank" class="bg-blue-100 text-blue-700 px-3 py-1 rounded-md hover:bg-blue-200 text-sm">
                                    <i class="fas fa-download mr-1"></i>Unduh
                                </a>
                            @elseif($j->jenis === 'link' && $j->url_eksternal)
                                <a href="{{ $j->url_eksternal }}" target="_blank" class="bg-green-100 text-green-700 px-3 py-1 rounded-md hover:bg-green-200 text-sm">
                                    <i class="fas fa-external-link-alt mr-1"></i>Buka Link
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-3 border-t border-gray-200">
                {{ $jadwal->links() }}
            </div>
        @else
            <div class="p-8 text-center">
                <i class="fas fa-calendar-times text-4xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Publikasi</h3>
                <p class="text-gray-600 mb-4">Belum ada jadwal seminar yang dipublikasikan.</p>
                <a href="{{ route('admin.jadwal-seminar.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    <i class="fas fa-plus mr-2"></i>Publikasikan Jadwal Pertama
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
