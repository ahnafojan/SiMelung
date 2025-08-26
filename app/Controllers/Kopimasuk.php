<?php

namespace App\Controllers;

use App\Models\KopiMasukModel;
use App\Models\PetaniModel;
use CodeIgniter\Controller;
use App\Models\PetaniPohonModel;
use App\Models\StokKopiModel;

class KopiMasuk extends Controller
{
    protected $kopiMasukModel;
    protected $petaniModel;
    protected $petaniPohonModel;
    protected $stokKopiModel;

    public function __construct()
    {
        $this->kopiMasukModel = new KopiMasukModel();
        $this->petaniModel    = new PetaniModel();
        $this->petaniPohonModel = new PetaniPohonModel();
        $this->stokKopiModel = new StokKopiModel();

        helper(['form', 'url']);
    }

    public function index()
    {
        // cukup panggil getAll() dari model
        $data['kopiMasuk'] = $this->kopiMasukModel->getAll();

        // untuk dropdown petani
        $data['petani'] = $this->petaniModel->orderBy('nama', 'ASC')->findAll();

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
        $db = \Config\Database::connect();
        $db->transStart(); // Mulai transaksi

        try {
            // 1. Ambil data LAMA dari database sebelum diubah
            $dataLama = $this->kopiMasukModel->find($id);
            if (!$dataLama) {
                throw new \Exception('Data kopi masuk tidak ditemukan.');
            }

            // 2. Ambil data BARU dari form input
            $jumlahBaru = (float) $this->request->getPost('jumlah');
            $petaniPohonIdBaru = $this->request->getPost('petani_pohon_id');

            // 3. Hitung selisih jumlah
            $selisih = $jumlahBaru - (float) $dataLama['jumlah'];

            // 4. Update tabel kopi_masuk dengan data baru
            $this->kopiMasukModel->update($id, [
                'petani_user_id'  => $this->request->getPost('petani_user_id'),
                'petani_pohon_id' => $petaniPohonIdBaru,
                'jumlah'          => $jumlahBaru,
                'tanggal'         => $this->request->getPost('tanggal'),
                'keterangan'      => $this->request->getPost('keterangan'),
            ]);

            // 5. Update tabel stok_kopi
            // Cari detail petani dan jenis pohon dari data yang baru
            $petaniPohon = $this->petaniPohonModel->find($petaniPohonIdBaru);
            if (!$petaniPohon) {
                throw new \Exception('Relasi petani dan pohon tidak valid.');
            }

            $petaniId     = $petaniPohon['user_id'];
            $jenisPohonId = $petaniPohon['jenis_pohon_id'];

            // Cari stok yang sesuai
            $stok = $this->stokKopiModel
                ->where('petani_id', $petaniId)
                ->where('jenis_pohon_id', $jenisPohonId)
                ->first();

            if ($stok) {
                // Jika stok ada, perbarui dengan selisihnya
                $stokBaru = (float) $stok['stok'] + $selisih;
                $this->stokKopiModel->update($stok['id'], ['stok' => $stokBaru]);
            } else {
                // Skenario ini jarang terjadi saat update, tapi sebagai pengaman
                // Jika stok tidak ada, buat baru dengan jumlah baru
                $this->stokKopiModel->insert([
                    'petani_id'      => $petaniId,
                    'jenis_pohon_id' => $jenisPohonId,
                    'stok'           => $jumlahBaru
                ]);
            }

            $db->transComplete(); // Selesaikan transaksi (commit)

            if ($db->transStatus() === false) {
                session()->setFlashdata('error', 'Transaksi gagal saat memperbarui data.');
            } else {
                session()->setFlashdata('success', 'Data kopi masuk dan stok berhasil diperbarui.');
            }
        } catch (\Exception $e) {
            $db->transRollback(); // Batalkan transaksi jika ada error
            session()->setFlashdata('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        return redirect()->to(site_url('kopi-masuk'));
    }


    public function delete($id)
    {
        $db = \Config\Database::connect();
        $db->transStart(); // Mulai transaksi

        try {
            // 1. Ambil data transaksi yang akan dihapus
            $kopiMasuk = $this->kopiMasukModel->find($id);
            if (!$kopiMasuk) {
                throw new \Exception('Data kopi masuk tidak ditemukan.');
            }

            $jumlahDihapus = (float) $kopiMasuk['jumlah'];
            $petaniPohonId = $kopiMasuk['petani_pohon_id'];

            // 2. Cari detail petani dan jenis pohon untuk menemukan stok yang benar
            $petaniPohon = $this->petaniPohonModel->find($petaniPohonId);
            if (!$petaniPohon) {
                // Jika relasi sudah tidak ada, mungkin aman untuk langsung hapus,
                // tapi lebih baik batalkan untuk mencegah inkonsistensi data.
                throw new \Exception('Relasi petani dan pohon terkait tidak ditemukan.');
            }

            $petaniId     = $petaniPohon['user_id'];
            $jenisPohonId = $petaniPohon['jenis_pohon_id'];

            // 3. Kurangi stok di tabel stok_kopi
            $stok = $this->stokKopiModel
                ->where('petani_id', $petaniId)
                ->where('jenis_pohon_id', $jenisPohonId)
                ->first();

            if ($stok) {
                $stokBaru = (float) $stok['stok'] - $jumlahDihapus;
                $this->stokKopiModel->update($stok['id'], ['stok' => $stokBaru]);
            }

            // 4. Hapus data dari tabel kopi_masuk
            $this->kopiMasukModel->delete($id);

            $db->transComplete(); // Selesaikan transaksi (commit)

            session()->setFlashdata('success', 'Data kopi masuk berhasil dihapus dan stok telah dikurangi.');
        } catch (\Exception $e) {
            $db->transRollback(); // Batalkan transaksi jika ada error
            session()->setFlashdata('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }

        return redirect()->to(site_url('kopi-masuk'));
    }

    // ✅ Tampilkan stok
    public function stok()
    {
        $data['stokKopi'] = $this->stokKopiModel->getWithRelations();
        return view('stok/index', $data);
    }
}
