<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PariwisataSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama'       => 'Kolam Renang Sawah Indah',
                'lokasi'     => 'Desa Melung, Banyumas, Jawa Tengah',
                'deskripsi'  => 'Kolam renang unik yang terletak di tengah hamparan sawah hijau, menawarkan suasana sejuk dan asri.',
                'gambar'     => 'kolam_renang_sawah.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'       => 'Bukit Panorama Melung',
                'lokasi'     => 'Desa Melung, Banyumas, Jawa Tengah',
                'deskripsi'  => 'Bukit dengan pemandangan 360 derajat, cocok untuk menikmati matahari terbit dan terbenam.',
                'gambar'     => 'bukit_panorama.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'       => 'Air Terjun Curug Indah',
                'lokasi'     => 'Desa Melung, Banyumas, Jawa Tengah',
                'deskripsi'  => 'Air terjun alami yang masih terjaga kebersihannya, dikelilingi pepohonan rindang.',
                'gambar'     => 'curug_indah.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Masukkan data ke tabel pariwisata
        $this->db->table('pariwisata')->insertBatch($data);
    }
}
