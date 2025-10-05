@extends('layouts.app')

@section('title', 'Log Aktivitas - SIPP PKL')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg p-6">
        <h1 class="text-2xl font-bold text-gray-900">Log Aktivitas</h1>
        <p class="text-gray-600 mt-2">Riwayat aktivitas dalam sistem</p>
    </div>

    <!-- Activities List -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Daftar Aktivitas</h3>
        </div>
        
        <div class="divide-y divide-gray-200">
            @forelse($activities as $activity)
            <div class="px-6 py-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 rounded-full flex items-center justify-center
                            @if($activity->tipe === 'upload_dokumen') bg-blue-100
                            @elseif($activity->tipe === 'validasi_dokumen') bg-green-100
                            @elseif($activity->tipe === 'login') bg-purple-100
                            @elseif($activity->tipe === 'logout') bg-red-100
                            @else bg-gray-100 @endif">
                            <i class="fas 
                                @if($activity->tipe === 'upload_dokumen') fa-upload
                                @elseif($activity->tipe === 'validasi_dokumen') fa-check
                                @elseif($activity->tipe === 'login') fa-sign-in-alt
                                @elseif($activity->tipe === 'logout') fa-sign-out-alt
                                @else fa-info @endif
                                @if($activity->tipe === 'upload_dokumen') text-blue-600
                                @elseif($activity->tipe === 'validasi_dokumen') text-green-600
                                @elseif($activity->tipe === 'login') text-purple-600
                                @elseif($activity->tipe === 'logout') text-red-600
                                @else text-gray-600 @endif"></i>
                        </div>
                    </div>
                    
                    <div class="ml-4 flex-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    @if($activity->tipe === 'upload_dokumen')
                                        {{ $activity->mahasiswa->name ?? $activity->user->name }} mengupload {{ $activity->pesan['document_type'] ?? 'dokumen' }}
                                    @elseif($activity->tipe === 'validasi_dokumen')
                                        {{ $activity->user->name }} memvalidasi dokumen {{ $activity->pesan['document_type'] ?? '' }} milik {{ $activity->mahasiswa->name ?? '' }}
                                    @elseif($activity->tipe === 'login')
                                        {{ $activity->pesan['message'] ?? ($activity->user->name . ' melakukan login') }}
                                    @elseif($activity->tipe === 'logout')
                                        {{ $activity->pesan['message'] ?? ($activity->user->name . ' melakukan logout') }}
                                    @elseif($activity->tipe === 'register')
                                        {{ $activity->pesan['message'] ?? ($activity->user->name . ' melakukan registrasi') }}
                                    @else
                                        {{ $activity->pesan['message'] ?? ($activity->pesan['action'] ?? 'Aktivitas') }}
                                    @endif
                                </p>
                                
                                @if($activity->pesan['catatan'] ?? false)
                                    <p class="text-sm text-gray-600 mt-1">
                                        <strong>Catatan:</strong> {{ $activity->pesan['catatan'] }}
                                    </p>
                                @endif
                                
                                @if($activity->pesan['file_name'] ?? false)
                                    <p class="text-sm text-gray-500 mt-1">
                                        <i class="fas fa-file mr-1"></i>{{ $activity->pesan['file_name'] }}
                                    </p>
                                @endif
                            </div>
                            
                            <div class="text-right">
                                <p class="text-xs text-gray-500">
                                    {{ $activity->tanggal_dibuat->format('d M Y H:i') }}
                                </p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($activity->tipe === 'upload_dokumen') bg-blue-100 text-blue-800
                                    @elseif($activity->tipe === 'validasi_dokumen') bg-green-100 text-green-800
                                    @elseif($activity->tipe === 'login') bg-purple-100 text-purple-800
                                    @elseif($activity->tipe === 'logout') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $activity->tipe)) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="px-6 py-8 text-center text-gray-500">
                <i class="fas fa-history text-4xl text-gray-300 mb-4"></i>
                <p>Tidak ada aktivitas</p>
            </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-3 border-t border-gray-200">
            {{ $activities->links() }}
        </div>
    </div>
</div>
@endsection
