<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\ProfilMahasiswa;
use App\Models\Mitra;
use App\Models\Khs;
use App\Models\SuratBalasan;
use App\Models\LaporanPkl;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin
        $admin1 = User::create([
            'name' => 'Admin SIPP 1',
            'email' => 'admin1@gmail.com',
            'password' => Hash::make('found1'),
            'role' => 'admin',
        ]);

        $admin2 = User::create([
            'name' => 'Admin SIPP 2',
            'email' => 'admin2@gmail.com',
            'password' => Hash::make('found1'),
            'role' => 'admin',
        ]);

        // Create Dosen Pembimbing
        $dospem1 = User::create([
            'name' => 'Dr. Ahmad Wijaya, S.T., M.T.',
            'email' => 'dospem1@gmail.com',
            'password' => Hash::make('found1'),
            'role' => 'dospem',
        ]);

        $dospem2 = User::create([
            'name' => 'Dr. Siti Nurhaliza, S.T., M.T.',
            'email' => 'dospem2@gmail.com',
            'password' => Hash::make('found1'),
            'role' => 'dospem',
        ]);

        $dospem3 = User::create([
            'name' => 'Dr. Budi Santoso, S.T., M.T.',
            'email' => 'dospem3@gmail.com',
            'password' => Hash::make('found1'),
            'role' => 'dospem',
        ]);

        // Create 10 Mahasiswa
        $mahasiswa = [];
        for ($i = 1; $i <= 10; $i++) {
            $mahasiswa[$i] = User::create([
                'name' => 'Mahasiswa ' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'email' => 'mhs' . str_pad($i, 3, '0', STR_PAD_LEFT) . '@gmail.com',
                'password' => Hash::make('found1'),
                'role' => 'mahasiswa',
            ]);
        }

        // Create Profil Mahasiswa (10 mahasiswa dibagi ke 3 dospem)
        $dospemIds = [$dospem1->id, $dospem2->id, $dospem3->id];
        
        for ($i = 1; $i <= 10; $i++) {
            $dospemIndex = ($i - 1) % 3; // Rotasi dospem: 0, 1, 2, 0, 1, 2, ...
            
            ProfilMahasiswa::create([
                'id_mahasiswa' => $mahasiswa[$i]->id,
                'nim' => 'TI23' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'prodi' => 'Teknologi Informasi',
                'semester' => 5,
                'no_whatsapp' => '081234567' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'jenis_kelamin' => $i % 2 == 0 ? 'P' : 'L',
                'ipk' => round(3.0 + (rand(0, 50) / 100), 2),
                'cek_min_semester' => true,
                'cek_ipk_nilaisks' => true,
                'cek_valid_biodata' => true,
                'id_dospem' => $dospemIds[$dospemIndex],
            ]);
        }

        // Create Mitra
        $mitra1 = Mitra::create([
            'nama' => 'PT. Teknologi Digital Indonesia',
            'alamat' => 'Jl. Teknologi No. 123, Jakarta',
            'kontak' => '021-12345678',
        ]);

        $mitra2 = Mitra::create([
            'nama' => 'CV. Solusi Informatika',
            'alamat' => 'Jl. Informatika No. 456, Bandung',
            'kontak' => '022-87654321',
        ]);

        $mitra3 = Mitra::create([
            'nama' => 'PT. Sistem Informasi Global',
            'alamat' => 'Jl. Sistem No. 789, Surabaya',
            'kontak' => '031-11223344',
        ]);

        // Create sample documents for some mahasiswa
        $statuses = ['menunggu', 'tervalidasi', 'belum_valid', 'revisi'];
        
        for ($i = 1; $i <= 10; $i++) {
            // Create KHS for some students
            if (rand(1, 10) <= 7) { // 70% chance
                Khs::create([
                    'mahasiswa_id' => $mahasiswa[$i]->id,
                    'file_path' => 'documents/khs/khs_ti23' . str_pad($i, 3, '0', STR_PAD_LEFT) . '.pdf',
                    'status_validasi' => $statuses[array_rand($statuses)],
                ]);
            }
            
            // Create Surat Balasan for some students
            if (rand(1, 10) <= 6) { // 60% chance
                $mitraId = rand(1, 3) == 1 ? null : rand(1, 3);
                SuratBalasan::create([
                    'mahasiswa_id' => $mahasiswa[$i]->id,
                    'mitra_id' => $mitraId,
                    'mitra_nama_custom' => $mitraId ? null : 'Mitra Custom ' . $i,
                    'file_path' => 'documents/surat_balasan/surat_ti23' . str_pad($i, 3, '0', STR_PAD_LEFT) . '.pdf',
                    'status_validasi' => $statuses[array_rand($statuses)],
                ]);
            }
            
            // Create Laporan PKL for some students
            if (rand(1, 10) <= 5) { // 50% chance
                LaporanPkl::create([
                    'mahasiswa_id' => $mahasiswa[$i]->id,
                    'file_path' => 'documents/laporan_pkl/laporan_ti23' . str_pad($i, 3, '0', STR_PAD_LEFT) . '.pdf',
                    'status_validasi' => $statuses[array_rand($statuses)],
                ]);
            }
        }
    }
}