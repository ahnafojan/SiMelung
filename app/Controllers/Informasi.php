<?php

namespace App\Controllers;

use App\Models\UmkmModel;

class Informasi extends BaseController
{
    public function index()
    {
        $umkmModel = new UmkmModel();
        $data = [
            'umkm'        => $umkmModel->findAll(),
            'csrf_token'  => csrf_token(),
            'csrf_hash'   => csrf_hash(),
        ];

        return view('admin_umkm/informasi/informasi', $data);
    }

    /**
     * Toggle status publikasi UMKM (is_published: 0/1)
     */
    public function togglePublish($id = null)
    {
        // Hapus baris ini:
        // if ($this->request->getMethod() !== 'post') { ... }

        // Validasi ID
        if (!is_numeric($id) || $id <= 0) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'ID UMKM tidak valid.'
            ]);
        }

        // Ambil data dari POST
        $newStatus = (int) $this->request->getPost('is_published');
        if (!in_array($newStatus, [0, 1], true)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Status publikasi tidak valid.'
            ]);
        }

        $umkmModel = new UmkmModel();

        // Cek apakah data ada
        if (!$umkmModel->find($id)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Data UMKM tidak ditemukan.'
            ]);
        }

        // Update status
        $updated = $umkmModel->update($id, ['is_published' => $newStatus]);

        if ($updated === false) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal memperbarui status. Silakan coba lagi.'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Status publikasi berhasil diperbarui.'
        ]);
    }
}
