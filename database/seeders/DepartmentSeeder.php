<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        // Level 1: Kepala Badan
        $kepalaBadan = Department::firstOrCreate(['id' => 1], [
            'name' => 'Kepala Badan',
            'parent_id' => null,
            'level' => 1,
        ]);

        // Level 2: Sekretariat & Bidang
        $sekretariat = Department::firstOrCreate(['id' => 2], [
            'name' => 'Sekretariat',
            'parent_id' => $kepalaBadan->id,
            'level' => 2,
        ]);

        $bidang1 = Department::firstOrCreate(['id' => 3], [
            'name' => 'Bidang Pengadaan, Pemberhentian dan Informasi Kepegawaian',
            'parent_id' => $kepalaBadan->id,
            'level' => 2,
        ]);

        $bidang2 = Department::firstOrCreate(['id' => 4], [
            'name' => 'Bidang Mutasi dan Promosi',
            'parent_id' => $kepalaBadan->id,
            'level' => 2,
        ]);

        $bidang3 = Department::firstOrCreate(['id' => 5], [
            'name' => 'Bidang Penilaian dan Evaluasi Kinerja Aparatur',
            'parent_id' => $kepalaBadan->id,
            'level' => 2,
        ]);

        $bidang4 = Department::firstOrCreate(['id' => 6], [
            'name' => 'Bidang Pengembangan Sumber Daya Manusia',
            'parent_id' => $kepalaBadan->id,
            'level' => 2,
        ]);

        $uptd = Department::firstOrCreate(['id' => 7], [
            'name' => 'UPTD',
            'parent_id' => $kepalaBadan->id,
            'level' => 2,
        ]);

        $jafung = Department::firstOrCreate(['id' => 8], [
            'name' => 'Kelompok Jabatan Fungsional',
            'parent_id' => $kepalaBadan->id,
            'level' => 2,
        ]);

        // Level 3: Subbagian
        Department::firstOrCreate(['id' => 9], [
            'name' => 'Subbagian Bina Program dan Keuangan',
            'parent_id' => $sekretariat->id,
            'level' => 3,
        ]);

        Department::firstOrCreate(['id' => 10], [
            'name' => 'Subbagian Umum dan Kepegawaian',
            'parent_id' => $sekretariat->id,
            'level' => 3,
        ]);
    }
}
