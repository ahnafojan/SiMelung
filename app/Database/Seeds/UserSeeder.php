<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'username' => 'desa',
                'password' => password_hash('desa123', PASSWORD_DEFAULT),
                'role' => 'desa',
            ],
            [
                'username' => 'bumdes',
                'password' => password_hash('bumdes123', PASSWORD_DEFAULT),
                'role' => 'bumdes',
            ],
            [
                'username' => 'keuangan',
                'password' => password_hash('keuangan123', PASSWORD_DEFAULT),
                'role' => 'keuangan',
            ],
            [
                'username' => 'umkm',
                'password' => password_hash('umkm123', PASSWORD_DEFAULT),
                'role' => 'umkm',
            ],
            [
                'username' => 'pariwisata',
                'password' => password_hash('pariwisata123', PASSWORD_DEFAULT),
                'role' => 'pariwisata',
            ],
            [
                'username' => 'broker',
                'password' => password_hash('broker123', PASSWORD_DEFAULT),
                'role' => 'broker',
            ],
        ];

        // Insert data into the users table
        $this->db->table('users')->insertBatch($data);
    }
}
