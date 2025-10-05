@extends('layouts.app')

@section('title', 'Menu Sistem')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Menu Sistem</h1>
                    <p class="mt-2 text-sm text-gray-600">Kelola pengaturan sistem untuk mengontrol fitur-fitur yang tersedia</p>
                </div>
                <a href="{{ route('admin.kelola-data') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Settings Form -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Pengaturan Fitur</h2>
                <p class="mt-1 text-sm text-gray-600">Aktifkan atau nonaktifkan fitur-fitur yang tersedia untuk mahasiswa</p>
            </div>

            <form method="POST" action="{{ route('admin.system-settings.update') }}" class="px-6 py-4">
                @csrf
                @method('PUT')

                <!-- Laporan PKL Toggle -->
                <div class="mb-6">
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900">Laporan PKL</h3>
                            <p class="mt-1 text-sm text-gray-600">
                                Mengontrol apakah mahasiswa dapat mengunggah Laporan PKL atau tidak.
                                Jika dinonaktifkan, tombol "Laporan PKL" akan menjadi abu-abu dan tidak dapat diklik.
                            </p>
                        </div>
                        <div class="ml-6">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="laporan_pkl_enabled" value="1" 
                                       class="sr-only peer" 
                                       {{ $laporanPklEnabled ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                <span class="ml-3 text-sm font-medium text-gray-900">
                                    {{ $laporanPklEnabled ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>

        <!-- Status Information -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Informasi Status</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ul class="list-disc list-inside space-y-1">
                            <li><strong>Laporan PKL:</strong> {{ $laporanPklEnabled ? 'Aktif - Mahasiswa dapat mengunggah Laporan PKL' : 'Nonaktif - Mahasiswa tidak dapat mengunggah Laporan PKL' }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Update toggle text when changed
document.querySelector('input[name="laporan_pkl_enabled"]').addEventListener('change', function() {
    const span = this.nextElementSibling.nextElementSibling;
    span.textContent = this.checked ? 'Aktif' : 'Nonaktif';
});
</script>
@endsection
