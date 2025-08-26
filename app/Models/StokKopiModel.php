<?php

namespace App\Models;

use CodeIgniter\Model;

class StokKopiModel extends Model
{
    protected $table = 'stok_kopi';
    protected $primaryKey = 'id';
    // allowedFields Anda sudah benar, tidak perlu diubah.
    protected $allowedFields = ['petani_id', 'jenis_pohon_id', 'stok', 'created_at', 'updated_at'];
    protected $useTimestamps = true;

    /**
     * Mengambil data stok beserta relasi nama petani dan nama jenis pohon.
     * FUNGSI INI TELAH DIPERBAIKI.
     */
    public function getWithRelations()
    {
        // PERBAIKAN:
        // 1. Join ke tabel 'petani', bukan 'users'.
        // 2. Gunakan 'petani.user_id' sebagai kunci join.
        // 3. Ambil 'petani.nama' sebagai 'nama_petani'.
        // 4. Ambil 'jenis_pohon.nama_jenis' agar konsisten dengan model lain.
        return $this->select('
                        stok_kopi.*, 
                        petani.nama as nama_petani, 
                        jenis_pohon.nama_jenis as nama_pohon
                    ')
            ->join('petani', 'petani.user_id = stok_kopi.petani_id', 'left')
            ->join('jenis_pohon', 'jenis_pohon.id = stok_kopi.jenis_pohon_id', 'left')
            ->findAll();
    }

    public function getGlobalStokByJenis()
    {
        return $this->select('jenis_pohon.id, jenis_pohon.nama_jenis as nama_pohon, SUM(stok_kopi.stok) as stok')
            ->join('jenis_pohon', 'jenis_pohon.id = stok_kopi.jenis_pohon_id')
            ->groupBy('jenis_pohon.id, jenis_pohon.nama_jenis')
            ->findAll();
    }

    public function getWithJenis()
    {
        return $this->select('stok_kopi.id, jenis_pohon.nama_jenis as nama_pohon, SUM(stok_kopi.stok) as total_stok')
            ->join('jenis_pohon', 'jenis_pohon.id = stok_kopi.jenis_pohon_id')
            ->groupBy('jenis_pohon.id, jenis_pohon.nama_jenis') // Sebaiknya group by nama_jenis juga
            ->findAll();
    }
}
