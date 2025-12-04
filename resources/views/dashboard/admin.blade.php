<!-- Admin Dashboard -->
<div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6">
    <!-- Total Mahasiswa -->
    <div class="group bg-white overflow-hidden shadow-md md:shadow-lg rounded-xl md:rounded-2xl transform transition-all duration-300 hover:-translate-y-1 md:hover:-translate-y-2 hover:shadow-xl md:hover:shadow-2xl border border-gray-100">
        <div class="p-3 md:p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-20 md:w-32 h-20 md:h-32 bg-blue-50 rounded-full -mr-10 md:-mr-16 -mt-10 md:-mt-16 opacity-50"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-2 md:mb-4">
                    <div class="w-10 h-10 md:w-14 md:h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg md:rounded-xl flex items-center justify-center shadow-md md:shadow-lg">
                        <i class="fas fa-users text-lg md:text-2xl text-white"></i>
                    </div>
                    <div class="bg-blue-100 text-blue-600 text-[10px] md:text-xs font-bold px-2 md:px-3 py-0.5 md:py-1 rounded-full">Mahasiswa</div>
                </div>
                <dt class="text-xs md:text-sm font-medium text-gray-500 mb-1 md:mb-2">Mahasiswa</dt>
                <dd class="text-xl md:text-3xl font-bold text-gray-900">{{ $stats['total_mahasiswa'] ?? 0 }}</dd>
                <p class="text-[10px] md:text-xs text-gray-400 mt-0.5 md:mt-1 hidden md:block">Mahasiswa Terdaftar</p>
            </div>
        </div>
    </div>

    <!-- Total Dosen -->
    <div class="group bg-white overflow-hidden shadow-md md:shadow-lg rounded-xl md:rounded-2xl transform transition-all duration-300 hover:-translate-y-1 md:hover:-translate-y-2 hover:shadow-xl md:hover:shadow-2xl border border-gray-100">
        <div class="p-3 md:p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-20 md:w-32 h-20 md:h-32 bg-green-50 rounded-full -mr-10 md:-mr-16 -mt-10 md:-mt-16 opacity-50"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-2 md:mb-4">
                    <div class="w-10 h-10 md:w-14 md:h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-lg md:rounded-xl flex items-center justify-center shadow-md md:shadow-lg">
                        <i class="fas fa-chalkboard-teacher text-lg md:text-2xl text-white"></i>
                    </div>
                    <div class="bg-green-100 text-green-600 text-[10px] md:text-xs font-bold px-2 md:px-3 py-0.5 md:py-1 rounded-full">Dospem</div>
                </div>
                <dt class="text-xs md:text-sm font-medium text-gray-500 mb-1 md:mb-2">Dosen</dt>
                <dd class="text-xl md:text-3xl font-bold text-gray-900">{{ $stats['total_dosen'] ?? 0 }}</dd>
                <p class="text-[10px] md:text-xs text-gray-400 mt-0.5 md:mt-1 hidden md:block">Dosen Pembimbing</p>
            </div>
        </div>
    </div>

    <!-- Total Mitra -->
    <div class="group bg-white overflow-hidden shadow-md md:shadow-lg rounded-xl md:rounded-2xl transform transition-all duration-300 hover:-translate-y-1 md:hover:-translate-y-2 hover:shadow-xl md:hover:shadow-2xl border border-gray-100">
        <div class="p-3 md:p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-20 md:w-32 h-20 md:h-32 bg-purple-50 rounded-full -mr-10 md:-mr-16 -mt-10 md:-mt-16 opacity-50"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-2 md:mb-4">
                    <div class="w-10 h-10 md:w-14 md:h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg md:rounded-xl flex items-center justify-center shadow-md md:shadow-lg">
                        <i class="fas fa-building text-lg md:text-2xl text-white"></i>
                    </div>
                    <div class="bg-purple-100 text-purple-600 text-[10px] md:text-xs font-bold px-2 md:px-3 py-0.5 md:py-1 rounded-full">Mitra</div>
                </div>
                <dt class="text-xs md:text-sm font-medium text-gray-500 mb-1 md:mb-2">Mitra</dt>
                <dd class="text-xl md:text-3xl font-bold text-gray-900">{{ $stats['total_mitra'] ?? 0 }}</dd>
                <p class="text-[10px] md:text-xs text-gray-400 mt-0.5 md:mt-1 hidden md:block">Mitra Terdaftar</p>
            </div>
        </div>
    </div>

    <!-- Berkas Pending -->
    <div class="group bg-white overflow-hidden shadow-md md:shadow-lg rounded-xl md:rounded-2xl transform transition-all duration-300 hover:-translate-y-1 md:hover:-translate-y-2 hover:shadow-xl md:hover:shadow-2xl border border-gray-100">
        <div class="p-3 md:p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-20 md:w-32 h-20 md:h-32 bg-yellow-50 rounded-full -mr-10 md:-mr-16 -mt-10 md:-mt-16 opacity-50"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-2 md:mb-4">
                    <div class="w-10 h-10 md:w-14 md:h-14 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg md:rounded-xl flex items-center justify-center shadow-md md:shadow-lg">
                        <i class="fas fa-clock text-lg md:text-2xl text-white"></i>
                    </div>
                    <div class="bg-yellow-100 text-yellow-700 text-[10px] md:text-xs font-bold px-2 md:px-3 py-0.5 md:py-1 rounded-full">Pending</div>
                </div>
                <dt class="text-xs md:text-sm font-medium text-gray-500 mb-1 md:mb-2">Pending</dt>
                <dd class="text-xl md:text-3xl font-bold text-gray-900">{{ $stats['berkas_pending'] ?? 0 }}</dd>
                <p class="text-[10px] md:text-xs text-gray-400 mt-0.5 md:mt-1 hidden md:block">Menunggu Validasi</p>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Overview -->
