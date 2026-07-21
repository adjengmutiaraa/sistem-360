<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Super Admin
        $superadmin = User::firstOrCreate(
            ['email' => 'superadmin@bkpsdm.go.id'],
            [
                'nip' => '199001012020011000',
                'name' => 'Super Administrator',
                'password' => Hash::make('password'),
                'department_id' => null,
                'position_id' => null,
                'atasan_id' => null,
            ]
        );
        $superadmin->assignRole('Super Admin');

        // 2. Admin BKPSDM
        $admin = User::firstOrCreate(
            ['email' => 'admin@bkpsdm.go.id'],
            [
                'nip' => '199001012020011001',
                'name' => 'Administrator BKPSDM',
                'password' => Hash::make('password'),
                'department_id' => null,
                'position_id' => null,
                'atasan_id' => null,
            ]
        );
        $admin->assignRole('Admin BKPSDM');

        // 3. Kepala Badan
        $kaban = User::firstOrCreate(
            ['email' => 'kaban@bkpsdm.go.id'],
            [
                'nip' => '196803231990031012',
                'name' => 'Khaeron, S.H., M.M.',
                'password' => Hash::make('password'),
                'department_id' => 1, // Kepala Badan
                'position_id' => 1, // Kepala Badan
                'atasan_id' => null,
            ]
        );
        $kaban->assignRole('Kepala Badan');

        // 4. Sekretaris
        $sekretaris = User::firstOrCreate(
            ['email' => 'sekretaris@bkpsdm.go.id'],
            [
                'nip' => '197501011998031002',
                'name' => 'Sekretaris Badan',
                'password' => Hash::make('password'),
                'department_id' => 2, // Sekretariat
                'position_id' => 2, // Sekretaris
                'atasan_id' => $kaban->id,
            ]
        );
        $sekretaris->assignRole('Sekretaris');

        // ==========================================
        // REAL USERS FOR BIDANG PPIK (Department 3)
        // ==========================================
        
        // Kepala Bidang PPIK
        $hadi = User::firstOrCreate(
            ['nip' => '197903072005011006'],
            [
                'name' => 'HADI SISWANTO, S.Kom',
                'email' => 'hadisiswanto@bkpsdm.go.id',
                'password' => Hash::make('password'),
                'department_id' => 3, // Bidang PPIK
                'position_id' => 12, // Kepala Bidang Pengadaan, Pemberhentian dan Informasi Kepegawaian
                'atasan_id' => $kaban->id,
                'telepon' => '08122669319',
            ]
        );
        $hadi->assignRole('Kepala Bidang');
        
        $staff1 = User::firstOrCreate(
            ['nip' => '198104192009011005'],
            [
                'name' => 'FENDI HERIAWAN, S.Kom',
                'email' => 'fendiheriawan@bkpsdm.go.id',
                'password' => Hash::make('password'),
                'department_id' => 3,
                'position_id' => 13, // Pranata Komputer Ahli Pertama
                'atasan_id' => $hadi->id,
                'telepon' => '085727886346',
            ]
        );
        $staff1->assignRole('ASN');
        
        $staff2 = User::firstOrCreate(
            ['nip' => '196810151992031007'],
            [
                'name' => 'MASKURI',
                'email' => 'maskuri@bkpsdm.go.id',
                'password' => Hash::make('password'),
                'department_id' => 3,
                'position_id' => 14, // Pengadministrasi Perkantoran
                'atasan_id' => $hadi->id,
                'telepon' => '085711521652',
            ]
        );
        $staff2->assignRole('ASN');
        
        $staff3 = User::firstOrCreate(
            ['nip' => '197709192008012008'],
            [
                'name' => 'DIAN FITRIANA, S.H.',
                'email' => 'dianfitriana@bkpsdm.go.id',
                'password' => Hash::make('password'),
                'department_id' => 3,
                'position_id' => 15, // Penelaah Teknis Kebijakan
                'atasan_id' => $hadi->id,
                'telepon' => '087711709098',
            ]
        );
        $staff3->assignRole('ASN');
        
        $staff4 = User::firstOrCreate(
            ['nip' => '198407302009031003'],
            [
                'name' => 'APIT SETIAWAN, S.Kom.',
                'email' => 'apitsetiawan@bkpsdm.go.id',
                'password' => Hash::make('password'),
                'department_id' => 3,
                'position_id' => 16, // Pranata Komputer Ahli Muda
                'atasan_id' => $hadi->id,
                'telepon' => '089644701421',
            ]
        );
        $staff4->assignRole('ASN');
        
        $staff5 = User::firstOrCreate(
            ['nip' => '197508062007011012'],
            [
                'name' => 'MOHAMAD TARMANTO',
                'email' => 'mohamadtarmanto@bkpsdm.go.id',
                'password' => Hash::make('password'),
                'department_id' => 3,
                'position_id' => 14, // Pengadministrasi Perkantoran
                'atasan_id' => $hadi->id,
                'telepon' => '081914109274',
            ]
        );
        $staff5->assignRole('ASN');
        
        $staff6 = User::firstOrCreate(
            ['nip' => '197106071992031005'],
            [
                'name' => 'ABDUL WAHID ZUHRY, S.IP, M.M.',
                'email' => 'abdulwahidzuhry@bkpsdm.go.id',
                'password' => Hash::make('password'),
                'department_id' => 3,
                'position_id' => 17, // Analis Sumber Daya Manusia Aparatur Ahli Muda
                'atasan_id' => $hadi->id,
                'telepon' => '082330224702',
            ]
        );
        $staff6->assignRole('ASN');
        
        $staff7 = User::firstOrCreate(
            ['nip' => '198609022015022001'],
            [
                'name' => 'RIZKI SEPTINA KUSUMANINGSIH, S.T.',
                'email' => 'rizkiseptina@bkpsdm.go.id',
                'password' => Hash::make('password'),
                'department_id' => 3,
                'position_id' => 18, // Pranata Komputer Mahir/Pelaksana Lanjutan
                'atasan_id' => $hadi->id,
                'telepon' => '085226792535',
            ]
        );
        $staff7->assignRole('ASN');
        
        $staff8 = User::firstOrCreate(
            ['nip' => '198208282014061003'],
            [
                'name' => 'TUSMANTO',
                'email' => 'tusmanto@bkpsdm.go.id',
                'password' => Hash::make('password'),
                'department_id' => 3,
                'position_id' => 14, // Pengadministrasi Perkantoran
                'atasan_id' => $hadi->id,
                'telepon' => '081389398313',
            ]
        );
        $staff8->assignRole('ASN');

        // ==========================================
        // OTHER KABIDS
        // ==========================================

        // Kepala Bidang Mutasi dan Promosi
        $kabid2 = User::firstOrCreate(
            ['email' => 'kabid.mutasi@bkpsdm.go.id'],
            [
                'nip' => '198101012006031004',
                'name' => 'Kepala Bidang Mutasi dan Promosi',
                'password' => Hash::make('password'),
                'department_id' => 4,
                'position_id' => 3,
                'atasan_id' => $kaban->id,
            ]
        );
        $kabid2->assignRole('Kepala Bidang');

        // Kepala Bidang Penilaian dan Evaluasi
        $kabid3 = User::firstOrCreate(
            ['email' => 'kabid.evaluasi@bkpsdm.go.id'],
            [
                'nip' => '198201012007031005',
                'name' => 'Kepala Bidang Penilaian dan Evaluasi',
                'password' => Hash::make('password'),
                'department_id' => 5,
                'position_id' => 3,
                'atasan_id' => $kaban->id,
            ]
        );
        $kabid3->assignRole('Kepala Bidang');

        // Kepala Bidang Pengembangan SDM
        $kabid4 = User::firstOrCreate(
            ['email' => 'kabid.psdm@bkpsdm.go.id'],
            [
                'nip' => '198301012008031006',
                'name' => 'Kepala Bidang Pengembangan SDM',
                'password' => Hash::make('password'),
                'department_id' => 6,
                'position_id' => 3,
                'atasan_id' => $kaban->id,
            ]
        );
        $kabid4->assignRole('Kepala Bidang');
    }
}
