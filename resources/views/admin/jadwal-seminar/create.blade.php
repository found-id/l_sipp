@extends('layouts.app')

@section('title', 'Publikasikan Jadwal Seminar - SIP PKL')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Publikasikan Jadwal Seminar</h1>
                <p class="text-gray-600 mt-2">Buat publikasi jadwal seminar PKL</p>
            </div>
            <a href="{{ route('admin.jadwal-seminar.manage') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white shadow rounded-lg p-6">
        <form method="POST" action="{{ route('admin.jadwal-seminar.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <!-- Judul -->
            <div>
                <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">Judul *</label>
                <input type="text" id="judul" name="judul" value="{{ old('judul') }}" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Contoh: Jadwal Seminar PKL Semester Genap 2024">
                @error('judul')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Subjudul -->
            <div>
                <label for="subjudul" class="block text-sm font-medium text-gray-700 mb-2">Subjudul (Opsional)</label>
                <input type="text" id="subjudul" name="subjudul" value="{{ old('subjudul') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Contoh: Jadwal Presentasi dan Sidang PKL">
                @error('subjudul')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Tipe Konten -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Konten *</label>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="radio" name="jenis" value="file" {{ old('jenis', 'file') === 'file' ? 'checked' : '' }} 
                               class="mr-2 text-blue-600 focus:ring-blue-500" onchange="toggleFileInput()">
                        <span class="text-sm text-gray-700">File (PDF/Excel/Gambar)</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="jenis" value="link" {{ old('jenis') === 'link' ? 'checked' : '' }} 
                               class="mr-2 text-blue-600 focus:ring-blue-500" onchange="toggleFileInput()">
                        <span class="text-sm text-gray-700">Tautan (Google Sheets/Docs)</span>
                    </label>
                </div>
                @error('jenis')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Upload File -->
            <div id="file-input" class="{{ old('jenis', 'file') === 'file' ? '' : 'hidden' }}">
                <label for="file" class="block text-sm font-medium text-gray-700 mb-2">Upload File *</label>
                <input type="file" id="file" name="file" accept=".pdf,.xls,.xlsx,.jpg,.jpeg,.png"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <p class="text-sm text-gray-500 mt-1">Diterima: PDF, XLS/XLSX, JPG/PNG. Maksimal 10MB.</p>
                @error('file')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- External URL -->
            <div id="url-input" class="{{ old('jenis') === 'link' ? '' : 'hidden' }}">
                <label for="url_eksternal" class="block text-sm font-medium text-gray-700 mb-2">External URL *</label>
                <input type="url" id="url_eksternal" name="url_eksternal" value="{{ old('url_eksternal') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="https://docs.google.com/spreadsheets/...">
                @error('url_eksternal')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Submit Button -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.jadwal-seminar.manage') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                    Batal
                </a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    <i class="fas fa-upload mr-2"></i>Publikasikan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleFileInput() {
    const jenis = document.querySelector('input[name="jenis"]:checked').value;
    const fileInput = document.getElementById('file-input');
    const urlInput = document.getElementById('url-input');
    
    if (jenis === 'file') {
        fileInput.classList.remove('hidden');
        urlInput.classList.add('hidden');
    } else {
        fileInput.classList.add('hidden');
        urlInput.classList.remove('hidden');
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleFileInput();
});
</script>
@endsection
