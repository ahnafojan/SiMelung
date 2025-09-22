<?php

namespace App\Controllers;

use App\Models\PetaniModel;
use App\Models\PermissionRequestModel;
use CodeIgniter\Controller;

class Petani extends Controller
{
    protected $petaniModel;
    protected $permissionModel;

    public function __construct()
    {
        $this->petaniModel = new PetaniModel();
        $this->permissionModel = new PermissionRequestModel();
        helper(['form', 'url', 'date']);
    }

    /**
     * Menampilkan daftar petani dengan status izin (approved, pending, none)
     * dan pagination, dioptimalkan dengan Caching.
     */
    public function index()
    {
        // Praktik terbaik: Wajibkan login untuk mengakses halaman ini
        if (!session()->get('user_id')) {
            session()->setFlashdata('error', 'Anda harus login untuk mengakses halaman ini.');
            return redirect()->to('/login'); // Arahkan ke halaman login Anda
        }

        $perPage = $this->request->getVar('per_page') ?? 10;
        $requesterId = session()->get('user_id');

        // 1. Ambil data petani menggunakan paginate
        $petani = $this->petaniModel->orderBy('id', 'ASC')->paginate($perPage, 'petani');

        $petaniIds = array_column($petani, 'id');
        $permissions = [];
        if (!empty($petaniIds) && !empty($requesterId)) {
            // ▼▼▼ PERUBAHAN DI SINI ▼▼▼
            // Gunakan cache key yang lebih sederhana, hanya berdasarkan user ID.
            $cacheKey = 'permissions_petani_user_' . $requesterId;

            if (!$permissionData = cache($cacheKey)) {
                // Ambil SEMUA izin aktif/pending milik user ini untuk target_type 'petani'
                $permissionData = $this->permissionModel
                    ->where('requester_id', $requesterId)
                    ->where('target_type', 'petani')
                    ->whereIn('status', ['approved', 'pending'])
                    // Hapus whereIn target_id agar kita cache semua izin user, bukan per halaman.
                    // ->whereIn('target_id', $petaniIds) 
                    ->findAll();

                // Simpan hasil query ke dalam cache selama 5 menit (300 detik).
                cache()->save($cacheKey, $permissionData, 300);
            }
            // ▲▲▲ AKHIR PERUBAH

            // ===== SELESAI OPTIMISASI CACHING =====

            // 3. Olah data izin untuk menyimpan statusnya ('approved' atau 'pending')
            if (!empty($permissionData)) {
                foreach ($permissionData as $perm) {
                    // Cek izin 'approved' yang masih aktif
                    if ($perm['status'] == 'approved' && strtotime($perm['expires_at']) > time()) {
                        $permissions[$perm['target_id']][$perm['action_type']] = 'approved';
                    } elseif ($perm['status'] == 'pending') {
                        $permissions[$perm['target_id']][$perm['action_type']] = 'pending';
                    }
                }
            }
        }

        // 4. Set status untuk setiap petani
        foreach ($petani as &$p) {
            $p['edit_status'] = $permissions[$p['id']]['edit'] ?? 'none';
            $p['delete_status'] = $permissions[$p['id']]['delete'] ?? 'none';
        }
        unset($p);

        $data = [
            'petani'      => $petani,
            'pager'       => $this->petaniModel->pager,
            'perPage'     => $perPage,
            'currentPage' => $this->petaniModel->pager->getCurrentPage('petani'),
            'breadcrumbs' => [
                [
                    'title' => 'Dashboard',
                    'url'   => site_url('/dashboard/dashboard_komersial'),
                    'icon'  => 'fas fa-fw fa-tachometer-alt' // <-- Tambahkan ini
                ],
                [
                    'title' => 'Data Petani',
                    'url'   => '#',
                    'icon'  => 'fas fa-users' // <-- Tambahkan ini (ikon untuk data orang)
                ]
            ]
        ];

        return view('admin_komersial/petani/index', $data);
    }

