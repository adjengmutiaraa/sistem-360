<?php

namespace Database\Seeders;

use App\Models\PeriodePenilaian;
use App\Services\Penugasan360Service;
use Illuminate\Database\Seeder;

class PeriodeSeeder extends Seeder
{
    public function run(): void
    {
        $periode = PeriodePenilaian::updateOrCreate(
            ['nama_periode' => 'Juli 2026'],
            [
                'tanggal_mulai' => '2026-07-01',
                'tanggal_selesai' => '2026-07-31',
                'status' => 'aktif',
                'deskripsi' => 'Periode Penilaian Kinerja 360° ASN Bulan Juli 2026',
            ]
        );

        $service = new Penugasan360Service();
        $service->generatePenugasan($periode);
    }
}
