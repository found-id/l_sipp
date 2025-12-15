@extends('layouts.app')

@section('title', 'Penilaian Mahasiswa')

@push('head')
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">
@endpush

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-center space-x-4">
            <div class="h-12 w-12 rounded-lg bg-blue-100 flex items-center justify-center">
                <i class="fas fa-clipboard-check text-blue-600 text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Penilaian Mahasiswa</h1>
                <p class="text-gray-600 mt-1">Penilaian mahasiswa bimbingan berdasarkan rubrik yang telah ditetapkan</p>
            </div>
        </div>
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
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-white">
                        <h3 class="text-base font-semibold text-gray-900">Pilih Mahasiswa</h3>
                        <p class="text-xs text-gray-500 mt-1">{{ $students->count() }} mahasiswa bimbingan</p>
                    </div>

                    @if(isset($students) && $students->count() > 0)
                        <!-- Search Input -->
                        <div class="px-4 py-3 border-b border-gray-100">
                            <div class="relative">
                                <input type="text"
                                       id="searchStudent"
                                       placeholder="Cari mahasiswa..."
                                       class="w-full pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            </div>
                        </div>

                        <div id="studentList" class="divide-y divide-gray-100 max-h-[600px] overflow-y-auto">
                            @foreach($students as $student)
                                <a href="{{ route('dospem.penilaian', ['m' => $student->id]) }}"
                                   class="block p-4 hover:bg-gray-50 transition-colors {{ $selectedStudent && $selectedStudent->id == $student->id ? 'bg-blue-50 border-l-4 border-blue-500' : '' }}">
                                    <div class="flex items-center space-x-3">
                                        <!-- Profile Photo -->
                                        <div class="flex-shrink-0">
                                            @if($student->photo)
                                                @if($student->google_linked)
                                                    @php
                                                        $photoUrl = $student->photo;
                                                        if (str_contains($photoUrl, 'googleusercontent.com')) {
                                                            $photoUrl = preg_replace('/=s\d+-c/', '', $photoUrl);
                                                            $photoUrl .= '=s96-c';
                                                        }
                                                    @endphp
                                                    <img src="{{ $photoUrl }}"
                                                         alt="{{ $student->name }}"
                                                         class="h-10 w-10 rounded-full object-cover"
                                                         referrerpolicy="no-referrer"
                                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                    <div class="h-10 w-10 rounded-full bg-blue-100 items-center justify-center" style="display: none;">
                                                        <i class="fas fa-user text-blue-600"></i>
                                                    </div>
                                                @else
                                                    <img src="{{ asset('storage/' . $student->photo) }}"
                                                         alt="{{ $student->name }}"
                                                         class="h-10 w-10 rounded-full object-cover"
                                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                    <div class="h-10 w-10 rounded-full bg-blue-100 items-center justify-center" style="display: none;">
                                                        <i class="fas fa-user text-blue-600"></i>
                                                    </div>
                                                @endif
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                    <i class="fas fa-user text-blue-600"></i>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Student Info -->
                                        <div class="flex-1 min-w-0">
                                            <div class="text-sm font-medium text-gray-900 truncate">
                                                {{ $student->name }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $student->profilMahasiswa->nim ?? 'N/A' }}
                                            </div>
                                        </div>

                                        <!-- Assessment Status -->
                                        <div class="flex-shrink-0">
                                            @if($allResults && $allResults->has($student->id))
                                                @php
                                                    $studentResult = $allResults->get($student->id);
                                                @endphp
                                                <div class="text-right">
                                                    <div class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        {{ $studentResult->letter_grade ?? 'N/A' }}
                                                    </div>
                                                    <div class="text-xs text-gray-500 mt-1">
                                                        {{ $studentResult->total_percent ?? 0 }}%
                                                    </div>
                                                </div>
                                            @else
                                                <div class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                                    <i class="fas fa-clock mr-1"></i> Pending
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-user-graduate text-gray-300 text-4xl mb-3"></i>
                            <p class="text-gray-500 text-sm">Belum ada mahasiswa bimbingan</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Assessment Form -->
            <div class="lg:col-span-2">
                @if($selectedStudent)
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-white">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <!-- Profile Photo -->
                                    <div class="flex-shrink-0">
                                        @if($selectedStudent->photo)
                                            @if($selectedStudent->google_linked)
                                                @php
                                                    $photoUrl = $selectedStudent->photo;
                                                    if (str_contains($photoUrl, 'googleusercontent.com')) {
                                                        $photoUrl = preg_replace('/=s\d+-c/', '', $photoUrl);
                                                        $photoUrl .= '=s96-c';
                                                    }
                                                @endphp
                                                <img src="{{ $photoUrl }}"
                                                     alt="{{ $selectedStudent->name }}"
                                                     class="h-12 w-12 rounded-full object-cover border-2 border-blue-200"
                                                     referrerpolicy="no-referrer"
                                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                <div class="h-12 w-12 rounded-full bg-blue-100 items-center justify-center" style="display: none;">
                                                    <i class="fas fa-user text-blue-600"></i>
                                                </div>
                                            @else
                                                <img src="{{ asset('storage/' . $selectedStudent->photo) }}"
                                                     alt="{{ $selectedStudent->name }}"
                                                     class="h-12 w-12 rounded-full object-cover border-2 border-blue-200"
                                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                <div class="h-12 w-12 rounded-full bg-blue-100 items-center justify-center" style="display: none;">
                                                    <i class="fas fa-user text-blue-600"></i>
                                                </div>
                                            @endif
                                        @else
                                            <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center">
                                                <i class="fas fa-user text-blue-600"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <h3 class="text-base font-semibold text-gray-900">{{ $selectedStudent->name }}</h3>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ $selectedStudent->profilMahasiswa->nim ?? 'N/A' }} &bull;
                                            {{ $selectedStudent->profilMahasiswa->prodi ?? 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                                @if($allResults && $allResults->has($selectedStudent->id))
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i> Sudah Dinilai
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="p-6">
                            <form action="{{ route('dospem.penilaian.store') }}" method="POST" id="penilaianForm">
                                @csrf
                                <input type="hidden" name="mahasiswa_id" value="{{ $selectedStudent->id }}">

                                <div class="space-y-4">
                                    @foreach($form['items'] as $index => $item)
                                        <div class="bg-gray-50 rounded-lg p-4">
                                            <div class="flex items-start justify-between mb-3">
                                                <label class="text-sm font-medium text-gray-900 flex items-center">
                                                    <span class="bg-blue-600 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center mr-2">{{ $index + 1 }}</span>
                                                    {{ $item['label'] }}
                                                    @if($item['required'])
                                                        <span class="text-red-500 ml-1">*</span>
                                                    @endif
                                                </label>
                                                <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-1 rounded">{{ $item['weight'] }}%</span>
                                            </div>

                                            @if($item['type'] === 'numeric')
                                                <div class="flex items-center space-x-3">
                                                    <input type="range"
                                                           id="range_{{ $item['id'] }}"
                                                           min="0"
                                                           max="100"
                                                           value="{{ $responses->get($item['id'])->value_numeric ?? 0 }}"
                                                           data-initial-value="{{ $responses->get($item['id'])->value_numeric ?? 0 }}"
                                                           class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer assessment-range-slider"
                                                           oninput="document.getElementById('input_{{ $item['id'] }}').value = this.value; updateRangeColor(this);">
                                                    <input type="number"
                                                           id="input_{{ $item['id'] }}"
                                                           name="items[{{ $item['id'] }}]"
                                                           value="{{ $responses->get($item['id'])->value_numeric ?? '' }}"
                                                           data-initial-value="{{ $responses->get($item['id'])->value_numeric ?? '' }}"
                                                           min="0"
                                                           max="100"
                                                           step="0.1"
                                                           class="w-20 px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-center"
                                                           oninput="document.getElementById('range_{{ $item['id'] }}').value = this.value; updateRangeColor(document.getElementById('range_{{ $item['id'] }}'));"
                                                           required>
                                                </div>
                                            @elseif($item['type'] === 'boolean')
                                                <div class="flex items-center space-x-6">
                                                    <label class="flex items-center cursor-pointer">
                                                        <input type="radio"
                                                               name="items[{{ $item['id'] }}]"
                                                               value="1"
                                                               {{ ($responses->get($item['id'])->value_bool ?? false) ? 'checked' : '' }}
                                                               class="w-4 h-4 text-green-600 focus:ring-green-500">
                                                        <span class="ml-2 text-sm text-gray-700">Ya</span>
                                                    </label>
                                                    <label class="flex items-center cursor-pointer">
                                                        <input type="radio"
                                                               name="items[{{ $item['id'] }}]"
                                                               value="0"
                                                               {{ !($responses->get($item['id'])->value_bool ?? false) ? 'checked' : '' }}
                                                               class="w-4 h-4 text-red-600 focus:ring-red-500">
                                                        <span class="ml-2 text-sm text-gray-700">Tidak</span>
                                                    </label>
                                                </div>
                                            @else
                                                <textarea name="items[{{ $item['id'] }}]"
                                                          rows="3"
                                                          class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                          placeholder="Masukkan penilaian...">{{ $responses->get($item['id'])->value_text ?? '' }}</textarea>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mt-6 flex justify-end space-x-3">
                                    @if($allResults && $allResults->has($selectedStudent->id))
                                        <button type="button" onclick="if(confirm('Apakah Anda yakin ingin menghapus/reset penilaian mahasiswa ini?')) { document.getElementById('deletePenilaianForm').submit(); }" class="px-4 py-2 text-sm border border-red-300 rounded-lg text-red-600 hover:bg-red-50 transition inline-flex items-center">
                                            <i class="fas fa-trash-alt mr-2"></i>
                                            Hapus
                                        </button>
                                    @endif
                                    <a href="{{ route('dospem.penilaian') }}"
                                       class="px-4 py-2 text-sm border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                                        Batal
                                    </a>
                                    <button type="submit"
                                            class="px-6 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                                        <i class="fas fa-save mr-2"></i>
                                        Simpan Penilaian
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Hidden form for delete action -->
                    @if($allResults && $allResults->has($selectedStudent->id))
                        <form id="deletePenilaianForm" action="{{ route('dospem.penilaian.destroy', $selectedStudent->id) }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endif

                    <!-- Hasil Akhir Section -->
                    @if($allResults && $allResults->has($selectedStudent->id))
                        @php
                            $studentResult = $allResults->get($selectedStudent->id);
                        @endphp
                        <div class="mt-6 bg-white rounded-lg shadow overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                                <h3 class="text-base font-semibold text-gray-900">Hasil Penilaian</h3>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                                        <div class="text-2xl font-bold text-blue-600">
                                            {{ $studentResult->total_percent ?? 0 }}%
                                        </div>
                                        <div class="text-xs text-gray-600 mt-1">Total Skor</div>
                                    </div>
                                    <div class="text-center p-4 bg-green-50 rounded-lg">
                                        <div class="text-2xl font-bold text-green-600">
                                            {{ $studentResult->letter_grade ?? 'N/A' }}
                                        </div>
                                        <div class="text-xs text-gray-600 mt-1">Grade</div>
                                    </div>
                                </div>
                                <div class="mt-4 text-xs text-gray-500 text-center">
                                    <i class="fas fa-clock mr-1"></i>
                                    Terakhir diperbarui: {{ $studentResult->updated_at ? $studentResult->updated_at->format('d M Y, H:i') : 'N/A' }}
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="bg-white rounded-lg shadow p-12 text-center">
                        <i class="fas fa-hand-pointer text-gray-300 text-5xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Pilih Mahasiswa</h3>
                        <p class="text-sm text-gray-600">Pilih mahasiswa dari daftar di sebelah kiri untuk mulai penilaian</p>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>

<style>
/* Custom Range Slider Thumb */
input[type="range"] {
    -webkit-appearance: none;
    appearance: none;
    height: 8px;
    border-radius: 4px;
    outline: none;
}

/* Thumb untuk Chrome, Safari, Opera */
input[type="range"]::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 20px;          /* Ukuran lebar */
    height: 20px;         /* Ukuran tinggi */
    border-radius: 50%;   /* Bentuk bulat */
    background: #FFFFFF;  /* Warna putih */
    border: 3px solid #3B82F6;  /* Stroke biru */
    cursor: pointer;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Thumb untuk Firefox */
input[type="range"]::-moz-range-thumb {
    width: 20px;          /* Ukuran lebar */
    height: 20px;         /* Ukuran tinggi */
    border-radius: 50%;   /* Bentuk bulat */
    background: #FFFFFF;  /* Warna putih */
    border: 3px solid #3B82F6;  /* Stroke biru */
    cursor: pointer;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Track untuk Firefox */
input[type="range"]::-moz-range-track {
    height: 8px;
    border-radius: 4px;
}
</style>

<script>
function updateRangeColor(rangeInput) {
    const value = rangeInput.value;
    const percentage = (value / 100) * 100;
    rangeInput.style.background = `linear-gradient(to right, #3B82F6 0%, #3B82F6 ${percentage}%, #E5E7EB ${percentage}%, #E5E7EB 100%)`;
}

// Function to force update all sliders
function forceUpdateAllSliders() {
    console.log('🔄 Force updating all sliders...');

    document.querySelectorAll('.assessment-range-slider').forEach(function(range) {
        // Get the corresponding input field
        const inputId = range.id.replace('range_', 'input_');
        const inputElement = document.getElementById(inputId);

        // Force sync: Set range value from data attribute (fresh from server)
        const initialValue = range.getAttribute('data-initial-value');

        console.log(`Slider ${range.id}:`, {
            initialValue: initialValue,
            currentRangeValue: range.value,
            currentInputValue: inputElement ? inputElement.value : 'N/A'
        });

        if (initialValue !== null && initialValue !== '') {
            const numValue = parseFloat(initialValue);
            range.value = numValue;
            if (inputElement) {
                inputElement.value = numValue;
            }

            console.log(`✓ Updated ${range.id} to ${numValue}`);
        }

        // Force update the visual appearance
        updateRangeColor(range);
    });

    console.log('✅ All sliders updated');
}

// Initialize range colors on page load
document.addEventListener('DOMContentLoaded', function() {
    // Force update all range sliders immediately
    forceUpdateAllSliders();

    // Search functionality
    const searchInput = document.getElementById('searchStudent');
    const studentList = document.getElementById('studentList');

    if (searchInput && studentList) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const studentItems = studentList.querySelectorAll('a');

            studentItems.forEach(function(item) {
                const name = item.querySelector('.text-sm.font-medium').textContent.toLowerCase();
                const nim = item.querySelector('.text-xs.text-gray-500').textContent.toLowerCase();

                if (name.includes(searchTerm) || nim.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }

    // Preserve scroll position
    const savedScrollPosition = sessionStorage.getItem('studentListScrollPosition');
    if (savedScrollPosition && studentList) {
        studentList.scrollTop = parseInt(savedScrollPosition);
    }

    // Save scroll position before navigating
    if (studentList) {
        const studentLinks = studentList.querySelectorAll('a');
        studentLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                sessionStorage.setItem('studentListScrollPosition', studentList.scrollTop);
            });
        });
    }
});

// Force update range sliders after window fully loads (including cache)
window.addEventListener('load', function() {
    // Force update all sliders after everything is loaded
    setTimeout(function() {
        forceUpdateAllSliders();
    }, 100); // Small delay to ensure DOM is fully ready
});

// Handle page show event (fires on back/forward navigation and cache)
window.addEventListener('pageshow', function(event) {
    // Always force update, whether from cache or fresh load
    setTimeout(function() {
        forceUpdateAllSliders();
    }, 50);
});

// Additional safety: Update on visibility change
document.addEventListener('visibilitychange', function() {
    if (!document.hidden) {
        setTimeout(function() {
            forceUpdateAllSliders();
        }, 50);
    }
});
</script>
@endsection
