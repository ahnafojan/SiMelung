<?php

namespace App\Controllers;

use App\Models\ObjekWisataModel;
use App\Models\PermissionRequestModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class ObjekWisata extends BaseController
{
    protected $wisataModel;
    protected $permissionModel;

    public function __construct()
    {
        $this->wisataModel = new ObjekWisataModel();
        $this->permissionModel = new PermissionRequestModel();
        helper(['date']);
    }

    public function index()
    {
        // Ambil parameter pagination dari URL
        $perPage = $this->request->getGet('per_page') ?? 10;
        $perPage = in_array($perPage, [10, 25, 100]) ? $perPage : 10;

        // Query dengan pagination
        $list_wisata = $this->wisataModel->orderBy('nama_wisata', 'ASC')->paginate($perPage, 'default');
        $pager = $this->wisataModel->pager;

        // Ambil ID admin yang sedang login
        $requesterId = session()->get('user_id');

        // Tambahkan status permission untuk setiap objek wisata
        if (!empty($list_wisata) && !empty($requesterId)) {
            foreach ($list_wisata as &$wisata) {
                $wisata['edit_status'] = $this->getPermissionStatus($wisata['id'], 'edit');
                $wisata['delete_status'] = $this->getPermissionStatus($wisata['id'], 'delete');
            }
        }

        $data = [
            'title'       => 'Manajemen Objek Wisata',
            'list_wisata' => $list_wisata,
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
                'title' => 'Manajemen Objek Wisata',
                'url'   => '#',
                'icon'  => 'fas fa-mountain'
            ]
        ];

        return view('admin_pariwisata/objek_pariwisata', $data);
    }

    public function store()
    {
        $id = $this->request->getPost('id');

        // Jika ini adalah operasi UPDATE, cek izin terlebih dahulu
        if (!empty($id) && !$this->hasActivePermission($id, 'edit')) {
            return redirect()->to('/objekwisata')->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk mengedit data ini.');
        }

        $data = [
            'nama_wisata' => $this->request->getPost('nama_wisata'),
            'lokasi'      => $this->request->getPost('lokasi'),
            'deskripsi'   => $this->request->getPost('deskripsi'),
        ];

        // Jika ada ID, tambahkan ke data untuk operasi update
        if (!empty($id)) {
            $data['id'] = $id;
        }

        if (empty($data['nama_wisata']) || empty($data['lokasi'])) {
            return redirect()->back()->withInput()->with('error', 'Nama wisata dan lokasi wajib diisi.');
        }

        if ($this->wisataModel->save($data)) {
            $message = !empty($id) ? 'Data objek wisata berhasil diperbarui.' : 'Data objek wisata baru berhasil ditambahkan.';
            return redirect()->to('/objekwisata')->with('success', $message);
        } else {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function delete($id = null)
    {
        if (!$id) {
            throw PageNotFoundException::forPageNotFound();
        }

        // Cek izin sebelum menghapus
        if ($this->getPermissionStatus($id, 'delete') !== 'approved') {
            return redirect()->to('/objekwisata')->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk menghapus data ini.');
        }

        if ($this->wisataModel->delete($id)) {
            return redirect()->to('/objekwisata')->with('success', 'Data objek wisata berhasil dihapus.');
        } else {
            return redirect()->to('/objekwisata')->with('error', 'Gagal menghapus data.');
        }
    }

    public function requestaccess() // <-- Sesuaikan dengan route: huruf kecil semua
    {
        if ($this->request->isAJAX()) {
            $wisataId = $this->request->getPost('wisata_id');
            $action = $this->request->getPost('action_type');
            $requesterId = session()->get('user_id');

            if (empty($requesterId)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Sesi tidak valid.'
                ])->setStatusCode(401);
            }

            $existing = $this->permissionModel->where([
                'requester_id' => $requesterId,
                'target_id'    => $wisataId,
                'action_type'  => $action,
                'target_type'  => 'objek_wisata',
                'status'       => 'pending'
            ])->first();

            if ($existing) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Anda sudah mengajukan permintaan serupa.'
                ]);
            }

            $this->permissionModel->save([
                'requester_id' => $requesterId,
                'target_id'    => $wisataId,
                'target_type'  => 'objek_wisata',
                'action_type'  => $action,
                'status'       => 'pending',
            ]);

            // Hapus cache agar status terupdate
            $cacheKey = 'permissions_objek_wisata_user_' . $requesterId;
            cache()->delete($cacheKey);

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Permintaan izin berhasil dikirim.'
            ]);
        }
        return redirect()->back();
    }

    private function hasActivePermission($wisataId, $action)
    {
        $requesterId = session()->get('user_id');
        if (empty($requesterId)) {
            return false;
        }

        $permission = $this->permissionModel->where([
            'requester_id' => $requesterId,
            'target_id'    => $wisataId,
            'target_type'  => 'objek_wisata',
            'action_type'  => $action,
            'status'       => 'approved',
        ])->where('expires_at >', date('Y-m-d H:i:s'))->first();

        return $permission ? true : false;
    }

    private function getPermissionStatus($wisataId, $action)
    {
        $requesterId = session()->get('user_id');
        if (empty($requesterId)) {
            return null;
        }

        // Cek izin 'approved' yang masih aktif
        $approved = $this->permissionModel->where([
            'requester_id' => $requesterId,
            'target_id'    => $wisataId,
            'target_type'  => 'objek_wisata',
            'action_type'  => $action,
            'status'       => 'approved',
        ])->where('expires_at >', date('Y-m-d H:i:s'))->first();

        if ($approved) {
            return 'approved';
        }

        // Cek permintaan yang 'pending'
        $pending = $this->permissionModel->where([
            'requester_id' => $requesterId,
            'target_id'    => $wisataId,
            'target_type'  => 'objek_wisata',
            'action_type'  => $action,
            'status'       => 'pending'
        ])->first();

        if ($pending) {
            return 'pending';
        }

        // Jika tidak ada, kembalikan null
        return null;
    }
}
