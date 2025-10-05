@extends('layouts.app')

@section('title', 'Instansi Mitra - SIPP PKL')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg p-6">
        <h1 class="text-2xl font-bold text-gray-900">Instansi Mitra</h1>
        <p class="text-gray-600 mt-2">Daftar instansi mitra PKL</p>
    </div>

    <!-- Mitra List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($mitra as $m)
        <div class="bg-white shadow rounded-lg p-6 hover:shadow-md transition-shadow">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-building text-blue-600"></i>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-lg font-medium text-gray-900">{{ $m->nama }}</h3>
                    
                    @if($m->alamat)
                    <div class="mt-2">
                        <div class="flex items-start">
                            <i class="fas fa-map-marker-alt text-gray-400 mr-2 mt-1"></i>
                            <p class="text-sm text-gray-600">{{ $m->alamat }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($m->kontak)
                    <div class="mt-2">
                        <div class="flex items-center">
                            <i class="fas fa-phone text-gray-400 mr-2"></i>
                            <p class="text-sm text-gray-600">{{ $m->kontak }}</p>
                        </div>
                    </div>
                    @endif
                    
                    <div class="mt-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check mr-1"></i>
                            Mitra Aktif
                        </span>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="bg-white shadow rounded-lg p-8 text-center">
                <i class="fas fa-building text-4xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Mitra</h3>
                <p class="text-gray-600">Belum ada instansi mitra yang terdaftar.</p>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection
