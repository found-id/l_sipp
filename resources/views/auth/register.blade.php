<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SIPP PKL</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        #step2Buttons:not(.hidden) {
            display: flex !important;
            flex-direction: row !important;
            gap: 1rem;
        }
        #step2Buttons:not(.hidden) button {
            flex: 1;
        }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">

    <div class="flex-1 flex items-center justify-center py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <div class="mx-auto h-12 w-12 flex items-center justify-center">
                    <i class="fas fa-user-plus text-3xl text-indigo-600"></i>
                </div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Daftar Akun Baru
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Buat akun untuk mengakses sistem
                </p>
            </div>
            
            <form class="mt-8 space-y-6" method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf
                
                <!-- Step 1: Informasi Akun -->
                <div id="step1" class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input id="name" name="name" type="text" required 
                               class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" 
                               placeholder="Masukkan nama lengkap"
                               value="{{ old('name') }}">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input id="email" name="email" type="email" required 
                               class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" 
                               placeholder="Masukkan email"
                               value="{{ old('email') }}">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input id="password" name="password" type="password" required 
                               class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" 
                               placeholder="Masukkan password">
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required 
                               class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" 
                               placeholder="Konfirmasi password">
                    </div>
                </div>

                <!-- Step 2: Biodata Mahasiswa (Hidden initially) -->
                <div id="step2" class="space-y-4" style="display: none;">
                    <div>
                        <label for="nim" class="block text-sm font-medium text-gray-700">NIM</label>
                        <input id="nim" name="nim" type="text" required 
                               class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" 
                               placeholder="Masukkan NIM"
                               value="{{ old('nim') }}">
                        @error('nim')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="prodi" class="block text-sm font-medium text-gray-700">Program Studi</label>
                        <select id="prodi" name="prodi" required 
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
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
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="semester" class="block text-sm font-medium text-gray-700">Semester</label>
                        <select id="semester" name="semester" required 
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Pilih Semester</option>
                            @for($i = 1; $i <= 8; $i++)
                                <option value="{{ $i }}" {{ old('semester') == $i ? 'selected' : '' }}>Semester {{ $i }}</option>
                            @endfor
                        </select>
                        @error('semester')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                        <select id="jenis_kelamin" name="jenis_kelamin" required 
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="no_wa" class="block text-sm font-medium text-gray-700">Nomor WhatsApp</label>
                        <div class="mt-1 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-700 font-medium">+62</span>
                            </div>
                            <input id="no_wa" name="no_wa" type="text" required 
                                   class="block w-full pl-12 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" 
                                   placeholder="8xxxxxxxxxx"
                                   value="{{ old('no_wa') }}"
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                   maxlength="13">
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Format: +62 8xxxxxxxxxx (tanpa 0 di depan, maksimal 13 digit)</p>
                        @error('no_wa')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="ipk" class="block text-sm font-medium text-gray-700">IPK Terakhir</label>
                        <input id="ipk" name="ipk" type="number" required step="0.01" min="0" max="4.0"
                               class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" 
                               placeholder="3.50"
                               value="{{ old('ipk') }}">
                        @error('ipk')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="id_dospem" class="block text-sm font-medium text-gray-700">Dosen Pembimbing</label>
                        <select id="id_dospem" name="id_dospem" required 
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Pilih Dosen Pembimbing</option>
                            @foreach($dosenPembimbingList as $dosen)
                                <option value="{{ $dosen->id }}" {{ old('id_dospem') == $dosen->id ? 'selected' : '' }}>
                                    {{ $dosen->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_dospem')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <input type="hidden" name="role" value="mahasiswa">

                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div>
                    <button type="button" id="nextBtn" 
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <i class="fas fa-user-plus text-indigo-500 group-hover:text-indigo-400"></i>
                        </span>
                        Daftar
                    </button>
                    
                    <div id="step2Buttons" class="hidden flex flex-row space-x-4">
                        <button type="button" id="backBtn" 
                                class="flex-1 flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 text-sm font-medium">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali
                        </button>
                        
                        <button type="submit" id="submitBtn"
                                class="flex-1 flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-check mr-2"></i>
                            Daftar Sekarang
                        </button>
                    </div>
                </div>

                <div class="text-center">
                    <p class="text-sm text-gray-600">
                        Sudah punya akun? 
                        <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                            Login di sini
                        </a>
                    </p>
                </div>
                
                <!-- Divider -->
                <div class="mt-6">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-3 bg-gray-50 text-gray-500 font-medium">Atau</span>
                        </div>
                    </div>
                </div>
                
                <!-- Google OAuth Button -->
                <div class="mt-6">
                    <a href="{{ route('auth.google') }}" 
                       class="w-full flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        Daftar dengan Google
                    </a>
                </div>
            </form>
        </div>
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
        // Form validation function
        function validateStep1() {
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const passwordConfirmation = document.getElementById('password_confirmation').value;
            
            if (!name || !email || !password || !passwordConfirmation) {
                alert('Semua field harus diisi!');
                return false;
            }
            
            if (password !== passwordConfirmation) {
                alert('Password dan konfirmasi password tidak sama!');
                return false;
            }
            
            if (password.length < 6) {
                alert('Password minimal 6 karakter!');
                return false;
            }
            
            return true;
        }
        
        // Next button click handler
        document.getElementById('nextBtn').addEventListener('click', function() {
            if (validateStep1()) {
                // Hide step 1
                document.getElementById('step1').style.display = 'none';
                // Show step 2
                document.getElementById('step2').style.display = 'block';
                // Hide next button, show step 2 buttons
                document.getElementById('nextBtn').style.display = 'none';
                document.getElementById('step2Buttons').style.display = 'block';
                // Update title
                document.querySelector('h2').textContent = 'Lengkapi Biodata';
                document.querySelector('p').textContent = 'Lengkapi data diri untuk menyelesaikan pendaftaran';
            }
        });
        
        // Back button click handler
        document.getElementById('backBtn').addEventListener('click', function() {
            // Show step 1
            document.getElementById('step1').style.display = 'block';
            // Hide step 2
            document.getElementById('step2').style.display = 'none';
            // Show next button, hide step 2 buttons
            document.getElementById('nextBtn').classList.remove('hidden');
            document.getElementById('step2Buttons').classList.add('hidden');
            // Update title
            document.querySelector('h2').textContent = 'Daftar Akun Baru';
            document.querySelector('p').textContent = 'Buat akun untuk mengakses sistem';
        });
        
        // Enter key handler for field navigation
        document.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const step1 = document.getElementById('step1');
                const step2 = document.getElementById('step2');
                
                // If step 1 is visible, handle field navigation
                if (step1.style.display !== 'none' && step2.style.display === 'none') {
                    const activeElement = document.activeElement;
                    
                    // Field navigation order for step 1
                    const fieldOrder = ['name', 'email', 'password', 'password_confirmation'];
                    const currentIndex = fieldOrder.indexOf(activeElement.id);
                    
                    if (currentIndex !== -1) {
                        e.preventDefault();
                        
                        // If on last field (password_confirmation), validate and proceed
                        if (currentIndex === fieldOrder.length - 1) {
                            if (validateStep1()) {
                                // Hide step 1
                                document.getElementById('step1').style.display = 'none';
                                // Show step 2
                                document.getElementById('step2').style.display = 'block';
                                // Hide next button, show step 2 buttons
                                document.getElementById('nextBtn').classList.add('hidden');
                                document.getElementById('step2Buttons').classList.remove('hidden');
                                // Update title
                                document.querySelector('h2').textContent = 'Lengkapi Biodata';
                                document.querySelector('p').textContent = 'Lengkapi data diri untuk menyelesaikan pendaftaran';
                                // Focus on first field of step 2
                                document.getElementById('nim').focus();
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
                
                // If step 2 is visible, handle field navigation
                if (step1.style.display === 'none' && step2.style.display !== 'none') {
                    const activeElement = document.activeElement;
                    
                    // Field navigation order for step 2
                    const fieldOrder = ['nim', 'prodi', 'semester', 'jenis_kelamin', 'no_wa', 'ipk', 'id_dospem'];
                    const currentIndex = fieldOrder.indexOf(activeElement.id);
                    
                    if (currentIndex !== -1) {
                        e.preventDefault();
                        
                        // If on last field (id_dospem), submit form
                        if (currentIndex === fieldOrder.length - 1) {
                            document.getElementById('registerForm').submit();
                        } else {
                            // Move to next field
                            const nextField = document.getElementById(fieldOrder[currentIndex + 1]);
                            if (nextField) {
                                nextField.focus();
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
