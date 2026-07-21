<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        $positions = [
            ['id' => 1, 'name' => 'Kepala Badan', 'level' => '1'],
            ['id' => 2, 'name' => 'Sekretaris', 'level' => '2'],
            ['id' => 3, 'name' => 'Kepala Bidang', 'level' => '3'],
            ['id' => 4, 'name' => 'Kepala Subbagian', 'level' => '4'],
            ['id' => 5, 'name' => 'Kepala UPTD', 'level' => '4'],
            ['id' => 6, 'name' => 'Subkoordinator', 'level' => '5'],
            ['id' => 7, 'name' => 'Analis Kepegawaian', 'level' => '6'],
            ['id' => 8, 'name' => 'Pengelola Kepegawaian', 'level' => '6'],
            ['id' => 9, 'name' => 'Pranata Komputer', 'level' => '6'],
            ['id' => 10, 'name' => 'ASN', 'level' => '7'],
            ['id' => 11, 'name' => 'PPPK', 'level' => '7'],
            
            // New specific positions requested
            ['id' => 12, 'name' => 'Kepala Bidang Pengadaan, Pemberhentian dan Informasi Kepegawaian', 'level' => '3'],
            ['id' => 13, 'name' => 'Pranata Komputer Ahli Pertama', 'level' => '6'],
            ['id' => 14, 'name' => 'Pengadministrasi Perkantoran', 'level' => '7'],
            ['id' => 15, 'name' => 'Penelaah Teknis Kebijakan', 'level' => '7'],
            ['id' => 16, 'name' => 'Pranata Komputer Ahli Muda', 'level' => '6'],
            ['id' => 17, 'name' => 'Analis Sumber Daya Manusia Aparatur Ahli Muda', 'level' => '6'],
            ['id' => 18, 'name' => 'Pranata Komputer Mahir/Pelaksana Lanjutan', 'level' => '6'],
        ];

        foreach ($positions as $position) {
            Position::updateOrCreate(['id' => $position['id']], $position);
        }
    }
}
