<?php

namespace App\Controllers;

use App\Models\PermissionRequestModel;
use App\Models\PetaniModel; // 1. Tambahkan PetaniModel
use CodeIgniter\Controller;

class PersetujuanKomersial extends BaseController
{
    protected $permissionModel;
    protected $petaniModel; // 2. Daftarkan PetaniModel

    public function __construct()
    {
        $this->permissionModel = new PermissionRequestModel();
        $this->petaniModel = new PetaniModel(); // 3. Inisialisasi PetaniModel
        helper('date');
    }

    public function index()
{
    $data['requests'] = $this->permissionModel
        ->select([
            'permission_requests.*',
            'users.username as requester_name',
            'p_target.nama as petani_target_name',
            'p_owner.nama as pohon_owner_name',
            'jp.nama_jenis as pohon_jenis_name',
            'km.jumlah as kopimasuk_jumlah',
            'km.tanggal as kopimasuk_tanggal',
            'p_kopimasuk.nama as kopimasuk_petani_name',
            'k_keluar.jumlah as kopikeluar_jumlah',
            'k_keluar.tujuan as kopikeluar_tujuan',
            'jp_keluar.nama_jenis as kopikeluar_jenis_kopi',
            'jp_master.nama_jenis as jenispohon_target_name',
            'aset.nama_aset as aset_target_name',
            'aset.kode_aset as aset_target_kode',
            // 🔹 Tambahan untuk UMKM
            'umkm.nama_umkm as umkm_nama',
            'umkm.pemilik as umkm_pemilik',
            'umkm.alamat as umkm_alamat',
            'umkm.kontak as umkm_kontak'
        ])
        ->join('users', 'users.id = permission_requests.requester_id', 'left')
        ->join('petani as p_target', 'p_target.id = permission_requests.target_id AND permission_requests.target_type = "petani"', 'left')
        ->join('petani_pohon as pp', 'pp.id = permission_requests.target_id AND permission_requests.target_type = "pohon"', 'left')
        ->join('petani as p_owner', 'p_owner.user_id = pp.user_id', 'left')
        ->join('jenis_pohon as jp', 'jp.id = pp.jenis_pohon_id', 'left')
        ->join('kopi_masuk as km', 'km.id = permission_requests.target_id AND permission_requests.target_type = "kopi_masuk"', 'left')
        ->join('petani as p_kopimasuk', 'p_kopimasuk.user_id = km.petani_user_id', 'left')
        ->join('kopi_keluar as k_keluar', 'k_keluar.id = permission_requests.target_id AND permission_requests.target_type = "kopi_keluar"', 'left')
        ->join('stok_kopi as sk', 'sk.id = k_keluar.stok_kopi_id', 'left')
        ->join('jenis_pohon as jp_keluar', 'jp_keluar.id = sk.jenis_pohon_id', 'left')
        ->join('jenis_pohon as jp_master', 'jp_master.id = permission_requests.target_id AND permission_requests.target_type = "jenis_pohon"', 'left')
        ->join('master_aset as aset', 'aset.id_aset = permission_requests.target_id AND permission_requests.target_type = "aset"', 'left')
        // 🔹 JOIN baru untuk UMKM
        ->join('umkm', 'umkm.id = permission_requests.target_id AND permission_requests.target_type = "umkm"', 'left')

        ->where('permission_requests.status', 'pending')
        ->orderBy('permission_requests.created_at', 'DESC')
        ->paginate(10); // 🔹 ganti findAll() jadi paginate

    $data['pager'] = $this->permissionModel->pager;

    return view('bumdes/persetujuan/admin_komersial/index', $data);
}
    public function respond()
    {
        // Fungsi respond tidak perlu diubah, biarkan seperti sebelumnya
        if ($this->request->isAJAX()) {
            $requestId = $this->request->getPost('request_id');
            $decision  = $this->request->getPost('decision');

            if (empty($requestId) || !in_array($decision, ['approve', 'reject'])) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Input tidak valid.'])->setStatusCode(400);
            }

            $responderId = session()->get('user_id');

            $updateData = [
                'status'       => ($decision == 'approve') ? 'approved' : 'rejected',
                'responder_id' => $responderId,
                'responded_at' => date('Y-m-d H:i:s')
            ];

            if ($decision == 'approve') {
                $updateData['expires_at'] = date('Y-m-d H:i:s', strtotime('+1 hour'));
            }

            if ($this->permissionModel->update($requestId, $updateData)) {
                return $this->response->setJSON(['status' => 'success', 'message' => 'Permintaan berhasil direspon.']);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal memperbarui database.'])->setStatusCode(500);
            }
        }
        return redirect()->back();
    }
}
