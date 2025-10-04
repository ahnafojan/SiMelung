<?php

namespace App\Controllers;

use App\Models\KopiKeluarModel;
use App\Models\KopiMasukModel;
use App\Models\StokKopiModel;
use App\Models\PermissionRequestModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Kopikeluar extends BaseController
{
    protected $kopiKeluarModel;
    protected $kopiMasukModel;
    protected $stokKopiModel;
    protected $permissionModel;
    protected $db;

    public function __construct()
    {
        $this->kopiKeluarModel = new KopiKeluarModel();
        $this->kopiMasukModel  = new KopiMasukModel();
        $this->stokKopiModel   = new StokKopiModel();
        $this->permissionModel = new PermissionRequestModel();
        $this->db              = \Config\Database::connect();
        helper(['date']);
    }

    public function index()
    {
        // Ambil jumlah item per halaman dari URL, default-nya 10
        $perPage = $this->request->getVar('per_page') ?? 10;

        // Ambil data menggunakan method pagination
        $kopikeluar = $this->kopiKeluarModel->getAllWithPagination($perPage);

        // ▼▼▼ MULAI BAGIAN OPTIMASI & CACHING ▼▼▼

        // 1. Siapkan variabel yang dibutuhkan
        $requesterId = session()->get('user_id');
        $permissions = [];

        // 2. Kumpulkan semua ID kopi_keluar dari data yang tampil
        $kopiKeluarIds = array_column($kopikeluar, 'id');

        if (!empty($kopiKeluarIds) && !empty($requesterId)) {
            // 3. Buat cache key yang spesifik untuk 'kopi_keluar'
            $cacheKey = 'permissions_kopi_keluar_user_' . $requesterId;

            if (!$permissionData = cache($cacheKey)) {
                // Jika cache kosong, ambil semua data izin untuk 'kopi_keluar'
                $permissionData = $this->permissionModel // Pastikan model ini di-load
                    ->where('requester_id', $requesterId)
                    ->where('target_type', 'kopi_keluar') // <-- Target baru
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
        if (!empty($kopikeluar)) {
            foreach ($kopikeluar as &$kopi) {
                $kopi['edit_status']   = $permissions[$kopi['id']]['edit'] ?? 'none';
                $kopi['delete_status'] = $permissions[$kopi['id']]['delete'] ?? 'none';
            }
        }

        // ▲▲▲ SELESAI BAGIAN OPTIMASI & CACHING ▲▲▲

        // Kalkulasi total stok (logika ini tetap sama)
        $totalMasuk = $this->kopiMasukModel->selectSum('jumlah')->first()['jumlah'] ?? 0;
        $totalKeluar = $this->kopiKeluarModel->selectSum('jumlah')->first()['jumlah'] ?? 0;
        $stok = $totalMasuk - $totalKeluar;

        $data = [
            'title'       => 'Data Kopi Keluar',
            'kopikeluar'  => $kopikeluar,
            'stokKopi'    => $this->stokKopiModel->getWithJenis(),
            'stok'        => $stok,
            'pager'       => $this->kopiKeluarModel->pager,
            'perPage'     => $perPage,
            'currentPage' => $this->kopiKeluarModel->pager->getCurrentPage('kopikeluar'),
        ];
        $data['breadcrumbs'] = [
            [
                'title' => 'Dashboard',
                'url'   => site_url('/dashboard/dashboard_komersial'),
                'icon'  => 'fas fa-fw fa-tachometer-alt'
            ],
            [
                'title' => 'Kopi Keluar',
                'url'   => '#',
                'icon'  => 'fas fa-seedling'
            ]
        ];
        return view('admin_komersial/kopi/kopi-keluar', $data);
    }

    public function store()
    {
        $stokKopiId = $this->request->getPost('stok_kopi_id');
        $jumlahRaw  = $this->request->getPost('jumlah');

        // === Validasi stok_kopi_id ===
        if (empty($stokKopiId) || !is_numeric($stokKopiId)) {
            session()->setFlashdata('error', 'Pilih jenis kopi terlebih dahulu.');
            return redirect()->to(site_url('/kopikeluar'));
        }

        // === Bersihkan dan konversi jumlah ===
        // Hapus semua karakter kecuali angka, koma, dan titik
        $jumlahClean = preg_replace('/[^0-9.,]/', '', $jumlahRaw);
        // Ganti koma (desimal Indonesia) menjadi titik
        $jumlahClean = str_replace(',', '.', $jumlahClean);
        // Tangani kasus seperti "1.000.000" → ambil hanya bagian sebelum titik pertama jika lebih dari satu titik
        $parts = explode('.', $jumlahClean);
        if (count($parts) > 2) {
            // Gabungkan bagian integer, abaikan ribuan
            $integerPart = preg_replace('/[^0-9]/', '', $parts[0]);
            $decimalPart = $parts[1];
            $jumlahClean = $integerPart . '.' . $decimalPart;
        }
        $jumlah = floatval($jumlahClean);

        if ($jumlah <= 0) {
            session()->setFlashdata('error', 'Jumlah harus lebih dari 0.');
            return redirect()->to(site_url('/kopikeluar'));
        }

        // === Ambil stok terkini langsung dari database (bukan dari model cache) ===
        $stokRow = $this->db->table('stok_kopi')
            ->select('stok')
            ->where('id', (int)$stokKopiId)
            ->get()
            ->getRow();

        if (!$stokRow) {
            session()->setFlashdata('error', 'Data stok kopi tidak ditemukan.');
            return redirect()->to(site_url('/kopikeluar'));
        }

        $stokTerkini = (float) $stokRow->stok;

        if ($jumlah > $stokTerkini) {
            session()->setFlashdata('error', 'Gagal! Jumlah keluar melebihi stok yang tersedia (' . number_format($stokTerkini, 2, ',', '.') . ' Kg).');
            return redirect()->to(site_url('/kopikeluar'));
        }

        // === Simpan data dengan transaksi ===
        $this->db->transStart();
        try {
            // Simpan ke kopi_keluar
            $this->kopiKeluarModel->save([
                'stok_kopi_id' => (int)$stokKopiId,
                'tujuan'       => $this->request->getPost('tujuan'),
                'jumlah'       => $jumlah,
                'tanggal'      => $this->request->getPost('tanggal'),
                'keterangan'   => $this->request->getPost('keterangan'),
            ]);

            // Update stok dengan nilai baru (lebih aman daripada raw SQL)
            $newStok = $stokTerkini - $jumlah;
            $this->db->table('stok_kopi')
                ->where('id', (int)$stokKopiId)
                ->update(['stok' => $newStok]);

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                session()->setFlashdata('error', 'Terjadi kesalahan pada database saat menyimpan data.');
            } else {
                $this->db->transCommit();
                session()->setFlashdata('success', 'Data kopi keluar berhasil ditambahkan dan stok telah diperbarui.');
            }
        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'Error di Kopikeluar::store(): ' . $e->getMessage());
            session()->setFlashdata('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }

        return redirect()->to(site_url('/kopikeluar'));
    }
    public function edit($id)
    {
        if ($this->request->isAJAX()) {
            $data = $this->kopiKeluarModel->find($id);
            if ($data) {
                return $this->response->setJSON($data);
            }
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Data tidak ditemukan.']);
        }
        throw new PageNotFoundException('Halaman tidak ditemukan');
    }

    public function update($id)
    {
        if (!$this->hasActivePermission($id, 'edit')) {
            session()->setFlashdata('error', 'Akses ditolak. Anda tidak memiliki izin untuk mengedit data ini.');
            return redirect()->to('/kopikeluar');
        }
        $this->db->transStart();
        try {
            $dataLama = $this->kopiKeluarModel->find($id);
            if (!$dataLama) {
                throw new \Exception('Data yang akan diupdate tidak ditemukan.');
            }
            $jumlahLama = (float) $dataLama['jumlah'];
            $stokKopiIdLama = $dataLama['stok_kopi_id'];

            $dataBaru = [
                'stok_kopi_id' => $this->request->getPost('stok_kopi_id'),
                'tujuan'       => $this->request->getPost('tujuan'),
                'jumlah'       => (float) $this->request->getPost('jumlah'),
                'tanggal'      => $this->request->getPost('tanggal'),
                'keterangan'   => $this->request->getPost('keterangan'),
            ];
            $jumlahBaru = $dataBaru['jumlah'];
            $stokKopiIdBaru = $dataBaru['stok_kopi_id'];

            $this->stokKopiModel->set('stok', "stok + $jumlahLama", false)->where('id', $stokKopiIdLama)->update();

            $stokTerkini = $this->stokKopiModel->find($stokKopiIdBaru)['stok'] ?? 0;
            if ($jumlahBaru > $stokTerkini) {
                throw new \Exception('Gagal! Jumlah keluar melebihi stok yang tersedia (' . number_format($stokTerkini, 2, ',', '.') . ' Kg).');
            }
            $this->stokKopiModel->set('stok', "stok - $jumlahBaru", false)->where('id', $stokKopiIdBaru)->update();

            $this->kopiKeluarModel->update($id, $dataBaru);

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                session()->setFlashdata('error', 'Gagal memperbarui data karena kesalahan database.');
            } else {
                $this->db->transCommit();
                session()->setFlashdata('success', 'Data berhasil diperbarui dan stok telah disesuaikan.');
            }
        } catch (\Exception $e) {
            $this->db->transRollback();
            session()->setFlashdata('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
        return redirect()->to('/kopikeluar');
    }

    public function delete($id)
    {
        if (!$this->hasActivePermission($id, 'delete')) {
            session()->setFlashdata('error', 'Akses ditolak. Anda tidak memiliki izin untuk menghapus data ini.');
            return redirect()->to('/kopikeluar');
        }
        $this->db->transStart();
        try {
            $dataKeluar = $this->kopiKeluarModel->find($id);
            if ($dataKeluar) {
                $jumlah = (float) $dataKeluar['jumlah'];
                $stokKopiId = $dataKeluar['stok_kopi_id'];
                $this->stokKopiModel->set('stok', "stok + $jumlah", false)->where('id', $stokKopiId)->update();
                $this->kopiKeluarModel->delete($id);
                if ($this->db->transStatus() === false) {
                    $this->db->transRollback();
                    session()->setFlashdata('error', 'Gagal menghapus data karena kesalahan database.');
                } else {
                    $this->db->transCommit();
                    session()->setFlashdata('success', 'Data berhasil dihapus dan stok telah dikembalikan.');
                }
            } else {
                session()->setFlashdata('error', 'Data yang akan dihapus tidak ditemukan.');
            }
        } catch (\Exception $e) {
            $this->db->transRollback();
            session()->setFlashdata('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
        return redirect()->to('/kopikeluar');
    }
    public function requestAccess()
    {
        if ($this->request->isAJAX()) {
            $kopiKeluarId = $this->request->getPost('kopikeluar_id');
            $action       = $this->request->getPost('action_type');
            $requesterId  = session()->get('user_id');

            if (empty($requesterId)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Sesi tidak valid.'])->setStatusCode(401);
            }

            $existing = $this->permissionModel->where([
                'requester_id' => $requesterId,
                'target_id'    => $kopiKeluarId,
                'action_type'  => $action,
                'target_type'  => 'kopi_keluar',
                'status'       => 'pending'
            ])->first();

            if ($existing) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Anda sudah memiliki permintaan yang sama.']);
            }

            // ✅ Simpan permintaan
            $this->permissionModel->save([
                'requester_id' => $requesterId,
                'target_id'    => $kopiKeluarId,
                'target_type'  => 'kopi_keluar',
                'action_type'  => $action,
                'status'       => 'pending',
            ]);

            // ✅ INVALIDASI CACHE AGAR STATUS UPDATE SAAT REFRESH
            $cacheKey = 'permissions_kopi_keluar_user_' . $requesterId;
            cache()->delete($cacheKey);

            return $this->response->setJSON(['status' => 'success', 'message' => 'Permintaan izin berhasil dikirim.']);
        }
        return redirect()->back();
    }

    // 8. [FUNGSI BARU] Helper untuk mengecek izin aktif
    private function hasActivePermission($kopiKeluarId, $action)
    {
        $requesterId = session()->get('user_id');
        if (empty($requesterId)) {
            return false;
        }

        $permission = $this->permissionModel->where([
            'requester_id' => $requesterId,
            'target_id'    => $kopiKeluarId,
            'target_type'  => 'kopi_keluar',
            'action_type'  => $action,
            'status'       => 'approved',
            'expires_at >' => date('Y-m-d H:i:s')
        ])->first();

        return $permission ? true : false;
    }
    private function getPermissionStatus($kopiKeluarId, $action)
    {
        $requesterId = session()->get('user_id');
        if (empty($requesterId)) {
            return 'none';
        }

        // 1. Cek dulu apakah izin sudah 'approved' dan aktif
        $approved = $this->permissionModel->where([
            'requester_id' => $requesterId,
            'target_id'    => $kopiKeluarId,
            'target_type'  => 'kopi_keluar',
            'action_type'  => $action,
            'status'       => 'approved',
            'expires_at >' => date('Y-m-d H:i:s')
        ])->first();

        if ($approved) {
            return 'approved';
        }

        // 2. Jika tidak, cek apakah ada permintaan 'pending'
        $pending = $this->permissionModel->where([
            'requester_id' => $requesterId,
            'target_id'    => $kopiKeluarId,
            'target_type'  => 'kopi_keluar',
            'action_type'  => $action,
            'status'       => 'pending'
        ])->first();

        if ($pending) {
            return 'pending';
        }

        // 3. Jika tidak keduanya, berarti belum ada aksi
        return 'none';
    }
}
