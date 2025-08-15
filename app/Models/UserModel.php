<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['username', 'password'];

    public function getUserByUsername($username)
    {
        $builder = $this->db->table('users');
        $builder->select('users.id, users.username, users.password, GROUP_CONCAT(user_roles.role) as roles');
        $builder->join('user_roles', 'user_roles.user_id = users.id', 'left');
        $builder->where('users.username', $username);
        $builder->groupBy('users.id');

        $user = $builder->get()->getRowArray();
        if ($user && isset($user['roles'])) {
            $user['roles'] = explode(',', $user['roles']);
        }
        return $user;
    }
}
