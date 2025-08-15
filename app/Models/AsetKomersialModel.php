<?php

namespace App\Models;

use CodeIgniter\Model;

class AsetKomersialModel extends Model
{
    protected $table      = 'master_aset';
    protected $primaryKey = 'id_aset';

    protected $allowedFields = [
        'nama_aset',
        'kode_aset',
        'nup',
        'tahun_perolehan',
        'merk_type',
        'nilai_perolehan',
        'keterangan'
    ];
}
