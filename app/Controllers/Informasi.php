<?php

namespace App\Controllers;

use App\Models\UmkmModel;

class Informasi extends BaseController
{
    // Menggunakan helper untuk mendapatkan instance Request
    protected $request;

    public function __construct()
    {
        $this->request = \Config\Services::request();
    }
    
    // Metode untuk menampilkan daftar UMKM
    public function index()
    {
        $umkmModel = new UmkmModel();
        // Pastikan Anda juga mengirimkan token CSRF ke view jika diperlukan
        $data['csrf_token'] = csrf_token();
        $data['csrf_hash'] = csrf_hash();
        $data['umkm'] = $umkmModel->findAll();

        return view('admin_umkm/informasi/informasi', $data);
    }

    // Metode untuk mengubah status publikasi via AJAX
    public function togglePublish($id = null)
    {
        // 1. Pastikan ini adalah permintaan AJAX POST
        if (!$this->request->isAJAX() || $this->request->getMethod() !== 'post') {
            return $this->response->setStatusCode(405)->setJSON(['status' => 'error', 'message' => 'Invalid Request Method.']);
        }

        // 2. Pastikan ID tersedia
        if ($id === null) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ID UMKM tidak valid.']);
        }
        
        $umkmModel = new UmkmModel();
        
        // 3. Ambil data status yang dikirim dari frontend
        // Gunakan getPost() untuk mengambil data dari body POST
        $newStatus = $this->request->getPost('is_published');
        
        $data = [
            'is_published' => $newStatus
        ];
        
        // 4. Update status di database
        try {
            // update($primaryKey, $data)
            $umkmModel->update($id, $data);
            
            // Berhasil: kembalikan respons sukses
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Status UMKM berhasil diubah menjadi ' . ($newStatus == 1 ? 'Ditampilkan' : 'Disembunyikan') . '.'
            ]);
        } catch (\Exception $e) {
            // Gagal: kembalikan respons error
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal mengubah status publikasi: ' . $e->getMessage()
            ]);
        }
    }
}
