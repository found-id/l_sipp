<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - SIPP PKL</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <a href="{{ route('welcome') }}" class="flex items-center">
                        <i class="fas fa-graduation-cap text-2xl text-indigo-600 mr-3"></i>
                        <span class="text-xl font-bold text-gray-900">SIPP PKL</span>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-indigo-600 transition-colors">
                        <i class="fas fa-sign-in-alt mr-1"></i>
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition-colors">
                        <i class="fas fa-user-plus mr-1"></i>
                        Daftar
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">
                <i class="fas fa-question-circle text-indigo-600 mr-3"></i>
                Frequently Asked Questions (FAQ)
            </h1>
            <p class="text-lg text-gray-600">
                Temukan jawaban untuk pertanyaan yang sering diajukan tentang Sistem Informasi Pengelolaan PKL
            </p>
        </div>

        <!-- FAQ Sections -->
        <div class="space-y-6">
            <!-- General Questions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-info-circle text-indigo-600 mr-2"></i>
                        Pertanyaan Umum
                    </h2>
                    
                    <div class="space-y-4">
                        <div class="border-l-4 border-indigo-500 pl-4">
                            <h3 class="font-medium text-gray-900 mb-2">Apa itu SIPP PKL?</h3>
                            <p class="text-gray-600">SIPP PKL adalah Sistem Informasi Pengelolaan Praktik Kerja Lapangan yang membantu mahasiswa, dosen pembimbing, dan admin dalam mengelola proses PKL secara digital.</p>
                        </div>

                        <div class="border-l-4 border-indigo-500 pl-4">
                            <h3 class="font-medium text-gray-900 mb-2">Siapa yang bisa menggunakan sistem ini?</h3>
                            <p class="text-gray-600">Sistem ini dapat digunakan oleh mahasiswa yang akan melaksanakan PKL, dosen pembimbing, dan admin yang mengelola sistem.</p>
                        </div>

                        <div class="border-l-4 border-indigo-500 pl-4">
                            <h3 class="font-medium text-gray-900 mb-2">Bagaimana cara mendaftar akun?</h3>
                            <p class="text-gray-600">Klik tombol "Daftar" di halaman utama, isi formulir pendaftaran dengan data yang valid, dan lengkapi biodata mahasiswa untuk menyelesaikan pendaftaran.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Student Questions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-user-graduate text-indigo-600 mr-2"></i>
                        Untuk Mahasiswa
                    </h2>
                    
                    <div class="space-y-4">
                        <div class="border-l-4 border-indigo-500 pl-4">
                            <h3 class="font-medium text-gray-900 mb-2">Dokumen apa saja yang perlu diunggah?</h3>
                            <p class="text-gray-600">Mahasiswa perlu mengunggah Kartu Hasil Studi (KHS), Surat Balasan dari mitra PKL, dan Laporan PKL sesuai dengan ketentuan yang berlaku.</p>
                        </div>

                        <div class="border-l-4 border-indigo-500 pl-4">
                            <h3 class="font-medium text-gray-900 mb-2">Bagaimana cara melihat hasil penilaian?</h3>
                            <p class="text-gray-600">Setelah dosen pembimbing melakukan penilaian, hasil penilaian akan muncul di halaman "Hasil Penilaian" pada profil mahasiswa.</p>
                        </div>

                        <div class="border-l-4 border-indigo-500 pl-4">
                            <h3 class="font-medium text-gray-900 mb-2">Kapan jadwal seminar PKL?</h3>
                            <p class="text-gray-600">Jadwal seminar akan diatur oleh admin dan dapat dilihat di menu "Jadwal Seminar" setelah login ke sistem.</p>
                        </div>

                        <div class="border-l-4 border-indigo-500 pl-4">
                            <h3 class="font-medium text-gray-900 mb-2">Bagaimana jika lupa password?</h3>
                            <p class="text-gray-600">Hubungi admin sistem untuk reset password atau gunakan fitur "Lupa Password" jika tersedia.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dosen Questions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-chalkboard-teacher text-indigo-600 mr-2"></i>
                        Untuk Dosen Pembimbing
                    </h2>
                    
                    <div class="space-y-4">
                        <div class="border-l-4 border-indigo-500 pl-4">
                            <h3 class="font-medium text-gray-900 mb-2">Bagaimana cara melakukan penilaian mahasiswa?</h3>
                            <p class="text-gray-600">Login sebagai dosen pembimbing, pilih menu "Penilaian", pilih mahasiswa yang akan dinilai, dan isi form penilaian sesuai dengan rubrik yang telah ditentukan.</p>
                        </div>

                        <div class="border-l-4 border-indigo-500 pl-4">
                            <h3 class="font-medium text-gray-900 mb-2">Bagaimana cara melihat daftar mahasiswa bimbingan?</h3>
                            <p class="text-gray-600">Daftar mahasiswa bimbingan dapat dilihat di dashboard dosen pembimbing atau di menu "Penilaian".</p>
                        </div>

                        <div class="border-l-4 border-indigo-500 pl-4">
                            <h3 class="font-medium text-gray-900 mb-2">Apakah bisa mengubah penilaian yang sudah disubmit?</h3>
                            <p class="text-gray-600">Hubungi admin sistem untuk perubahan penilaian yang sudah disubmit, karena penilaian yang sudah final tidak dapat diubah secara langsung.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Technical Questions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-cogs text-indigo-600 mr-2"></i>
                        Masalah Teknis
                    </h2>
                    
                    <div class="space-y-4">
                        <div class="border-l-4 border-indigo-500 pl-4">
                            <h3 class="font-medium text-gray-900 mb-2">Website tidak bisa diakses atau lambat?</h3>
                            <p class="text-gray-600">Pastikan koneksi internet stabil, clear cache browser, atau coba akses dari browser/device yang berbeda. Jika masalah berlanjut, hubungi admin sistem.</p>
                        </div>

                        <div class="border-l-4 border-indigo-500 pl-4">
                            <h3 class="font-medium text-gray-900 mb-2">File tidak bisa diunggah?</h3>
                            <p class="text-gray-600">Pastikan format file sesuai dengan ketentuan (PDF, JPG, PNG), ukuran file tidak melebihi batas maksimal, dan koneksi internet stabil.</p>
                        </div>

                        <div class="border-l-4 border-indigo-500 pl-4">
                            <h3 class="font-medium text-gray-900 mb-2">Error saat login atau registrasi?</h3>
                            <p class="text-gray-600">Pastikan data yang dimasukkan sudah benar, email sudah terverifikasi, dan tidak ada masalah dengan koneksi internet. Jika masih error, hubungi admin sistem.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="bg-indigo-50 rounded-lg border border-indigo-200">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-indigo-900 mb-4 flex items-center">
                        <i class="fas fa-headset text-indigo-600 mr-2"></i>
                        Butuh Bantuan Lebih Lanjut?
                    </h2>
                    <p class="text-indigo-700 mb-4">
                        Jika pertanyaan Anda tidak terjawab di FAQ ini, silakan hubungi tim support kami:
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center">
                            <i class="fas fa-envelope text-indigo-600 mr-3"></i>
                            <div>
                                <p class="font-medium text-indigo-900">Email Support</p>
                                <p class="text-indigo-700">support@sipp-pkl.ac.id</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-phone text-indigo-600 mr-3"></i>
                            <div>
                                <p class="font-medium text-indigo-900">Telepon</p>
                                <p class="text-indigo-700">(021) 1234-5678</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-12">
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
</body>
</html>

