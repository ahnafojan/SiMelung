<?php

namespace App\Controllers;

use App\Models\KopiKeluarModel;
use App\Models\KopiMasukModel;
use App\Models\StokKopiModel;
use App\Models\PermissionRequestModel;
use App\Models\HargaJenisKopiModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Kopikeluar extends BaseController
{
    protected $kopiKeluarModel;
    protected $kopiMasukModel;
    protected $stokKopiModel;
    protected $permissionModel;
    protected $hargaJenisKopiModel;
    protected $db;

    public function __construct()
    {
        $this->kopiKeluarModel = new KopiKeluarModel();
        $this->kopiMasukModel  = new KopiMasukModel();
        $this->stokKopiModel   = new StokKopiModel();
        $this->permissionModel = new PermissionRequestModel();
        $this->hargaJenisKopiModel = new HargaJenisKopiModel();
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
                $permissionData = $this->permissionModel
                    ->where('requester_id', $requesterId)
                    ->where('target_type', 'kopi_keluar')
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

        // ✅ TAMBAHAN: Ambil daftar petani untuk dropdown
        $petaniList = $this->db->table('petani')
            ->select('user_id, nama')
            ->orderBy('nama', 'ASC')
            ->get()
            ->getResultArray();

        $data = [
            'title'       => 'Data Kopi Keluar',
            'kopikeluar'  => $kopikeluar,
            'stokKopi'    => $this->stokKopiModel->getWithJenis(),
            'petaniList'  => $petaniList, // ✅ TAMBAHAN
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

    /**
     * ✅ METHOD BARU: Ambil jenis pohon berdasarkan petani (untuk AJAX)
     */
    public function getJenisPohonByPetani()
    {
        if ($this->request->isAJAX()) {
            $petaniId = $this->request->getPost('petani_id');

            if (empty($petaniId)) {
                return $this->response->setJSON([]);
            }

            $data = $this->db->table('stok_kopi')
                ->select('
                    stok_kopi.id, 
                    jenis_pohon.nama_jenis, 
                    stok_kopi.stok
                ')
                ->join('jenis_pohon', 'jenis_pohon.id = stok_kopi.jenis_pohon_id', 'left')
                ->where('stok_kopi.petani_id', $petaniId)
                ->where('stok_kopi.stok >', 0) // Hanya tampilkan yang ada stoknya
                ->orderBy('jenis_pohon.nama_jenis', 'ASC')
                ->get()
                ->getResultArray();

            return $this->response->setJSON($data);
        }

        return $this->response->setStatusCode(403)->setJSON(['error' => 'Forbidden']);
    }

    public function store()
    {
        $stokKopiId = $this->request->getPost('stok_kopi_id');
        $jumlahRaw  = $this->request->getPost('jumlah');
        $tanggal    = $this->request->getPost('tanggal');

        // === Validasi stok_kopi_id ===
        if (empty($stokKopiId) || !is_numeric($stokKopiId)) {
            session()->setFlashdata('error', 'Pilih jenis kopi terlebih dahulu.');
            return redirect()->to(site_url('/kopikeluar'));
        }

        // === Bersihkan jumlah ===
        $jumlahClean = preg_replace('/[^0-9.,]/', '', $jumlahRaw);
        $jumlahClean = str_replace(',', '.', $jumlahClean);
        $parts = explode('.', $jumlahClean);
        if (count($parts) > 2) {
            $integerPart = preg_replace('/[^0-9]/', '', $parts[0]);
            $decimalPart = $parts[1];
            $jumlahClean = $integerPart . '.' . $decimalPart;
        }
        $jumlah = floatval($jumlahClean);

        if ($jumlah <= 0) {
            session()->setFlashdata('error', 'Jumlah harus lebih dari 0.');
            return redirect()->to(site_url('/kopikeluar'));
        }

        // === Ambil jenis_pohon_id dari stok_kopi ===
        $stokRow = $this->db->table('stok_kopi')
            ->select('jenis_pohon_id, petani_id')
            ->where('id', (int)$stokKopiId)
            ->get()
            ->getRow();

        if (!$stokRow) {
            session()->setFlashdata('error', 'Data stok kopi tidak ditemukan.');
            return redirect()->to(site_url('/kopikeluar'));
        }

        $jenisPohonId = $stokRow->jenis_pohon_id;

        // === Ambil harga jual terbaru berdasarkan tanggal transaksi ===
        $hargaData = $this->hargaJenisKopiModel->getLatestPrice($jenisPohonId, $tanggal);

        if (!$hargaData) {
            session()->setFlashdata('error', 'Tidak ada harga jual yang berlaku untuk jenis kopi ini pada tanggal ' . date('d-m-Y', strtotime($tanggal)) . '. Harap atur harga terlebih dahulu.');
            return redirect()->to(site_url('/kopikeluar'));
        }

        $hargaSaatTransaksi = (float) $hargaData['harga_jual_per_kg'];
        $totalHargaJual     = $jumlah * $hargaSaatTransaksi;

        // === Validasi stok ===
        $stokTerkiniRow = $this->db->table('stok_kopi')
            ->select('stok')
            ->where('id', (int)$stokKopiId)
            ->get()
            ->getRow();

        if (!$stokTerkiniRow) {
            session()->setFlashdata('error', 'Data stok tidak ditemukan.');
            return redirect()->to(site_url('/kopikeluar'));
        }

        $stokTerkini = (float) $stokTerkiniRow->stok;
        if ($jumlah > $stokTerkini) {
            session()->setFlashdata('error', 'Gagal! Jumlah keluar melebihi stok yang tersedia (' . number_format($stokTerkini, 2, ',', '.') . ' Kg).');
            return redirect()->to(site_url('/kopikeluar'));
        }

        // === Simpan dengan transaksi ===
        $this->db->transStart();
        try {
            $this->kopiKeluarModel->save([
                'stok_kopi_id'         => (int)$stokKopiId,
                'tujuan'               => $this->request->getPost('tujuan'),
                'jumlah'               => $jumlah,
                'tanggal'              => $tanggal,
                'keterangan'           => $this->request->getPost('keterangan'),
                'harga_saat_transaksi' => $hargaSaatTransaksi,
                'total_harga_jual'     => $totalHargaJual,
            ]);

            // Update stok
            $newStok = $stokTerkini - $jumlah;
            $this->db->table('stok_kopi')
                ->where('id', (int)$stokKopiId)
                ->update(['stok' => $newStok]);

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                session()->setFlashdata('error', 'Terjadi kesalahan pada database saat menyimpan data.');
            } else {
                $this->db->transCommit();
                session()->setFlashdata('success', 'Data kopi keluar berhasil ditambahkan dengan harga jual otomatis.');
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
            $data = $this->kopiKeluarModel
                ->select('
                    kopi_keluar.*, 
                    stok_kopi.petani_id
                ')
                ->join('stok_kopi', 'stok_kopi.id = kopi_keluar.stok_kopi_id', 'left')
                ->where('kopi_keluar.id', $id)
                ->first();

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
            // Ambil data lama
            $dataLama = $this->kopiKeluarModel->find($id);
            if (!$dataLama) {
                throw new \Exception('Data yang akan diupdate tidak ditemukan.');
            }

            $jumlahLama       = (float) $dataLama['jumlah'];
            $stokKopiIdLama   = $dataLama['stok_kopi_id'];

            // Ambil input baru
            $stokKopiIdBaru = (int) $this->request->getPost('stok_kopi_id');
            $jumlahBaru     = (float) $this->request->getPost('jumlah');
            $tanggalBaru    = $this->request->getPost('tanggal');

            // === Validasi jumlah ===
            if ($jumlahBaru <= 0) {
                throw new \Exception('Jumlah harus lebih dari 0.');
            }

            // === Ambil jenis_pohon_id dari stok_kopi_id baru ===
            $stokRowBaru = $this->db->table('stok_kopi')
                ->select('jenis_pohon_id')
                ->where('id', $stokKopiIdBaru)
                ->get()
                ->getRow();

            if (!$stokRowBaru) {
                throw new \Exception('Jenis pohon tidak ditemukan untuk stok kopi yang dipilih.');
            }

            $jenisPohonIdBaru = $stokRowBaru->jenis_pohon_id;

            // === Ambil harga jual terbaru berdasarkan tanggal transaksi BARU ===
            $hargaData = $this->hargaJenisKopiModel->getLatestPrice($jenisPohonIdBaru, $tanggalBaru);

            if (!$hargaData) {
                throw new \Exception('Tidak ada harga jual yang berlaku untuk jenis kopi ini pada tanggal ' . date('d-m-Y', strtotime($tanggalBaru)) . '. Harap atur harga terlebih dahulu.');
            }

            $hargaSaatTransaksiBaru = (float) $hargaData['harga_jual_per_kg'];
            $totalHargaJualBaru     = $jumlahBaru * $hargaSaatTransaksiBaru;

            // === Kembalikan stok lama (karena data lama akan diubah) ===
            $this->stokKopiModel->set('stok', "stok + $jumlahLama", false)
                ->where('id', $stokKopiIdLama)
                ->update();

            // === Cek stok baru setelah pengembalian stok lama ===
            $stokTerkiniBaru = $this->stokKopiModel->find($stokKopiIdBaru)['stok'] ?? 0;

            if ($jumlahBaru > $stokTerkiniBaru) {
                throw new \Exception('Gagal! Jumlah keluar melebihi stok yang tersedia (' . number_format($stokTerkiniBaru, 2, ',', '.') . ' Kg).');
            }

            // === Kurangi stok baru ===
            $this->stokKopiModel->set('stok', "stok - $jumlahBaru", false)
                ->where('id', $stokKopiIdBaru)
                ->update();

            // === Simpan data kopi keluar yang diperbarui ===
            $dataUpdate = [
                'stok_kopi_id'         => $stokKopiIdBaru,
                'tujuan'               => $this->request->getPost('tujuan'),
                'jumlah'               => $jumlahBaru,
                'tanggal'              => $tanggalBaru,
                'keterangan'           => $this->request->getPost('keterangan'),
                'harga_saat_transaksi' => $hargaSaatTransaksiBaru,
                'total_harga_jual'     => $totalHargaJualBaru,
            ];

            $this->kopiKeluarModel->update($id, $dataUpdate);

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

    /**
     * Helper untuk mengecek izin aktif
     */
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

    /**
     * Helper untuk mendapatkan status permission
     */
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
