<?php

namespace App\Models;

use CodeIgniter\Model;

class KopiKeluarModel extends Model
{
    protected $table = 'kopi_keluar';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'stok_kopi_id',   // relasi ke tabel stok
        'tujuan',
        'jumlah',
        'tanggal',
        'keterangan'
    ];
    protected $useTimestamps = true;
}
