<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class AuthController extends BaseController
{
    protected $session;

    public function __construct()
    {
        helper(['form']);
        $this->session = session();
    }

    public function login()
    {
        return view('login_view');
    }

    public function processLogin()
    {
        $rules = [
            'username' => 'required',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/login')->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $userModel->getUserByUsername($username);

        if ($user && password_verify($password, $user['password'])) {
            // Ambil role pertama dari array roles
            $defaultRole = $user['roles'][0] ?? null;

            if ($defaultRole) {
                $this->setUserSession($user, $defaultRole);
                session()->regenerate(); // pastikan ID session baru
                session_write_close(); // simpan session
                return redirect()->to('/dashboard/dashboard_' . $defaultRole);
            } else {
                $this->session->setFlashdata('error', 'Role pengguna tidak ditemukan.');
                return redirect()->to('/login');
            }
        } else {
            $this->session->setFlashdata('error', 'Username atau Password salah.');
            return redirect()->to('/login');
        }
    }

    private function setUserSession($user, $role)
    {
        $this->session->set([
            'user_id' => $user['user_id'] ?? $user['id'],
            'username' => $user['username'],
            'role' => $role,
            'logged_in' => true
        ]);
    }

    public function switchRole($role)
    {
        $userId = session()->get('user_id');

        // Cek apakah role ini memang dimiliki user
        $db = \Config\Database::connect();
        $check = $db->table('user_roles')
            ->where('user_id', $userId)
            ->where('role', $role)
            ->countAllResults();

        if ($check > 0) {
            session()->set('role', $role);
            session()->setFlashdata('message', 'Role berhasil diganti menjadi: ' . ucfirst($role));

            return redirect()->to('/dashboard/dashboard_' . strtolower($role));
        } else {
            session()->setFlashdata('error', 'Role tidak valid.');
            return redirect()->back();
        }
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/login')->with('message', 'Anda berhasil logout.');
    }
}
