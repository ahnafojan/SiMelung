<?php

namespace App\Models;

use CodeIgniter\Model;

class AsetKomersialModel extends Model
{
    protected $table = 'master_aset';
    protected $primaryKey = 'id_aset';

    protected $allowedFields = [
        'nama_aset',
        'kode_aset',
        'nup',
        'tahun_perolehan',
        'merk_type',
        'nilai_perolehan',
        'keterangan',
        'metode_pengadaan',
        'sumber_pengadaan',
        'foto'
    ];

    /**
     * Mengambil daftar tahun perolehan yang unik dari database.
     * Fungsi ini digunakan untuk mengisi dropdown filter di view.
     * @return array Daftar tahun.
     */
    public function getTahunPerolehan()
    {
        return $this->select('tahun_perolehan')
            ->distinct()
            ->orderBy('tahun_perolehan', 'DESC')
            ->findAll();
    }

    /**
     * Mengambil SEMUA data aset dengan filter tahun untuk keperluan ekspor.
     * Fungsi ini mengabaikan paginasi.
     * @param int|string|null $tahun Filter berdasarkan tahun perolehan.
     * @return array Semua data aset yang cocok.
     */
    public function getAllAset($tahun = null)
    {
        $builder = $this->table($this->table);

        // Terapkan filter jika ada tahun yang dipilih
        if ($tahun && $tahun != 'semua') {
            $builder->where('tahun_perolehan', $tahun);
        }

        // Urutkan berdasarkan tahun terbaru
        $builder->orderBy('tahun_perolehan', 'DESC');

        // Kembalikan semua hasil tanpa paginasi
        return $builder->findAll();
    }
}
