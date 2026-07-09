<?php

namespace App\Models;

use CodeIgniter\Model;

class PermissionRequestModel extends Model
{
    protected $table = 'permission_requests';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'requester_id',
        'target_id',
        'target_type',
        'action_type',
        'status',
        'request_notes',
        'response_notes',
        'responder_id',
        'responded_at',
        'expires_at',

        // ✅ tambahkan ini
        'requested_jenis_pohon_id',
        'requested_harga_beli_per_kg',
        'requested_harga_jual_per_kg',
        'requested_tanggal_berlaku',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // Tidak ada updated_at
}
