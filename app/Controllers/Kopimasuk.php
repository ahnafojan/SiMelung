<?php

namespace App\Controllers;

use App\Models\KopiMasukModel;
use App\Models\PetaniModel;
use CodeIgniter\Controller;
use App\Models\PetaniPohonModel;
use App\Models\StokKopiModel;
use App\Models\PermissionRequestModel;

class KopiMasuk extends Controller
{
    protected $kopiMasukModel;
    protected $petaniModel;
    protected $petaniPohonModel;
    protected $stokKopiModel;
    protected $permissionModel;

    public function __construct()
    {
        $this->kopiMasukModel = new KopiMasukModel();
        $this->petaniModel      = new PetaniModel();
        $this->petaniPohonModel = new PetaniPohonModel();
        $this->stokKopiModel = new StokKopiModel();
        $this->permissionModel  = new PermissionRequestModel();
        helper(['form', 'url', 'date']);
    }

    public function index()
    {
        // [MODIFIKASI] Ambil jumlah per halaman dari URL, default-nya 10
        $perPage = $this->request->getGet('per_page') ?? 10;

        // Ambil data kopi masuk menggunakan paginate
        $data['kopiMasuk'] = $this->kopiMasukModel->getAllWithPagination($perPage);

        // Ambil pager dari model
        $data['pager'] = $this->kopiMasukModel->pager;

        // [MODIFIKASI] Kirim variabel perPage dan currentPage ke view
        $data['currentPage'] = $data['pager']->getCurrentPage();
        $data['perPage'] = $perPage;

        // untuk dropdown petani
        $data['petani'] = $this->petaniModel->orderBy('nama', 'ASC')->findAll();

        // Cek izin untuk setiap item di halaman saat ini
        if (!empty($data['kopiMasuk'])) {
            foreach ($data['kopiMasuk'] as &$kopi) {
                $kopi['can_edit']   = $this->hasActivePermission($kopi['id'], 'edit');
                $kopi['can_delete'] = $this->hasActivePermission($kopi['id'], 'delete');
            }
        }

        return view('admin_komersial/kopi/kopi-masuk', $data);
    }


    public function getJenisPohon($petaniId)
    {
        $jenisPohon = $this->petaniPohonModel
            ->select('petani_pohon.id, jenis_pohon.nama_jenis')
            ->join('jenis_pohon', 'jenis_pohon.id = petani_pohon.jenis_pohon_id')
            ->where('petani_pohon.user_id', $petaniId)
            ->findAll();

        return $this->response->setJSON($jenisPohon);
    }


