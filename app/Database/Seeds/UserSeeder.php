<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Data users
        $users = [
            [
                'username' => 'desa',
                'password' => password_hash('desa123', PASSWORD_DEFAULT),
                'role'     => 'desa',
            ],
            [
                'username' => 'bumdes',
                'password' => password_hash('bumdes123', PASSWORD_DEFAULT),
                'role'     => 'bumdes',
            ],
            [
                'username' => 'keuangan',
                'password' => password_hash('keuangan123', PASSWORD_DEFAULT),
                'role'     => 'keuangan',
            ],
            [
                'username' => 'umkm',
                'password' => password_hash('umkm123', PASSWORD_DEFAULT),
                'role'     => 'umkm',
            ],
            [
                'username' => 'pariwisata',
                'password' => password_hash('pariwisata123', PASSWORD_DEFAULT),
                'role'     => 'pariwisata',
            ],
            [
                'username' => 'broker',
                'password' => password_hash('broker123', PASSWORD_DEFAULT),
                'role'     => 'broker',
            ],
            [
                'username' => 'syarif',
                'password' => password_hash('syarif123', PASSWORD_DEFAULT),
                'role'     => 'bumdes',
            ],
            [
                'username' => 'alif',
                'password' => password_hash('alif123', PASSWORD_DEFAULT),
                'role'     => 'desa',
            ],
            [
                'username' => 'mamang',
                'password' => password_hash('mamang123', PASSWORD_DEFAULT),
                'role'     => 'pariwisata',
            ],
        ];

        // Insert users
        $this->db->table('users')->insertBatch($users);

        // Jika kamu punya tabel user_roles terpisah, bisa tambahkan data seperti ini:
        $roles = [
            ['user_id' => 7, 'role' => 'bumdes'],
            ['user_id' => 7, 'role' => 'komersial'],
            ['user_id' => 8, 'role' => 'desa'],
            ['user_id' => 9, 'role' => 'pariwisata'],
        ];

        $this->db->table('user_roles')->insertBatch($roles);
    }
}
