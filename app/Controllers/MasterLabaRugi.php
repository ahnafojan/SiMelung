<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MasterLabaRugiModel;
use App\Models\LogAktivitasModel;

class MasterLabaRugi extends BaseController
{
    protected $masterLabaRugiModel;

    public function __construct()
    {
        $this->masterLabaRugiModel = new MasterLabaRugiModel();
        helper('form');
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


    public function index()
    {
        $semuaKomponen = $this->masterLabaRugiModel->orderBy('kategori, id')->findAll();

        // Kelompokkan berdasarkan kategori untuk tampilan yang lebih rapi
        $komponen = ['pendapatan' => [], 'biaya' => []];
        foreach ($semuaKomponen as $item) {
            $komponen[$item['kategori']][] = $item;
        }

        $data = [
            'title' => 'Master Komponen Laba Rugi',
            'komponen' => $komponen
        ];
        return view('admin_keuangan/master_laba_rugi/index', $data);
    }

    public function new()
    {
        $data = [
            'title' => 'Tambah Komponen Laba Rugi Baru',
            'validation' => \Config\Services::validation()
        ];
        return view('admin_keuangan/master_laba_rugi/new', $data);
    }

    public function create()
    {
        $rules = [
            'nama_komponen' => 'required|is_unique[master_laba_rugi.nama_komponen]',
            'kategori'      => 'required|in_list[pendapatan,biaya]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/master-laba-rugi/new')->withInput();
        }

        $this->masterLabaRugiModel->save([
            'nama_komponen' => $this->request->getVar('nama_komponen'),
            'kategori'      => $this->request->getVar('kategori'),
        ]);
        // menambahkan log aktivitas
        $this->logAktivitas('TAMBAH', "Menambahkan komponen laba rugi baru: " . $this->request->getVar('nama_komponen'));
        session()->setFlashdata('success', 'Komponen berhasil ditambahkan.');
        return redirect()->to('/master-laba-rugi');
    }

    public function delete($id)
    {
        // [DIUBAH] Ambil data berdasarkan ID SEBELUM dihapus
        $data = $this->masterLabaRugiModel->find($id);

        // Lakukan pengecekan apakah data ditemukan
        if ($data) {
            // Simpan nama komponen untuk dicatat di log
            $namaKomponen = $data['nama_komponen'];

            // Hapus data dari database
            $this->masterLabaRugiModel->delete($id);

            // Catat aktivitas dengan nama yang sudah disimpan
            $this->logAktivitas('HAPUS', "Menghapus komponen laba rugi: '" . $namaKomponen . "'");

            // Siapkan pesan sukses
            session()->setFlashdata('success', 'Komponen berhasil dihapus.');
        } else {
            // Jika data tidak ditemukan, beri pesan error
            session()->setFlashdata('error', 'Data komponen tidak ditemukan atau sudah dihapus.');
        }

        // Kembalikan ke halaman index
        return redirect()->to('/master-laba-rugi');
    }
}
