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
                <p class="text-gray-600 mt-1">Penilaian seluruh mahasiswa berdasarkan rubrik yang telah ditetapkan</p>
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
                        <h3 class="text-base font-semibold text-gray-900">
                            @if($selectedDospem)
                                Mahasiswa Bimbingan
                            @elseif($lecturers->isNotEmpty())
                                Pilih Dosen Pembimbing
                            @else
                                Pilih Mahasiswa
                            @endif
                        </h3>
                        @if($selectedDospem)
                            <p class="text-xs text-gray-500 mt-1">{{ $selectedDospem->name }}</p>
                        @else
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $students->count() }} mahasiswa total
                            </p>
                        @endif
                    </div>

                    @if($lecturers->isNotEmpty())
                        {{-- Lecturer List View --}}
                        <div class="divide-y divide-gray-100">
                            @foreach($lecturers as $lecturer)
                                <a href="{{ route('admin.rubrik.index', ['sort' => 'dospem_status', 'dospem_id' => $lecturer->id]) }}"
                                   class="block p-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center">
                                                <i class="fas fa-chalkboard-teacher text-purple-600"></i>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="text-sm font-medium text-gray-900 truncate">{{ $lecturer->name }}</div>
                                            <div class="text-xs text-gray-500">
                                                {{ $lecturer->total_mahasiswa }} mahasiswa
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $lecturer->dinilai_count == $lecturer->total_mahasiswa ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ $lecturer->dinilai_count }}/{{ $lecturer->total_mahasiswa }}
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @elseif($students->count() > 0)
                        {{-- Student List View --}}
                        @if($selectedDospem)
                        <div class="px-4 py-3 border-b border-gray-100">
                            <a href="{{ route('admin.rubrik.index', ['sort' => 'dospem_status']) }}" class="text-sm text-blue-600 hover:underline">
                                <i class="fas fa-arrow-left mr-1"></i> Kembali ke daftar dosen
                            </a>
                        </div>
                        @endif

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
                                @php
                                    // Build query without 'm' parameter first, then add the new student id
                                    $queryParams = request()->query->all();
                                    unset($queryParams['m']); // Remove existing 'm' parameter
                                    $queryParams['m'] = $student->id; // Add new student id
                                @endphp
                                <a href="{{ route('admin.rubrik.index', $queryParams) }}"
                                   class="block p-4 hover:bg-gray-50 transition-colors student-link {{ $selectedStudent && $selectedStudent->id == $student->id ? 'bg-blue-50 border-l-4 border-blue-500' : '' }}"
                                   data-student-id="{{ $student->id }}">
                                    <div class="flex items-center space-x-3">
                                        <!-- Profile Photo -->
                                        <div class="flex-shrink-0">
                                            @if($student->photo && $student->google_linked)
                                                <img src="{{ $student->photo }}"
                                                     alt="{{ $student->name }}"
                                                     class="h-10 w-10 rounded-full object-cover"
                                                     onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                <div class="h-10 w-10 rounded-full bg-blue-100 items-center justify-center hidden">
                                                    <i class="fas fa-user text-blue-600"></i>
                                                </div>
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
                                            @if($student->profilMahasiswa && $student->profilMahasiswa->dosenPembimbing)
                                            <div class="text-xs text-blue-600 mt-1 truncate">
                                                <i class="fas fa-user-tie mr-1"></i>{{ $student->profilMahasiswa->dosenPembimbing->name }}
                                            </div>
                                            @endif
                                        </div>

                                        <!-- Assessment Status -->
                                        <div class="flex-shrink-0">
                                            @if($allResults && $allResults->has($student->id))
                                                @php $studentResult = $allResults->get($student->id); @endphp
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
                        <div class="text-center py-8">
                            @if($selectedDospem)
                                <a href="{{ route('admin.rubrik.index', ['sort' => 'dospem_status']) }}" class="text-sm text-blue-600 hover:underline mb-4 block">
                                    &larr; Kembali ke daftar dosen
                                </a>
                            @endif
                            <i class="fas fa-user-graduate text-gray-400 text-4xl mb-4"></i>
                            <p class="text-gray-500">
                                @if($selectedDospem)
                                    Tidak ada mahasiswa bimbingan untuk dosen ini.
                                @else
                                    Belum ada data untuk ditampilkan.
                                @endif
                            </p>
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
                                        @if($selectedStudent->photo && $selectedStudent->google_linked)
                                            <img src="{{ $selectedStudent->photo }}"
                                                 alt="{{ $selectedStudent->name }}"
                                                 class="h-12 w-12 rounded-full object-cover border-2 border-blue-200"
                                                 onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="h-12 w-12 rounded-full bg-blue-100 items-center justify-center hidden">
                                                <i class="fas fa-user text-blue-600"></i>
                                            </div>
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
                            <form action="{{ route('admin.penilaian.store') }}" method="POST" id="penilaianForm" data-student-id="{{ $selectedStudent->id }}">
                                @csrf
                                <input type="hidden" name="mahasiswa_id" value="{{ $selectedStudent->id }}">
                                <input type="hidden" name="_form_token" value="{{ $selectedStudent->id }}_{{ time() }}">

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
                                                           data-input-id="input_{{ $item['id'] }}"
                                                           min="0"
                                                           max="100"
                                                           value="{{ $responses->get($item['id'])->value_numeric ?? 0 }}"
                                                           class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer range-slider">
                                                    <input type="number"
                                                           id="input_{{ $item['id'] }}"
                                                           data-range-id="range_{{ $item['id'] }}"
                                                           name="items[{{ $item['id'] }}]"
                                                           value="{{ $responses->get($item['id'])->value_numeric ?? '' }}"
                                                           min="0"
                                                           max="100"
                                                           step="0.1"
                                                           class="w-20 px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-center number-input"
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
                                    @php
                                        // Build URL without 'm' parameter for cancel button
                                        $cancelParams = request()->query->all();
                                        unset($cancelParams['m']);
                                    @endphp
                                    <a href="{{ route('admin.rubrik.index', $cancelParams) }}"
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
                                <div class="grid grid-cols-3 gap-4">
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
                                    <div class="text-center p-4 bg-purple-50 rounded-lg">
                                        <div class="text-2xl font-bold text-purple-600">
                                            {{ $studentResult->gpa_point ?? 'N/A' }}
                                        </div>
                                        <div class="text-xs text-gray-600 mt-1">Poin</div>
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
    transition: background 0.15s ease;
}

