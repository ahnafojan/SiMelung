<?php

namespace App\Controllers;

use App\Models\UmkmModel;
use App\Models\PermissionRequestModel; // Tambahkan Model Izin
use CodeIgniter\Controller;

class Umkm extends BaseController
{
    protected $umkmModel;
    protected $permissionModel; // Deklarasikan Model Izin

    public function __construct()
    {
        $this->umkmModel = new UmkmModel();
        $this->permissionModel = new PermissionRequestModel(); // Inisialisasi Model Izin
        helper('date'); // Pastikan helper date dimuat
    }

    public function index()
    {
        $userId = session()->get('user_id') ?? 0; // Ambil ID pengguna saat ini
        $keyword = $this->request->getVar('keyword'); // <-- AMBIL KATA KUNCI PENCARIAN

        // Logic Pencarian Data UMKM
        if ($keyword) {
            // Jika ada keyword, filter data berdasarkan nama UMKM, pemilik, atau kategori
            $umkmList = $this->umkmModel
                ->like('nama_umkm', $keyword)
                ->orLike('pemilik', $keyword)
                ->orLike('kategori', $keyword)
                ->orderBy('id', 'DESC')
                ->findAll();
        } else {
            // Jika tidak ada keyword, ambil semua data
            $umkmList = $this->umkmModel->orderBy('id', 'DESC')->findAll();
        }
        // Akhir Logic Pencarian

        $umkmWithStatus = [];

        // Loop untuk menambahkan status izin ke setiap data UMKM
        foreach ($umkmList as $u) {
            $u['edit_status'] = $this->getPermissionStatus($u['id'], 'edit', $userId, 'umkm');
            $u['delete_status'] = $this->getPermissionStatus($u['id'], 'delete', $userId, 'umkm');
            $umkmWithStatus[] = $u;
        }

        $data = [
            'umkm' => $umkmWithStatus,
            'keyword' => $keyword // <-- KIRIM KEMBALI KEYWORD KE VIEW
        ];

        return view('admin_umkm/umkm/index', $data);
    }

