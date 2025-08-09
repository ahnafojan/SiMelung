<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class AuthController extends BaseController
{
    protected $session;

    public function __construct()
    {
        // Muat library helper dan session
        helper(['form']);
        $this->session = session();
    }

    public function login()
    {
        // Tampilkan halaman login
        return view('login_view');
    }

    public function processLogin()
    {
        // Validasi input
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
            // Jika login berhasil, set session
            $sessionData = [
                'user_id'    => $user['id'],
                'username'   => $user['username'],
                'role'       => $user['role'],
                'logged_in'  => TRUE
            ];
            $this->session->set($sessionData);

            switch ($user['role']) {
                case 'desa':
                    return redirect()->to('/desa/dashboard');
                case 'bumdes':
                    return redirect()->to('/bumdes/dashboard');
                case 'keuangan':
                    return redirect()->to('/keuangan/dashboard');
                case 'umkm':
                    return redirect()->to('/umkm/dashboard');
                case 'broker':
                    return redirect()->to('/broker/dashboard');
                case 'pariwisata':
                    return redirect()->to('/pariwisata/dashboard');
                default:
                    return redirect()->to('/login');
            }
        } else {
            // Jika login gagal
            $this->session->setFlashdata('error', 'Username atau Password salah.');
            return redirect()->to('/login');
        }
    }

    public function logout()
    {
        // Hapus session dan redirect ke halaman login
        $this->session->destroy();
        return redirect()->to('/login');
    }
}
