<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIPP PKL</title>

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

        <!-- Right Side - Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 overflow-y-auto">
            <div class="max-w-md w-full space-y-8">
            <div>
                <div class="mx-auto h-12 w-12 flex items-center justify-center">
                    <i class="fas fa-graduation-cap text-3xl text-indigo-600"></i>
                </div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Sistem Informasi Pengelolaan PKL
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Silakan login untuk mengakses sistem
                </p>
            </div>
            
            <form class="mt-8 space-y-6" method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="rounded-md shadow-sm -space-y-px">
                    <div>
                        <label for="email" class="sr-only">Email atau NIM</label>
                        <input id="email" name="email" type="text" required 
                               class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" 
                               placeholder="Email atau NIM"
                               value="{{ old('email') }}">
                    </div>
                    <div>
                        <label for="password" class="sr-only">Password</label>
                        <input id="password" name="password" type="password" required 
                               class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" 
                               placeholder="Password">
                    </div>
                </div>

                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Full Screen Auto-Retry Overlay -->
                <div id="auto-retry-overlay" class="fixed inset-0 bg-white z-50 flex items-center justify-center hidden">
                    <div class="text-center">
                        <div class="relative">
                            <div class="animate-spin rounded-full h-16 w-16 border-4 border-gray-200 border-t-indigo-600 mx-auto mb-6"></div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="w-8 h-8 bg-indigo-600 rounded-full animate-pulse"></div>
                            </div>
                        </div>
                        <p class="text-gray-700 text-lg font-medium">Tunggu sebentar, silahkan login kembali</p>
                        <p class="text-gray-500 text-sm mt-2">Memproses permintaan Anda...</p>
                    </div>
                </div>

                <div>
                    <button type="submit" id="login-btn"
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <i class="fas fa-lock text-indigo-500 group-hover:text-indigo-400" id="login-icon"></i>
                        </span>
                        <span id="login-text">Login</span>
                        <div id="login-loading" class="hidden flex items-center">
                            <div class="relative mr-3">
                                <div class="animate-spin rounded-full h-4 w-4 border-2 border-white border-t-transparent"></div>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="w-2 h-2 bg-white rounded-full animate-pulse"></div>
                                </div>
                            </div>
                            <span class="text-white">Memproses...</span>
                        </div>
                    </button>
                </div>

                <div class="text-center">
                    <p class="text-sm text-gray-600" id="registration-text">
                        Belum punya akun? 
                        <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-500" id="registration-link">
                            Daftar di sini
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
                       id="google-login-btn"
                       class="w-full flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" id="google-icon" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        <span id="google-text">Login dengan Google</span>
                        <div id="google-loading" class="hidden flex items-center">
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
                
                <!-- Retry Button (hidden by default) -->
                <div id="retry-section" class="mt-4 hidden">
                    <button onclick="retryGoogleLogin()" 
                            class="w-full flex justify-center items-center px-4 py-2 border border-red-300 rounded-md shadow-sm bg-red-50 text-sm font-medium text-red-700 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <i class="fas fa-redo mr-2"></i>
                        Coba Lagi Login Google
                    </button>
                </div>
            </form>

            <!-- Footer -->
            <footer class="mt-8 text-center">
                <p class="text-sm text-gray-500">
                    © {{ date('Y') }} SIPP PKL. All rights reserved.
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
        // Check if registration is enabled
        const registrationEnabled = {{ \App\Models\SystemSetting::isEnabled('registration_enabled') ? 'true' : 'false' }};
        
        // Handle registration link click
        document.getElementById('registration-link').addEventListener('click', function(e) {
            if (!registrationEnabled) {
                e.preventDefault(); // Prevent default link behavior
                
                // Change the text content
                const registrationText = document.getElementById('registration-text');
                registrationText.innerHTML = `
                    <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-md">
                        <i class="fas fa-lock text-yellow-600 mr-2"></i>
                        <span class="text-sm text-yellow-800">Registrasi telah ditutup. Silakan hubungi Koordinator.</span>
                    </div>
                `;
                
            }
        });
        
        // Login form handling
        document.getElementById('login-btn').addEventListener('click', function() {
            showLoginLoading();
            
            // Auto-hide loading after 4 seconds
            setTimeout(() => {
                hideLoginLoading();
            }, 4000);
        });
        
        // Handle Enter key navigation between fields
        document.getElementById('email').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                // Move to password field without showing loading
                document.getElementById('password').focus();
            }
        });
        
        document.getElementById('password').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                // Show loading animation and submit form
                showLoginLoading();
                
                // Auto-hide loading after 4 seconds
                setTimeout(() => {
                    hideLoginLoading();
                }, 4000);
                
                // Submit the form
                document.querySelector('form').submit();
            }
        });
        
        function showLoginLoading() {
            document.getElementById('login-icon').classList.add('hidden');
            document.getElementById('login-text').classList.add('hidden');
            document.getElementById('login-loading').classList.remove('hidden');
            document.getElementById('login-btn').classList.add('opacity-75', 'cursor-not-allowed');
        }
        
        function hideLoginLoading() {
            document.getElementById('login-icon').classList.remove('hidden');
            document.getElementById('login-text').classList.remove('hidden');
            document.getElementById('login-loading').classList.add('hidden');
            document.getElementById('login-btn').classList.remove('opacity-75', 'cursor-not-allowed');
        }
        
        // Google OAuth handling
        let googleLoginAttempts = 0;
        const maxRetries = 3;
        
        // Handle Google login button click
        document.getElementById('google-login-btn').addEventListener('click', function(e) {
            googleLoginAttempts++;
            showGoogleLoading();
            
            // Add a small delay to show loading state
            setTimeout(() => {
                // The page will redirect to Google, so this is just for UX
            }, 100);
        });
        
        function showGoogleLoading() {
            document.getElementById('google-icon').classList.add('hidden');
            document.getElementById('google-text').classList.add('hidden');
            document.getElementById('google-loading').classList.remove('hidden');
            document.getElementById('google-login-btn').classList.add('opacity-75', 'cursor-not-allowed');
        }
        
        function hideGoogleLoading() {
            document.getElementById('google-icon').classList.remove('hidden');
            document.getElementById('google-text').classList.remove('hidden');
            document.getElementById('google-loading').classList.add('hidden');
            document.getElementById('google-login-btn').classList.remove('opacity-75', 'cursor-not-allowed');
        }
        
        function showRetryButton() {
            document.getElementById('retry-section').classList.remove('hidden');
            hideGoogleLoading();
        }
        
        function retryGoogleLogin() {
            googleLoginAttempts = 0;
            document.getElementById('retry-section').classList.add('hidden');
            window.location.href = '{{ route("auth.google") }}';
        }
        
        // Check for Google OAuth errors and show retry button
        document.addEventListener('DOMContentLoaded', function() {
            const errorMessages = document.querySelectorAll('.bg-red-100');
            errorMessages.forEach(function(error) {
                if (error.textContent.includes('Google') || error.textContent.includes('OAuth')) {
                    showRetryButton();
                }
            });
        });
        
        // Auto-retry mechanism for Google OAuth
        if (window.location.search.includes('error') || window.location.search.includes('oauth')) {
            setTimeout(() => {
                if (googleLoginAttempts < maxRetries) {
                    showRetryButton();
                }
            }, 2000);
        }
        
        // Enhanced auto-retry mechanism for Google OAuth errors
        function checkForGoogleOAuthError() {
            const errorMessages = document.querySelectorAll('.bg-red-100');
            let hasGoogleError = false;
            let hasAutoRetryFlag = false;
            
            errorMessages.forEach(function(error) {
                const errorText = error.textContent.toLowerCase();
                if (errorText.includes('google') || 
                    errorText.includes('oauth') || 
                    errorText.includes('terjadi kesalahan') ||
                    errorText.includes('silakan coba lagi')) {
                    hasGoogleError = true;
                }
                
                // Check for AUTO_RETRY flag
                if (errorText.includes('[auto_retry]')) {
                    hasAutoRetryFlag = true;
                }
            });
            
            if (hasGoogleError || hasAutoRetryFlag) {
                showAutoRetryNotification();
            }
        }
        
        function showAutoRetryNotification() {
            const overlay = document.getElementById('auto-retry-overlay');
            
            // Show full screen overlay immediately
            overlay.classList.remove('hidden');
            
            // Auto-retry after 0.01 seconds (10ms)
            setTimeout(() => {
                const googleButton = document.getElementById('google-login-btn');
                if (googleButton) {
                    googleButton.click();
                }
            }, 10);
        }
        
        // Check for errors on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Wait a bit for any dynamic content to load
            setTimeout(checkForGoogleOAuthError, 1000);
        });
    </script>
</body>
</html>