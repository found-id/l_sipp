<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lengkapi Biodata - SIPP PKL</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
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
                        <input type="text" id="prodi" name="prodi" required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                               value="{{ old('prodi', 'Teknologi Informasi') }}">
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
                        <label for="id_dospem" class="block text-sm font-medium text-gray-700">Dosen Pembimbing (Opsional)</label>
                        <select id="id_dospem" name="id_dospem"
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
                    <a href="{{ route('logout') }}" 
                       class="flex-1 flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                    <button type="submit" 
                            class="flex-1 flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-check mr-2"></i>
                        Selesai
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
