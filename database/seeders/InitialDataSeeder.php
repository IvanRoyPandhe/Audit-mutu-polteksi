<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class InitialDataSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('role')->insert([
            ['role_name' => 'Admin', 'description' => 'Administrator sistem'],
            ['role_name' => 'Auditor', 'description' => 'Auditor mutu'],
            ['role_name' => 'Pimpinan Unit', 'description' => 'Pimpinan unit kerja'],
            ['role_name' => 'Direktur', 'description' => 'Direktur Kampus'],
        ]);

        DB::table('unit')->insert([
            ['nama_unit' => 'admin', 'tipe_unit' => 'admin', 'pimpinan' => 'Dr. Ahmad'],
            ['nama_unit' => 'Direktur', 'tipe_unit' => 'Direktur', 'pimpinan' => 'Dr. Budi'],
            ['nama_unit' => 'Biro Akademik', 'tipe_unit' => 'Biro', 'pimpinan' => 'Dr. Citra'],
            ['nama_unit' => 'Kemahasiswaan', 'tipe_unit' => 'Biro', 'pimpinan' => 'Dr. Dewi'],
            ['nama_unit' => 'PMB', 'tipe_unit' => 'Biro', 'pimpinan' => 'Dr. Eko'],
        ]);

        DB::table('users')->insert([
            [
                'name' => 'Admin System',
                'email' => 'admin@ipass.ac.id',
                'password_hash' => Hash::make('password'),
                'role_id' => 1,
                'unit_id' => 1,
                'status' => 'Aktif',
            ],
            [
                'name' => 'Staff Kemahasiswaan',
                'email' => 'kemahasiswaan@ipass.ac.id',
                'password_hash' => Hash::make('password'),
                'role_id' => 3,
                'unit_id' => 4,
                'status' => 'Aktif',
            ],
            [
                'name' => 'Staff PMB',
                'email' => 'pmb@ipass.ac.id',
                'password_hash' => Hash::make('password'),
                'role_id' => 3,
                'unit_id' => 5,
                'status' => 'Aktif',
            ],
            [
                'name' => 'Staff Akademik',
                'email' => 'akademik@ipass.ac.id',
                'password_hash' => Hash::make('password'),
                'role_id' => 3,
                'unit_id' => 3,
                'status' => 'Aktif',
            ],
            [
                'name' => 'Direktur',
                'email' => 'direktur@ipass.ac.id',
                'password_hash' => Hash::make('password'),
                'role_id' => 4,
                'unit_id' => 2,
                'status' => 'Aktif',
            ],
        ]);
    }
}
