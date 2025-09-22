<?php

namespace App\Controllers;

use App\Models\AsetKomersialModel;
use App\Models\PermissionRequestModel;
use CodeIgniter\Controller;

class ManajemenAsetKomersial extends Controller
{
    protected $asetModel;
    protected $permissionModel;

    public function __construct()
    {
        $this->asetModel = new AsetKomersialModel();
        $this->permissionModel = new PermissionRequestModel();
        helper(['date']);
    }

    /**
     * Menampilkan daftar aset dengan pagination.
     */
    public function index()
    {
        if (!session()->get('user_id')) {
            session()->setFlashdata('error', 'Anda harus login untuk mengakses halaman ini.');
            return redirect()->to('/login');
        }

        $kategoriAset = [
            'Mesin Giling',
            'Mesin Pengupas Kopi',
            'Mesin Pengering Kopi',
            'Gudang Penyimpanan',
            'Kendaraan Operasional',
            'Peralatan Pertanian',
        ];

        $perPage = $this->request->getVar('per_page') ?? 10;
        $asets = $this->asetModel->orderBy('id_aset', 'DESC')->paginate($perPage, 'asets');

        // ▼▼▼ MULAI BAGIAN OPTIMASI & CACHING ▼▼▼

        // 1. Siapkan variabel yang dibutuhkan
        $requesterId = session()->get('user_id');
        $permissions = [];

        // 2. Kumpulkan semua ID aset dari data yang tampil
        // Perhatikan kita menggunakan 'id_aset' sesuai dengan kolom di tabel Anda
        $asetIds = array_column($asets, 'id_aset');

        if (!empty($asetIds) && !empty($requesterId)) {
            // 3. Buat cache key yang spesifik untuk 'aset'
            $cacheKey = 'permissions_aset_user_' . $requesterId;

            if (!$permissionData = cache($cacheKey)) {
                // Jika cache kosong, ambil semua data izin untuk 'aset'
                $permissionData = $this->permissionModel // Pastikan model ini di-load
                    ->where('requester_id', $requesterId)
                    ->where('target_type', 'aset') // <-- Target baru
                    ->whereIn('status', ['approved', 'pending'])
                    ->findAll();

                // Simpan ke cache
                cache()->save($cacheKey, $permissionData, 300);
            }

            // 4. Olah data izin agar mudah diakses
            if (!empty($permissionData)) {
                foreach ($permissionData as $perm) {
                    if ($perm['status'] == 'approved' && strtotime($perm['expires_at']) > now('Asia/Jakarta')) {
                        $permissions[$perm['target_id']][$perm['action_type']] = 'approved';
                    } elseif ($perm['status'] == 'pending') {
                        $permissions[$perm['target_id']][$perm['action_type']] = 'pending';
                    }
                }
            }
        }

        // 5. Tetapkan status izin ke setiap baris data (tanpa query berulang)
        if (!empty($asets)) {
            foreach ($asets as &$aset) {
                // Gunakan 'id_aset' sebagai kunci untuk mencari di array permissions
                $aset['edit_status']   = $permissions[$aset['id_aset']]['edit'] ?? 'none';
                $aset['delete_status'] = $permissions[$aset['id_aset']]['delete'] ?? 'none';
            }
        }

        // ▲▲▲ SELESAI BAGIAN OPTIMASI & CACHING ▲▲▲

        $data = [
            'kategoriAset' => $kategoriAset,
            'asets'        => $asets,
            'pager'        => $this->asetModel->pager,
            'perPage'      => $perPage,
            'currentPage'  => $this->asetModel->pager->getCurrentPage('asets'),
        ];
        $data['breadcrumbs'] = [
            [
                'title' => 'Dashboard',
                'url'   => site_url('/dashboard/dashboard_komersial'),
                'icon'  => 'fas fa-fw fa-tachometer-alt'
            ],
            [
                'title' => 'Manajemen Aset',
                'url'   => '#',
                'icon'  => 'fas fa-fw fa-tools'
            ]
        ];

        return view('admin_komersial/aset/manajemen_aset', $data);
    }

    /**
     * Memperbarui data aset dari form modal edit.
     */
    public function update($id)
    {
        if ($this->getPermissionStatus($id, 'edit') !== 'approved') {
            session()->setFlashdata('error', 'Akses ditolak. Anda tidak memiliki izin untuk mengedit data ini.');
            return redirect()->to(site_url('/ManajemenAsetKomersial'));
        }

        try {
            $aset = $this->asetModel->find($id);
            if (!$aset) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Data tidak ditemukan');
            }

            $kategoriDipilih = $this->request->getPost('kategori_aset');
            $namaAsetFinal = $kategoriDipilih;

            if ($kategoriDipilih === 'Lainnya') {
                $namaAsetLainnya = $this->request->getPost('nama_aset_lainnya');
                if (empty($namaAsetLainnya)) {
                    session()->setFlashdata('error', 'Nama Aset Lainnya wajib diisi jika kategori "Lainnya" dipilih.');
                    return redirect()->back();
                }
                $namaAsetFinal = $namaAsetLainnya;
            }

            $fotoFile = $this->request->getFile('foto');
            $fotoName = $aset['foto'];

            if ($fotoFile && $fotoFile->isValid() && !$fotoFile->hasMoved()) {
                $fotoName = $fotoFile->getRandomName();

                // ====================================================================
                // KODE ADAPTIF UNTUK PATH UPLOAD
                // ====================================================================
                if (ENVIRONMENT === 'development') {
                    $uploadPath = FCPATH . 'uploads/foto_aset';
                } else {
                    $uploadPath = ROOTPATH . '../public_html/uploads/foto_aset';
                }
                $fotoFile->move($uploadPath, $fotoName);

                // Hapus file lama setelah yang baru berhasil diupload
                if (!empty($aset['foto']) && file_exists($uploadPath . '/' . $aset['foto'])) {
                    unlink($uploadPath . '/' . $aset['foto']);
                }
            }

            $this->asetModel->update($id, [
                'nama_aset'        => $namaAsetFinal,
                'kode_aset'        => $this->request->getPost('kode_aset'),
                'nup'              => $this->request->getPost('nup'),
                'tahun_perolehan'  => $this->request->getPost('tahun_perolehan'),
                'merk_type'        => $this->request->getPost('merk_type'),
                'nilai_perolehan'  => $this->request->getPost('nilai_perolehan'),
                'keterangan'       => $this->request->getPost('keterangan'),
                'metode_pengadaan' => $this->request->getPost('metode_pengadaan'),
                'sumber_pengadaan' => $this->request->getPost('sumber_pengadaan'),
                'foto'             => $fotoName,
            ]);

            session()->setFlashdata('success', 'Data aset berhasil diperbarui ✅');
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }

