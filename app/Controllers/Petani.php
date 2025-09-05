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
        helper(['form', 'url', 'date']); // Helper date ditambahkan
    }

    /**
     * Menampilkan daftar petani dengan status izin dan pagination.
     */
    /**
     * Menampilkan daftar petani dengan Caching untuk performa maksimal.
     */
    public function index()
    {
        $perPage = $this->request->getVar('per_page') ?? 10;
        $requesterId = session()->get('user_id');

        // 1. Ambil data petani menggunakan paginate (Query ini tetap berjalan)
        $petani = $this->petaniModel->orderBy('id', 'ASC')->paginate($perPage, 'petani');

        $petaniIds = array_column($petani, 'id');
        $permissions = [];

        if (!empty($petaniIds) && !empty($requesterId)) {
            // ===== MULAI OPTIMISASI CACHING =====

            // 2a. Buat 'kunci' unik untuk cache ini.
            // Kunci ini spesifik untuk user dan daftar petani di halaman ini.
            $cacheKey = 'perms_user' . $requesterId . '_petani_' . md5(implode(',', $petaniIds));

            // 2b. Coba ambil data dari cache terlebih dahulu.
            if (! $activePermissions = cache($cacheKey)) {

                // 2c. Jika tidak ada di cache (cache miss), jalankan query ke database.
                $activePermissions = $this->permissionModel
                    ->where('requester_id', $requesterId)
                    ->where('target_type', 'petani')
                    ->where('status', 'approved')
                    ->where('expires_at >', date('Y-m-d H:i:s'))
                    ->whereIn('target_id', $petaniIds)
                    ->findAll();

                // 2d. Simpan hasil query ke dalam cache selama 5 menit (300 detik).
                cache()->save($cacheKey, $activePermissions, 300);
            }

            // ===== SELESAI OPTIMISASI CACHING =====

            // 3. Olah data izin (baik dari cache maupun database)
            if (!empty($activePermissions)) {
                foreach ($activePermissions as $perm) {
                    $permissions[$perm['target_id']][$perm['action_type']] = true;
                }
            }
        }

        // 4. Cek izin dengan data yang sudah siap
        foreach ($petani as &$p) {
            $p['can_edit'] = isset($permissions[$p['id']]['edit']);
            $p['can_delete'] = isset($permissions[$p['id']]['delete']);
        }
        unset($p);

        $data = [
            'petani'      => $petani,
            'pager'       => $this->petaniModel->pager,
            'perPage'     => $perPage,
            'currentPage' => $this->petaniModel->pager->getCurrentPage('petani'),
        ];

        return view('admin_komersial/petani/index', $data);
    }

    /**
     * Menyimpan data petani baru.
     * Fungsi ini tidak diubah karena tidak memerlukan izin.
     */
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
                $fotoFile->move('uploads/foto_petani', $fotoName);

                // ===== MULAI OPTIMASI GAMBAR =====
                $imagePath = 'uploads/foto_petani/' . $fotoName;
                \Config\Services::image()
                    ->withFile($imagePath)
                    ->resize(600, 600, true, 'height') // Resize gambar dengan lebar maks 600px
                    ->save($imagePath, 75);             // Simpan dengan kualitas 75%
                // ===== SELESAI OPTIMASI GAMBAR =====
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

    /**
     * Memperbarui data petani dengan optimasi gambar.
     */
    public function postUpdate()
    {
        $id = $this->request->getPost('id');

        if (!$this->hasActivePermission($id, 'edit')) {
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
            $fotoFile->move(FCPATH . 'uploads/foto_petani', $fotoName);

            // ===== MULAI OPTIMASI GAMBAR =====
            $imagePath = FCPATH . 'uploads/foto_petani/' . $fotoName;
            \Config\Services::image()
                ->withFile($imagePath)
                ->resize(600, 600, true, 'height') // Resize gambar dengan lebar maks 600px
                ->save($imagePath, 75);             // Simpan dengan kualitas 75%
            // ===== SELESAI OPTIMASI GAMBAR =====

            if (!empty($petaniLama['foto']) && file_exists(FCPATH . 'uploads/foto_petani/' . $petaniLama['foto'])) {
                unlink(FCPATH . 'uploads/foto_petani/' . $petaniLama['foto']);
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

    /**
     * Menghapus data petani, dengan pemeriksaan izin terlebih dahulu.
     */
    public function delete()
    {
        $id = $this->request->getPost('id');

        // ======== PEMERIKSAAN IZIN DITAMBAHKAN DI SINI ========
        if (!$this->hasActivePermission($id, 'delete')) {
            session()->setFlashdata('error', 'Akses ditolak. Anda tidak memiliki izin untuk menghapus data ini.');
            return redirect()->to('/petani');
        }
        // =======================================================

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

    /**
     * [FUNGSI BARU] Untuk membuat permintaan izin via AJAX.
     */
    public function requestAccess()
    {
        if ($this->request->isAJAX()) {
            $petaniId = $this->request->getPost('petani_id');
            $action = $this->request->getPost('action_type');
            $requesterId = session()->get('user_id'); // Pastikan Anda punya user_id di session

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

            $this->permissionModel->save([
                'requester_id' => $requesterId,
                'target_id'    => $petaniId,
                'target_type'  => 'petani',
                'action_type'  => $action,
                'status'       => 'pending',
            ]);

            return $this->response->setJSON(['status' => 'success', 'message' => 'Permintaan izin berhasil dikirim.']);
        }
        return redirect()->back();
    }

    /**
     * [FUNGSI BARU] Helper untuk mengecek izin aktif.
     */
    private function hasActivePermission($petaniId, $action)
    {
        $requesterId = session()->get('user_id');
        if (empty($requesterId)) {
            return false;
        }

        $permission = $this->permissionModel->where([
            'requester_id' => $requesterId,
            'target_id'    => $petaniId,
            'target_type'  => 'petani',
            'action_type'  => $action,
            'status'       => 'approved',
            'expires_at >' => date('Y-m-d H:i:s')
        ])->first();

        return $permission ? true : false;
    }
}
