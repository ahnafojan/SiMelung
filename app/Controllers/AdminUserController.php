<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class AdminUserController extends Controller
{
    public function index()
    {
        $db = \Config\Database::connect();
        $users = $db->table('users')
            ->select('users.id, users.username,users.password, GROUP_CONCAT(user_roles.role) as roles')
            ->join('user_roles', 'user_roles.user_id = users.id', 'left')
            ->groupBy('users.id')
            ->get()->getResultArray();
        $data['breadcrumbs'] = [
            [
                'title' => 'Dashboard',
                'url'   => site_url('dashboard/dashboard_bumdes'), // Sesuaikan URL dashboard Anda
                'icon'  => 'fas fa-fw fa-tachometer-alt'
            ],
            [
                'title' => 'Manajemen User Admin',
                'url'   => '#',
                'icon'  => 'fas fa-fw fa-users fa-lg' // Ikon untuk stok atau barang masuk
            ]
        ];
        $data['users'] = $users;
        return view('bumdes/akunuser/index', $data);
    }

    public function create()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $roles    = $this->request->getPost('roles'); // array

        $userModel = new UserModel();

        // Simpan user baru
        $userId = $userModel->insert([
            'username' => $username,
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ]);

        // Simpan role terkait
        if (!empty($roles)) {
            $roleData = [];
            foreach ($roles as $role) {
                $roleData[] = [
                    'user_id' => $userId,
                    'role'    => $role
                ];
            }
            $db = \Config\Database::connect();
            $db->table('user_roles')->insertBatch($roleData);
        }

        // Redirect ke halaman form+list dengan pesan sukses
        return redirect()->to('/admin-user')->with('success', 'User admin berhasil ditambahkan!');
    }
    // method edit user (proses update)
    public function edit()
    {
        $userId   = $this->request->getPost('user_id');
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $newRoles = $this->request->getPost('roles') ?? [];

        $userModel = new UserModel();
        $db = \Config\Database::connect();

        // Ambil role LAMA dari database
        $existingRolesQuery = $db->table('user_roles')->where('user_id', $userId)->get()->getResultArray();
        $existingRoles = array_column($existingRolesQuery, 'role');

        // Tentukan role yang dilindungi
        $protectedRolesList = ['bumdes', 'desa', 'kepala desa'];
        $protectedRoles = array_intersect($existingRoles, $protectedRolesList);

        // Gabungkan role yang dilindungi dengan role BARU dari form
        $finalRoles = array_unique(array_merge($protectedRoles, $newRoles));

        // ===================================================================
        // PERBAIKAN KRITIS: PAKSA BATAS MAKSIMAL 2 ROLE DI SISI SERVER
        // ===================================================================
        if (count($finalRoles) > 2) {
            $finalRoles = array_slice($finalRoles, 0, 2); // Ambil hanya 2 role pertama
        }

        // Update username dan password jika ada
        $dataUpdate = ['username' => $username];
        if (!empty($password)) {
            $dataUpdate['password'] = password_hash($password, PASSWORD_DEFAULT);
        }
        $userModel->update($userId, $dataUpdate);

        // Hapus semua role lama
        $db->table('user_roles')->where('user_id', $userId)->delete();

        // Simpan role yang sudah valid dan terbatas
        if (!empty($finalRoles)) {
            $roleData = [];
            foreach ($finalRoles as $role) {
                $roleData[] = [
                    'user_id' => $userId,
                    'role'    => $role,
                ];
            }
            $db->table('user_roles')->insertBatch($roleData);
        }

        return redirect()->to('/admin-user')->with('success', 'User berhasil diperbarui.');
    }

    // method delete user
    public function delete()
    {
        $userId = $this->request->getPost('user_id');
        $userModel = new UserModel();
        $db = \Config\Database::connect();

        // Hapus role dulu
        $db->table('user_roles')->where('user_id', $userId)->delete();

        // Hapus user
        $userModel->delete($userId);

        return redirect()->to('/admin-user')->with('success', 'User berhasil dihapus.');
    }
}
