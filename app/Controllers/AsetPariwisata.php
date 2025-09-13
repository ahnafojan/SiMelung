<?php

namespace App\Controllers;

use App\Models\AsetPariwisataModel;
use App\Models\ObjekWisataModel;
use App\Models\PermissionRequestModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class AsetPariwisata extends BaseController
{
    protected $asetModel;
    protected $permissionModel;

    /**
     * Menginisialisasi model yang akan digunakan.
     */
    public function __construct()
    {
        $this->asetModel = new AsetPariwisataModel();
        $this->permissionModel = new PermissionRequestModel();
        helper(['date']);
    }

    /**
     * Halaman utama manajemen aset pariwisata dengan pagination.
     */
    public function index()
    {
        $objekWisataModel = new ObjekWisataModel();

        // Ambil parameter pagination
        $perPage = $this->request->getGet('per_page') ?? 10;
        $perPage = in_array($perPage, [10, 25, 100]) ? $perPage : 10;

        // Query dengan pagination
        $query = $this->asetModel->select('
            aset_pariwisata.*, 
            objek_wisata.nama_wisata,
            aset_wisata.wisata_id AS objek_wisata_id
        ')
            ->join('aset_wisata', 'aset_wisata.aset_id = aset_pariwisata.id', 'left')
            ->join('objek_wisata', 'objek_wisata.id = aset_wisata.wisata_id', 'left')
            ->orderBy('aset_pariwisata.id', 'DESC');

        $asets = $query->paginate($perPage, 'default');
        $pager = $this->asetModel->pager;

        // ▼▼▼ MULAI BAGIAN OPTIMASI & CACHING ▼▼▼

        // 1. Siapkan variabel yang dibutuhkan
        $requesterId = session()->get('user_id');
        $permissions = [];

        // 2. Kumpulkan semua ID aset pariwisata dari data yang tampil
        $asetPariwisataIds = array_column($asets, 'id');

        if (!empty($asetPariwisataIds) && !empty($requesterId)) {
            // 3. Buat cache key yang spesifik untuk 'aset_pariwisata'
            $cacheKey = 'permissions_aset_pariwisata_user_' . $requesterId;

            if (!$permissionData = cache($cacheKey)) {
                // Jika cache kosong, ambil semua data izin untuk 'aset_pariwisata'
                $permissionData = $this->permissionModel // Pastikan model ini di-load
                    ->where('requester_id', $requesterId)
                    ->where('target_type', 'aset_pariwisata') // <-- Target baru
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
                $aset['edit_status']   = $permissions[$aset['id']]['edit'] ?? 'none';
                $aset['delete_status'] = $permissions[$aset['id']]['delete'] ?? 'none';
            }
        }

        // ▲▲▲ SELESAI BAGIAN OPTIMASI & CACHING ▲▲▲

        $data = [
            'title'       => 'Manajemen Aset Pariwisata',
            'asets'       => $asets,
            'list_wisata' => $objekWisataModel->orderBy('nama_wisata', 'ASC')->findAll(),
            'pager'       => $pager,
            'currentPage' => $pager->getCurrentPage(),
            'perPage'     => $perPage,
        ];
        $data['breadcrumbs'] = [
            [
                'title' => 'Dashboard',
                'url'   => site_url('dashboard/dashboard_pariwisata'),
                'icon'  => 'fas fa-fw fa-tachometer-alt'
            ],
            [
                'title' => 'Manajemen Aset Pariwisata',
                'url'   => '#',
                'icon'  => 'fas fa-archive'
            ]
        ];

        return view('admin_pariwisata/manajemen', $data);
    }

    /**
     * Simpan data aset baru.
     */
    public function store()
    {
        $validationRules = [
            'objek_wisata_id' => 'required',
            'nama_aset'       => 'required|min_length[3]',
            'kode_aset'       => 'required',
            'tahun_perolehan' => 'required|integer',
            'nilai_perolehan' => 'required',
            'metode_pengadaan' => 'required',
            'sumber_pengadaan' => 'required',
            'foto_aset'       => 'max_size[foto_aset,2048]|is_image[foto_aset]|mime_in[foto_aset,image/jpg,image/jpeg,image/png]',
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        $fotoFile = $this->request->getFile('foto_aset');
        $namaFoto = null;
        if ($fotoFile && $fotoFile->isValid() && !$fotoFile->hasMoved()) {
            $namaFoto = $fotoFile->getRandomName();
            if (ENVIRONMENT === 'development') {
                // Path untuk localhost (XAMPP)
                $uploadPath = FCPATH . 'uploads/aset_pariwisata';
            } else {
                // Path untuk server hosting
                $uploadPath = ROOTPATH . '../public_html/uploads/aset_pariwisata';
            }
            $fotoFile->move($uploadPath, $namaFoto);
        }

        $nilaiPerolehan = preg_replace('/[^0-9]/', '', $this->request->getPost('nilai_perolehan'));

        // ====================================================================
        // PERBAIKAN UTAMA DI SINI
        // Menyimpan ke 'nama_aset' dan juga 'nama_pariwisata' untuk konsistensi
        // ====================================================================
        $asetData = [
            'nama_aset'       => $this->request->getPost('nama_aset'),
            'nama_pariwisata' => $this->request->getPost('nama_aset'),
            'kode_aset'       => $this->request->getPost('kode_aset'),
            'nup'             => $this->request->getPost('nup'),
            'tahun_perolehan' => $this->request->getPost('tahun_perolehan'),
            'nilai_perolehan' => $nilaiPerolehan,
            'metode_pengadaan' => $this->request->getPost('metode_pengadaan'),
            'sumber_pengadaan' => $this->request->getPost('sumber_pengadaan'),
            'keterangan'      => $this->request->getPost('keterangan'),
        ];

        if ($namaFoto) {
            $asetData['foto_aset'] = $namaFoto;
        }

        $wisataId = $this->request->getPost('objek_wisata_id');

        if ($this->asetModel->saveAsetAndRelation($asetData, $wisataId)) {
            return redirect()->to('/asetpariwisata')->with('success', 'Data aset baru berhasil ditambahkan.');
        }

        return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data.');
    }

    /**
     * Update data aset + relasi.
     */
    public function update($id = null)
    {
        if (!$this->hasActivePermission($id, 'edit')) {
            return redirect()->to('/asetpariwisata')->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk mengedit data ini.');
        }

        if (!$id) {
            throw PageNotFoundException::forPageNotFound();
        }

        $oldData = $this->asetModel->find($id);

        if (!$oldData) {
            return redirect()->to('/asetpariwisata')->with('error', 'Data aset tidak ditemukan.');
        }

        $db = \Config\Database::connect();
        $asetWisataData = $db->table('aset_wisata')->where('aset_id', $id)->get()->getRowArray();
        $oldWisataId = $asetWisataData ? $asetWisataData['wisata_id'] : null;

        $validationRules = [
            'objek_wisata_id' => 'required',
            'nama_aset'       => 'required|min_length[3]',
            'kode_aset'       => 'required',
            'tahun_perolehan' => 'required|integer',
            'nilai_perolehan' => 'required',
            'metode_pengadaan' => 'required',
            'sumber_pengadaan' => 'required',
            'foto_aset'       => 'max_size[foto_aset,2048]|is_image[foto_aset]|mime_in[foto_aset,image/jpg,image/jpeg,image/png]',
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        $fotoFile = $this->request->getFile('foto_aset');
        $namaFoto = $oldData['foto_aset'];

        if ($fotoFile && $fotoFile->isValid() && !$fotoFile->hasMoved()) {
            if ($namaFoto && file_exists('uploads/aset_pariwisata/' . $namaFoto)) {
                unlink('uploads/aset_pariwisata/' . $namaFoto);
            }
            $namaFoto = $fotoFile->getRandomName();
            if (ENVIRONMENT === 'development') {
                // Path untuk localhost (XAMPP)
                $uploadPath = FCPATH . 'uploads/aset_pariwisata';
            } else {
                // Path untuk server hosting
                $uploadPath = ROOTPATH . '../public_html/uploads/aset_pariwisata';
            }
            $fotoFile->move($uploadPath, $namaFoto);
        }

        $nilaiPerolehan = preg_replace('/[^0-9]/', '', $this->request->getPost('nilai_perolehan'));

        // ====================================================================
        // PERBAIKAN UTAMA DI SINI JUGA
        // ====================================================================
        $asetData = [
            'nama_aset'       => $this->request->getPost('nama_aset'),
            'nama_pariwisata' => $this->request->getPost('nama_aset'),
            'kode_aset'       => $this->request->getPost('kode_aset'),
            'nup'             => $this->request->getPost('nup'),
            'tahun_perolehan' => $this->request->getPost('tahun_perolehan'),
            'nilai_perolehan' => $nilaiPerolehan,
            'metode_pengadaan' => $this->request->getPost('metode_pengadaan'),
            'sumber_pengadaan' => $this->request->getPost('sumber_pengadaan'),
            'keterangan'      => $this->request->getPost('keterangan'),
            'foto_aset'       => $namaFoto,
        ];

        $newWisataId = $this->request->getPost('objek_wisata_id');

        if ($this->asetModel->updateAsetAndRelation($id, $asetData, $newWisataId, $oldWisataId)) {
            return redirect()->to('/asetpariwisata')->with('success', 'Data aset berhasil diperbarui.');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data aset.');
    }

    /**
     * Hapus aset + relasinya.
     */
    public function delete($id = null)
    {
        // Disarankan untuk mengganti ini menjadi getPermissionStatus agar konsisten
        if ($this->getPermissionStatus($id, 'delete') !== 'approved') {
            return redirect()->to('/asetpariwisata')->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk menghapus data ini.');
        }

        if (!$id) {
            throw PageNotFoundException::forPageNotFound();
        }

        $aset = $this->asetModel->find($id);

        if ($aset) {
            // Hapus file foto jika ada
            if (!empty($aset['foto_aset'])) {
                // ====================================================================
                // KODE ADAPTIF UNTUK PATH FILE
                // ====================================================================
                if (ENVIRONMENT === 'development') {
                    // Path untuk localhost (XAMPP)
                    $uploadPath = FCPATH . 'uploads/aset_pariwisata';
                } else {
                    // Path untuk server hosting
                    $uploadPath = ROOTPATH . '../public_html/uploads/aset_pariwisata';
                }

                if (file_exists($uploadPath . '/' . $aset['foto_aset'])) {
                    unlink($uploadPath . '/' . $aset['foto_aset']);
                }
            }

            if ($this->asetModel->deleteAsetAndRelation($id)) {
                return redirect()->to('/asetpariwisata')->with('success', 'Data aset berhasil dihapus.');
            }
        }
        return redirect()->to('/asetpariwisata')->with('error', 'Gagal menghapus data aset.');
    }


    /**
     * Menangani permintaan izin via AJAX.
     */
    public function requestAccess()
    {
        if ($this->request->isAJAX()) {
            $asetId = $this->request->getPost('aset_id');
            $action = $this->request->getPost('action_type');
            $requesterId = session()->get('user_id'); // Pastikan Anda memiliki 'user_id' di session

            if (empty($requesterId)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Sesi tidak valid atau Anda belum login.'])->setStatusCode(401);
            }

            $existing = $this->permissionModel->where([
                'requester_id' => $requesterId,
                'target_id'    => $asetId,
                'action_type'  => $action,
                'target_type'  => 'aset_pariwisata', // Spesifik untuk aset pariwisata
                'status'       => 'pending'
            ])->first();

            if ($existing) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Anda sudah mengajukan permintaan serupa yang masih menunggu persetujuan.']);
            }

            $this->permissionModel->save([
                'requester_id' => $requesterId,
                'target_id'    => $asetId,
                'target_type'  => 'aset_pariwisata', // INILAH BAGIAN YANG PALING PENTING
                'action_type'  => $action,
                'status'       => 'pending',
            ]);

            return $this->response->setJSON(['status' => 'success', 'message' => 'Permintaan izin berhasil dikirim.']);
        }
        return redirect()->back();
    }

    /**
     * Helper untuk mengecek izin aktif.
     */
    private function hasActivePermission($asetId, $action)
    {
        $requesterId = session()->get('user_id');
        if (empty($requesterId)) {
            return false;
        }

        $permission = $this->permissionModel->where([
            'requester_id' => $requesterId,
            'target_id'    => $asetId,
            'target_type'  => 'aset_pariwisata',
            'action_type'  => $action,
            'status'       => 'approved',
            'expires_at >' => date('Y-m-d H:i:s') // Cek apakah izin belum kedaluwarsa
        ])->first();

        return $permission ? true : false;
    }
    private function getPermissionStatus($asetId, $action)
    {
        $requesterId = session()->get('user_id');
        if (empty($requesterId)) {
            return 'none';
        }

        // 1. Cek izin 'approved' yang masih aktif
        $approved = $this->permissionModel->where([
            'requester_id' => $requesterId,
            'target_id'    => $asetId,
            'target_type'  => 'aset_pariwisata',
            'action_type'  => $action,
            'status'       => 'approved',
            'expires_at >' => date('Y-m-d H:i:s')
        ])->first();

        if ($approved) {
            return 'approved';
        }

        // 2. Cek permintaan yang 'pending'
        $pending = $this->permissionModel->where([
            'requester_id' => $requesterId,
            'target_id'    => $asetId,
            'target_type'  => 'aset_pariwisata',
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
