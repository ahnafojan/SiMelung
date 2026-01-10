<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\HargaJenisKopiModel; // Tambahkan jika ingin mengakses model harga secara langsung

class JenisPohonModel extends Model
{
    protected $table = 'jenis_pohon';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama_jenis', 'created_at', 'updated_at'];
    protected $useTimestamps = true;

    // Tambahkan method ini
    public function getJenisPohonWithLatestPrice()
    {
        $db = \Config\Database::connect();

        $subQuery = $db->table('harga_jenis_kopi')
            ->select('jenis_pohon_id, MAX(tanggal_berlaku) as latest_date')
            ->groupBy('jenis_pohon_id');

        $builder = $db->table('jenis_pohon jp')
            ->select('jp.id, jp.nama_jenis, hjk.id AS harga_id, hjk.harga_beli_per_kg AS harga_beli_saat_ini, hjk.harga_jual_per_kg AS harga_jual_saat_ini, hjk.tanggal_berlaku')
            ->join('(' . $subQuery->getCompiledSelect() . ') sq', 'jp.id = sq.jenis_pohon_id', 'left')
            ->join('harga_jenis_kopi hjk', 'jp.id = hjk.jenis_pohon_id AND hjk.tanggal_berlaku = sq.latest_date', 'left')
            ->orderBy('jp.id', 'ASC');

        return $builder->get()->getResultArray();
    }
}