input[type="range"]:focus {
    outline: none;
}

/* Thumb untuk Chrome, Safari, Opera */
input[type="range"]::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #FFFFFF;
    border: 3px solid #3B82F6;
    cursor: pointer;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: all 0.15s ease;
}

input[type="range"]::-webkit-slider-thumb:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
}

input[type="range"]::-webkit-slider-thumb:active {
    transform: scale(0.95);
}

/* Thumb untuk Firefox */
input[type="range"]::-moz-range-thumb {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #FFFFFF;
    border: 3px solid #3B82F6;
    cursor: pointer;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: all 0.15s ease;
}

input[type="range"]::-moz-range-thumb:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
}

input[type="range"]::-moz-range-thumb:active {
    transform: scale(0.95);
}

/* Track untuk Firefox */
input[type="range"]::-moz-range-track {
    height: 8px;
    border-radius: 4px;
}

/* Remove spinner arrows from number input */
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
    opacity: 1;
}

input[type="number"]:focus {
    border-color: #3B82F6;
}
</style>

<script>
function updateRangeColor(rangeInput) {
    const value = rangeInput.value;
    const percentage = (value / 100) * 100;
    rangeInput.style.background = `linear-gradient(to right, #3B82F6 0%, #3B82F6 ${percentage}%, #E5E7EB ${percentage}%, #E5E7EB 100%)`;
}

// Detect if page was loaded from cache (back/forward)
window.addEventListener('pageshow', function(event) {
    if (event.persisted) {
        // Page was loaded from cache, force reload
        window.location.reload();
    }
});

