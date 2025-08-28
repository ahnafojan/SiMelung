<?php

namespace App\Models;

use CodeIgniter\Model;

class PetaniModel extends Model
{

    protected $table = 'petani';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id',
        'nama',
        'alamat',
        'no_hp',
        'usia',
        'tempat_lahir',
        'tanggal_lahir',
        'foto',
        'created_at',
        'updated_at'
    ];
    protected $useTimestamps = true;
}
