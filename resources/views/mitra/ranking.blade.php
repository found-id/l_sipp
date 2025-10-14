@extends('layouts.app')

@section('title', 'Peringkat Mitra - SIPP PKL')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg p-6">
        <h1 class="text-2xl font-bold text-gray-900">Peringkat Instansi Mitra</h1>
        <p class="text-gray-600 mt-2">Daftar peringkat instansi mitra berdasarkan metode SAW.</p>
    </div>

    <!-- Ranking Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peringkat</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Instansi</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alamat</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Skor Akhir</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($rankedMitra as $item)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-lg font-bold text-gray-800">#{{ $loop->iteration }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $item['nama'] }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-600">{{ $item['alamat'] }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ number_format($item['score'], 4) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                        <i class="fas fa-building text-4xl text-gray-300 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900">Belum ada data mitra untuk diranking.</h3>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
