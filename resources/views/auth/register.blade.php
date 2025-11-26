<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SIP PKL</title>

    <!-- Favicons -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .auth-image-container {
            background: url('{{ asset('images/auth/bg_login.jpg') }}') center/cover no-repeat;
            background-color: #667eea;
        }
    </style>
</head>
<body class="bg-gray-50 h-screen overflow-hidden">
    <div class="flex h-screen">
        <!-- Left Side - Image -->
        <div class="hidden lg:flex lg:w-1/2 auth-image-container items-center justify-center relative">
        </div>

        <!-- Right Side - Register Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 overflow-y-auto">
            <div class="max-w-md w-full space-y-8">
            <div class="mt-20">
                <div class="mx-auto h-16 w-16 flex items-center justify-center">
                    <i class="fas fa-user-plus text-4xl text-indigo-600"></i>
                </div>
                <h2 class="mt-8 text-center text-3xl font-extrabold text-gray-900">
                    Daftar Akun Baru
                </h2>
                <p class="mt-3 text-center text-sm text-gray-600">
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
                               pattern="^[^0-9]+$"
                               title="Nama tidak boleh mengandung angka"
                               oninput="this.value = this.value.replace(/[0-9]/g, '')"
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
                        <div class="relative">
                            <input id="password" name="password" type="password" required
                                   class="mt-1 appearance-none relative block w-full px-3 py-2 pr-10 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                   placeholder="Masukkan password">
                            <button type="button" onclick="togglePassword('password', 'togglePasswordIcon')"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none z-10">
                                <i id="togglePasswordIcon" class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                        <div class="relative">
                            <input id="password_confirmation" name="password_confirmation" type="password" required
                                   class="mt-1 appearance-none relative block w-full px-3 py-2 pr-10 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                   placeholder="Konfirmasi password">
                            <button type="button" onclick="togglePassword('password_confirmation', 'togglePasswordConfirmIcon')"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none z-10">
                                <i id="togglePasswordConfirmIcon" class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Biodata Mahasiswa (Hidden initially) -->
                <div id="step2" class="space-y-4" style="display: none;">
                    <div>
                        <label for="nim" class="block text-sm font-medium text-gray-700">NIM</label>
                        <input id="nim" name="nim" type="text" required 
                               class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" 
                               placeholder="Masukkan NIM"
                               inputmode="numeric" pattern="\d+" title="NIM harus angka"
                               oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                               maxlength="20"
                               value="{{ old('nim') }}">
                        @error('nim')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="semester" class="block text-sm font-medium text-gray-700">Semester</label>
                        <select id="semester" name="semester" required 
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Pilih Semester</option>
                            @for($i = 5; $i <= 8; $i++)
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
                                   inputmode="numeric"
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
                        <input id="ipk" name="ipk" type="text" required
                               class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                               placeholder="3,50 atau 3.50"
                               oninput="validateIPK(this)"
                               value="{{ old('ipk') }}">
                        <p class="mt-1 text-xs text-gray-500">IPK harus antara 0,00 - 4,00 (bisa menggunakan koma atau titik)</p>
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

                <!-- Loading Overlay -->
                <div id="loadingOverlay" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-white rounded-lg p-8 flex flex-col items-center shadow-xl">
                        <div class="relative">
                            <div class="animate-spin rounded-full h-16 w-16 border-4 border-gray-200 border-t-indigo-600"></div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="w-8 h-8 bg-indigo-600 rounded-full animate-pulse"></div>
                            </div>
                        </div>
                        <p class="mt-4 text-gray-700 font-medium">Memproses...</p>
                        <p class="mt-2 text-sm text-gray-500">Mohon tunggu sebentar</p>
                    </div>
                </div>

                <div>
                    <button type="button" id="nextBtn" 
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <i class="fas fa-user-plus text-indigo-500 group-hover:text-indigo-400" id="next-icon"></i>
                        </span>
                        <span id="next-text">Daftar</span>
                        <div id="next-loading" class="hidden">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Memproses...</span>
                        </div>
                    </button>
                    
                    <div id="step2Buttons" class="hidden space-y-4">
                        <button type="submit" id="submitBtn"
                                class="w-full flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                            <i class="fas fa-check mr-2"></i>
                            Daftar Sekarang
                        </button>

                        <button type="button" id="backBtn"
                                class="w-full flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali
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
                    <a href="{{ route('auth.google') }}" id="google-register-btn"
                       class="w-full flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" id="google-register-icon" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        <span id="google-register-text">Daftar dengan Google</span>
                        <div id="google-register-loading" class="hidden flex items-center">
                            <div class="relative mr-3">
                                <div class="animate-spin rounded-full h-4 w-4 border-2 border-gray-300 border-t-indigo-600"></div>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="w-2 h-2 bg-indigo-600 rounded-full animate-pulse"></div>
                                </div>
                            </div>
                            <span class="text-gray-600">Memproses...</span>
                        </div>
                    </a>
                </div>
            </form>

            <!-- Footer -->
            <footer class="mt-8 text-center">
                <p class="text-sm text-gray-500">
                    © {{ date('Y') }} SIP PKL. All rights reserved.
                </p>
                <div class="mt-2">
                    <a href="{{ route('faq') }}" class="text-sm text-gray-500 hover:text-indigo-600 transition-colors">
                        <i class="fas fa-question-circle mr-1"></i>
                        FAQ
                    </a>
                </div>
            </footer>
        </div>
        </div>
    </div>

    <script>
        // Validate IPK input (max 4.0) - accepts both comma and dot
        function validateIPK(input) {
            // Allow only numbers, comma, and dot
            input.value = input.value.replace(/[^0-9.,]/g, '');

            // Replace multiple commas/dots with single one
            input.value = input.value.replace(/[.,]+/g, function(match) {
                return match[0];
            });

            // Prevent multiple decimal separators
            const separatorCount = (input.value.match(/[.,]/g) || []).length;
            if (separatorCount > 1) {
                // Keep only the first separator
                let firstSeparatorFound = false;
                input.value = input.value.split('').filter(char => {
                    if (char === ',' || char === '.') {
                        if (firstSeparatorFound) return false;
                        firstSeparatorFound = true;
                    }
                    return true;
                }).join('');
            }

            // Convert comma to dot for validation
            let valueForValidation = input.value.replace(',', '.');
            let value = parseFloat(valueForValidation);

            // Check if value exceeds 4.0
            if (value > 4.0) {
                input.value = input.value.includes(',') ? '4,0' : '4.0';
                alert('IPK maksimal adalah 4,00');
            }

            // Check if value is negative
            if (value < 0) {
                input.value = '0';
                alert('IPK tidak boleh negatif');
            }

            // Limit to 2 decimal places
            if (input.value.includes(',') || input.value.includes('.')) {
                const separator = input.value.includes(',') ? ',' : '.';
                const parts = input.value.split(separator);
                if (parts[1] && parts[1].length > 2) {
                    input.value = parts[0] + separator + parts[1].substring(0, 2);
                }
            }
        }

        // Toggle password visibility
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Loading state functions
        function showNextLoading() {
            document.getElementById('next-icon').classList.add('hidden');
            document.getElementById('next-text').classList.add('hidden');
            document.getElementById('next-loading').classList.remove('hidden');
            document.getElementById('nextBtn').classList.add('opacity-75', 'cursor-not-allowed');
        }
        
        function hideNextLoading() {
            document.getElementById('next-icon').classList.remove('hidden');
            document.getElementById('next-text').classList.remove('hidden');
            document.getElementById('next-loading').classList.add('hidden');
            document.getElementById('nextBtn').classList.remove('opacity-75', 'cursor-not-allowed');
        }
        
        function showLoadingOverlay() {
            document.getElementById('loadingOverlay').classList.remove('hidden');
        }

        function hideLoadingOverlay() {
            document.getElementById('loadingOverlay').classList.add('hidden');
        }
        
        function showGoogleRegisterLoading() {
            document.getElementById('google-register-icon').classList.add('hidden');
            document.getElementById('google-register-text').classList.add('hidden');
            document.getElementById('google-register-loading').classList.remove('hidden');
            document.getElementById('google-register-btn').classList.add('opacity-75', 'cursor-not-allowed');
        }
        
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
                showNextLoading();
                
                // Simulate processing time
                setTimeout(() => {
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
                    
                    hideNextLoading();
                }, 800);
            }
        });
        
        // Submit button click handler
        document.getElementById('submitBtn').addEventListener('click', function(e) {
            // Convert comma to dot before validation and submission
            const ipkInput = document.getElementById('ipk');
            const ipkValue = ipkInput.value.replace(',', '.');
            const ipk = parseFloat(ipkValue);

            if (ipk > 4.0) {
                e.preventDefault();
                alert('IPK maksimal adalah 4,00. Silakan perbaiki nilai IPK Anda.');
                ipkInput.focus();
                return false;
            }

            if (ipk < 0) {
                e.preventDefault();
                alert('IPK tidak boleh negatif. Silakan perbaiki nilai IPK Anda.');
                ipkInput.focus();
                return false;
            }

            // Convert comma to dot before submission
            if (ipkInput.value.includes(',')) {
                ipkInput.value = ipkInput.value.replace(',', '.');
            }

            // Show loading overlay
            showLoadingOverlay();
        });
        
        // Google register button click handler
        document.getElementById('google-register-btn').addEventListener('click', function() {
            showGoogleRegisterLoading();
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
                    const fieldOrder = ['nim', 'semester', 'jenis_kelamin', 'no_wa', 'ipk', 'id_dospem'];
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
