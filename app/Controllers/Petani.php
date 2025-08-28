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
     * Menampilkan daftar petani dengan status izin untuk setiap baris.
     */
    public function index()
    {
        $data['petani'] = $this->petaniModel->orderBy('id', 'ASC')->findAll();
        if (!empty($data['petani'])) {
            foreach ($data['petani'] as &$p) {
                // 'id' di sini adalah ID dari petani, bukan user_id
                $p['can_edit'] = $this->hasActivePermission($p['id'], 'edit');
                $p['can_delete'] = $this->hasActivePermission($p['id'], 'delete');
            }
        }

        echo view('admin_komersial/petani/index', $data);
    }

    /**
     * Menyimpan data petani baru.
     * Fungsi ini tidak diubah karena tidak memerlukan izin.
     */
    public function create()
    {
        // ... (Logika create Anda tidak berubah)
        try {
            $validation = \Config\Services::validation();
            $validation->setRules([
                'nama'          => 'required|min_length[3]',
                'alamat'        => 'required',
                'no_hp'         => 'required|numeric',
                'foto'          => 'permit_empty|uploaded[foto]|is_image[foto]|max_size[foto,2048]',
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
     * Memperbarui data petani, dengan pemeriksaan izin terlebih dahulu.
     */
    public function postUpdate()
    {
        $id = $this->request->getPost('id');

        // ======== PEMERIKSAAN IZIN DITAMBAHKAN DI SINI ========
        if (!$this->hasActivePermission($id, 'edit')) {
            session()->setFlashdata('error', 'Akses ditolak. Anda tidak memiliki izin untuk mengedit data ini.');
            return redirect()->to('/petani');
        }
        // =======================================================

        $petaniLama = $this->petaniModel->find($id);

        if (!$petaniLama) {
            session()->setFlashdata('error', 'Data petani tidak ditemukan');
            return redirect()->to('/petani');
        }

        // ... (Sisa logika validasi dan update Anda tidak berubah)
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
