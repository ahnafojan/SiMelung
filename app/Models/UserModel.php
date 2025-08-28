<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['username', 'password', 'role'];

    public function getUserByUsername($username)
    {
<<<<<<< HEAD
        return $this->where('username', $username)->first();
=======
        // Ambil data user dulu
        $user = $this->where('username', $username)->first();
        
        if ($user) {
            // Ambil roles terpisah
            $roleBuilder = $this->db->table('user_roles');
            $roles = $roleBuilder->select('role')
                                ->where('user_id', $user['id'])
                                ->get()
                                ->getResultArray();
            
            $user['roles'] = array_column($roles, 'role');
        }
        
        return $user;
>>>>>>> f97281d (Aset Pariwisata)
    }
}