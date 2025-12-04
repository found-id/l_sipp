<!-- Navigation -->
<nav class="fixed w-full z-50 transition-all duration-300 bg-white/80 backdrop-blur-md border-b border-gray-100" id="navbar">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ url('/') }}" class="flex items-center text-xl font-bold text-gray-900 hover:text-blue-600 transition-colors">
                    <i class="fas fa-graduation-cap text-2xl text-indigo-600 mr-2"></i>
                    SIP PKL
                </a>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ Route::currentRouteName() == 'welcome' ? '#about' : url('/#about') }}" class="text-base font-medium text-gray-600 hover:text-indigo-600 transition-colors">Tentang</a>
                <a href="{{ Route::currentRouteName() == 'welcome' ? '#stats' : url('/#stats') }}" class="text-base font-medium text-gray-600 hover:text-indigo-600 transition-colors">Statistik</a>
                <a href="{{ Route::currentRouteName() == 'welcome' ? '#features' : url('/#features') }}" class="text-base font-medium text-gray-600 hover:text-indigo-600 transition-colors">Fitur</a>
                <a href="{{ Route::currentRouteName() == 'welcome' ? '#mitra' : url('/#mitra') }}" class="text-base font-medium text-gray-600 hover:text-indigo-600 transition-colors">Mitra</a>
                <a href="{{ route('faq') }}" class="text-base font-medium text-gray-600 hover:text-indigo-600 transition-colors {{ Route::currentRouteName() == 'faq' ? 'text-indigo-600' : '' }}">FAQ</a>
                
                <div class="pl-4 border-l border-gray-200 flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="text-base font-medium text-gray-600 hover:text-indigo-600 transition-colors">Masuk</a>
                    <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-full bg-gray-900 text-white text-base font-medium hover:bg-gray-800 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        Daftar
                    </a>
                </div>
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden flex items-center">
                <button id="mobile-menu-btn" class="text-gray-600 hover:text-indigo-600 focus:outline-none p-2">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-100 absolute w-full left-0 shadow-xl">
        <div class="px-4 pt-2 pb-6 space-y-2">
            <a href="{{ Route::currentRouteName() == 'welcome' ? '#about' : url('/#about') }}" class="block px-4 py-3 rounded-lg text-base font-medium text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 transition-colors">Tentang</a>
            <a href="{{ Route::currentRouteName() == 'welcome' ? '#stats' : url('/#stats') }}" class="block px-4 py-3 rounded-lg text-base font-medium text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 transition-colors">Statistik</a>
            <a href="{{ Route::currentRouteName() == 'welcome' ? '#features' : url('/#features') }}" class="block px-4 py-3 rounded-lg text-base font-medium text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 transition-colors">Fitur</a>
            <a href="{{ Route::currentRouteName() == 'welcome' ? '#mitra' : url('/#mitra') }}" class="block px-4 py-3 rounded-lg text-base font-medium text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 transition-colors">Mitra</a>
            <a href="{{ route('faq') }}" class="block px-4 py-3 rounded-lg text-base font-medium text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 transition-colors {{ Route::currentRouteName() == 'faq' ? 'bg-indigo-50 text-indigo-600' : '' }}">FAQ</a>
            <div class="border-t border-gray-100 my-2 pt-2">
                <a href="{{ route('login') }}" class="block px-4 py-3 rounded-lg text-base font-medium text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 transition-colors">Masuk</a>
                <a href="{{ route('register') }}" class="block px-4 py-3 mt-2 rounded-lg text-base font-medium bg-indigo-600 text-white hover:bg-indigo-700 text-center shadow-md">
                    Daftar Sekarang
                </a>
            </div>
        </div>
    </div>
</nav>

<script>
    // Mobile Menu Toggle
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');

    // Function to close mobile menu
    function closeMobileMenu() {
        if (mobileMenu && !mobileMenu.classList.contains('hidden')) {
            mobileMenu.classList.add('hidden');
            const icon = mobileMenuBtn.querySelector('i');
            icon.classList.remove('fa-times');
            icon.classList.add('fa-bars');
        }
    }

    if (mobileMenuBtn && mobileMenu) {
        // Toggle menu on button click
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
            const icon = mobileMenuBtn.querySelector('i');
            if (mobileMenu.classList.contains('hidden')) {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            } else {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            }
        });

        // Close menu when clicking any link inside mobile menu
        const mobileMenuLinks = mobileMenu.querySelectorAll('a');
        mobileMenuLinks.forEach(link => {
            link.addEventListener('click', () => {
                closeMobileMenu();
            });
        });
    }

    // Navbar Scroll Effect
    const navbar = document.getElementById('navbar');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 10) {
            navbar.classList.add('shadow-sm');
        } else {
            navbar.classList.remove('shadow-sm');
        }
    });
</script>
