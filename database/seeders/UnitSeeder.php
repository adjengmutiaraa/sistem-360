<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            [
                'id' => 1,
                'nama_unit' => 'Sekretariat Utama',
                'kode_unit' => 'SEKRETARIAT',
            ],
            [
                'id' => 2,
                'nama_unit' => 'Bidang Perencanaan & Kinerja',
                'kode_unit' => 'PERENCANAAN',
            ],
            [
                'id' => 3,
                'nama_unit' => 'Bidang Kepegawaian & SDM',
                'kode_unit' => 'KEPEGAWAIAN',
            ],
            [
                'id' => 4,
                'nama_unit' => 'Bidang Teknologi Informasi',
                'kode_unit' => 'TI',
            ],
        ];

        foreach ($units as $unit) {
            Unit::updateOrCreate(['id' => $unit['id']], $unit);
        }
    }
}
