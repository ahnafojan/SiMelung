<?php

namespace App\Controllers;

use App\Models\ObjekWisataModel;
use App\Models\PermissionRequestModel; // PASTIKAN ANDA MEMBUAT MODEL INI
use CodeIgniter\Exceptions\PageNotFoundException;

class ObjekWisata extends BaseController
{
    protected $wisataModel;
    protected $permissionModel;

    /**
     * Menginisialisasi model yang akan digunakan.
     */
    public function __construct()
    {
        $this->wisataModel = new ObjekWisataModel();
        // Diasumsikan Anda sudah membuat PermissionRequestModel
        // Jika belum, Anda harus membuatnya sesuai dengan logika di controller AsetPariwisata
        $this->permissionModel = new PermissionRequestModel();
        helper(['date']);
    }

    /**
     * Menampilkan halaman utama manajemen objek wisata dengan pagination dan permission.
     */
    public function index()
    {
        // Ambil parameter pagination dari URL
        $perPage = $this->request->getGet('per_page') ?? 10;
        $perPage = in_array($perPage, [10, 25, 100]) ? $perPage : 10;

        // Query dengan pagination
        $list_wisata = $this->wisataModel->orderBy('nama_wisata', 'ASC')->paginate($perPage, 'default');
        $pager = $this->wisataModel->pager;

        // ▼▼▼ MULAI BAGIAN OPTIMASI & CACHING ▼▼▼

        // 1. Siapkan variabel yang dibutuhkan
        $requesterId = session()->get('user_id');
        $permissions = [];

        // 2. Kumpulkan semua ID objek wisata dari data yang tampil
        $wisataIds = array_column($list_wisata, 'id');

        if (!empty($wisataIds) && !empty($requesterId)) {
            // 3. Buat cache key yang spesifik untuk 'objek_wisata'
            $cacheKey = 'permissions_objek_wisata_user_' . $requesterId;

            if (!$permissionData = cache($cacheKey)) {
                // Jika cache kosong, ambil semua data izin untuk 'objek_wisata'
                $permissionData = $this->permissionModel // Pastikan model ini di-load
                    ->where('requester_id', $requesterId)
                    ->where('target_type', 'objek_wisata') // <-- Target baru
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
        if (!empty($list_wisata)) {
            foreach ($list_wisata as &$wisata) {
                $wisata['edit_status']   = $permissions[$wisata['id']]['edit'] ?? 'none';
                $wisata['delete_status'] = $permissions[$wisata['id']]['delete'] ?? 'none';
            }
        }

        // ▲▲▲ SELESAI BAGIAN OPTIMASI & CACHING ▲▲▲

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
                'title' => 'Manajemen Objek Wisata', // Judul breadcrumb disesuaikan
                'url'   => '#',
                'icon'  => 'fas fa-mountain'
            ]
        ];
        return view('admin_pariwisata/objek_pariwisata', $data);
    }

    /**
     * Menyimpan data baru atau memperbarui data yang ada dengan cek izin.
     */
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

    /**
     * Menghapus data objek wisata dengan cek izin.
     */
    public function delete($id = null)
    {
        if (!$id) {
            throw PageNotFoundException::forPageNotFound();
        }

        // PENTING: Cek izin sebelum menghapus
        if (!$this->hasActivePermission($id, 'delete')) {
            return redirect()->to('/objekwisata')->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk menghapus data ini.');
        }

        // Tambahkan validasi lain jika diperlukan di sini
        // ...

        if ($this->wisataModel->delete($id)) {
            return redirect()->to('/objekwisata')->with('success', 'Data objek wisata berhasil dihapus.');
        } else {
            return redirect()->to('/objekwisata')->with('error', 'Gagal menghapus data.');
        }
    }

    /**
     * Menangani permintaan izin via AJAX.
     */
    public function requestAccess()
    {
        if ($this->request->isAJAX()) {
            $wisataId = $this->request->getPost('wisata_id');
            $action = $this->request->getPost('action_type');
            $requesterId = session()->get('user_id'); // Pastikan 'user_id' ada di session

            if (empty($requesterId)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Sesi tidak valid.'])->setStatusCode(401);
            }

            // Cek apakah sudah ada permintaan yang sama dan masih pending
            $existing = $this->permissionModel->where([
                'requester_id' => $requesterId,
                'target_id'    => $wisataId,
                'action_type'  => $action,
                'target_type'  => 'objek_wisata', // Penting untuk membedakan target
                'status'       => 'pending'
            ])->first();

            if ($existing) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Anda sudah mengajukan permintaan serupa.']);
            }

            // Simpan permintaan baru
            $this->permissionModel->save([
                'requester_id' => $requesterId,
                'target_id'    => $wisataId,
                'target_type'  => 'objek_wisata', // Sesuaikan dengan konteks
                'action_type'  => $action,
                'status'       => 'pending',
            ]);

            return $this->response->setJSON(['status' => 'success', 'message' => 'Permintaan izin berhasil dikirim.']);
        }
        return redirect()->back();
    }

    /**
     * Helper untuk mengecek izin aktif yang belum kedaluwarsa.
     */
    private function hasActivePermission($wisataId, $action)
    {
        // Bypass untuk admin atau peran tertentu (opsional)
        // if (session()->get('role') === 'admin') {
        //     return true;
        // }

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
            'expires_at >' => date('Y-m-d H:i:s')
        ])->first();

        return $permission ? true : false;
    }
    private function getPermissionStatus($wisataId, $action)
    {
        $requesterId = session()->get('user_id');
        if (empty($requesterId)) {
            return 'none';
        }

        // 1. Cek izin 'approved' yang masih aktif
        $approved = $this->permissionModel->where([
            'requester_id' => $requesterId,
            'target_id'    => $wisataId,
            'target_type'  => 'objek_wisata',
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
            'target_id'    => $wisataId,
            'target_type'  => 'objek_wisata',
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
