<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin
        User::updateOrCreate(
            ['email' => 'admin@sistem360.go.id'],
            [
                'nip' => '199001012020011001',
                'name' => 'Administrator Sistem',
                'email' => 'admin@sistem360.go.id',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'jabatan_id' => null,
                'unit_id' => null,
                'atasan_id' => null,
                'telepon' => '081234567890',
            ]
        );

        // 2. Ketua Umum
        $ketua = User::updateOrCreate(
            ['email' => 'ketua@sistem360.go.id'],
            [
                'nip' => '197001011995031001',
                'name' => 'Dr. H. Ahmad Fauzi, M.Si.',
                'email' => 'ketua@sistem360.go.id',
                'password' => Hash::make('password'),
                'role' => 'pegawai',
                'jabatan_id' => 1, // Ketua Umum
                'unit_id' => 1,    // Sekretariat
                'atasan_id' => null,
                'telepon' => '081234567891',
            ]
        );

        // 3. Kepala Bidang (Kabid 1, 2, 3)
        $kabid1 = User::updateOrCreate(
            ['email' => 'kabid1@sistem360.go.id'],
            [
                'nip' => '198002022005011001',
                'name' => 'Budi Santoso, S.T., M.T.',
                'email' => 'kabid1@sistem360.go.id',
                'password' => Hash::make('password'),
                'role' => 'pegawai',
                'jabatan_id' => 2, // Kabid
                'unit_id' => 2,    // Perencanaan
                'atasan_id' => $ketua->id,
                'telepon' => '081234567892',
            ]
        );

        $kabid2 = User::updateOrCreate(
            ['email' => 'kabid2@sistem360.go.id'],
            [
                'nip' => '198103032005011002',
                'name' => 'Siti Aminah, S.E., M.M.',
                'email' => 'kabid2@sistem360.go.id',
                'password' => Hash::make('password'),
                'role' => 'pegawai',
                'jabatan_id' => 2, // Kabid
                'unit_id' => 3,    // Kepegawaian
                'atasan_id' => $ketua->id,
                'telepon' => '081234567893',
            ]
        );

        $kabid3 = User::updateOrCreate(
            ['email' => 'kabid3@sistem360.go.id'],
            [
                'nip' => '198204042005011003',
                'name' => 'Dedi Wijaya, S.Kom., M.Kom.',
                'email' => 'kabid3@sistem360.go.id',
                'password' => Hash::make('password'),
                'role' => 'pegawai',
                'jabatan_id' => 2, // Kabid
                'unit_id' => 4,    // TI
                'atasan_id' => $ketua->id,
                'telepon' => '081234567894',
            ]
        );

        // 4. Staff Bidang Perencanaan (Bawahan Kabid 1)
        User::updateOrCreate(
            ['email' => 'staff1@sistem360.go.id'],
            [
                'nip' => '199205052018011001',
                'name' => 'Eko Prasetyo, S.Kom.',
                'email' => 'staff1@sistem360.go.id',
                'password' => Hash::make('password'),
                'role' => 'pegawai',
                'jabatan_id' => 3, // Staff
                'unit_id' => 2,    // Perencanaan
                'atasan_id' => $kabid1->id,
                'telepon' => '081234567895',
            ]
        );

        User::updateOrCreate(
            ['email' => 'staff2@sistem360.go.id'],
            [
                'nip' => '199306062018011002',
                'name' => 'Fitriani, S.E.',
                'email' => 'staff2@sistem360.go.id',
                'password' => Hash::make('password'),
                'role' => 'pegawai',
                'jabatan_id' => 3, // Staff
                'unit_id' => 2,    // Perencanaan
                'atasan_id' => $kabid1->id,
                'telepon' => '081234567896',
            ]
        );

        User::updateOrCreate(
            ['email' => 'staff3@sistem360.go.id'],
            [
                'nip' => '199407072018011003',
                'name' => 'Gita Gutawa, S.T.',
                'email' => 'staff3@sistem360.go.id',
                'password' => Hash::make('password'),
                'role' => 'pegawai',
                'jabatan_id' => 3, // Staff
                'unit_id' => 2,    // Perencanaan
                'atasan_id' => $kabid1->id,
                'telepon' => '081234567897',
            ]
        );

        // 5. Staff Bidang Kepegawaian (Bawahan Kabid 2)
        User::updateOrCreate(
            ['email' => 'staff4@sistem360.go.id'],
            [
                'nip' => '199508082018011004',
                'name' => 'Hendra Setiawan, S.IP.',
                'email' => 'staff4@sistem360.go.id',
                'password' => Hash::make('password'),
                'role' => 'pegawai',
                'jabatan_id' => 3, // Staff
                'unit_id' => 3,    // Kepegawaian
                'atasan_id' => $kabid2->id,
                'telepon' => '081234567898',
            ]
        );

        // 6. Staff Bidang TI (Bawahan Kabid 3)
        User::updateOrCreate(
            ['email' => 'staff5@sistem360.go.id'],
            [
                'nip' => '199609092018011005',
                'name' => 'Indah Permata, S.Kom.',
                'email' => 'staff5@sistem360.go.id',
                'password' => Hash::make('password'),
                'role' => 'pegawai',
                'jabatan_id' => 3, // Staff
                'unit_id' => 4,    // TI
                'atasan_id' => $kabid3->id,
                'telepon' => '081234567899',
            ]
        );
    }
}
