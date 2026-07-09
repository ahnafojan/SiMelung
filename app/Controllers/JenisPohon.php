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

        $validation = \Config\Services::validation();
        $validation->setRules([
            'jenis_pohon_id'      => 'required|integer|greater_than[0]',
            'harga_beli_per_kg'   => 'required|decimal',
            'harga_jual_per_kg'   => 'required|decimal',
            'tanggal_berlaku'     => 'required|valid_date'
        ]);

        if (!$validation->run([
            'jenis_pohon_id' => $jenisPohonId,
            'harga_beli_per_kg' => $hargaBeli,
            'harga_jual_per_kg' => $hargaJual,
            'tanggal_berlaku' => $tanggalBerlaku
        ])) {
            session()->setFlashdata('error', 'Data tidak valid: ' . $validation->listErrors());
            return redirect()->back();
        }

        if (!$this->jenisPohonModel->find($jenisPohonId)) {
            session()->setFlashdata('error', 'Jenis pohon tidak ditemukan.');
            return redirect()->back();
        }

        $requesterId = session()->get('user_id');
        if (empty($requesterId)) {
            session()->setFlashdata('error', 'Sesi tidak valid.');
            return redirect()->back();
        }

        // ✅ Cegah duplikat pending untuk jenis pohon yang sama (harga)
        $existing = $this->permissionModel->where([
            'requester_id' => $requesterId,
            'target_id'    => $jenisPohonId,
            'target_type'  => 'jenis_pohon',
            'action_type'  => 'edit',
            'status'       => 'pending'
        ])->first();

        if ($existing) {
            session()->setFlashdata('error', 'Sudah ada permintaan harga yang sedang diproses untuk jenis kopi ini.');
            return redirect()->to(site_url('/jenispohon'));
        }

        // ✅ Simpan sebagai request (bukan simpan ke harga_jenis_kopi)
        $saveData = [
            'requester_id' => $requesterId,
            'target_id'    => $jenisPohonId,
            'target_type'  => 'jenis_pohon',   // satu pintu
            'action_type'  => 'edit',          // enum kamu: edit/delete
            'status'       => 'pending',
            'expires_at'   => date('Y-m-d H:i:s', strtotime('+24 hours')),

            // kolom usulan harga
            'requested_jenis_pohon_id'      => (int)$jenisPohonId,
            'requested_harga_beli_per_kg'   => $hargaBeli,
            'requested_harga_jual_per_kg'   => $hargaJual,
            'requested_tanggal_berlaku'     => $tanggalBerlaku,
        ];

        if (!$this->permissionModel->save($saveData)) {
            session()->setFlashdata('error', 'Gagal mengirim permintaan harga.');
            return redirect()->back();
        }

        session()->setFlashdata('success', 'Permintaan harga berhasil dikirim. Menunggu approval Bumdes.');
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

        $requesterId  = session()->get('user_id');
        if (empty($requesterId)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Sesi tidak valid.'
            ])->setStatusCode(401);
        }

        // ===== Ambil data dasar =====
        $jenisPohonId = (int) $this->request->getPost('jenispohon_id');
        $hargaId      = $this->request->getPost('harga_id'); // optional
        $action       = $this->request->getPost('action_type');

        // ===== Ambil kolom usulan harga (dikirim dari AJAX) =====
        $reqJenisId = $this->request->getPost('requested_jenis_pohon_id');
        $reqBeli    = $this->request->getPost('requested_harga_beli_per_kg');
        $reqJual    = $this->request->getPost('requested_harga_jual_per_kg');
        $reqTgl     = $this->request->getPost('requested_tanggal_berlaku');

        log_message('debug', 'Request Access - Data received: ' . json_encode([
            'jenispohon_id' => $jenisPohonId,
            'harga_id' => $hargaId,
            'action_type' => $action,
            'requested_jenis_pohon_id' => $reqJenisId,
            'requested_harga_beli_per_kg' => $reqBeli,
            'requested_harga_jual_per_kg' => $reqJual,
            'requested_tanggal_berlaku' => $reqTgl,
        ]));

        // Validasi minimal
        if (empty($action)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Aksi tidak valid.'
            ])->setStatusCode(400);
        }

        // Inisialisasi variabel target
        $targetType = '';
        $targetId   = null;

        // ===== Tentukan target berdasarkan aksi =====
        if (in_array($action, ['edit', 'delete'])) {
            // Edit/Delete Jenis Pohon
            if (empty($jenisPohonId) || !$this->jenisPohonModel->find($jenisPohonId)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Data jenis pohon tidak ditemukan.'
                ])->setStatusCode(404);
            }

            $targetType = 'jenis_pohon';
            $targetId   = $jenisPohonId;
        } elseif ($action === 'harga_edit') {
            // Untuk request perubahan/penambahan harga:
            // Kita pakai target_type khusus biar Bumdes gampang filter + tidak tergantung harga_id
            // (karena yang disetujui adalah data usulan di kolom requested_*)
            if (empty($jenisPohonId) || !$this->jenisPohonModel->find($jenisPohonId)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Data jenis pohon tidak ditemukan.'
                ])->setStatusCode(404);
            }

            $targetType = 'harga_jenis_kopi_request';
            $targetId   = $jenisPohonId; // simpan jenis_pohon_id sebagai target_id
        } elseif ($action === 'harga_delete') {
            // Delete harga historis (jika kamu masih butuh)
            if (!is_numeric($hargaId) || (int)$hargaId <= 0) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'ID harga tidak valid.'
                ])->setStatusCode(400);
            }
            $hargaId = (int)$hargaId;

            if (!$this->hargaJenisKopiModel->find($hargaId)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Data harga tidak ditemukan.'
                ])->setStatusCode(404);
            }

            $targetType = 'harga_jenis_kopi';
            $targetId   = $hargaId;
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Aksi tidak dikenali.'
            ])->setStatusCode(400);
        }

        // ===== Map action_type ke enum DB (edit/delete) =====
        $mappedAction = $action;
        if ($action === 'harga_edit')  $mappedAction = 'edit';
        if ($action === 'harga_delete') $mappedAction = 'delete';

        // ===== Cegah duplikat request pending =====
        // Untuk harga, duplikat dicegah berdasarkan jenis_pohon_id (targetId)
        $existing = $this->permissionModel->where([
            'requester_id' => $requesterId,
            'target_id'    => $targetId,
            'target_type'  => $targetType,
            'action_type'  => $mappedAction,
            'status'       => 'pending'
        ])->first();

        if ($existing) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Anda sudah memiliki permintaan yang sedang diproses untuk aksi ini.'
            ]);
        }

        // ===== Data utama yang disimpan =====
        $saveData = [
            'requester_id' => $requesterId,
            'target_id'    => $targetId,
            'target_type'  => $targetType,
            'action_type'  => $mappedAction,
            'status'       => 'pending',
            'expires_at'   => date('Y-m-d H:i:s', strtotime('+24 hours')),
        ];

        // ===== Jika ini request harga, simpan usulan ke kolom baru =====
        if ($action === 'harga_edit') {
            // Validasi usulan harga
            if (empty($reqJenisId) || empty($reqTgl) || $reqBeli === null || $reqJual === null) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Data usulan harga belum lengkap.'
                ])->setStatusCode(400);
            }

            // Paksa konsisten: requested_jenis_pohon_id harus sama dengan target (jenisPohonId)
            $saveData['requested_jenis_pohon_id']    = (int) $jenisPohonId;
            $saveData['requested_harga_beli_per_kg'] = $reqBeli;
            $saveData['requested_harga_jual_per_kg'] = $reqJual;
            $saveData['requested_tanggal_berlaku']   = $reqTgl;
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