        return redirect()->to(site_url('/ManajemenAsetKomersial'));
    }

    public function delete($id)
    {
        if ($this->getPermissionStatus($id, 'delete') !== 'approved') {
            session()->setFlashdata('error', 'Akses ditolak. Anda tidak memiliki izin untuk menghapus data ini.');
            return redirect()->to(site_url('/ManajemenAsetKomersial'));
        }
        try {
            $aset = $this->asetModel->find($id);
            if (!$aset) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Data tidak ditemukan');
            }

            if (!empty($aset['foto'])) {
                // ====================================================================
                // KODE ADAPTIF UNTUK PATH FILE
                // ====================================================================
                if (ENVIRONMENT === 'development') {
                    $uploadPath = FCPATH . 'uploads/foto_aset';
                } else {
                    $uploadPath = ROOTPATH . '../public_html/uploads/foto_aset';
                }

                if (file_exists($uploadPath . '/' . $aset['foto'])) {
                    unlink($uploadPath . '/' . $aset['foto']);
                }
            }

            $this->asetModel->delete($id);
            session()->setFlashdata('success', 'Data Aset berhasil dihapus ✅');
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }

        return redirect()->to(site_url('/ManajemenAsetKomersial'));
    }

    public function requestAccess()
    {
        if ($this->request->isAJAX()) {
            $asetId = $this->request->getPost('aset_id');
            $action = $this->request->getPost('action_type');
            $requesterId = session()->get('user_id');

            if (empty($requesterId)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Sesi tidak valid.'])->setStatusCode(401);
            }

            // Cek apakah sudah ada permintaan pending untuk aksi ini
            $existing = $this->permissionModel->where([
                'requester_id' => $requesterId,
                'target_id'    => $asetId,
                'action_type'  => $action,
                'target_type'  => 'aset',
                'status'       => 'pending'
            ])->first();

            if ($existing) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Anda sudah memiliki permintaan yang sama.']);
            }

            // Simpan permintaan baru
            $this->permissionModel->save([
                'requester_id' => $requesterId,
                'target_id'    => $asetId,
                'target_type'  => 'aset',
                'action_type'  => $action,
                'status'       => 'pending',
            ]);

            // 🔥 INVALIDASI CACHE AGAR STATUS UPDATE SAAT REFRESH
            $cacheKey = 'permissions_aset_user_' . $requesterId;
            cache()->delete($cacheKey);

            return $this->response->setJSON(['status' => 'success', 'message' => 'Permintaan izin berhasil dikirim.']);
        }
        return redirect()->back();
    }

    private function hasActivePermission($asetId, $action)
    {
        $requesterId = session()->get('user_id');
        if (empty($requesterId)) {
            return false;
        }

        $permission = $this->permissionModel->where([
            'requester_id' => $requesterId,
            'target_id'    => $asetId,
            'target_type'  => 'aset',
            'action_type'  => $action,
            'status'       => 'approved',
            'expires_at >' => date('Y-m-d H:i:s')
        ])->first();

        return $permission ? true : false;
    }
    private function getPermissionStatus($asetId, $action)
    {
        $requesterId = session()->get('user_id');
        if (empty($requesterId)) {
            return 'none'; // Status untuk pengguna yang tidak login
        }

        // 1. Prioritaskan cek izin yang sudah disetujui dan masih aktif
        $approved = $this->permissionModel->where([
            'requester_id' => $requesterId,
            'target_id'    => $asetId,
            'target_type'  => 'aset',
            'action_type'  => $action,
            'status'       => 'approved',
            'expires_at >' => date('Y-m-d H:i:s')
        ])->first();

        if ($approved) {
            return 'approved';
        }

        // 2. Jika tidak ada yang disetujui, cek apakah ada permintaan yang tertunda
        $pending = $this->permissionModel->where([
            'requester_id' => $requesterId,
            'target_id'    => $asetId,
            'target_type'  => 'aset',
            'action_type'  => $action,
            'status'       => 'pending'
        ])->first();

        if ($pending) {
            return 'pending';
        }

        // 3. Jika tidak ada keduanya, berarti belum ada permintaan
        return 'none';
    }
}