    /**
     * [HELPER BARU] Mengembalikan status izin: 'approved', 'pending', atau 'none'.
     * Ini menggantikan fungsi hasActivePermission.
     */
    private function getPermissionStatus($petaniId, $action)
    {
        $requesterId = session()->get('user_id');
        if (empty($requesterId)) {
            return 'none';
        }

        // 1. Cek izin yang 'approved' dan masih aktif
        $approved = $this->permissionModel->where([
            'requester_id' => $requesterId,
            'target_id'    => $petaniId,
            'target_type'  => 'petani',
            'action_type'  => $action,
            'status'       => 'approved',
            'expires_at >' => date('Y-m-d H:i:s')
        ])->first();

        if ($approved) {
            return 'approved';
        }

        // 2. Jika tidak ada, cek permintaan yang 'pending'
        $pending = $this->permissionModel->where([
            'requester_id' => $requesterId,
            'target_id'    => $petaniId,
            'target_type'  => 'petani',
            'action_type'  => $action,
            'status'       => 'pending'
        ])->first();

        if ($pending) {
            return 'pending';
        }

        // 3. Jika tidak keduanya, berarti belum ada aksi
        return 'none';
    }


    public function create()
    {
        try {
            $validation = \Config\Services::validation();
            $validation->setRules([
                'nama'   => 'required|min_length[3]',
                'alamat' => 'required',
                'no_hp'  => 'required|numeric',
                'foto'   => 'permit_empty|uploaded[foto]|is_image[foto]|max_size[foto,2048]',
            ]);

            if (!$this->validate($validation->getRules())) {
                return redirect()->to(site_url('petani'))->with('errors', $this->validator->getErrors());
            }

            $lastPetani = $this->petaniModel->orderBy('id', 'DESC')->first();
            $newUserId = $lastPetani
                ? 'P' . str_pad(((int) substr($lastPetani['user_id'], 1)) + 1, 3, '0', STR_PAD_LEFT)
                : 'P001';

            $fotoName = null;
            $fotoFile = $this->request->getFile('foto');
            if ($fotoFile && $fotoFile->isValid()) {
                $fotoName = $fotoFile->getRandomName();
                if (ENVIRONMENT === 'development') {
                    // Path untuk localhost (XAMPP) -> public/uploads/foto_petani
                    $uploadPath = FCPATH . 'uploads/foto_petani';
                } else {
                    // Path untuk server hosting -> public_html/uploads/foto_petani
                    $uploadPath = ROOTPATH . '../public_html/uploads/foto_petani';
                }
                $fotoFile->move($uploadPath, $fotoName);

                $imagePath = $uploadPath . '/' . $fotoName;
                \Config\Services::image()
                    ->withFile($imagePath)
                    ->resize(600, 600, true, 'height')
                    ->save($imagePath, 75);
            }

            $this->petaniModel->save([
                'user_id'       => $newUserId,
                'nama'          => $this->request->getPost('nama'),
                'alamat'        => $this->request->getPost('alamat'),
                'no_hp'         => $this->request->getPost('no_hp'),
                'usia'          => $this->request->getPost('usia'),
                'tempat_lahir'  => $this->request->getPost('tempat_lahir'),
                'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
                'foto'          => $fotoName
            ]);
            session()->setFlashdata('success', 'Data petani berhasil ditambahkan');
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Terjadi kesalahan saat menambah data: ' . $e->getMessage());
        }

        return redirect()->to('/petani');
    }

    public function postUpdate()
    {
        $id = $this->request->getPost('id');

        // [DIUBAH] Menggunakan getPermissionStatus
        if ($this->getPermissionStatus($id, 'edit') !== 'approved') {
            session()->setFlashdata('error', 'Akses ditolak. Anda tidak memiliki izin untuk mengedit data ini.');
            return redirect()->to('/petani');
        }

        $petaniLama = $this->petaniModel->find($id);
        if (!$petaniLama) {
            session()->setFlashdata('error', 'Data petani tidak ditemukan');
            return redirect()->to('/petani');
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama'   => 'required|min_length[3]',
            'alamat' => 'required',
            'no_hp'  => 'required|numeric',
            'foto'   => 'permit_empty|is_image[foto]|max_size[foto,5000]',
        ]);

        if (!$this->validate($validation->getRules())) {
            return redirect()->to('/petani')->with('errors', $this->validator->getErrors());
        }

