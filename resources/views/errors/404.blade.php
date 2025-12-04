<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Not Found</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    @php
        $fontConfig = \App\Models\SystemSetting::getFontConfig();
    @endphp
    
    @if($fontConfig['url'])
        <link href="{{ $fontConfig['url'] }}" rel="stylesheet">
    @endif
    
    <style>
        body { font-family: {!! $fontConfig['family'] !!}; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 h-screen flex items-center justify-center">
    <div class="text-center">
        <!-- Logo samar -->
        <div class="flex items-center justify-center mb-6 opacity-20">
            <i class="fas fa-graduation-cap text-5xl text-gray-400 mr-2"></i>
            <span class="text-3xl font-bold text-gray-400">SIP PKL</span>
        </div>
        
        <h1 class="text-8xl font-bold text-gray-200">404</h1>
        <p class="text-gray-500 mt-4">Halaman tidak ditemukan</p>
    </div>
</body>
</html>
