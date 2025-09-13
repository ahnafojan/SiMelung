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

        // 1. Ambil ID admin yang sedang login
        $requesterId = session()->get('user_id');
        $permissions = []; // Siapkan array untuk menampung status izin

        // 2. Kumpulkan semua ID pohon yang akan ditampilkan di halaman ini
        $pohonIds = array_column($detailPohon, 'id');

        if (!empty($pohonIds) && !empty($requesterId)) {

            // 3. Gunakan cache key yang konsisten untuk izin 'pohon'
            //    Pastikan cache ini juga dihapus saat ada persetujuan untuk target 'pohon'
            $cacheKey = 'permissions_pohon_user_' . $requesterId;

            if (!$permissionData = cache($cacheKey)) {
                // Jika tidak ada di cache, ambil SEMUA izin 'pohon' milik user ini dalam 1 query
                $permissionData = $this->permissionModel // Pastikan Anda punya $this->permissionModel
                    ->where('requester_id', $requesterId)
                    ->where('target_type', 'pohon') // Fokus pada izin untuk pohon
                    ->whereIn('status', ['approved', 'pending'])
                    ->findAll();

                // Simpan ke cache selama 5 menit
                cache()->save($cacheKey, $permissionData, 300);
            }
            if (!empty($permissionData)) {
                foreach ($permissionData as $perm) {
                    // Cek izin 'approved' yang masih aktif (belum kedaluwarsa)
                    if ($perm['status'] == 'approved' && strtotime($perm['expires_at']) > now('Asia/Jakarta')) {
                        $permissions[$perm['target_id']][$perm['action_type']] = 'approved';
                    } elseif ($perm['status'] == 'pending') {
                        $permissions[$perm['target_id']][$perm['action_type']] = 'pending';
                    }
                }
            }
        }
        if (!empty($detailPohon)) {
            foreach ($detailPohon as &$pohon) {
                // Hanya menetapkan delete_status sesuai kebutuhan
                $pohon['delete_status'] = $permissions[$pohon['id']]['delete'] ?? 'none';
            }
        }
        // ▲▲▲ SELESAI BAGIAN OPTIMASI ▲▲▲

        return view('admin_komersial/petani/petanipohon', [
            'petani'      => $petani,
            'jenisPohon'  => $jenisPohon,
            'detailPohon' => $detailPohon, // Sekarang sudah berisi 'delete_status'
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
    private function getPermissionStatus($pohonId, $action)
    {
        $requesterId = session()->get('user_id');
        if (empty($requesterId)) {
            return 'none';
        }

        // 1. Cek izin yang 'approved' dan masih aktif
        $approved = $this->permissionModel->where([
            'requester_id' => $requesterId,
            'target_id'    => $pohonId,
            'target_type'  => 'pohon',
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
            'target_id'    => $pohonId,
            'target_type'  => 'pohon',
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
