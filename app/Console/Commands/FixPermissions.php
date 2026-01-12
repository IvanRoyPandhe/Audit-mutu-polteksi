<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixPermissions extends Command
{
    protected $signature = 'fix:permissions';
    protected $description = 'Fix role permissions for production';

    public function handle()
    {
        // Set permissions for Admin role (full access)
        DB::table('role')->where('role_id', 1)->update([
            'permissions' => json_encode([
                'dashboard',
                'standar-mutu',
                'kriteria',
                'indikator-kinerja',
                'approval',
                'penetapan',
                'pelaksanaan',
                'evaluasi',
                'laporan',
                'direktur-review',
                'users',
                'roles',
                'units',
                'unit-auditors',
                'buku-kebijakan'
            ])
        ]);

        // Set permissions for Auditor role
        DB::table('role')->where('role_id', 2)->update([
            'permissions' => json_encode([
                'dashboard',
                'evaluasi',
                'pelaksanaan',
                'laporan'
            ])
        ]);

        // Set permissions for Unit Kerja role  
        DB::table('role')->where('role_id', 3)->update([
            'permissions' => json_encode([
                'dashboard',
                'standar-mutu',
                'kriteria',
                'indikator-kinerja',
                'penetapan',
                'pelaksanaan',
                'evaluasi',
                'laporan'
            ])
        ]);

        // Set permissions for SPI role
        DB::table('role')->where('role_id', 4)->update([
            'permissions' => json_encode([
                'dashboard',
                'spi-monitoring'
            ])
        ]);

        $this->info('Default permissions have been set for all roles.');
        $this->info('Admin: Full access');
        $this->info('Auditor: Dashboard, Evaluasi, Pelaksanaan, Laporan');
        $this->info('Unit Kerja: Dashboard, Standar Mutu, Kriteria, Indikator, Penetapan, Pelaksanaan, Evaluasi, Laporan');
        $this->info('SPI: Dashboard, SPI Monitoring');
        
        return 0;
    }
}