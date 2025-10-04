<?php namespace App\Models;

use CodeIgniter\Model;

class UmkmModel extends Model
{
    // Konfigurasi Dasar
    protected $table      = 'umkm';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType     = 'array';

    protected $allowedFields = [
        'nama_umkm', 
        'pemilik', 
        'kategori', 
        'deskripsi', 
        'alamat', 
        'gmaps_url', 
        'kontak', 
        'foto_umkm',
        'is_published' 
    ];

    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    

    
    
    protected $validationRules = [
        'nama_umkm' => 'required|min_length[3]|max_length[255]',
        'kategori' => 'required',
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;
    
}