// Initialize range colors on page load
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all range sliders
    document.querySelectorAll('.range-slider').forEach(function(rangeInput) {
        updateRangeColor(rangeInput);

        // Range slider event - update number input
        rangeInput.addEventListener('input', function() {
            const inputId = this.getAttribute('data-input-id');
            const numberInput = document.getElementById(inputId);
            if (numberInput) {
                numberInput.value = this.value;
            }
            updateRangeColor(this);
        });
    });

    // Initialize all number inputs
    document.querySelectorAll('.number-input').forEach(function(numberInput) {
        // Number input event - update range slider
        numberInput.addEventListener('input', function() {
            let value = parseFloat(this.value);

            // Validate and constrain value
            if (isNaN(value)) {
                value = 0;
            } else if (value < 0) {
                value = 0;
                this.value = 0;
            } else if (value > 100) {
                value = 100;
                this.value = 100;
            }

            const rangeId = this.getAttribute('data-range-id');
            const rangeInput = document.getElementById(rangeId);
            if (rangeInput) {
                rangeInput.value = value;
                updateRangeColor(rangeInput);
            }
        });

        // Handle blur event to ensure valid value
        numberInput.addEventListener('blur', function() {
            if (this.value === '' || isNaN(parseFloat(this.value))) {
                this.value = 0;
                const rangeId = this.getAttribute('data-range-id');
                const rangeInput = document.getElementById(rangeId);
                if (rangeInput) {
                    rangeInput.value = 0;
                    updateRangeColor(rangeInput);
                }
            }
        });
    });

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

    // Preserve scroll position (only if not selecting a new student)
    const urlParams = new URLSearchParams(window.location.search);
    const currentStudentId = urlParams.get('m');

    if (studentList) {
        // Restore scroll position only on initial load
        const savedScrollPosition = sessionStorage.getItem('studentListScrollPosition');
        const savedStudentId = sessionStorage.getItem('lastSelectedStudentId');

        if (savedScrollPosition && savedStudentId === currentStudentId) {
            studentList.scrollTop = parseInt(savedScrollPosition);
        }

        // Save scroll position and student ID before navigating
        const studentLinks = studentList.querySelectorAll('a.student-link');
        studentLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                const newStudentId = this.getAttribute('data-student-id');

                // Check if selecting a different student
                if (newStudentId && newStudentId !== currentStudentId && currentStudentId) {
                    // Check if form has been modified
                    const form = document.getElementById('penilaianForm');
                    if (form) {
                        const formInputs = form.querySelectorAll('input[type="number"], input[type="radio"]:checked, textarea');
                        let hasChanges = false;

                        formInputs.forEach(function(input) {
                            if (input.type === 'number' && input.value && parseFloat(input.value) > 0) {
                                hasChanges = true;
                            } else if (input.type === 'radio' && input.checked) {
                                hasChanges = true;
                            } else if (input.tagName === 'TEXTAREA' && input.value.trim()) {
                                hasChanges = true;
                            }
                        });

                        // Confirm if there are unsaved changes
                        if (hasChanges) {
                            if (!confirm('Anda memiliki perubahan yang belum disimpan. Yakin ingin pindah ke mahasiswa lain?')) {
                                e.preventDefault();
                                return false;
                            }
                        }
                    }

                    // Show loading indicator
                    const loadingOverlay = document.createElement('div');
                    loadingOverlay.id = 'loading-overlay';
                    loadingOverlay.className = 'fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50';
                    loadingOverlay.innerHTML = `
                        <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
                            <svg class="animate-spin h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="text-gray-700">Memuat data mahasiswa...</span>
                        </div>
                    `;
                    document.body.appendChild(loadingOverlay);
                }

                sessionStorage.setItem('studentListScrollPosition', studentList.scrollTop);
                sessionStorage.setItem('lastSelectedStudentId', newStudentId);
            });
        });
    }

    @if(session('success'))
    // Show success message with animation
    const successDiv = document.createElement('div');
    successDiv.className = 'fixed top-4 right-4 bg-green-100 border-2 border-green-400 text-green-700 px-6 py-4 rounded-lg shadow-lg z-50 transform transition-all duration-300 ease-in-out';
    successDiv.style.transform = 'translateX(400px)';
    successDiv.innerHTML = `
        <div class="flex items-center space-x-3">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="font-semibold">{{ session("success") }}</p>
                <p class="text-sm text-green-600 mt-1">Data telah diperbarui</p>
            </div>
        </div>
    `;
    document.body.appendChild(successDiv);

    // Animate in
    setTimeout(() => {
        successDiv.style.transform = 'translateX(0)';
    }, 10);

    // Remove after 5 seconds with animation
    setTimeout(() => {
        successDiv.style.transform = 'translateX(400px)';
        setTimeout(() => {
            successDiv.remove();
        }, 300);
    }, 5000);

    // Highlight the updated student in sidebar
    const currentStudent = document.querySelector('.student-link.bg-blue-50');
    if (currentStudent) {
        currentStudent.classList.add('ring-2', 'ring-green-500');
        setTimeout(() => {
            currentStudent.classList.remove('ring-2', 'ring-green-500');
        }, 3000);
    }
    @endif
});
</script>
@endsection
