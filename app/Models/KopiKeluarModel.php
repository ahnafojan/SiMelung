<?php

namespace App\Models;

use CodeIgniter\Model;

class KopiKeluarModel extends Model
{
    protected $table = 'kopi_keluar';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'stok_kopi_id',  // relasi ke tabel stok
        'tujuan',
        'jumlah',
        'tanggal',
        'keterangan'
    ];
    protected $useTimestamps = true;

    /**
     * Mengambil semua data kopi keluar dengan relasi dan pagination.
     *
     * @param int $perPage Jumlah data per halaman.
     * @return array
     */
    public function getAllWithPagination($perPage)
    {
        return $this->select('kopi_keluar.*, jenis_pohon.nama_jenis as nama_pohon, stok_kopi.stok as sisa_stok_jenis')
            ->join('stok_kopi', 'kopi_keluar.stok_kopi_id = stok_kopi.id', 'left')
            ->join('jenis_pohon', 'jenis_pohon.id = stok_kopi.jenis_pohon_id', 'left')
            ->orderBy('kopi_keluar.tanggal', 'DESC')
            ->orderBy('kopi_keluar.id', 'DESC')
            ->paginate($perPage, 'kopikeluar'); // Memberi nama grup pager 'kopikeluar'
    }
}
