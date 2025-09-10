<?php

namespace App\Controllers;

use App\Models\JenisPohonModel;
use App\Models\PermissionRequestModel;

class JenisPohon extends BaseController
{
    protected $jenisPohonModel;
    protected $permissionModel;

    public function __construct()
    {
        $this->jenisPohonModel = new JenisPohonModel();
        $this->permissionModel = new PermissionRequestModel(); // 3. Inisialisasi model
        helper(['date']);
    }

    public function index()
    {
        $data['jenisPohon'] = $this->jenisPohonModel->findAll();
        if (!empty($data['jenisPohon'])) {
            foreach ($data['jenisPohon'] as &$jenis) {
                // Mengganti 'can_edit' menjadi 'edit_status' yang lebih deskriptif
                $jenis['edit_status'] = $this->getPermissionStatus($jenis['id'], 'edit');
                // Mengganti 'can_delete' menjadi 'delete_status'
                $jenis['delete_status'] = $this->getPermissionStatus($jenis['id'], 'delete');
            }
        }
        $data['breadcrumbs'] = [
            [
                'title' => 'Dashboard',
                'url'   => site_url('/dashboard/dashboard_komersial'), // Sesuaikan URL dashboard Anda
                'icon'  => 'fas fa-fw fa-tachometer-alt' // Ikon sesuai permintaan Anda
            ],
            [
                'title' => 'Daftar Jenis Pohon',
                'url'   => '#',
                'icon'  => 'fas fa-tree' // Ikon yang relevan untuk pohon
            ]
        ];
        return view('admin_komersial/petani/daftarpohon', $data);
    }

    public function store()
    {
        try {
            $this->jenisPohonModel->save([
                'nama_jenis' => $this->request->getPost('nama_jenis')
            ]);
            session()->setFlashdata('success', 'Jenis pohon berhasil ditambahkan.');
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Terjadi kesalahan saat menambah data: ' . $e->getMessage());
        }
        return redirect()->to(site_url('/jenispohon'));
    }

    /**
     * Mengambil data untuk form edit.
     * Catatan: Untuk kasus sederhana ini, kita bisa lewatkan data via atribut data-* di tombol.
     * Jika data lebih kompleks, metode AJAX akan lebih baik.
     */
    // public function edit($id) { ... } // Tidak diperlukan untuk kasus ini

    /**
     * Memperbarui data di database.
     */
    public function update($id)
    {
        if (!$this->hasActivePermission($id, 'edit')) {
            session()->setFlashdata('error', 'Akses ditolak. Anda tidak memiliki izin untuk mengedit data ini.');
            return redirect()->to(site_url('/jenispohon'));
        }
        try {
            $this->jenisPohonModel->update($id, [
                'nama_jenis' => $this->request->getPost('nama_jenis')
            ]);
            session()->setFlashdata('success', 'Jenis pohon berhasil diperbarui.');
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
        return redirect()->to(site_url('/jenispohon'));
    }

    public function delete($id)
    {
        if (!$this->hasActivePermission($id, 'delete')) {
            session()->setFlashdata('error', 'Akses ditolak. Anda tidak memiliki izin untuk menghapus data ini.');
            return redirect()->to(site_url('/jenispohon'));
        }
        try {
            // Cek relasi data sebelum menghapus jika diperlukan
            $this->jenisPohonModel->delete($id);
            session()->setFlashdata('success', 'Jenis pohon berhasil dihapus.');
        } catch (\Exception $e) {
            // Tangani error jika ada foreign key constraint
            session()->setFlashdata('error', 'Gagal menghapus data. Kemungkinan jenis pohon ini masih digunakan di data lain.');
        }
        return redirect()->to(site_url('/jenispohon'));
    }
    public function requestAccess()
    {
        if ($this->request->isAJAX()) {
            $jenisPohonId = $this->request->getPost('jenispohon_id');
            $action       = $this->request->getPost('action_type');
            $requesterId  = session()->get('user_id');

            if (empty($requesterId)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Sesi tidak valid.'])->setStatusCode(401);
            }

            $existing = $this->permissionModel->where([
                'requester_id' => $requesterId,
                'target_id'    => $jenisPohonId,
                'action_type'  => $action,
                'target_type'  => 'jenis_pohon',
                'status'       => 'pending'
            ])->first();

            if ($existing) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Anda sudah memiliki permintaan yang sama.']);
            }

            $this->permissionModel->save([
                'requester_id' => $requesterId,
                'target_id'    => $jenisPohonId,
                'target_type'  => 'jenis_pohon',
                'action_type'  => $action,
                'status'       => 'pending',
            ]);

            return $this->response->setJSON(['status' => 'success', 'message' => 'Permintaan izin berhasil dikirim.']);
        }
        return redirect()->back();
    }

    // 9. [FUNGSI BARU] Helper untuk mengecek izin aktif
    private function hasActivePermission($jenisPohonId, $action)
    {
        $requesterId = session()->get('user_id');
        if (empty($requesterId)) {
            return false;
        }

        $permission = $this->permissionModel->where([
            'requester_id' => $requesterId,
            'target_id'    => $jenisPohonId,
            'target_type'  => 'jenis_pohon',
            'action_type'  => $action,
            'status'       => 'approved',
            'expires_at >' => date('Y-m-d H:i:s')
        ])->first();

        return $permission ? true : false;
    }
    private function getPermissionStatus($jenisPohonId, $action)
    {
        $requesterId = session()->get('user_id');
        if (empty($requesterId)) {
            return 'none';
        }

        // 1. Cek izin yang sudah 'approved' dan aktif
        $approved = $this->permissionModel->where([
            'requester_id' => $requesterId,
            'target_id'    => $jenisPohonId,
            'target_type'  => 'jenis_pohon',
            'action_type'  => $action,
            'status'       => 'approved',
            'expires_at >' => date('Y-m-d H:i:s')
        ])->first();

        if ($approved) {
            return 'approved';
        }

        // 2. Cek permintaan yang masih 'pending'
        $pending = $this->permissionModel->where([
            'requester_id' => $requesterId,
            'target_id'    => $jenisPohonId,
            'target_type'  => 'jenis_pohon',
            'action_type'  => $action,
            'status'       => 'pending'
        ])->first();

        if ($pending) {
            return 'pending';
        }

        // 3. Jika tidak ada, kembalikan 'none'
        return 'none';
    }
}