    public function store()
    {
        $foto_umkm = $this->request->getFile('foto_umkm');
        $nama_foto = null;

        if ($foto_umkm && $foto_umkm->isValid() && !$foto_umkm->hasMoved()) {
            $nama_foto = $foto_umkm->getRandomName();

            // Tentukan path berdasarkan environment
            if (ENVIRONMENT === 'development') {
                $uploadPath = FCPATH . 'uploads/foto_umkm';
            } else {
                $uploadPath = ROOTPATH . '../public_html/uploads/foto_umkm';
            }

            // Pastikan folder ada
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $foto_umkm->move($uploadPath, $nama_foto);
        }

        // Simpan ke database...
        $this->umkmModel->save([
            'nama_umkm' => $this->request->getPost('nama_umkm'),
            'pemilik' => $this->request->getPost('pemilik'),
            'kategori' => $this->request->getPost('kategori'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'alamat' => $this->request->getPost('alamat'),
            'gmaps_url' => $this->request->getPost('gmaps_url'),
            'kontak' => $this->request->getPost('kontak'),
            'foto_umkm' => $nama_foto
        ]);

        return redirect()->to(site_url('umkm'))->with('success', 'UMKM berhasil ditambahkan');
    }

    public function update($id)
    {
        $userId = session()->get('user_id') ?? 0;

        // 1. Cek Izin Edit
        if (!$this->hasActivePermission($id, 'edit', $userId, 'umkm')) {
            return redirect()->to(site_url('umkm'))->with('error', 'Aksi edit dibatalkan. Anda tidak memiliki izin aktif.');
        }

        // Ambil data UMKM yang lama
        $old_umkm = $this->umkmModel->find($id);
        if (!$old_umkm) {
            return redirect()->to(site_url('umkm'))->with('error', 'Data UMKM tidak ditemukan.');
        }

        $foto_umkm = $this->request->getFile('foto_umkm');
        $nama_foto = $old_umkm['foto_umkm']; // Pertahankan foto lama jika tidak ada upload baru

        // Tentukan path upload berdasarkan environment
        if (ENVIRONMENT === 'development') {
            $uploadPath = FCPATH . 'uploads/foto_umkm';
        } else {
            $uploadPath = ROOTPATH . '../public_html/uploads/foto_umkm';
        }

        // Pastikan folder upload ada
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Cek apakah ada file foto baru yang diunggah
        if ($foto_umkm && $foto_umkm->isValid() && !$foto_umkm->hasMoved()) {
            // Hapus file foto lama jika ada
            if (!empty($old_umkm['foto_umkm'])) {
                $oldFilePath = $uploadPath . '/' . $old_umkm['foto_umkm'];
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }

            // Simpan file baru
            $nama_foto = $foto_umkm->getRandomName();
            $foto_umkm->move($uploadPath, $nama_foto);
        }

        // Update data ke database
        $this->umkmModel->update($id, [
            'nama_umkm'   => $this->request->getPost('nama_umkm'),
            'pemilik'     => $this->request->getPost('pemilik'),
            'kategori'    => $this->request->getPost('kategori'),
            'deskripsi'   => $this->request->getPost('deskripsi'),
            'alamat'      => $this->request->getPost('alamat'),
            'gmaps_url'   => $this->request->getPost('gmaps_url'),
            'kontak'      => $this->request->getPost('kontak'),
            'foto_umkm'   => $nama_foto
        ]);

        // Hapus permintaan izin yang sudah digunakan
        $this->permissionModel->where([
            'requester_id' => $userId,
            'target_id'    => $id,
            'target_type'  => 'umkm',
            'action_type'  => 'edit',
            'status'       => 'approved'
        ])->delete();

        return redirect()->to(site_url('umkm'))->with('success', 'UMKM berhasil diupdate');
    }
    public function delete($id)
    {
        $userId = session()->get('user_id') ?? 0;

        // 1. Cek Izin Hapus
        if (!$this->hasActivePermission($id, 'delete', $userId, 'umkm')) {
            return redirect()->to(site_url('umkm'))->with('error', 'Aksi hapus dibatalkan. Anda tidak memiliki izin aktif.');
        }

        // Ambil data UMKM sebelum dihapus untuk mendapatkan nama file foto
        $umkm = $this->umkmModel->find($id);

        // Hapus file foto dari server jika ada
        if ($umkm['foto_umkm'] != null && file_exists('./uploads/foto_umkm/' . $umkm['foto_umkm'])) {
            unlink('./uploads/foto_umkm/' . $umkm['foto_umkm']);
        }

        // Hapus data dari database
        $this->umkmModel->delete($id);

        // Hapus permintaan izin yang sudah digunakan setelah hapus berhasil
        $this->permissionModel->where([
            'requester_id' => $userId,
            'target_id' => $id,
            'target_type' => 'umkm',
            'action_type' => 'delete',
            'status' => 'approved'
        ])->delete();

        return redirect()->to(site_url('umkm'))->with('success', 'UMKM berhasil dihapus');
    }

    // Fungsi untuk mengirimkan permintaan izin (dipanggil via AJAX dari View)
    public function requestAccess()
    {
        if ($this->request->isAJAX()) {
            $umkmId = $this->request->getPost('kopimasuk_id'); // Ganti nama input menjadi umkm_id jika memungkinkan
            $action = $this->request->getPost('action_type');
            $requesterId = session()->get('user_id');

            if (empty($requesterId)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Sesi tidak valid.'])->setStatusCode(401);
            }
            if (empty($umkmId) || !in_array($action, ['edit', 'delete'])) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Data UMKM atau aksi tidak valid.'])->setStatusCode(400);
            }

            // Cek apakah sudah ada permintaan pending atau approved yang masih aktif
            $existing = $this->permissionModel->where([
                'requester_id' => $requesterId,
                'target_id' => $umkmId,
                'target_type' => 'umkm',
                'action_type' => $action,
            ])
                ->groupStart()
                ->where('status', 'pending')
                ->orWhere('expires_at >', date('Y-m-d H:i:s'))
                ->groupEnd()
                ->first();

            if ($existing) {
                if ($existing['status'] === 'approved') {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Anda sudah memiliki izin aktif untuk aksi ini!']);
                }
                return $this->response->setJSON(['status' => 'error', 'message' => 'Anda sudah memiliki permintaan yang sama yang sedang menunggu persetujuan.']);
            }

            // Simpan permintaan baru
            $this->permissionModel->save([
                'requester_id' => $requesterId,
                'target_id' => $umkmId,
                'target_type' => 'umkm',
                'action_type' => $action,
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s')
            ]);

            return $this->response->setJSON(['status' => 'success', 'message' => 'Permintaan izin berhasil dikirim. Menunggu verifikasi admin lain.']);
        }
        return redirect()->back();
    }

    // Fungsi untuk memeriksa apakah izin 'approved' dan masih aktif
    private function hasActivePermission($targetId, $action, $requesterId, $targetType)
    {
        if (empty($requesterId)) {
            return false;
        }

        $permission = $this->permissionModel->where([
            'requester_id' => $requesterId,
            'target_id' => $targetId,
            'target_type' => $targetType,
            'action_type' => $action,
            'status' => 'approved',
            'expires_at >' => date('Y-m-d H:i:s')
        ])->first();

        return $permission ? true : false;
    }

    // Fungsi untuk mendapatkan status izin (untuk ditampilkan di View)
    private function getPermissionStatus($targetId, $action, $requesterId, $targetType)
    {
        if (empty($requesterId)) {
            return 'none';
        }

        // 1. Cek status APPROVED dan aktif
        $approved = $this->permissionModel->where([
            'requester_id' => $requesterId,
            'target_id' => $targetId,
            'target_type' => $targetType,
            'action_type' => $action,
            'status' => 'approved',
            'expires_at >' => date('Y-m-d H:i:s')
        ])->first();

        if ($approved) {
            return 'approved';
        }

        // 2. Jika tidak, cek apakah ada permintaan PENDING
        $pending = $this->permissionModel->where([
            'requester_id' => $requesterId,
            'target_id' => $targetId,
            'target_type' => $targetType,
            'action_type' => $action,
            'status' => 'pending'
        ])->first();

        if ($pending) {
            return 'pending';
        }

        // 3. Jika tidak keduanya
        return 'none';
    }
}
