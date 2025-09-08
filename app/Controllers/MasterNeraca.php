<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MasterNeracaModel;
use App\Models\LogAktivitasModel;

class MasterNeraca extends BaseController
{
    protected $masterNeracaModel;

    public function __construct()
    {
        $this->masterNeracaModel = new MasterNeracaModel();
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
     * Menampilkan daftar semua komponen neraca
     */
    public function index()
    {
        $semuaKomponen = $this->masterNeracaModel->orderBy('kategori, id')->findAll();

        // Kelompokkan komponen berdasarkan kategori untuk tampilan yang lebih rapi
        $komponenTergrup = [
            'aktiva_lancar' => [],
            'aktiva_tetap' => [],
            'hutang_lancar' => [],
            'hutang_jangka_panjang' => [],
            'modal' => []
        ];
        foreach ($semuaKomponen as $item) {
            if (isset($komponenTergrup[$item['kategori']])) {
                $komponenTergrup[$item['kategori']][] = $item;
            }
        }

        $data = [
            'title' => 'Master Komponen Neraca Keuangan',
            'komponen' => $komponenTergrup
        ];

        return view('admin_keuangan/master_neraca/index', $data);
    }

    /**
     * Menampilkan form untuk menambah data baru
     */
    public function new()
    {
        $data = [
            'title' => 'Tambah Komponen Neraca Baru',
            'validation' => \Config\Services::validation()
        ];
        return view('admin_keuangan/master_neraca/new', $data);
    }

    /**
     * Memproses data dari form tambah
     */
    public function create()
    {
        $rules = [
            'nama_komponen' => 'required|is_unique[master_neraca.nama_komponen]',
            'kategori'      => 'required|in_list[aktiva_lancar,aktiva_tetap,hutang_lancar,hutang_jangka_panjang,modal]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/master-neraca/new')->withInput();
        }

        $this->masterNeracaModel->save([
            'nama_komponen' => $this->request->getVar('nama_komponen'),
            'kategori'      => $this->request->getVar('kategori'),
        ]);
        // menambahkan log aktivitas
        $this->logAktivitas('TAMBAH', "Menambahkan komponen neraca baru: " . $this->request->getVar('nama_komponen'));
        session()->setFlashdata('success', 'Komponen neraca berhasil ditambahkan.');
        return redirect()->to('/master-neraca');
    }

    /**
     * Menampilkan form untuk mengedit data
     */
    public function edit($id = null)
    {
        $data = [
            'title'    => 'Edit Komponen Neraca',
            'validation' => \Config\Services::validation(),
            'komponen' => $this->masterNeracaModel->find($id)
        ];

        if (empty($data['komponen'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Komponen neraca tidak ditemukan.');
        }

        return view('admin_keuangan/master_neraca/edit', $data);
    }

    /**
     * Memproses data dari form edit
     */
    public function update($id = null)
    {
        $dataLama = $this->masterNeracaModel->find($id);
        $namaKomponenRule = ($this->request->getVar('nama_komponen') == $dataLama['nama_komponen'])
            ? 'required'
            : 'required|is_unique[master_neraca.nama_komponen]';

        $rules = [
            'nama_komponen' => $namaKomponenRule,
            'kategori'      => 'required|in_list[aktiva_lancar,aktiva_tetap,hutang_lancar,hutang_jangka_panjang,modal]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/master-neraca/' . $id . '/edit')->withInput();
        }

        $this->masterNeracaModel->save([
            'id'            => $id,
            'nama_komponen' => $this->request->getVar('nama_komponen'),
            'kategori'      => $this->request->getVar('kategori'),
        ]);

        // menambahkan log aktivitas
        $this->logAktivitas('EDIT', "Memperbarui komponen neraca ID {$id}: " . $this->request->getVar('nama_komponen'));
        session()->setFlashdata('success', 'Komponen neraca berhasil diperbarui.');
        return redirect()->to('/master-neraca');
    }

    /**
     * Menghapus data
     */
    public function delete($id = null)
    {
        // [DIUBAH] Ambil data berdasarkan ID SEBELUM dihapus
        $data = $this->masterNeracaModel->find($id);

        // Lakukan pengecekan apakah data ditemukan
        if ($data) {
            // Simpan nama komponen untuk dicatat di log
            $namaKomponen = $data['nama_komponen'];

            // Hapus data dari database
            $this->masterNeracaModel->delete($id);

            // Catat aktivitas dengan nama yang sudah disimpan
            $this->logAktivitas('HAPUS', "Menghapus komponen neraca: '" . $namaKomponen . "'");

            session()->setFlashdata('success', 'Komponen neraca berhasil dihapus.');
        } else {
            // Jika data tidak ditemukan, beri pesan error
            session()->setFlashdata('error', 'Data komponen tidak ditemukan atau sudah dihapus.');
        }

        // Redirect kembali ke halaman utama
        return redirect()->to('/master-neraca');
    }
}
