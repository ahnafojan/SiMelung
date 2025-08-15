<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PetaniSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'user_id' => 'P001',
                'nama'    => 'Pak Ahmad',
                'alamat'  => 'Jl. Mawar No. 10',
                'no_hp'   => '081234567890',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'user_id' => 'P002',
                'nama'    => 'Bu Sari',
                'alamat'  => 'Jl. Melati No. 5',
                'no_hp'   => '081298765432',
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('petani')->insertBatch($data);
    }
}
