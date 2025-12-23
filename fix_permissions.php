<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

// Set default permissions for roles
DB::table('role')->where('role_id', 2)->update([
    'permissions' => json_encode(['dashboard', 'evaluasi'])
]);

DB::table('role')->where('role_id', 3)->update([
    'permissions' => json_encode(['dashboard', 'penetapan', 'pelaksanaan', 'evaluasi'])
]);

echo "Default permissions set for roles.\n";