<div class="bg-white shadow-lg rounded-xl border border-gray-100 overflow-hidden">
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-clipboard-check text-2xl text-white"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-lg font-semibold text-white">Langkah 1: Cek Kelayakan PKL</h3>
                <p class="text-blue-100 text-sm">Unggah KHS semester 1–4 (terpisah). Sistem akan memproses kelayakan otomatis.</p>
            </div>
        </div>
    </div>

    <div class="p-6">
        {{-- Ringkasan syarat --}}
        <div class="mb-5 p-4 rounded-lg bg-blue-50 border border-blue-200 text-sm text-blue-900">
            <div class="font-semibold mb-1">Syarat Kelayakan PKL</div>
            <ul class="list-disc pl-5 space-y-1">
                <li>IPK minimal <strong>2.50</strong></li>
                <li>Total nilai <strong>D</strong> maksimal <strong>6 SKS</strong></li>
                <li><strong>Tidak ada</strong> nilai <strong>E</strong></li>
            </ul>
        </div>

        {{-- Upload KHS per semester --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach([1,2,3,4] as $smt)
                <form action="{{ route('pemberkasan.khs.semester', $smt) }}" method="POST" enctype="multipart/form-data" class="p-4 border rounded-lg space-y-3">
                    @csrf
                    <label class="block text-sm font-medium text-gray-700">
                        KHS Semester {{ $smt }}
                    </label>
                    <input type="file" name="file" accept=".pdf" required
                        class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0
                               file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100
                               border border-gray-300 rounded-lg cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-blue-600 to-indigo-700 text-white py-2.5 rounded-lg hover:from-blue-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <i class="fas fa-upload mr-2"></i>Upload Smt {{ $smt }}
                    </button>
                    <p class="text-xs text-gray-400">PDF maksimum 10MB.</p>
                </form>
            @endforeach
        </div>

        {{-- Panel hasil analisis (dummy untuk sementara) --}}
        <div class="mt-8 bg-white border rounded-lg shadow-sm">
            <div class="px-6 py-4 border-b bg-gray-50">
                <h4 class="font-semibold text-gray-900">Hasil Analisis Kelayakan</h4>
                <p class="text-xs text-gray-500">Ditampilkan setelah sistem selesai memproses KHS.</p>
            </div>
            <div class="p-6">
                @php
                    $dummy = ['ipk'=>null,'d_sks'=>null,'ada_e'=>null,'eligible'=>null];
                @endphp

                @if($dummy['ipk'] === null)
                    <div class="p-4 bg-gray-50 border rounded text-gray-600 text-sm">
                        Analisis belum tersedia. Unggah KHS semester 1–4 untuk diproses.
                    </div>
                @else
                    <dl class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                        <div class="p-4 rounded border">
                            <dt class="text-gray-500">IPK</dt>
                            <dd class="text-lg font-semibold">{{ number_format($dummy['ipk'], 2) }}</dd>
                        </div>
                        <div class="p-4 rounded border">
                            <dt class="text-gray-500">Total SKS bernilai D</dt>
                            <dd class="text-lg font-semibold">{{ $dummy['d_sks'] }} SKS</dd>
                        </div>
                        <div class="p-4 rounded border">
                            <dt class="text-gray-500">Nilai E</dt>
                            <dd class="text-lg font-semibold">{{ $dummy['ada_e'] ? 'Ada' : 'Tidak ada' }}</dd>
                        </div>
                    </dl>

                    <div class="mt-4 p-4 rounded border 
                        {{ $dummy['eligible'] ? 'bg-blue-50 border-blue-200 text-blue-800' : 'bg-rose-50 border-rose-200 text-rose-800' }}">
                        <strong>{{ $dummy['eligible'] ? 'Layak PKL' : 'Tidak Layak PKL' }}</strong>
                        <div class="text-xs mt-1">
                            Syarat: IPK ≥ 2.50, total D ≤ 6 SKS, tanpa E.
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