<div class="mt-8">
    <!-- Berkas Status -->
    <div class="bg-white shadow-lg rounded-2xl mb-6 border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-5 border-b border-gray-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-chart-pie text-white"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Status Berkas</h3>
                    <p class="text-xs text-gray-600">Ringkasan Validasi</p>
                </div>
            </div>
        </div>
        <div class="px-6 py-6">
            @php
                $totalBerkas = ($stats['berkas_pending'] ?? 0) + ($stats['berkas_tervalidasi'] ?? 0) + ($stats['berkas_belum_valid'] ?? 0);
                $tervalidasiPercent = $totalBerkas > 0 ? round((($stats['berkas_tervalidasi'] ?? 0) / $totalBerkas) * 100) : 0;
                $pendingPercent = $totalBerkas > 0 ? round((($stats['berkas_pending'] ?? 0) / $totalBerkas) * 100) : 0;
                $belumValidPercent = $totalBerkas > 0 ? round((($stats['berkas_belum_valid'] ?? 0) / $totalBerkas) * 100) : 0;
            @endphp
            <div class="space-y-5">
                <div class="group hover:bg-green-50 p-3 rounded-xl transition-colors">
                    <div class="flex justify-between items-center mb-2">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                            <span class="text-sm font-medium text-gray-700">Tervalidasi</span>
                        </div>
                        <span class="text-sm font-bold text-green-600 bg-green-100 px-3 py-1 rounded-full">{{ $tervalidasiPercent }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                        <div class="bg-gradient-to-r from-green-500 to-green-600 h-3 rounded-full transition-all duration-500 shadow-sm" style="width: {{ $tervalidasiPercent }}%"></div>
                    </div>
                </div>

                <div class="group hover:bg-yellow-50 p-3 rounded-xl transition-colors">
                    <div class="flex justify-between items-center mb-2">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></div>
                            <span class="text-sm font-medium text-gray-700">Menunggu Validasi</span>
                        </div>
                        <span class="text-sm font-bold text-yellow-600 bg-yellow-100 px-3 py-1 rounded-full">{{ $pendingPercent }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 h-3 rounded-full transition-all duration-500 shadow-sm" style="width: {{ $pendingPercent }}%"></div>
                    </div>
                </div>

                <div class="group hover:bg-red-50 p-3 rounded-xl transition-colors">
                    <div class="flex justify-between items-center mb-2">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                            <span class="text-sm font-medium text-gray-700">Belum Valid</span>
                        </div>
                        <span class="text-sm font-bold text-red-600 bg-red-100 px-3 py-1 rounded-full">{{ $belumValidPercent }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                        <div class="bg-gradient-to-r from-red-500 to-red-600 h-3 rounded-full transition-all duration-500 shadow-sm" style="width: {{ $belumValidPercent }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <!-- Unassigned Students -->
    @if(isset($stats['unassigned_students']) && $stats['unassigned_students']->count() > 0)
    <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-6 mb-6 shadow-md">
        <div class="flex items-center justify-between mb-5">
            <div class="flex items-center">
                <div class="bg-blue-500 rounded-full p-3 mr-3">
                    <i class="fas fa-user-plus text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Mahasiswa Belum Punya Dospem</h3>
                    <p class="text-sm text-gray-600">{{ $stats['unassigned_students']->count() }} Perlu Dosen Pembimbing</p>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            @foreach($stats['unassigned_students'] as $student)
            <div class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow border border-gray-200 overflow-hidden">
                <div class="p-5">
                    <div class="flex items-center space-x-4">
                        <!-- Profile Photo -->
                        <div class="flex-shrink-0">
                            @if($student->photo && $student->google_linked)
                                <img src="{{ $student->photo }}"
                                     alt="{{ $student->name }}"
                                     class="h-14 w-14 rounded-full object-cover ring-2 ring-blue-400"
                                     onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="h-14 w-14 rounded-full bg-blue-500 items-center justify-center hidden ring-2 ring-blue-400">
                                    <i class="fas fa-user text-white text-xl"></i>
                                </div>
                            @else
                                <div class="h-14 w-14 rounded-full bg-blue-500 flex items-center justify-center ring-2 ring-blue-400">
                                    <i class="fas fa-user text-white text-xl"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Student Info -->
                        <div class="flex-1 min-w-0">
                            <h4 class="text-base font-semibold text-gray-900 truncate">{{ $student->name }}</h4>
                            <div class="flex items-center mt-1 space-x-3 text-sm text-gray-600">
                                <span class="flex items-center">
                                    <i class="fas fa-id-card mr-1 text-gray-400"></i>
                                    {{ $student->profilMahasiswa->nim ?? 'Belum diisi' }}
                                </span>
                                <span class="flex items-center">
                                    <i class="fas fa-envelope mr-1 text-gray-400"></i>
                                    {{ Str::limit($student->email, 20) }}
                                </span>
                            </div>
                            @if($student->profilMahasiswa && $student->profilMahasiswa->prodi)
                            <div class="mt-1">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-graduation-cap mr-1"></i>
                                    {{ $student->profilMahasiswa->prodi }}
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Assign Dospem Form -->
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <div class="flex items-center space-x-2">
                            <select id="dospem-select-{{ $student->id }}"
                                    class="flex-1 text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Pilih Dosen Pembimbing --</option>
                                @foreach(\App\Models\User::where('role', 'dospem')->orderBy('name')->get() as $dospem)
                                    <option value="{{ $dospem->id }}">{{ $dospem->name }}</option>
                                @endforeach
                            </select>
                            <button onclick="assignDospem({{ $student->id }}, document.getElementById('dospem-select-{{ $student->id }}').value)"
                                    class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-check-circle mr-1"></i>
                                Tetapkan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="bg-white shadow-lg rounded-2xl border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-5 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-history text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Aktivitas Terbaru</h3>
                        <p class="text-xs text-gray-600">Log Aktivitas Sistem</p>
                    </div>
                </div>
                <a href="{{ route('activity') }}" class="text-sm text-blue-600 hover:text-blue-800 font-semibold hover:bg-blue-50 px-4 py-2 rounded-lg transition-colors flex items-center">
                    Lihat Semua
                    <i class="fas fa-arrow-right ml-2 text-xs"></i>
                </a>
            </div>
        </div>
        <div class="px-6 py-4">
            <div class="mt-5">
                @if(isset($stats['recent_activities']) && $stats['recent_activities']->count() > 0)
                    <div class="space-y-4">
                        @foreach($stats['recent_activities'] as $activity)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-start justify-between">
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        @if($activity->tipe === 'upload_dokumen')
                                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-upload text-blue-600 text-sm"></i>
                                            </div>
                                        @elseif($activity->tipe === 'validasi_dokumen')
                                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-check text-green-600 text-sm"></i>
                                            </div>
                                        @elseif($activity->tipe === 'login')
                                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-sign-in-alt text-purple-600 text-sm"></i>
                                            </div>
                                        @elseif($activity->tipe === 'logout')
                                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-sign-out-alt text-red-600 text-sm"></i>
                                            </div>
                                        @else
                                            <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-info-circle text-gray-600 text-sm"></i>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900">
                                            @if(is_array($activity->pesan))
                                                @if($activity->pesan['action'] === 'upload_dokumen')
                                                    {{ $activity->pesan['mahasiswa'] }} mengunggah berkas {{ $activity->pesan['document_type'] }}
                                                @elseif($activity->pesan['action'] === 'validasi_dokumen')
                                                    {{ $activity->user->name }} memvalidasi dokumen {{ $activity->pesan['document_type'] }} milik {{ $activity->pesan['mahasiswa'] }}
                                                @elseif($activity->pesan['action'] === 'login')
                                                    {{ $activity->pesan['user'] }} ({{ ucfirst($activity->pesan['role']) }}) melakukan login
                                                @elseif($activity->pesan['action'] === 'logout')
                                                    {{ $activity->pesan['user'] }} ({{ ucfirst($activity->pesan['role']) }}) melakukan logout
                                                @elseif($activity->pesan['action'] === 'register_google')
                                                    {{ $activity->pesan['user'] }} telah registrasi via Google sebagai {{ ucfirst($activity->pesan['role']) }}
                                                @elseif($activity->pesan['action'] === 'save_gdrive_links')
                                                    {{ $activity->pesan['mahasiswa'] }} menyimpan link Google Drive
                                                @elseif($activity->pesan['action'] === 'save_transcript_data')
                                                    {{ $activity->pesan['mahasiswa'] }} menyimpan data transkrip semester {{ $activity->pesan['semester'] }}
                                                @else
                                                    {{ $activity->pesan['message'] ?? 'Aktivitas sistem: ' . $activity->pesan['action'] }}
                                                @endif
                                            @else
                                                {{ $activity->pesan }}
                                            @endif
                                        </p>
                                        
                                        @if($activity->pesan['file_name'] ?? false)
                                            <p class="text-sm text-gray-500 mt-1">
                                                <i class="fas fa-file mr-1"></i>{{ $activity->pesan['file_name'] }}
                                            </p>
                                        @endif
                                    </div>
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
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <p class="text-sm text-gray-500">Belum ada aktivitas terbaru</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function assignDospem(studentId, dospemId) {
    if (!dospemId) {
        alert('Pilih dospem terlebih dahulu!');
        return;
    }
    
    if (confirm('Yakin ingin menetapkan dospem untuk mahasiswa ini?')) {
        fetch(`/admin/assign-dospem`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                student_id: studentId,
                dospem_id: dospemId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Gagal menetapkan dospem: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menetapkan dospem');
        });
    }
}
</script>
