<?php

namespace App\Models;

use CodeIgniter\Model;

class KopiKeluarModel extends Model
{
    protected $table = 'kopi_keluar';
    protected $primaryKey = 'id';
    protected $allowedFields = ['tujuan', 'jumlah', 'tanggal', 'keterangan'];
    protected $useTimestamps = true;
}
