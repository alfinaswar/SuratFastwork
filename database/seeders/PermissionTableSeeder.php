<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Dashboard
            'dashboard',

            // Draft Surat
            'draft',


            // Verifikasi Surat
            'verify',


            // Persetujuan Surat
            'approval',


            // Surat Terkirim
            'sent-view',

            // Surat Masuk
            'inbox-view',


            // Pengaturan Website
            'pengaturan-website',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
