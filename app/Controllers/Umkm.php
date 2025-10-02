<?php

namespace App\Controllers;

use App\Models\UmkmModel;

class Umkm extends BaseController
{
    protected $umkmModel;

    public function __construct()
    {
        $this->umkmModel = new UmkmModel();
    }

    public function index()
    {
        $data['umkm'] = $this->umkmModel->orderBy('id', 'DESC')->findAll();
        return view('admin_umkm/umkm/index', $data);
    }

    public function store()
    {
        // Mendapatkan file foto dari request
        $foto_umkm = $this->request->getFile('foto_umkm');
        $nama_foto = null;

        // Cek apakah ada file foto yang diunggah
        if ($foto_umkm && $foto_umkm->isValid() && !$foto_umkm->hasMoved()) {
            // Mengambil nama file baru secara acak untuk menghindari duplikasi
            $nama_foto = $foto_umkm->getRandomName();
            // Memindahkan file ke folder 'uploads/foto_umkm'
            $foto_umkm->move('./uploads/foto_umkm', $nama_foto);
        }

        // Simpan data ke database
        $this->umkmModel->save([
            'nama_umkm' => $this->request->getPost('nama_umkm'),
            'pemilik' => $this->request->getPost('pemilik'),
            'kategori' => $this->request->getPost('kategori'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'alamat' => $this->request->getPost('alamat'),
            'gmaps_url' => $this->request->getPost('gmaps_url'), // Menambahkan gmaps_url
            'kontak' => $this->request->getPost('kontak'),
            'foto_umkm' => $nama_foto // Menyimpan nama file foto yang baru
        ]);

        return redirect()->to(site_url('umkm'))->with('success', 'UMKM berhasil ditambahkan');
    }

    public function update($id)
    {
        // Ambil data UMKM yang lama untuk mengecek foto lama
        $old_umkm = $this->umkmModel->find($id);

        // Mendapatkan file foto dari request
        $foto_umkm = $this->request->getFile('foto_umkm');
        $nama_foto = $old_umkm['foto_umkm']; // Gunakan nama foto lama sebagai default

        // Cek apakah ada file foto baru yang diunggah
        if ($foto_umkm && $foto_umkm->isValid() && !$foto_umkm->hasMoved()) {
            // Hapus file foto lama jika ada
            if ($old_umkm['foto_umkm'] != null) {
                unlink('./uploads/foto_umkm/' . $old_umkm['foto_umkm']);
            }
            // Mengambil nama file baru secara acak
            $nama_foto = $foto_umkm->getRandomName();
            // Memindahkan file baru
            $foto_umkm->move('./uploads/foto_umkm', $nama_foto);
        }

        // Update data ke database
        $this->umkmModel->update($id, [
            'nama_umkm' => $this->request->getPost('nama_umkm'),
            'pemilik' => $this->request->getPost('pemilik'),
            'kategori' => $this->request->getPost('kategori'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'alamat' => $this->request->getPost('alamat'),
            'gmaps_url' => $this->request->getPost('gmaps_url'), // Memperbarui gmaps_url
            'kontak' => $this->request->getPost('kontak'),
            'foto_umkm' => $nama_foto // Memperbarui nama file foto
        ]);

        return redirect()->to(site_url('umkm'))->with('success', 'UMKM berhasil diupdate');
    }

    public function delete($id)
    {
        // Ambil data UMKM sebelum dihapus untuk mendapatkan nama file foto
        $umkm = $this->umkmModel->find($id);
        
        // Hapus file foto dari server jika ada
        if ($umkm['foto_umkm'] != null) {
            unlink('./uploads/foto_umkm/' . $umkm['foto_umkm']);
        }
        
        // Hapus data dari database
        $this->umkmModel->delete($id);

        return redirect()->to(site_url('umkm'))->with('success', 'UMKM berhasil dihapus');
    }

    public function requestAccess()
    {
        if ($this->request->isAJAX()) {
            $kopiMasukId = $this->request->getPost('kopimasuk_id');
            $action = $this->request->getPost('action_type');
            $requesterId = session()->get('user_id');

            if (empty($requesterId)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Sesi tidak valid.'])->setStatusCode(401);
            }

            $existing = $this->permissionModel->where([
                'requester_id' => $requesterId,
                'target_id' => $kopiMasukId,
                'action_type' => $action,
                'target_type' => 'kopi_masuk',
                'status' => 'pending'
            ])->first();

            if ($existing) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Anda sudah memiliki permintaan yang sama.']);
            }

            $this->permissionModel->save([
                'requester_id' => $requesterId,
                'target_id' => $kopiMasukId,
                'target_type' => 'kopi_masuk',
                'action_type' => $action,
                'status' => 'pending',
            ]);

            return $this->response->setJSON(['status' => 'success', 'message' => 'Permintaan izin berhasil dikirim.']);
        }
        return redirect()->back();
    }

    private function hasActivePermission($kopiMasukId, $action)
    {
        $requesterId = session()->get('user_id');
        if (empty($requesterId)) {
            return false;
        }

        $permission = $this->permissionModel->where([
            'requester_id' => $requesterId,
            'target_id' => $kopiMasukId,
            'target_type' => 'kopi_masuk',
            'action_type' => $action,
            'status' => 'approved',
            'expires_at >' => date('Y-m-d H:i:s')
        ])->first();

        return $permission ? true : false;
    }
    private function getPermissionStatus($kopiMasukId, $action)
    {
        $requesterId = session()->get('user_id');
        if (empty($requesterId)) {
            return 'none'; // Status untuk user yang tidak login
        }

        // 1. Cek dulu apakah izin sudah 'approved' dan aktif
        $approved = $this->permissionModel->where([
            'requester_id' => $requesterId,
            'target_id' => $kopiMasukId,
            'target_type' => 'kopi_masuk',
            'action_type' => $action,
            'status' => 'approved',
            'expires_at >' => date('Y-m-d H:i:s')
        ])->first();

        if ($approved) {
            return 'approved';
        }

        // 2. Jika tidak, cek apakah ada permintaan 'pending'
        $pending = $this->permissionModel->where([
            'requester_id' => $requesterId,
            'target_id' => $kopiMasukId,
            'target_type' => 'kopi_masuk',
            'action_type' => $action,
            'status' => 'pending'
        ])->first();

        if ($pending) {
            return 'pending';
        }

        // 3. Jika tidak keduanya, berarti belum ada aksi
        return 'none';
    }
}