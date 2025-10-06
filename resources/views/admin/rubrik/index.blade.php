@extends('layouts.app')

@section('title', 'Kelola Rubrik Penilaian')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Kelola Rubrik Penilaian</h1>
        <p class="text-gray-600 mt-2">Kelola rubrik penilaian untuk PKL</p>
    </div>

    <!-- Create New Form Button -->
    <div class="mb-6">
        <a href="{{ route('admin.rubrik.create-form') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i>Tambah Rubrik Baru
        </a>
    </div>

    <!-- Assessment Forms List -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Daftar Rubrik Penilaian</h2>
        </div>
        <div class="p-6">
                    @if(isset($forms) && $forms->count() > 0)
                <div class="space-y-4">
                    @foreach($forms as $form)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h3 class="text-lg font-medium text-gray-900">{{ $form->name }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">{{ $form->description }}</p>
                                    <div class="flex items-center mt-2 space-x-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $form->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            <i class="fas {{ $form->is_active ? 'fa-check' : 'fa-times' }} mr-1"></i>
                                            {{ $form->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                        </span>
                                        <span class="text-sm text-gray-500">
                                            {{ $form->items->count() }} item penilaian
                                        </span>
                                        <span class="text-sm text-gray-500">
                                            Dibuat: {{ $form->created_at->format('d M Y') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.rubrik.edit', $form->id) }}" class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.rubrik.toggle', $form->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-yellow-600 hover:text-yellow-800">
                                            <i class="fas {{ $form->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.rubrik.delete', $form->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus rubrik ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-clipboard-list text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Rubrik</h3>
                    <p class="text-gray-600">Silakan buat rubrik penilaian pertama Anda.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Grade Scale Steps -->
    @if($gradeSteps->count() > 0)
    <div class="mt-8 bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Skala Penilaian</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($gradeSteps as $step)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <h4 class="font-medium text-gray-900">{{ $step->letter }}</h4>
                                <p class="text-sm text-gray-600">GPA: {{ $step->gpa_point }}</p>
                            </div>
                            <div class="text-right">
                                <span class="text-sm text-gray-500">
                                    {{ $step->min_score }}% - {{ $step->max_score }}%
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
