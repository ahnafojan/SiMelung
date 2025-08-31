<?php

namespace App\Controllers;

use App\Models\PetaniModel;
use App\Models\JenisPohonModel;
use App\Models\PetaniPohonModel;
use App\Models\PermissionRequestModel; // 1. Tambahkan model permission

class PetaniPohon extends BaseController
{
    protected $petaniModel;
    protected $jenisPohonModel;
    protected $petaniPohonModel;
    protected $permissionModel; // 2. Daftarkan model permission

    public function __construct()
    {
        $this->petaniModel = new PetaniModel();
        $this->jenisPohonModel = new JenisPohonModel();
        $this->petaniPohonModel = new PetaniPohonModel();
        $this->permissionModel = new PermissionRequestModel(); // 3. Inisialisasi model
        helper(['date']); // 4. Tambahkan helper date
    }

    // Tampilkan detail pohon berdasarkan user_id
    public function index($user_id)
    {
        $petani = $this->petaniModel->where('user_id', $user_id)->first();
        if (!$petani) {
            return redirect()->to('/petani')->with('error', 'Petani tidak ditemukan');
        }

        $jenisPohon = $this->jenisPohonModel->findAll();

        $detailPohon = $this->petaniPohonModel
            ->select('petani_pohon.*, jenis_pohon.nama_jenis')
            ->join('jenis_pohon', 'jenis_pohon.id = petani_pohon.jenis_pohon_id')
            ->where('petani_pohon.user_id', $user_id)
            ->findAll();

        // 5. Tambahkan pengecekan izin untuk setiap data pohon
        if (!empty($detailPohon)) {
            foreach ($detailPohon as &$pohon) {
                $pohon['can_delete'] = $this->hasActivePermission($pohon['id'], 'delete');
            }
        }

        return view('admin_komersial/petani/petanipohon', [
            'petani'      => $petani,
            'jenisPohon'  => $jenisPohon,
            'detailPohon' => $detailPohon,
            'validation'  => \Config\Services::validation(),
        ]);
    }

    // Simpan pohon baru (fungsi ini tidak diubah)
    // Simpan pohon baru
    public function store()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'user_id'        => 'required',
            'jenis_pohon_id' => 'required|integer',
            'luas_lahan'     => 'required|decimal',
            'jumlah_pohon'   => 'required|integer',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()
                ->with('errors', $validation->getErrors())
                ->withInput();
        }

        $data = [
            'user_id'        => $this->request->getPost('user_id'),
            'jenis_pohon_id' => $this->request->getPost('jenis_pohon_id'),
            'luas_lahan'     => $this->request->getPost('luas_lahan'),
            'jumlah_pohon'   => $this->request->getPost('jumlah_pohon'),
        ];

        $this->petaniPohonModel->save($data);

        return redirect()->to('/petanipohon/' . $data['user_id'])
            ->with('success', 'Data pohon berhasil ditambahkan');
    }



    // Hapus data pohon (fungsi ini dimodifikasi)
    public function delete()
    {
        $id = $this->request->getPost('id');

        // 6. Tambahkan pengecekan izin sebelum menghapus
        if (!$this->hasActivePermission($id, 'delete')) {
            return redirect()->back()->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk menghapus data ini.');
        }

        if (!$id) {
            return redirect()->back()->with('error', 'ID pohon tidak ditemukan');
        }

        $pohon = $this->petaniPohonModel->find($id);
        if (!$pohon) {
            return redirect()->back()->with('error', 'Data pohon tidak ditemukan');
        }

        $this->petaniPohonModel->delete($id);
        return redirect()->back()->with('success', 'Data pohon berhasil dihapus');
    }

    // 7. [FUNGSI BARU] Untuk menangani permintaan izin via AJAX
    public function requestAccess()
    {
        if ($this->request->isAJAX()) {
            $pohonId = $this->request->getPost('pohon_id');
            $action = $this->request->getPost('action_type');
            $requesterId = session()->get('user_id');

            if (empty($requesterId)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Sesi tidak valid.'])->setStatusCode(401);
            }

            $existing = $this->permissionModel->where([
                'requester_id' => $requesterId,
                'target_id'    => $pohonId,
                'action_type'  => $action,
                'target_type'  => 'pohon', // Tandai ini sebagai permintaan untuk 'pohon'
                'status'       => 'pending'
            ])->first();

            if ($existing) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Anda sudah memiliki permintaan yang sama.']);
            }

            $this->permissionModel->save([
                'requester_id' => $requesterId,
                'target_id'    => $pohonId,
                'target_type'  => 'pohon',
                'action_type'  => $action,
                'status'       => 'pending',
            ]);

            return $this->response->setJSON(['status' => 'success', 'message' => 'Permintaan izin berhasil dikirim.']);
        }
        return redirect()->back();
    }

    // 8. [FUNGSI BARU] Helper untuk mengecek izin aktif
    private function hasActivePermission($pohonId, $action)
    {
        $requesterId = session()->get('user_id');
        if (empty($requesterId)) {
            return false;
        }

        $permission = $this->permissionModel->where([
            'requester_id' => $requesterId,
            'target_id'    => $pohonId,
            'target_type'  => 'pohon',
            'action_type'  => $action,
            'status'       => 'approved',
            'expires_at >' => date('Y-m-d H:i:s')
        ])->first();

        return $permission ? true : false;
    }
}
