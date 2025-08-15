<?php

namespace App\Models;

use CodeIgniter\Model;

class UmkmModel extends Model
{
    protected $table = 'umkm';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama_umkm', 'deskripsi', 'pemilik', 'alamat', 'kontak'];
    protected $useTimestamps = true;
}
