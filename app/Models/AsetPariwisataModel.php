<?php

namespace App\Models;

use CodeIgniter\Model;

class AsetPariwisataModel extends Model
{
    protected $table = 'aset_pariwisata';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nama_pariwisata',
        'nama_aset',
        'kode_aset',
        'nup',
        'tahun_perolehan',
        'nilai_perolehan',
        'keterangan',
        'metode_pengadaan',
        'sumber_pengadaan',
        'foto_aset',
        'created_at'
    ];
    protected $useTimestamps = true;
}
