<?php

namespace App\Models;

use CodeIgniter\Model;

class JenisPohonModel extends Model
{
    protected $table = 'jenis_pohon';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama_jenis', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
}
