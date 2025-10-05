@extends('layouts.app')

@section('title', 'Jadwal Seminar - SIPP PKL')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg p-6">
        <h1 class="text-2xl font-bold text-gray-900">Jadwal Seminar</h1>
        <p class="text-gray-600 mt-2">Jadwal seminar PKL mahasiswa</p>
    </div>

    <!-- Jadwal List -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Daftar Jadwal Seminar</h3>
        </div>
        
        <div class="divide-y divide-gray-200">
            @forelse($jadwal as $j)
            <div class="px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-calendar-alt text-blue-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">
                            {{ $j->judul ?? 'N/A' }}
                        </div>
                        <div class="text-sm text-gray-500">
                            Subjudul: {{ $j->subjudul ?? 'N/A' }}
                        </div>
                        <div class="text-sm text-gray-500">
                            Dibuat oleh: {{ $j->pembuat->name ?? 'N/A' }}
                        </div>
                        </div>
                    </div>
                    
                    <div class="text-right">
                        <div class="text-sm font-medium text-gray-900">
                            {{ $j->tanggal_dibuat->format('d M Y') }}
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ $j->tanggal_dibuat->format('H:i') }}
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ $j->judul ?? 'Judul belum ditentukan' }}
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="px-6 py-8 text-center text-gray-500">
                <i class="fas fa-calendar-times text-4xl text-gray-300 mb-4"></i>
                <p>Belum ada jadwal seminar</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
