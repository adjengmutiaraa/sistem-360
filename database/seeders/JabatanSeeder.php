<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use Illuminate\Database\Seeder;

class JabatanSeeder extends Seeder
{
    public function run(): void
    {
        $jabatans = [
            ['id' => 1, 'nama_jabatan' => 'Ketua Umum', 'level' => 'ketua_umum'],
            ['id' => 2, 'nama_jabatan' => 'Kepala Bidang PPIK', 'level' => 'kabid'],
            ['id' => 3, 'nama_jabatan' => 'Pranata Komputer Ahli Pertama', 'level' => 'staff'],
            ['id' => 4, 'nama_jabatan' => 'Pengadministrasi Perkantoran', 'level' => 'staff'],
            ['id' => 5, 'nama_jabatan' => 'Penelaah Teknis Kebijakan', 'level' => 'staff'],
            ['id' => 6, 'nama_jabatan' => 'Pranata Komputer Ahli Muda', 'level' => 'staff'],
            ['id' => 7, 'nama_jabatan' => 'Analis Sumber Daya Manusia Aparatur Ahli Muda', 'level' => 'staff'],
            ['id' => 8, 'nama_jabatan' => 'Pranata Komputer Mahir/Pelaksana Lanjutan', 'level' => 'staff'],
        ];

        foreach ($jabatans as $jabatan) {
            Jabatan::updateOrCreate(['id' => $jabatan['id']], $jabatan);
        }
    }
}
