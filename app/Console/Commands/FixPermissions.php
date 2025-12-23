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
        // Set permissions for Auditor role
        DB::table('role')->where('role_id', 2)->update([
            'permissions' => json_encode(['dashboard', 'evaluasi'])
        ]);

        // Set permissions for Unit Kerja role  
        DB::table('role')->where('role_id', 3)->update([
            'permissions' => json_encode(['dashboard', 'penetapan', 'pelaksanaan', 'evaluasi'])
        ]);

        $this->info('Default permissions have been set for all roles.');
        return 0;
    }
}