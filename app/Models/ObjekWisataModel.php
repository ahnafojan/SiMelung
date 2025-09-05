<?php

namespace App\Models;

use CodeIgniter\Model;

class ObjekWisataModel extends Model
{
    protected $table            = 'objek_wisata';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields    = [
        'id', // <-- TAMBAHKAN BARIS INI
        'nama_wisata',
        'lokasi',
        'deskripsi',
    ];

    // ====================================================================
    // PERUBAHAN DI SINI: Fitur Timestamps Dinonaktifkan
    // ====================================================================
    // Diubah dari true menjadi false karena tabel di database Anda
    // tidak memiliki kolom 'created_at' dan 'updated_at'.
    protected $useTimestamps = false;
    // ====================================================================

}