    public function store()
    {
        $db = \Config\Database::connect();
        $db->transStart(); // Mulai transaksi
        log_message('debug', '================= AWAL TRANSAKSI =================');

        try {
            // Ambil input dari form
            $petaniUserId   = $this->request->getPost('petani_user_id');
            $petaniPohonId  = $this->request->getPost('petani_pohon_id');
            $jumlah         = (float) $this->request->getPost('jumlah');
            log_message('debug', 'Input Form => petani_user_id: ' . $petaniUserId . ', petani_pohon_id: ' . $petaniPohonId . ', jumlah: ' . $jumlah);

            // Simpan transaksi kopi masuk
            $dataKopiMasuk = [
                'petani_user_id'  => $petaniUserId,
                'petani_pohon_id' => $petaniPohonId,
                'jumlah'          => $jumlah,
                'tanggal'         => $this->request->getPost('tanggal'),
                'keterangan'      => $this->request->getPost('keterangan'),
            ];
            log_message('debug', 'STEP 1: Menyiapkan data untuk disimpan ke kopi_masuk: ' . json_encode($dataKopiMasuk));

            $this->kopiMasukModel->save($dataKopiMasuk);
            log_message('debug', 'STEP 2: SUKSES menyimpan data ke kopi_masuk.');

            // Cari jenis pohon berdasarkan petani_pohon_id
            $petaniPohon = $this->petaniPohonModel->find($petaniPohonId);
            log_message('debug', 'STEP 3: Mencari data petani_pohon dengan ID ' . $petaniPohonId . '. Hasil: ' . json_encode($petaniPohon));

            if ($petaniPohon) {
                $petaniId     = $petaniPohon['user_id'];
                $jenisPohonId = $petaniPohon['jenis_pohon_id'];
                log_message('debug', 'Petani ID: ' . $petaniId . ' | Jenis Pohon ID: ' . $jenisPohonId);

                // Cek apakah stok sudah ada
                $stok = $this->stokKopiModel
                    ->where('petani_id', $petaniId)
                    ->where('jenis_pohon_id', $jenisPohonId)
                    ->first();
                log_message('debug', 'STEP 4: Mencari stok lama. Hasil: ' . json_encode($stok));

                if ($stok) {
                    // Update stok
                    $newStok = $stok['stok'] + $jumlah;
                    log_message('debug', 'STEP 5a: Akan UPDATE stok. ID Stok: ' . $stok['id'] . ', Stok Baru: ' . $newStok);
                    $this->stokKopiModel->update($stok['id'], ['stok' => $newStok]);
                    log_message('debug', 'STEP 6a: SUKSES UPDATE stok.');
                } else {
                    // Insert stok baru
                    $dataStokBaru = [
                        'petani_id'      => $petaniId,
                        'jenis_pohon_id' => $jenisPohonId,
                        'stok'           => $jumlah
                    ];
                    log_message('debug', 'STEP 5b: Akan INSERT stok baru: ' . json_encode($dataStokBaru));
                    $this->stokKopiModel->insert($dataStokBaru);
                    log_message('debug', 'STEP 6b: SUKSES INSERT stok baru.');
                }
            } else {
                log_message('error', 'Petani pohon dengan ID ' . $petaniPohonId . ' tidak ditemukan! Transaksi dibatalkan.');
                throw new \Exception('Data petani pohon tidak ditemukan.');
            }

            $db->transComplete(); // Commit transaksi
            log_message('debug', '================= AKHIR TRANSAKSI (COMMIT) =================');

            if ($db->transStatus() === false) {
                session()->setFlashdata('error', 'Terjadi kesalahan saat menyimpan data (transaksi gagal).');
            } else {
                session()->setFlashdata('success', 'Data kopi masuk berhasil ditambahkan dan stok diperbarui');
            }
        } catch (\Exception $e) {
            $db->transRollback(); // Rollback jika error
            log_message('error', '!!! EXCEPTION !!! ' . $e->getMessage());
            log_message('debug', '================= AKHIR TRANSAKSI (ROLLBACK) =================');
            session()->setFlashdata('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        return redirect()->to(site_url('kopi-masuk'));
    }

    public function edit($id)
    {
        return $this->response->setJSON($this->kopiMasukModel->find($id));
    }

    public function update($id)
    {
        if (!$this->hasActivePermission($id, 'edit')) {
            session()->setFlashdata('error', 'Akses ditolak. Anda tidak memiliki izin untuk mengedit data ini.');
            return redirect()->to(site_url('kopi-masuk'));
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $dataLama = $this->kopiMasukModel->find($id);
            if (!$dataLama) {
                throw new \Exception('Data kopi masuk yang akan diupdate tidak ditemukan.');
            }

            $dataBaru = [
                'petani_user_id'  => $this->request->getPost('petani_user_id'),
                'petani_pohon_id' => $this->request->getPost('petani_pohon_id'),
                'jumlah'          => (float) $this->request->getPost('jumlah'),
                'tanggal'         => $this->request->getPost('tanggal'),
                'keterangan'      => $this->request->getPost('keterangan'),
            ];

            $petaniPohonLama = $this->petaniPohonModel->find($dataLama['petani_pohon_id']);
            if ($petaniPohonLama) {
                $stokLama = $this->stokKopiModel
                    ->where('petani_id', $petaniPohonLama['user_id'])
                    ->where('jenis_pohon_id', $petaniPohonLama['jenis_pohon_id'])
                    ->first();

                if ($stokLama) {
                    $stokBaru = (float) $stokLama['stok'] - (float) $dataLama['jumlah'];
                    $this->stokKopiModel->update($stokLama['id'], ['stok' => $stokBaru]);
                }
            }

            $petaniPohonBaru = $this->petaniPohonModel->find($dataBaru['petani_pohon_id']);
            if (!$petaniPohonBaru) {
                throw new \Exception('Data jenis pohon petani yang baru tidak valid.');
            }
            $stokBaru = $this->stokKopiModel
                ->where('petani_id', $petaniPohonBaru['user_id'])
                ->where('jenis_pohon_id', $petaniPohonBaru['jenis_pohon_id'])
                ->first();

            if ($stokBaru) {
                $stokAkhir = (float) $stokBaru['stok'] + $dataBaru['jumlah'];
                $this->stokKopiModel->update($stokBaru['id'], ['stok' => $stokAkhir]);
            } else {
                $this->stokKopiModel->insert([
                    'petani_id'      => $petaniPohonBaru['user_id'],
                    'jenis_pohon_id' => $petaniPohonBaru['jenis_pohon_id'],
                    'stok'           => $dataBaru['jumlah']
                ]);
            }

            $this->kopiMasukModel->update($id, $dataBaru);

            $db->transComplete();

            if ($db->transStatus() === false) {
                session()->setFlashdata('error', 'Transaksi gagal saat memperbarui data.');
            } else {
                session()->setFlashdata('success', 'Data kopi masuk dan stok berhasil diperbarui.');
            }
        } catch (\Exception $e) {
            $db->transRollback();
            session()->setFlashdata('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        return redirect()->to(site_url('kopi-masuk'));
    }

    public function delete($id)
    {
        if (!$this->hasActivePermission($id, 'delete')) {
            session()->setFlashdata('error', 'Akses ditolak. Anda tidak memiliki izin untuk menghapus data ini.');
            return redirect()->to(site_url('kopi-masuk'));
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $kopiMasuk = $this->kopiMasukModel->find($id);
            if (!$kopiMasuk) {
                throw new \Exception('Data kopi masuk tidak ditemukan.');
            }

            $jumlahDihapus = (float) $kopiMasuk['jumlah'];
            $petaniPohonId = $kopiMasuk['petani_pohon_id'];

            $petaniPohon = $this->petaniPohonModel->find($petaniPohonId);
            if ($petaniPohon) {
                $petaniId     = $petaniPohon['user_id'];
                $jenisPohonId = $petaniPohon['jenis_pohon_id'];

                $stok = $this->stokKopiModel
                    ->where('petani_id', $petaniId)
                    ->where('jenis_pohon_id', $jenisPohonId)
                    ->first();

                if ($stok) {
                    $stokBaru = (float) $stok['stok'] - $jumlahDihapus;
                    $this->stokKopiModel->update($stok['id'], ['stok' => $stokBaru]);
                }
            }

            $this->kopiMasukModel->delete($id);

            $db->transComplete();
            session()->setFlashdata('success', 'Data kopi masuk berhasil dihapus dan stok telah dikurangi.');
        } catch (\Exception $e) {
            $db->transRollback();
            session()->setFlashdata('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }

        return redirect()->to(site_url('kopi-masuk'));
    }


    public function stok()
    {
        $data['stokKopi'] = $this->stokKopiModel->getWithRelations();
        return view('stok/index', $data);
    }
    public function requestAccess()
    {
        if ($this->request->isAJAX()) {
            $kopiMasukId = $this->request->getPost('kopimasuk_id');
            $action      = $this->request->getPost('action_type');
            $requesterId = session()->get('user_id');

            if (empty($requesterId)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Sesi tidak valid.'])->setStatusCode(401);
            }

            $existing = $this->permissionModel->where([
                'requester_id' => $requesterId,
                'target_id'    => $kopiMasukId,
                'action_type'  => $action,
                'target_type'  => 'kopi_masuk',
                'status'       => 'pending'
            ])->first();

            if ($existing) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Anda sudah memiliki permintaan yang sama.']);
            }

            $this->permissionModel->save([
                'requester_id' => $requesterId,
                'target_id'    => $kopiMasukId,
                'target_type'  => 'kopi_masuk',
                'action_type'  => $action,
                'status'       => 'pending',
            ]);

            return $this->response->setJSON(['status' => 'success', 'message' => 'Permintaan izin berhasil dikirim.']);
        }
        return redirect()->back();
    }


    private function hasActivePermission($kopiMasukId, $action)
    {
        $requesterId = session()->get('user_id');
        if (empty($requesterId)) {
            return false;
        }

        $permission = $this->permissionModel->where([
            'requester_id' => $requesterId,
            'target_id'    => $kopiMasukId,
            'target_type'  => 'kopi_masuk',
            'action_type'  => $action,
            'status'       => 'approved',
            'expires_at >' => date('Y-m-d H:i:s')
        ])->first();

        return $permission ? true : false;
    }
}
