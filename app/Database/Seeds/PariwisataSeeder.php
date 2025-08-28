<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PariwisataSeeder extends Seeder
{
    public function run()
    {
        // Data tabel pariwisata
        $pariwisataData = [
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

        // Insert data ke tabel pariwisata
        $this->db->table('pariwisata')->insertBatch($pariwisataData);

        // Data tabel aset_pariwisata
        $asetData = [
            [
                'nama_pariwisata'   => 'Air Terjun Melung',
                'nama_aset'         => 'Gazebo',
                'kode_aset'         => 'AS001',
                'nup'               => '001',
                'tahun_perolehan'   => 2022,
                'nilai_perolehan'   => 15000000,
                'keterangan'        => 'Gazebo untuk pengunjung beristirahat',
                'metode_pengadaan'  => 'Pembelian',
                'sumber_pengadaan'  => 'Dana Desa',
                'foto_aset'         => 'gazebo.jpg',
            ],
            [
                'nama_pariwisata'   => 'Bukit Melung',
                'nama_aset'         => 'Spot Foto',
                'kode_aset'         => 'AS002',
                'nup'               => '002',
                'tahun_perolehan'   => 2021,
                'nilai_perolehan'   => 5000000,
                'keterangan'        => 'Dek kayu untuk spot foto',
                'metode_pengadaan'  => 'Swadaya Masyarakat',
                'sumber_pengadaan'  => 'Gotong Royong',
                'foto_aset'         => 'spotfoto.jpg',
            ],
            [
                'nama_pariwisata'   => 'Kolam Renang Desa',
                'nama_aset'         => 'Kursi & Meja',
                'kode_aset'         => 'AS003',
                'nup'               => '003',
                'tahun_perolehan'   => 2023,
                'nilai_perolehan'   => 8000000,
                'keterangan'        => 'Fasilitas kursi untuk pengunjung kolam',
                'metode_pengadaan'  => 'Hibah',
                'sumber_pengadaan'  => 'CSR',
                'foto_aset'         => 'kursi.jpg',
            ],
        ];

        // Insert data ke tabel aset_pariwisata
        $this->db->table('aset_pariwisata')->insertBatch($asetData);
    }
}
