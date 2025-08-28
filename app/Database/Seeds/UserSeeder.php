<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $password = password_hash('123456', PASSWORD_DEFAULT);

        // Insert User
        $this->db->table('users')->insertBatch([
            ['id' => 2, 'username' => 'alif',   'password' => $password],
        ]);

        // Insert Roles
        $this->db->table('user_roles')->insertBatch([
            ['user_id' => 2, 'role' => 'desa'],
        ]);
    }
}
