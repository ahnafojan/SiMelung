<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\StokKopiModel;


class KopiMasukModel extends Model
{
    protected $table = 'kopi_masuk';
    protected $primaryKey = 'id';
    protected $allowedFields = ['petani_user_id', 'petani_pohon_id', 'jumlah', 'tanggal', 'keterangan'];
    protected $useTimestamps = true;
    public function getAll()
    {
        return $this->select('
            kopi_masuk.id,
            kopi_masuk.petani_user_id,
            kopi_masuk.petani_pohon_id,
            kopi_masuk.jumlah,
            kopi_masuk.tanggal,
            kopi_masuk.keterangan,
            petani.nama as nama_petani,
            jenis_pohon.nama_jenis as nama_pohon,
            stok_kopi.stok as stok
        ')
            ->join('petani_pohon', 'petani_pohon.id = kopi_masuk.petani_pohon_id', 'left')
            ->join('petani', 'petani.user_id = kopi_masuk.petani_user_id', 'left')
            ->join('jenis_pohon', 'jenis_pohon.id = petani_pohon.jenis_pohon_id', 'left')
            ->join('stok_kopi', 'stok_kopi.petani_id = kopi_masuk.petani_user_id 
                          AND stok_kopi.jenis_pohon_id = petani_pohon.jenis_pohon_id', 'left')
            ->findAll();
    }
}
