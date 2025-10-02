<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MasterArusKasModel;
use App\Models\LogAktivitasModel;

class MasterArusKas extends BaseController
{
    // Gunakan properti untuk model agar konsisten
    protected $arusKasModel;

    public function __construct()
    {
        // Inisialisasi model di constructor
        $this->arusKasModel = new MasterArusKasModel();
    }
    public function index()
    {
        $model = new MasterArusKasModel();
        $data = [
            'title' => 'Master Komponen Arus Kas',
            'komponen_masuk' => $model->where('kategori', 'masuk')->findAll(),
            'komponen_keluar' => $model->where('kategori', 'keluar')->findAll(),
        ];
        return view('admin_keuangan/master_arus_kas/index', $data);
    }

    private function logAktivitas($aktivitas, $deskripsi, $bku_id = null)
    {
        $logModel = new LogAktivitasModel();
        $logModel->save([
            'username'  => session()->get('username') ?? 'System', // Ambil username dari session
            'aktivitas' => $aktivitas,
            'deskripsi' => $deskripsi,
            'bku_id'    => $bku_id
        ]);
    }

    public function create()
    {
        $model = new MasterArusKasModel();
        $data = [
            'nama_komponen' => $this->request->getPost('nama_komponen'),
            'kategori' => $this->request->getPost('kategori'),
        ];

        if ($model->save($data)) {
            // menambahkan log aktivitas
            $this->logAktivitas('TAMBAH', "Menambahkan komponen arus kas baru: " . $this->request->getPost('nama_komponen'));
            return redirect()->to('/master-arus-kas')->with('success', 'Komponen berhasil ditambahkan.');
        } else {
            return redirect()->to('/master-arus-kas')->with('error', 'Gagal menambahkan komponen.');
        }
    }

    public function update($id)
    {
        $model = new MasterArusKasModel();
        $data = [
            'nama_komponen' => $this->request->getPost('nama_komponen'),
        ];

        if ($model->update($id, $data)) {
            // menambahkan log aktivitas
            $this->logAktivitas('EDIT', "Memperbarui komponen arus kas ID {$id}: " . $this->request->getPost('nama_komponen'));
            return redirect()->to('/master-arus-kas')->with('success', 'Komponen berhasil diperbarui.');
        } else {
            return redirect()->to('/master-arus-kas')->with('error', 'Gagal memperbarui komponen.');
        }
    }

    public function delete($id)
    {
        // Ambil data berdasarkan ID SEBELUM dihapus
        $data = $this->arusKasModel->find($id);

        // Lakukan pengecekan apakah data ditemukan
        if ($data) {
            // Simpan nama komponen untuk dicatat di log
            $namaKomponen = $data['nama_komponen'];

            // Hapus data dari database
            $this->arusKasModel->delete($id);

            // Catat aktivitas dengan nama yang sudah disimpan
            $this->logAktivitas('HAPUS', "Menghapus komponen arus kas: '" . $namaKomponen . "'");

            return redirect()->to('/master-arus-kas')->with('success', 'Komponen berhasil dihapus.');
        } else {
            // Jika data tidak ditemukan, beri pesan error
            return redirect()->to('/master-arus-kas')->with('error', 'Data komponen tidak ditemukan atau sudah dihapus.');
        }
    }
}
