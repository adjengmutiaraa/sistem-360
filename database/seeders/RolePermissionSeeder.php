<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Define roles
        $roles = [
            'Super Admin',
            'Admin BKPSDM',
            'Kepala Badan',
            'Sekretaris',
            'Kepala Bidang',
            'Kepala Subbagian',
            'Pejabat Penilai',
            'ASN',
            'Administrator Sistem'
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
    }
}
