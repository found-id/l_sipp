@extends('layouts.app')

@section('title', 'Penilaian Mahasiswa')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Penilaian Mahasiswa</h1>
        <p class="text-gray-600 mt-2">Penilaian mahasiswa bimbingan berdasarkan rubrik yang telah ditetapkan</p>
    </div>

    @if(!$form)
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-yellow-600 text-xl mr-3"></i>
                <div>
                    <h3 class="text-lg font-medium text-yellow-800">Belum Ada Rubrik Aktif</h3>
                    <p class="text-yellow-700 mt-1">Admin belum membuat rubrik penilaian yang aktif. Silakan hubungi admin untuk membuat rubrik penilaian.</p>
                </div>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Student Selection -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Pilih Mahasiswa</h3>

                    </div>
                    
                    @if(isset($students) && $students->count() > 0)
                        <div class="space-y-2">
                            @foreach($students as $student)
                                <a href="{{ route('dospem.penilaian', ['m' => $student->id]) }}" 
                                   class="block p-4 rounded-lg border {{ $selectedStudent && $selectedStudent->id == $student->id ? 'bg-blue-50 border-blue-200' : 'bg-gray-50 border-gray-200 hover:bg-gray-100' }} transition-colors">
                                    <div class="flex items-center space-x-4">
                                        <!-- Profile Photo -->
                                        <div class="flex-shrink-0">
                                            @if($student->photo && $student->google_linked)
                                                <img src="{{ $student->photo }}" alt="{{ $student->name }}" class="h-12 w-12 rounded-full object-cover">
                                            @else
                                                <div class="h-12 w-12 rounded-full bg-gray-300 flex items-center justify-center">
                                                    <i class="fas fa-user text-gray-600"></i>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Student Info -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <div class="font-medium text-gray-900 truncate">{{ $student->name }}</div>
                                                    <div class="text-sm text-gray-500">
                                                        NIM: {{ $student->profilMahasiswa->nim ?? 'N/A' }} • 
                                                        Prodi: {{ $student->profilMahasiswa->prodi ?? 'N/A' }}
                                                    </div>
                                                </div>
                                                
                                                <!-- Assessment Status -->
                                                <div class="flex flex-col items-end space-y-2">
                                                    @if($allResults && $allResults->has($student->id))
                                                        @php
                                                            $studentResult = $allResults->get($student->id);
                                                        @endphp
                                                        <div class="flex items-center space-x-2">
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                <i class="fas fa-check mr-1"></i>
                                                                Sudah dinilai
                                                            </span>
                                                        </div>
                                                        @if($studentResult)
                                                            <div class="text-right">
                                                                <div class="text-sm font-medium text-gray-900">
                                                                    Total: {{ $studentResult->total_percent ?? 0 }}%
                                                                </div>
                                                                <div class="text-xs text-gray-600">
                                                                    Grade: <span class="font-medium text-blue-600">{{ $studentResult->letter_grade ?? 'N/A' }}</span>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @else
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            <i class="fas fa-clock mr-1"></i>
                                                            Belum dinilai
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-user-graduate text-gray-400 text-4xl mb-4"></i>
                            <p class="text-gray-500">Belum ada mahasiswa bimbingan</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Assessment Form -->
            <div class="lg:col-span-2">
                @if($selectedStudent)
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Penilaian: {{ $selectedStudent->name }}</h3>
                            <p class="text-sm text-gray-600 mt-1">
                                NIM: {{ $selectedStudent->profilMahasiswa->nim ?? 'N/A' }} • 
                                Prodi: {{ $selectedStudent->profilMahasiswa->prodi ?? 'N/A' }}
                            </p>
                        </div>

                        <div class="p-6">
                            <form action="{{ route('dospem.penilaian.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="mahasiswa_id" value="{{ $selectedStudent->id }}">
                                <div class="mb-6">
                                    <h4 class="text-md font-medium text-gray-900 mb-2">{{ $form['name'] }}</h4>
                                    @if($form['description'])
                                    <p class="text-sm text-gray-600">{{ $form['description'] }}</p>
                                    @endif
                                </div>

                                <div class="space-y-6">
                                    @foreach($form['items'] as $item)
                                        <div class="border border-gray-200 rounded-lg p-4">
                                            <div class="flex items-center justify-between mb-3">
                                                <label class="text-sm font-medium text-gray-900">
                                                    {{ $item['label'] }}
                                                    @if($item['required'])
                                                        <span class="text-red-500">*</span>
                                                    @endif
                                                </label>
                                                <span class="text-xs text-gray-500">Bobot: {{ $item['weight'] }}%</span>
                                            </div>

                                            @if($item['type'] === 'numeric')
                                                <div class="flex items-center space-x-4">
                                                    <input type="number" 
                                                           name="items[{{ $item['id'] }}]" 
                                                           value="{{ $responses->get($item['id'])->value_numeric ?? '' }}"
                                                           min="0" 
                                                           max="100" 
                                                           step="0.1"
                                                           class="w-24 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                           required>
                                                    <span class="text-sm text-gray-500">/ 100</span>
                                                </div>
                                            @elseif($item['type'] === 'boolean')
                                                <div class="flex items-center space-x-4">
                                                    <label class="flex items-center">
                                                        <input type="radio" 
                                                               name="items[{{ $item['id'] }}]" 
                                                               value="1"
                                                               {{ ($responses->get($item['id'])->value_bool ?? false) ? 'checked' : '' }}
                                                               class="mr-2">
                                                        <span class="text-sm">Ya</span>
                                                    </label>
                                                    <label class="flex items-center">
                                                        <input type="radio" 
                                                               name="items[{{ $item['id'] }}]" 
                                                               value="0"
                                                               {{ !($responses->get($item['id'])->value_bool ?? false) ? 'checked' : '' }}
                                                               class="mr-2">
                                                        <span class="text-sm">Tidak</span>
                                                    </label>
                                                </div>
                                            @else
                                                <textarea name="items[{{ $item['id'] }}]" 
                                                          rows="3"
                                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                          placeholder="Masukkan penilaian...">{{ $responses->get($item['id'])->value_text ?? '' }}</textarea>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mt-8 flex justify-end space-x-4">
                                    <a href="{{ route('dospem.penilaian') }}" 
                                       class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                                        Batal
                                    </a>
                                    <button type="submit" 
                                            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <i class="fas fa-save mr-2"></i>
                                        Simpan Penilaian
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Hasil Akhir Section -->
                    @if($allResults && $allResults->has($selectedStudent->id))
                        @php
                            $studentResult = $allResults->get($selectedStudent->id);
                        @endphp
                        <div class="mt-6 bg-white rounded-lg shadow">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900">Hasil Akhir</h3>
                            </div>
                            <div class="p-6">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <h4 class="text-md font-medium text-gray-900">Hasil Saat Ini</h4>
                                            <p class="text-sm text-gray-600 mt-1">
                                                Ditetapkan: {{ $studentResult->created_at ? $studentResult->created_at->format('Y-m-d H:i:s') : 'N/A' }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-2xl font-bold text-blue-600">
                                                Total: {{ $studentResult->total_percent ?? 0 }}%
                                            </div>
                                            <div class="text-lg font-medium text-gray-700">
                                                Grade: {{ $studentResult->letter_grade ?? 'N/A' }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                Poin: {{ $studentResult->gpa_point ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="bg-white rounded-lg shadow p-8 text-center">
                        <i class="fas fa-user-graduate text-gray-400 text-4xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Pilih Mahasiswa</h3>
                        <p class="text-gray-600">Pilih mahasiswa dari daftar di sebelah kiri untuk mulai penilaian</p>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>

@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Show success message
            const successDiv = document.createElement('div');
            successDiv.className = 'fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50';
            successDiv.innerHTML = '<i class="fas fa-check mr-2"></i>' + '{{ session("success") }}';
            document.body.appendChild(successDiv);
            
            // Remove after 3 seconds
            setTimeout(() => {
                successDiv.remove();
            }, 3000);
            

        });
    </script>
@endif
@endsection
