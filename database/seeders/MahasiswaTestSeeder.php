<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ProfilMahasiswa;
use App\Models\AssessmentResult;
use App\Models\AssessmentResponse;
use App\Models\AssessmentResponseItem;
use App\Services\AssessmentService;

class MahasiswaTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test mahasiswa
        $mahasiswa = User::create([
            'name' => 'Test Mahasiswa',
            'email' => 'mahasiswa@test.com',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
            'email_verified_at' => now(),
        ]);

        // Create profil mahasiswa
        ProfilMahasiswa::create([
            'id_mahasiswa' => $mahasiswa->id,
            'nim' => 'TEST001',
            'prodi' => 'Teknologi Informasi',
            'id_dospem' => 3, // Dr. Ahmad Wijaya
        ]);

        // Create assessment response
        $response = AssessmentResponse::create([
            'mahasiswa_user_id' => $mahasiswa->id,
            'dosen_user_id' => 3,
            'is_final' => true,
            'submitted_at' => now(),
        ]);

        // Create assessment response items
        $form = AssessmentService::getAssessmentForm();
        foreach ($form['items'] as $item) {
            if ($item['type'] === 'numeric') {
                AssessmentResponseItem::create([
                    'response_id' => $response->id,
                    'item_id' => $item['id'],
                    'value_numeric' => rand(70, 95),
                ]);
            } elseif ($item['type'] === 'boolean') {
                AssessmentResponseItem::create([
                    'response_id' => $response->id,
                    'item_id' => $item['id'],
                    'value_bool' => true,
                ]);
            } else {
                AssessmentResponseItem::create([
                    'response_id' => $response->id,
                    'item_id' => $item['id'],
                    'value_text' => 'Komentar test untuk ' . $item['label'],
                ]);
            }
        }

        // Create assessment result
        AssessmentResult::create([
            'mahasiswa_user_id' => $mahasiswa->id,
            'total_percent' => 88.65,
            'letter_grade' => 'A',
            'gpa_point' => 4.00,
            'decided_at' => now(),
            'decided_by' => 3,
        ]);

        $this->command->info('Test mahasiswa created successfully!');
        $this->command->info('Email: mahasiswa@test.com');
        $this->command->info('Password: password');
    }
}