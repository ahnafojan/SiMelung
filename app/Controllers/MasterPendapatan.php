<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MasterPendapatanModel;
use App\Models\LogAktivitasModel;

class MasterPendapatan extends BaseController
{
    protected $pendapatanModel;

    public function __construct()
    {
        $this->pendapatanModel = new MasterPendapatanModel();
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

    /**
     * Menampilkan daftar semua jenis pendapatan (READ)
     */
    public function index()
    {
        $data = [
            'title' => 'Master Jenis Pendapatan',
            'pendapatan' => $this->pendapatanModel->findAll()
        ];
        return view('admin_keuangan/master_pendapatan/index', $data);
    }

    /**
     * Menampilkan form untuk menambah data baru
     */
    public function new()
    {
        $data = [
            'title' => 'Tambah Jenis Pendapatan Baru',
            'validation' => \Config\Services::validation()
        ];
        return view('admin_keuangan/master_pendapatan/new', $data);
    }

    /**
     * Memproses data dari form tambah
     */
    public function create()
    {
        $rules = [
            'nama_pendapatan' => 'required|is_unique[master_pendapatan.nama_pendapatan]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/master-pendapatan/new')->withInput();
        }

        $this->pendapatanModel->save([
            'nama_pendapatan' => $this->request->getVar('nama_pendapatan'),
            'deskripsi' => $this->request->getVar('deskripsi'),
        ]);
        // menambahkan log aktivitas
        $this->logAktivitas('TAMBAH', "Menambahkan jenis pendapatan baru: " . $this->request->getVar('nama_pendapatan'));
        session()->setFlashdata('success', 'Data pendapatan berhasil ditambahkan.');
        return redirect()->to('/master-pendapatan');
    }

    /**
     * Menampilkan form untuk mengedit data
     */
    public function edit($id = null)
    {
        $data = [
            'title' => 'Edit Jenis Pendapatan',
            'validation' => \Config\Services::validation(),
            'pendapatan' => $this->pendapatanModel->find($id)
        ];

        if (empty($data['pendapatan'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data pendapatan tidak ditemukan.');
        }
        return view('admin_keuangan/master_pendapatan/edit', $data);
    }

    /**
     * Memproses data dari form edit
     */
    public function update($id = null)
    {
        $dataLama = $this->pendapatanModel->find($id);
        if (!$dataLama) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Data pendapatan tidak ditemukan dengan ID: ' . $id);
        }

        $namaPendapatanRule = ($this->request->getVar('nama_pendapatan') == $dataLama['nama_pendapatan'])
            ? 'required'
            : 'required|is_unique[master_pendapatan.nama_pendapatan]';

        $rules = ['nama_pendapatan' => $namaPendapatanRule];

        if (!$this->validate($rules)) {
            return redirect()->to('/master-pendapatan/' . $id . '/edit')->withInput();
        }

        $this->pendapatanModel->save([
            'id' => $id,
            'nama_pendapatan' => $this->request->getVar('nama_pendapatan'),
            'deskripsi' => $this->request->getVar('deskripsi'),
        ]);
        // menambahkan log aktivitas
        $this->logAktivitas('EDIT', "Memperbarui jenis pendapatan ID {$id}: " . $this->request->getVar('nama_pendapatan'));
        session()->setFlashdata('success', 'Data pendapatan berhasil diperbarui.');
        return redirect()->to('/master-pendapatan');
    }

    /**
     * Menghapus data secara permanen
     */
    public function delete($id = null)
    {
        // [DIUBAH] Ambil data berdasarkan ID SEBELUM dihapus
        $data = $this->pendapatanModel->find($id);

        // Lakukan pengecekan apakah data ditemukan
        if ($data) {
            // Simpan nama komponen untuk dicatat di log
            $namaPendapatan = $data['nama_pendapatan'];

            // Hapus data dari database
            $this->pendapatanModel->delete($id);

            // Catat aktivitas dengan nama yang sudah disimpan
            $this->logAktivitas('HAPUS', "Menghapus jenis pendapatan: '" . $namaPendapatan . "'");

            session()->setFlashdata('success', 'Data pendapatan berhasil dihapus.');
        } else {
            // Jika data tidak ditemukan, beri pesan error
            session()->setFlashdata('error', 'Data pendapatan tidak ditemukan atau sudah dihapus.');
        }

        // Redirect kembali ke halaman utama
        return redirect()->to('/master-pendapatan');
    }

    /**
     * Method khusus untuk pengecekan nama via AJAX
     */
    public function checkNama()
    {
        if ($this->request->isAJAX()) {
            $namaPendapatan = $this->request->getGet('nama_pendapatan');
            $id = $this->request->getGet('id');

            $query = $this->pendapatanModel->where('nama_pendapatan', $namaPendapatan);

            if ($id) {
                $query->where('id !=', $id);
            }

            $result = $query->first();
            $dataExists = ($result !== null);

            return $this->response->setJSON(['exists' => $dataExists]);
        }
        return $this->response->setStatusCode(403);
    }
}
