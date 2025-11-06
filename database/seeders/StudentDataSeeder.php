<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ProfilMahasiswa;
use Illuminate\Support\Facades\Hash;

class StudentDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $students = [
            [
                'nama_lengkap' => 'Sayyidah Nafisah',
                'nim' => '2301301092',
                'prodi' => 'TEKNOLOGI INFORMASI',
                'semester' => 5,
                'no_wa' => '085393749800',
                'jenis_kelamin' => 'P',
                'ipk_terakhir' => 3.81,
                'email' => 'sayyidahnafisah23@mhs.politala.ac.id',
            ],
            [
                'nama_lengkap' => 'muhammad widigda',
                'nim' => '2301301073',
                'prodi' => 'TEKNOLOGI INFORMASI',
                'semester' => 5,
                'no_wa' => '082252316600',
                'jenis_kelamin' => 'L',
                'ipk_terakhir' => 3.20,
                'email' => 'muhammadwidigdapratama23@mhs.politala.ac.id',
            ],
            [
                'nama_lengkap' => 'M. Zainal Akli',
                'nim' => '2301301114',
                'prodi' => 'TEKNOLOGI INFORMASI',
                'semester' => 5,
                'no_wa' => '085752813800',
                'jenis_kelamin' => 'L',
                'ipk_terakhir' => 3.86,
                'email' => 'mzainalakli23@mhs.politala.ac.id',
            ],
            [
                'nama_lengkap' => 'Ahmad Faisal Aditya',
                'nim' => '2301301029',
                'prodi' => 'TEKNOLOGI INFORMASI',
                'semester' => 5,
                'no_wa' => '085951194100',
                'jenis_kelamin' => 'L',
                'ipk_terakhir' => 3.70,
                'email' => 'ahmadfaisaladitya23@mhs.politala.ac.id',
            ],
            [
                'nama_lengkap' => 'Zainal',
                'nim' => '2301301100',
                'prodi' => 'TEKNOLOGI INFORMASI',
                'semester' => 5,
                'no_wa' => '082250657900',
                'jenis_kelamin' => 'L',
                'ipk_terakhir' => 3.50,
                'email' => 'zainal23@mhs.politala.ac.id',
            ],
            [
                'nama_lengkap' => 'AIDA SEKAR NINGRUM',
                'nim' => '2301301093',
                'prodi' => 'TEKNOLOGI INFORMASI',
                'semester' => 5,
                'no_wa' => '083824320100',
                'jenis_kelamin' => 'P',
                'ipk_terakhir' => 3.71,
                'email' => 'aidasekarningrum23@mhs.politala.ac.id',
            ],
            [
                'nama_lengkap' => 'Sima Sabrina',
                'nim' => '2301301121',
                'prodi' => 'TEKNOLOGI INFORMASI',
                'semester' => 5,
                'no_wa' => '081251784500',
                'jenis_kelamin' => 'P',
                'ipk_terakhir' => 3.50,
                'email' => 'simasabrina23@mhs.politala.ac.id',
            ],
            [
                'nama_lengkap' => 'Muhammad Aditya',
                'nim' => '2301301094',
                'prodi' => 'TEKNOLOGI INFORMASI',
                'semester' => 5,
                'no_wa' => '085248131800',
                'jenis_kelamin' => 'L',
                'ipk_terakhir' => 4.00,
                'email' => 'muhammadaditya23@mhs.politala.ac.id',
            ],
            [
                'nama_lengkap' => 'Muhammad Rifani',
                'nim' => '2301301062',
                'prodi' => 'TEKNOLOGI INFORMASI',
                'semester' => 5,
                'no_wa' => '085754152200',
                'jenis_kelamin' => 'L',
                'ipk_terakhir' => 3.75,
                'email' => 'muhammadrifani23@mhs.politala.ac.id',
            ],
            [
                'nama_lengkap' => 'Ani Khairiyah',
                'nim' => '2301301075',
                'prodi' => 'TEKNOLOGI INFORMASI',
                'semester' => 5,
                'no_wa' => '083862166800',
                'jenis_kelamin' => 'P',
                'ipk_terakhir' => 3.31,
                'email' => 'anikhairiyah23@mhs.politala.ac.id',
            ],
        ];

        foreach ($students as $studentData) {
            // Create User
            $user = User::firstOrCreate(
                ['email' => $studentData['email']],
                [
                    'name' => $studentData['nama_lengkap'],
                    'password' => Hash::make($studentData['nim']), // Using NIM as default password
                    'role' => 'mahasiswa',
                ]
            );

            // Create ProfilMahasiswa
            ProfilMahasiswa::firstOrCreate(
                ['id_mahasiswa' => $user->id],
                [
                    'nim' => $studentData['nim'],
                    'prodi' => $studentData['prodi'],
                    'semester' => $studentData['semester'],
                    'no_whatsapp' => $studentData['no_wa'],
                    'jenis_kelamin' => $studentData['jenis_kelamin'],
                    'ipk' => $studentData['ipk_terakhir'],
                ]
            );
        }
    }
}
