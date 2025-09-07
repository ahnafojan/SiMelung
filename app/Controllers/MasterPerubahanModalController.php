<?php

namespace App\Controllers;

use App\Models\MasterPerubahanModalModel;
use App\Models\LogAktivitasModel;

class MasterPerubahanModalController extends BaseController
{
    protected $masterModel;

    public function __construct()
    {
        $this->masterModel = new MasterPerubahanModalModel();
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

    // Read: Menampilkan semua data
    public function index()
    {
        $data = [
            'title' => 'Master Komponen Perubahan Modal',
            'komponen' => $this->masterModel->findAll(),
        ];
        return view('admin_keuangan/master_perubahan_modal/index', $data);
    }

    // Create: Menampilkan form tambah data
    public function new()
    {
        $data = [
            'title' => 'Tambah Komponen Baru',
        ];
        return view('admin_keuangan/master_perubahan_modal/new', $data);
    }

    // Create: Menyimpan data baru
    public function create()
    {
        // Aturan validasi
        $rules = [
            'nama_komponen' => 'required|min_length[3]',
            'kategori' => 'required|in_list[penambahan,pengurangan]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->masterModel->save([
            'nama_komponen' => $this->request->getPost('nama_komponen'),
            'kategori' => $this->request->getPost('kategori'),
        ]);
        // menambahkan log aktivitas
        $this->logAktivitas('TAMBAH', "Menambahkan komponen perubahan modal baru: " . $this->request->getPost('nama_komponen'));
        return redirect()->to('/master-perubahan-modal')->with('success', 'Komponen baru berhasil ditambahkan.');
    }

    // Delete: Menghapus data
    public function delete($id)
    {
        // [DIUBAH] Ambil data berdasarkan ID SEBELUM dihapus
        $data = $this->masterModel->find($id);

        // Lakukan pengecekan apakah data ditemukan
        if ($data) {
            // Simpan nama komponen untuk dicatat di log
            $namaKomponen = $data['nama_komponen'];

            // Hapus data dari database
            $this->masterModel->delete($id);

            // Catat aktivitas dengan nama yang sudah disimpan
            $this->logAktivitas('HAPUS', "Menghapus komponen perubahan modal: '" . $namaKomponen . "'");

            return redirect()->to('/master-perubahan-modal')->with('success', 'Komponen berhasil dihapus.');
        } else {
            // Jika data tidak ditemukan, beri pesan error
            return redirect()->to('/master-perubahan-modal')->with('error', 'Data komponen tidak ditemukan atau sudah dihapus.');
        }
    }
}
