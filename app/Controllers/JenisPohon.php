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
        $this->permissionModel = new PermissionRequestModel();
        helper(['date']);
    }

    public function index()
    {
        $data['jenisPohon'] = $this->jenisPohonModel->findAll();

        // Ambil ID admin yang sedang login
        $requesterId = session()->get('user_id');

        // Tambahkan status permission untuk setiap jenis pohon
        if (!empty($data['jenisPohon']) && !empty($requesterId)) {
            foreach ($data['jenisPohon'] as &$jenis) {
                $jenis['edit_status'] = $this->getPermissionStatus($jenis['id'], 'edit');
                $jenis['delete_status'] = $this->getPermissionStatus($jenis['id'], 'delete');
            }
        }

        $data['breadcrumbs'] = [
            [
                'title' => 'Dashboard',
                'url'   => site_url('/dashboard/dashboard_komersial'),
                'icon'  => 'fas fa-fw fa-tachometer-alt'
            ],
            [
                'title' => 'Daftar Jenis Pohon',
                'url'   => '#',
                'icon'  => 'fas fa-tree'
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
            $this->jenisPohonModel->delete($id);
            session()->setFlashdata('success', 'Jenis pohon berhasil dihapus.');
        } catch (\Exception $e) {
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

            // Hapus cache agar status terupdate
            $cacheKey = 'permissions_jenis_pohon_user_' . $requesterId;
            cache()->delete($cacheKey);

            return $this->response->setJSON(['status' => 'success', 'message' => 'Permintaan izin berhasil dikirim.']);
        }
        return redirect()->back();
    }

    // Helper untuk mengecek izin aktif
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

    // Helper untuk mendapatkan status permission
    private function getPermissionStatus($jenisPohonId, $action)
    {
        $requesterId = session()->get('user_id');
        if (empty($requesterId)) {
            return null;
        }

        // Cek izin yang 'approved' dan masih aktif
        $approved = $this->permissionModel->where([
            'requester_id' => $requesterId,
            'target_id'    => $jenisPohonId,
            'target_type'  => 'jenis_pohon',
            'action_type'  => $action,
            'status'       => 'approved',
        ])->where('expires_at >', date('Y-m-d H:i:s'))->first();

        if ($approved) {
            return 'approved';
        }

        // Cek permintaan yang masih 'pending'
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

        // Jika tidak ada, kembalikan null
        return null;
    }
}
