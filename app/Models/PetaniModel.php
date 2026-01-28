<?php

namespace App\Models;

use CodeIgniter\Model;

class PetaniModel extends Model
{
    protected $table      = 'petani';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nik',
        'user_id',
        'nama',
        'alamat',
        'no_hp',
        'foto',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;

    // Opsional tapi sangat disarankan
    protected $validationRules = [
        'nik'   => 'required|is_unique[petani.nik,id,{id}]',
        'nama'  => 'required|min_length[3]',
        'alamat' => 'required',
        'no_hp' => 'required|min_length[10]'
    ];

    protected $validationMessages = [
        'nik' => [
            'required'  => 'NIK wajib diisi',
            'is_unique' => 'NIK sudah terdaftar'
        ]
    ];
}
