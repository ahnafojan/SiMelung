<?php

namespace App\Models;

use CodeIgniter\Model;

class PetaniPohonModel extends Model
{
    protected $table = 'petani_pohon';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id',
        'jenis_pohon_id',
        'luas_lahan',
        'jumlah_pohon',
        'created_at',
        'updated_at'
    ];
    protected $useTimestamps = true;
}
