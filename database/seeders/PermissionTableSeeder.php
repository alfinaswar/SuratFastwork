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
            'dashboard-view',

            // Draft Surat
            'draft-create',
            'draft-edit',
            'draft-delete',
            'draft-view',

            // Verifikasi Surat
            'verify-view',
            'verify-approve',
            'verify-reject',

            // Persetujuan Surat
            'approval-view',
            'approval-approve',
            'approval-reject',

            // Surat Terkirim
            'sent-view',

            // Surat Masuk
            'inbox-view',
            'inbox-receive',
            'inbox-read',

            // Surat CC
            'cc-view',

            // Surat BC
            'bc-view',

            // Manajemen Pengguna
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',

            // Template Surat
            'template-list',
            'template-create',
            'template-edit',
            'template-delete',

            // Pengaturan Sistem
            'settings-view',
            'settings-edit',

            // Log Aktivitas
            'log-view',

            // Laporan
            'report-view',
            'report-generate',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
