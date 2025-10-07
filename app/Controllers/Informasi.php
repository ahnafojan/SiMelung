<?php

namespace App\Controllers;

use App\Models\UmkmModel;

class Informasi extends BaseController
{
    public function index()
    {
        $umkmModel = new UmkmModel();
        $data['umkm'] = $umkmModel->findAll();
        return view('admin_umkm/informasi/informasi', $data);
    }

    // 🔥 Tambahkan method ini untuk mengubah status publikasi
    public function togglePublish($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Akses ditolak'
            ]);
        }

        $umkmModel = new UmkmModel();
        $umkm = $umkmModel->find($id);

        if (!$umkm) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Data UMKM tidak ditemukan'
            ]);
        }

        $newStatus = $this->request->getPost('is_published') == '1' ? 1 : 0;

        if ($umkmModel->update($id, ['is_published' => $newStatus])) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Status UMKM berhasil diubah'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal mengubah status UMKM'
            ]);
        }
    }
}
