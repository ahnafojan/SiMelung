<?php

namespace App\Controllers;

use App\Models\JenisPohonModel;
use App\Models\HargaJenisKopiModel;
use App\Models\PermissionRequestModel;

class JenisPohon extends BaseController
{
    protected $jenisPohonModel;
    protected $hargaJenisKopiModel;
    protected $permissionModel;

    public function __construct()
    {
        $this->jenisPohonModel = new JenisPohonModel();
        $this->hargaJenisKopiModel = new HargaJenisKopiModel();
        $this->permissionModel = new PermissionRequestModel();
        helper(['date']);
    }

    public function index()
    {
        // Gunakan method baru di model untuk mendapatkan data dengan harga
        $data['jenisPohon'] = $this->jenisPohonModel->getJenisPohonWithLatestPrice();

        // Ambil ID admin yang sedang login
        $requesterId = session()->get('user_id');

        // Tambahkan status permission untuk setiap jenis pohon
        if (!empty($data['jenisPohon']) && !empty($requesterId)) {
            foreach ($data['jenisPohon'] as &$jenis) {
                // Status untuk edit/delete jenis pohon (gunakan jenis pohon ID)
                $jenis['edit_status'] = $this->getPermissionStatus($jenis['id'], 'edit', 'jenis_pohon');
                $jenis['delete_status'] = $this->getPermissionStatus($jenis['id'], 'delete', 'jenis_pohon');

                // Status untuk aksi harga (gunakan harga_id jika ada, jika tidak gunakan jenis_pohon_id)
                if (!empty($jenis['harga_id'])) {
                    // Jika sudah ada harga, gunakan harga_id sebagai target
                    $jenis['harga_edit_status'] = $this->getPermissionStatus($jenis['harga_id'], 'harga_edit', 'harga_jenis_kopi');
                    $jenis['harga_delete_status'] = $this->getPermissionStatus($jenis['harga_id'], 'harga_delete', 'harga_jenis_kopi');
                } else {
                    // Jika belum ada harga, gunakan jenis_pohon_id dengan target_type khusus
                    $jenis['harga_edit_status'] = $this->getPermissionStatus($jenis['id'], 'harga_edit', 'jenis_pohon_new_price');
                    $jenis['harga_delete_status'] = null; // Tidak bisa delete yang belum ada
                }
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
        if (!$this->hasActivePermission($id, 'edit', 'jenis_pohon')) {
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
        if (!$this->hasActivePermission($id, 'delete', 'jenis_pohon')) {
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

    public function storeHarga()
    {
        $jenisPohonId = $this->request->getPost('jenis_pohon_id');
        $hargaBeli = $this->request->getPost('harga_beli_per_kg');
        $hargaJual = $this->request->getPost('harga_jual_per_kg');
        $tanggalBerlaku = $this->request->getPost('tanggal_berlaku');

        $validation =  \Config\Services::validation();
        $validation->setRules([
            'jenis_pohon_id'      => 'required|integer|greater_than[0]',
            'harga_beli_per_kg'   => 'required|decimal',
            'harga_jual_per_kg'   => 'required|decimal',
            'tanggal_berlaku'     => 'required|valid_date'
        ]);

        if (!$validation->run(['jenis_pohon_id' => $jenisPohonId, 'harga_beli_per_kg' => $hargaBeli, 'harga_jual_per_kg' => $hargaJual, 'tanggal_berlaku' => $tanggalBerlaku])) {
            session()->setFlashdata('error', 'Data tidak valid: ' . $validation->listErrors());
            return redirect()->back();
        }

        if (!$this->jenisPohonModel->find($jenisPohonId)) {
            session()->setFlashdata('error', 'Data tidak valid: Jenis pohon tidak ditemukan.');
            return redirect()->back();
        }

        // Cek apakah sudah ada harga sebelumnya
        $existingPrice = $this->hargaJenisKopiModel->getLatestPrice($jenisPohonId); // Gunakan method yang benar

        if ($existingPrice) {
            // Jika SUDAH ADA harga, cek izin edit
            // Gunakan ID entri harga yang ditemukan untuk mengecek izin
            $hargaIdUntukIzin = $existingPrice['id']; // Pastikan field 'id' ada di hasil getLatestPrice
            if (!$this->hasActivePermission($hargaIdUntukIzin, 'harga_edit', 'harga_jenis_kopi')) {
                session()->setFlashdata('error', 'Akses ditolak. Anda tidak memiliki izin untuk mengedit harga ini.');
                return redirect()->to(site_url('/jenispohon'));
            }
        }
        // Jika BELUM ADA harga (harga baru), tidak perlu cek izin. Biarkan disimpan.

        $data = [
            'jenis_pohon_id'    => $jenisPohonId,
            'harga_beli_per_kg' => $hargaBeli,
            'harga_jual_per_kg' => $hargaJual,
            'tanggal_berlaku'   => $tanggalBerlaku,
        ];

        try {
            $this->hargaJenisKopiModel->save($data);
            session()->setFlashdata('success', 'Harga jenis pohon berhasil disimpan.');
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Terjadi kesalahan saat menyimpan data harga: ' . $e->getMessage());
        }

        return redirect()->to(site_url('/jenispohon'));
    }

    public function updateHarga($id)
    {
        $harga = $this->hargaJenisKopiModel->getPriceById($id);
        if (!$harga) {
            session()->setFlashdata('error', 'Entri harga tidak ditemukan.');
            return redirect()->to(site_url('/jenispohon'));
        }

        if (!$this->hasActivePermission($id, 'harga_edit', 'harga_jenis_kopi')) {
            session()->setFlashdata('error', 'Akses ditolak. Anda tidak memiliki izin untuk mengedit entri harga ini.');
            return redirect()->to(site_url('/jenispohon'));
        }

        $validation =  \Config\Services::validation();
        $validation->setRules([
            'harga_beli_per_kg'   => 'required|decimal',
            'harga_jual_per_kg'   => 'required|decimal',
            'tanggal_berlaku'     => 'required|valid_date'
        ]);

        if (!$validation->run($this->request->getPost())) {
            session()->setFlashdata('error', 'Data tidak valid: ' . $validation->listErrors());
            return redirect()->back();
        }

        $data = [
            'harga_beli_per_kg' => $this->request->getPost('harga_beli_per_kg'),
            'harga_jual_per_kg' => $this->request->getPost('harga_jual_per_kg'),
            'tanggal_berlaku'   => $this->request->getPost('tanggal_berlaku'),
        ];

        try {
            $this->hargaJenisKopiModel->updateHarga($id, $data);
            session()->setFlashdata('success', 'Entri histori harga berhasil diperbarui.');
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Terjadi kesalahan saat memperbarui data harga: ' . $e->getMessage());
        }

        return redirect()->to(site_url('/jenispohon'));
    }

    public function deleteHarga($id)
    {
        $harga = $this->hargaJenisKopiModel->getPriceById($id);
        if (!$harga) {
            session()->setFlashdata('error', 'Entri harga tidak ditemukan.');
            return redirect()->to(site_url('/jenispohon'));
        }

        if (!$this->hasActivePermission($id, 'harga_delete', 'harga_jenis_kopi')) {
            session()->setFlashdata('error', 'Akses ditolak. Anda tidak memiliki izin untuk menghapus entri harga ini.');
            return redirect()->to(site_url('/jenispohon'));
        }

        try {
            $this->hargaJenisKopiModel->deleteHarga($id);
            session()->setFlashdata('success', 'Entri histori harga berhasil dihapus.');
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Gagal menghapus data harga: ' . $e->getMessage());
        }

        return redirect()->to(site_url('/jenispohon'));
    }

    // 🔧 METHOD UTAMA YANG DIPERBAIKI
    public function requestAccess()
    {
        log_message('debug', '=== REQUEST ACCESS START ===');
        log_message('debug', 'POST Data: ' . json_encode($_POST));

        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        $jenisPohonId = $this->request->getPost('jenispohon_id');
        $hargaId      = $this->request->getPost('harga_id');
        $action       = $this->request->getPost('action_type');
        $requesterId  = session()->get('user_id');

        log_message('debug', 'Request Access - Data received: ' . json_encode([
            'jenispohon_id' => $jenisPohonId,
            'harga_id' => $hargaId,
            'action_type' => $action
        ]));

        if (empty($requesterId)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Sesi tidak valid.'
            ])->setStatusCode(401);
        }

        // Inisialisasi variabel
        $targetType = '';
        $targetId   = null;

        // 🎯 LOGIKA PERBAIKAN: Tentukan target berdasarkan tipe aksi
        if (in_array($action, ['edit', 'delete'])) {
            // Untuk edit/delete jenis pohon
            $targetType = 'jenis_pohon';
            $targetId   = $jenisPohonId;
        } elseif ($action === 'harga_edit') {
            // Validasi harga_id: harus angka > 0 atau 0 (untuk baru)
            if (isset($hargaId) && !empty($hargaId) && is_numeric($hargaId)) {
                $hargaId = (int)$hargaId;
                // Jika harga_id valid (sudah ada harga)
                $targetType = 'harga_jenis_kopi';
                $targetId   = $hargaId;

                // Validasi apakah harga_id benar-benar ada
                if (!$this->hargaJenisKopiModel->find($targetId)) {
                    return $this->response->setJSON([
                        'status' => 'error',
                        'message' => 'Data harga tidak ditemukan.'
                    ]);
                }
            } else {
                // Jika harga_id tidak valid (kosong atau 0), maka tambah harga baru
                $targetType = 'jenis_pohon_new_price';
                $targetId   = $jenisPohonId;

                // Validasi jenis pohon
                if (!$this->jenisPohonModel->find($targetId)) {
                    return $this->response->setJSON([
                        'status' => 'error',
                        'message' => 'Data jenis pohon tidak ditemukan.'
                    ]);
                }
            }
        }

        // Cegah duplikat request pending
        $existing = $this->permissionModel->where([
            'requester_id' => $requesterId,
            'target_id'    => $targetId,
            'target_type'  => $targetType,
            'action_type'  => $action, // ← Ini seharusnya "edit" jika action="harga_edit"
            'status'       => 'pending'
        ])->first();

        if ($existing) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Anda sudah memiliki permintaan yang sedang diproses untuk aksi ini.'
            ]);
        }

        // Simpan permintaan baru
        $saveData = [
            'requester_id' => $requesterId,
            'target_id'    => $targetId,
            'target_type'  => $targetType,
            'action_type'  => $action, // ← TAPI INI HARUS DI-UBAH KE NILAI YANG VALID
            'status'       => 'pending',
            'expires_at'   => date('Y-m-d H:i:s', strtotime('+24 hours'))
        ];

        // MAP ACTION_TYPE KE NILAI YANG VALID UNTUK ENUM
        switch ($action) {
            case 'harga_edit':
                $saveData['action_type'] = 'edit';
                break;
            case 'harga_delete':
                $saveData['action_type'] = 'delete';
                break;
            default:
                $saveData['action_type'] = $action;
                break;
        }

        log_message('debug', 'Saving permission request: ' . json_encode($saveData));

        if (!$this->permissionModel->save($saveData)) {
            log_message('error', 'Failed to save permission request: ' . json_encode($this->permissionModel->errors()));
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal menyimpan permintaan izin.'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Permintaan izin berhasil dikirim dan akan diproses oleh admin Bumdes.'
        ]);
    }

    // Helper untuk mengecek izin aktif
    // Helper untuk mengecek izin aktif
    private function hasActivePermission($targetId, $action, $targetType)
    {
        $requesterId = session()->get('user_id');
        if (empty($requesterId)) {
            return false;
        }

        // MAP action_type SEBELUM DICARI
        $mappedAction = $action;
        if ($action === 'harga_edit') {
            $mappedAction = 'edit';
        } elseif ($action === 'harga_delete') {
            $mappedAction = 'delete';
        }

        $permission = $this->permissionModel->where([
            'requester_id' => $requesterId,
            'target_id'    => $targetId,
            'target_type'  => $targetType,
            'action_type'  => $mappedAction, // Gunakan $mappedAction
            'status'       => 'approved',
        ])->where('expires_at >', date('Y-m-d H:i:s'))->first();

        return $permission ? true : false;
    }

    // Helper untuk mendapatkan status permission
    private function getPermissionStatus($targetId, $action, $targetType)
    {
        $requesterId = session()->get('user_id');
        if (empty($requesterId)) {
            return null;
        }

        // Map action_type jika perlu
        $mappedAction = $action;
        if ($action === 'harga_edit') {
            $mappedAction = 'edit';
        } elseif ($action === 'harga_delete') {
            $mappedAction = 'delete';
        }

        // Cek izin yang 'approved' dan masih aktif
        $approved = $this->permissionModel->where([
            'requester_id' => $requesterId,
            'target_id'    => $targetId,
            'target_type'  => $targetType,
            'action_type'  => $mappedAction,
            'status'       => 'approved',
        ])->where('expires_at >', date('Y-m-d H:i:s'))->first();

        if ($approved) {
            return 'approved';
        }

        // Cek permintaan yang masih 'pending'
        $pending = $this->permissionModel->where([
            'requester_id' => $requesterId,
            'target_id'    => $targetId,
            'target_type'  => $targetType,
            'action_type'  => $mappedAction,
            'status'       => 'pending'
        ])->first();

        if ($pending) {
            return 'pending';
        }

        return null;
    }
}
