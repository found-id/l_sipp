@extends('layouts.app')

@section('title', 'Hasil Penilaian')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Hasil Penilaian</h1>
        <p class="text-gray-600 mt-2">Lihat hasil penilaian PKL Anda</p>
    </div>

    @if($results->count() > 0)
        <!-- Final Results -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Hasil Penilaian Final</h2>
            </div>
            <div class="p-6">
                @foreach($results as $result)
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">{{ $form['name'] ?? 'Penilaian PKL' }}</h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    Dinilai oleh: {{ $result->decidedBy->name ?? 'N/A' }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    Tanggal: {{ $result->created_at ? $result->created_at->format('d M Y H:i') : 'N/A' }}
                                </p>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-blue-600">
                                    {{ $result->total_percent ?? 0 }}%
                                </div>
                                <div class="text-lg font-medium text-gray-700">
                                    {{ $result->letter_grade ?? 'N/A' }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    GPA: {{ $result->gpa_point ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if($responses->count() > 0)
        <!-- Assessment History -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Riwayat Penilaian</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($responses as $response)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-md font-medium text-gray-900">{{ $form['name'] ?? 'Penilaian PKL' }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Dosen: {{ $response->dosen->name ?? 'N/A' }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        Tanggal: {{ $response->updated_at ? $response->updated_at->format('d M Y H:i') : 'N/A' }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    @if($response->is_final)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i>
                                            Final
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-edit mr-1"></i>
                                            Draft
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Detail Penilaian -->
                            <div class="mt-4">
                                <h4 class="text-sm font-medium text-gray-700 mb-3">Detail Penilaian:</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @foreach($form['items'] as $item)
                                        @php
                                            $responseItem = $response->responseItems->where('item_id', $item['id'])->first();
                                            $value = '';
                                            if ($responseItem) {
                                                if ($item['type'] === 'numeric') {
                                                    $value = $responseItem->value_numeric;
                                                } elseif ($item['type'] === 'boolean') {
                                                    $value = $responseItem->value_bool ? 'Ya' : 'Tidak';
                                                } else {
                                                    $value = $responseItem->value_text;
                                                }
                                            }
                                        @endphp
                                        <div class="bg-gray-50 rounded p-3">
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm font-medium text-gray-700">{{ $item['label'] }}</span>
                                                <span class="text-sm text-gray-600">{{ $value ?: 'Belum dinilai' }}</span>
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1">Bobot: {{ $item['weight'] }}%</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @else
        <!-- No Results -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 text-center">
                <i class="fas fa-clipboard-list text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Penilaian</h3>
                <p class="text-gray-600">Anda belum memiliki hasil penilaian. Silakan hubungi dosen pembimbing Anda.</p>
            </div>
        </div>
    @endif
</div>
@endsection
