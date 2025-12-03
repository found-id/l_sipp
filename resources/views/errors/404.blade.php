<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Not Found</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 h-screen flex items-center justify-center overflow-hidden">
    <div class="text-center space-y-8 relative z-10">
        <!-- Logo -->
        <div class="flex items-center justify-center mb-8">
            <div class="flex items-center text-3xl font-bold text-gray-900">
                <i class="fas fa-graduation-cap text-4xl text-indigo-600 mr-3"></i>
                SIP PKL
            </div>
        </div>

        <!-- 404 Text -->
        <div class="relative">
            <h1 class="text-9xl font-bold text-gray-200 select-none">404</h1>
            <div class="absolute inset-0 flex items-center justify-center">
                <span class="text-2xl font-medium text-gray-600 tracking-widest uppercase">Not Found</span>
            </div>
        </div>

        <!-- Message -->
        <p class="text-gray-500 max-w-md mx-auto text-lg">
            Halaman yang Anda cari tidak ditemukan atau telah dipindahkan.
        </p>

        <!-- Back Button -->
        <div>
            <a href="{{ url()->previous() }}" 
               class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl transition-all duration-300 group shadow-lg hover:shadow-indigo-600/30 font-semibold">
                <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <!-- Decorative Elements -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-indigo-500/5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-blue-500/5 rounded-full blur-3xl"></div>
    </div>
</body>
</html>
