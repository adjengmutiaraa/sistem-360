<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use Illuminate\Database\Seeder;

class JabatanSeeder extends Seeder
{
    public function run(): void
    {
        $jabatans = [
            [
                'id' => 1,
                'nama_jabatan' => 'Ketua Umum',
                'level' => 'ketua_umum',
            ],
            [
                'id' => 2,
                'nama_jabatan' => 'Kepala Bidang',
                'level' => 'kabid',
            ],
            [
                'id' => 3,
                'nama_jabatan' => 'Staff / Pelaksana',
                'level' => 'staff',
            ],
        ];

        foreach ($jabatans as $jabatan) {
            Jabatan::updateOrCreate(['id' => $jabatan['id']], $jabatan);
        }
    }
}
