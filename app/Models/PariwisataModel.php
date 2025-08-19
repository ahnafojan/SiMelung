<?php

namespace App\Models;

use CodeIgniter\Model;

class PariwisataModel extends Model
{
    protected $table            = 'pariwisata';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['nama', 'lokasi', 'deskripsi', 'gambar', 'created_at', 'updated_at'];
    protected $useTimestamps    = true;
}
