<?php

namespace App\Models;

use CodeIgniter\Model;

class KopiMasukModel extends Model
{
    protected $table = 'kopi_masuk';
    protected $primaryKey = 'id';
    protected $allowedFields = ['petani_user_id', 'jumlah', 'tanggal', 'keterangan'];
    protected $useTimestamps = true;
}
