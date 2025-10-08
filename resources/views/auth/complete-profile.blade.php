<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lengkapi Biodata - SIPP PKL</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    <div class="flex-1 flex items-center justify-center py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <div class="mx-auto h-12 w-12 flex items-center justify-center">
                    <i class="fas fa-user-edit text-3xl text-indigo-600"></i>
                </div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Lengkapi Biodata
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Lengkapi data diri untuk menyelesaikan pendaftaran
                </p>
            </div>
            
            <form class="mt-8 space-y-6" method="POST" action="{{ route('complete-profile') }}">
                @csrf
                
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input type="text" id="name" name="name" required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                               value="{{ old('name', auth()->user()->name ?? '') }}">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="nim" class="block text-sm font-medium text-gray-700">NIM</label>
                        <input type="text" id="nim" name="nim" required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                               value="{{ old('nim') }}">
                        @error('nim')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="prodi" class="block text-sm font-medium text-gray-700">Program Studi</label>
                        <select id="prodi" name="prodi" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Pilih Program Studi</option>
                            <option value="D3 Agroindustri" {{ old('prodi') == 'D3 Agroindustri' ? 'selected' : '' }}>D3 Agroindustri</option>
                            <option value="D3 Akuntansi" {{ old('prodi') == 'D3 Akuntansi' ? 'selected' : '' }}>D3 Akuntansi</option>
                            <option value="D3 Teknologi Informasi" {{ old('prodi') == 'D3 Teknologi Informasi' ? 'selected' : '' }}>D3 Teknologi Informasi</option>
                            <option value="D3 Teknologi Otomotif" {{ old('prodi') == 'D3 Teknologi Otomotif' ? 'selected' : '' }}>D3 Teknologi Otomotif</option>
                            <option value="D4 Teknologi Rekayasa Komputer Jaringan" {{ old('prodi') == 'D4 Teknologi Rekayasa Komputer Jaringan' ? 'selected' : '' }}>D4 Teknologi Rekayasa Komputer Jaringan</option>
                            <option value="D4 Teknologi Pakan Ternak" {{ old('prodi') == 'D4 Teknologi Pakan Ternak' ? 'selected' : '' }}>D4 Teknologi Pakan Ternak</option>
                            <option value="D4 Teknologi Rekayasa Konstruksi Jalan dan Jembatan" {{ old('prodi') == 'D4 Teknologi Rekayasa Konstruksi Jalan dan Jembatan' ? 'selected' : '' }}>D4 Teknologi Rekayasa Konstruksi Jalan dan Jembatan</option>
                            <option value="D4 Teknologi Rekayasa Pemeliharaan Alat Berat" {{ old('prodi') == 'D4 Teknologi Rekayasa Pemeliharaan Alat Berat' ? 'selected' : '' }}>D4 Teknologi Rekayasa Pemeliharaan Alat Berat</option>
                        </select>
                        @error('prodi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="semester" class="block text-sm font-medium text-gray-700">Semester</label>
                        <select id="semester" name="semester" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">-- Pilih Semester --</option>
                            @for($i = 1; $i <= 14; $i++)
                                <option value="{{ $i }}" {{ old('semester', 5) == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                        @error('semester')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                        <select id="jenis_kelamin" name="jenis_kelamin" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="no_whatsapp" class="block text-sm font-medium text-gray-700">Nomor WhatsApp <span class="text-red-500">*</span></label>
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                +62
                            </span>
                            <input type="text" id="no_whatsapp" name="no_whatsapp" required
                                   class="flex-1 min-w-0 block w-full px-3 py-2 border border-gray-300 rounded-r-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="8xxxxxxxxxx"
                                   value="{{ old('no_whatsapp') }}"
                                   pattern="^8[0-9]{10,13}$"
                                   title="Nomor WhatsApp harus dimulai dengan 8 dan minimal 11 digit">
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Format: +628xxxxxxxxxx (minimal 11 digit)</p>
                        @error('no_whatsapp')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="ipk" class="block text-sm font-medium text-gray-700">IPK Terakhir <span class="text-red-500">*</span></label>
                        <input type="number" id="ipk" name="ipk" required step="0.01" min="0" max="4.0"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                               placeholder="3.50"
                               value="{{ old('ipk') }}">
                        @error('ipk')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="id_dospem" class="block text-sm font-medium text-gray-700">Dosen Pembimbing</label>
                        <select id="id_dospem" name="id_dospem" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">-- Pilih Dosen Pembimbing --</option>
                            @foreach($dosenPembimbingList as $dosen)
                                <option value="{{ $dosen->id }}" {{ old('id_dospem') == $dosen->id ? 'selected' : '' }}>
                                    {{ $dosen->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_dospem')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="flex space-x-4">
                    <button type="button" 
                            class="flex-1 flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 text-sm font-medium"
                            onclick="cancelRegistration()">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </button>
                    <button type="submit" 
                            class="flex-1 flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            onclick="return validateForm()">
                        <i class="fas fa-check mr-2"></i>
                        Selesai
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-8">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="text-sm text-gray-500">
                    © {{ date('Y') }} SIPP PKL. All rights reserved.
                </p>
                <div class="flex space-x-4 mt-2 md:mt-0">
                    <a href="{{ route('faq') }}" class="text-sm text-gray-500 hover:text-indigo-600 transition-colors">
                        <i class="fas fa-question-circle mr-1"></i>
                        FAQ
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        function validateForm() {
            // Get all required fields
            const requiredFields = [
                { id: 'name', name: 'Nama Lengkap' },
                { id: 'nim', name: 'NIM' },
                { id: 'prodi', name: 'Program Studi' },
                { id: 'semester', name: 'Semester' },
                { id: 'jenis_kelamin', name: 'Jenis Kelamin' },
                { id: 'no_whatsapp', name: 'Nomor WhatsApp' },
                { id: 'ipk', name: 'IPK Terakhir' },
                { id: 'id_dospem', name: 'Dosen Pembimbing' }
            ];

            let missingFields = [];

            // Check each required field
            requiredFields.forEach(field => {
                const element = document.getElementById(field.id);
                if (!element.value.trim()) {
                    missingFields.push(field.name);
                }
            });

            // Validate WhatsApp format
            const whatsapp = document.getElementById('no_whatsapp').value.trim();
            if (whatsapp && !/^8[0-9]{10,13}$/.test(whatsapp)) {
                missingFields.push('Nomor WhatsApp (format tidak valid)');
            }

            // Validate IPK range
            const ipk = document.getElementById('ipk').value;
            if (ipk && (parseFloat(ipk) < 0 || parseFloat(ipk) > 4.0)) {
                missingFields.push('IPK Terakhir (harus antara 0.00 - 4.00)');
            }

            // Show error if there are missing fields
            if (missingFields.length > 0) {
                alert('Mohon lengkapi field berikut:\n\n' + missingFields.join('\n'));
                return false;
            }

            return true;
        }

        function cancelRegistration() {
            if (confirm('Apakah Anda yakin ingin membatalkan registrasi? Akun Google akan dihapus.')) {
                // Create a form to submit the cancellation
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("cancel-registration") }}';
                
                // Add CSRF token
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);
                
                // Submit the form
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Enter key handler for field navigation
        document.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const activeElement = document.activeElement;
                
                // Field navigation order
                const fieldOrder = ['name', 'nim', 'prodi', 'semester', 'jenis_kelamin', 'no_whatsapp', 'ipk', 'id_dospem'];
                const currentIndex = fieldOrder.indexOf(activeElement.id);
                
                if (currentIndex !== -1) {
                    e.preventDefault();
                    
                    // If on last field (id_dospem), validate and submit
                    if (currentIndex === fieldOrder.length - 1) {
                        if (validateForm()) {
                            document.querySelector('form').submit();
                        }
                    } else {
                        // Move to next field
                        const nextField = document.getElementById(fieldOrder[currentIndex + 1]);
                        if (nextField) {
                            nextField.focus();
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