        $dataUpdate = [
            'nama'          => $this->request->getPost('nama'),
            'alamat'        => $this->request->getPost('alamat'),
            'no_hp'         => $this->request->getPost('no_hp'),
            'usia'          => $this->request->getPost('usia'),
            'tempat_lahir'  => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
        ];

        $fotoFile = $this->request->getFile('foto');
        if ($fotoFile && $fotoFile->isValid() && !$fotoFile->hasMoved()) {
            $fotoName = $fotoFile->getRandomName();
            // ====================================================================
            if (ENVIRONMENT === 'development') {
                // Path untuk localhost (XAMPP) -> public/uploads/foto_petani
                $uploadPath = FCPATH . 'uploads/foto_petani';
            } else {
                // Path untuk server hosting -> public_html/uploads/foto_petani
                $uploadPath = ROOTPATH . '../public_html/uploads/foto_petani';
            }
            $fotoFile->move($uploadPath, $fotoName);

            $imagePath = $uploadPath . '/' . $fotoName;
            \Config\Services::image()
                ->withFile($imagePath)
                ->resize(600, 600, true, 'height')
                ->save($imagePath, 75);

            if (!empty($petaniLama['foto']) && file_exists($uploadPath . '/' . $petaniLama['foto'])) {
                unlink($uploadPath . '/' . $petaniLama['foto']);
            }
            $dataUpdate['foto'] = $fotoName;
        }

        try {
            $this->petaniModel->update($id, $dataUpdate);
            session()->setFlashdata('success', 'Data petani berhasil diperbarui');
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Terjadi kesalahan saat memperbarui data');
        }

        return redirect()->to('/petani');
    }

    public function delete()
    {
        $id = $this->request->getPost('id');

        // [DIUBAH] Menggunakan getPermissionStatus
        if ($this->getPermissionStatus($id, 'delete') !== 'approved') {
            session()->setFlashdata('error', 'Akses ditolak. Anda tidak memiliki izin untuk menghapus data ini.');
            return redirect()->to('/petani');
        }

        if (!$id) {
            return redirect()->to('/petani')->with('error', 'Gagal menghapus, ID petani tidak valid.');
        }

        try {
            $petani = $this->petaniModel->find($id);

            if ($petani) {
                if (!empty($petani['foto']) && file_exists(FCPATH . 'uploads/foto_petani/' . $petani['foto'])) {
                    unlink(FCPATH . 'uploads/foto_petani/' . $petani['foto']);
                }
                $this->petaniModel->delete($id);
                session()->setFlashdata('success', 'Data petani berhasil dihapus.');
            } else {
                session()->setFlashdata('error', 'Data petani tidak ditemukan.');
            }
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Terjadi kesalahan pada server saat menghapus data.');
        }

        return redirect()->to('/petani');
    }

    public function requestAccess()
    {
        if ($this->request->isAJAX()) {
            $petaniId = $this->request->getPost('petani_id');
            $action = $this->request->getPost('action_type');
            $requesterId = session()->get('user_id');

            if (empty($requesterId)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Sesi tidak valid. Silakan login ulang.'])->setStatusCode(401);
            }

            $existing = $this->permissionModel->where([
                'requester_id' => $requesterId,
                'target_id'    => $petaniId,
                'action_type'  => $action,
                'target_type'  => 'petani',
                'status'       => 'pending'
            ])->first();

            if ($existing) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Anda sudah memiliki permintaan yang sama dan masih menunggu persetujuan.']);
            }

            // Simpan permintaan baru
            $this->permissionModel->save([
                'requester_id' => $requesterId,
                'target_id'    => $petaniId,
                'target_type'  => 'petani',
                'action_type'  => $action,
                'status'       => 'pending',
            ]);

            // 🔥 INVALIDASI CACHE AGAR STATUS UPDATE SAAT REFRESH
            $cacheKey = 'permissions_petani_user_' . $requesterId;
            cache()->delete($cacheKey);

            return $this->response->setJSON(['status' => 'success', 'message' => 'Permintaan izin berhasil dikirim.']);
        }
        return redirect()->back();
    }
}
