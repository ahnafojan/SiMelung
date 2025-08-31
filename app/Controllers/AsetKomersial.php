<?php

namespace App\Controllers;

use App\Models\MasterAsetModel;
use CodeIgniter\Controller;

class AsetKomersial extends Controller
{
    protected $masterAsetModel;

    public function __construct()
    {
        $this->masterAsetModel = new MasterAsetModel();
    }

    /**
     * Menampilkan form untuk menambah aset baru.
     * Fungsi ini juga mengirimkan daftar kategori aset ke view.
     */
    public function index()
    {
        // Daftar kategori aset yang akan ditampilkan di dropdown.
        // Cukup ubah daftar ini jika Anda ingin menambah atau mengubah kategori di masa depan.
        $data['kategoriAset'] = [
            'Mesin Giling',
            'Mesin Pengupas Kopi',
            'Mesin Pengering Kopi',
            'Gudang Penyimpanan',
            'Kendaraan Operasional',
            'Peralatan Pertanian',
        ];

        return view('admin_komersial/aset/master_aset', $data);
    }

    /**
     * Menyimpan data aset baru yang diinput dari form.
     */
    public function store()
    {
        try {
            // Logika untuk menentukan nama aset final dari dropdown
            $kategoriDipilih = $this->request->getPost('kategori_aset');
            $namaAsetFinal = $kategoriDipilih;

            if ($kategoriDipilih === 'Lainnya') {
                $namaAsetLainnya = $this->request->getPost('nama_aset_lainnya');
                // Validasi: Pastikan input "Lainnya" tidak kosong
                if (!empty($namaAsetLainnya)) {
                    $namaAsetFinal = $namaAsetLainnya;
                } else {
                    session()->setFlashdata('error', 'Nama Aset Lainnya wajib diisi jika kategori "Lainnya" dipilih.');
                    return redirect()->back()->withInput();
                }
            }

            // Handle upload foto
            $fotoFile = $this->request->getFile('foto');
            $fotoName = null;

            if ($fotoFile && $fotoFile->isValid() && !$fotoFile->hasMoved()) {
                $fotoName = $fotoFile->getRandomName();
                $fotoFile->move(FCPATH . 'uploads/foto_aset', $fotoName);
            }

            // Simpan data ke database
            $this->masterAsetModel->save([
                'nama_aset'        => $namaAsetFinal,
                'kode_aset'        => $this->request->getPost('kode_aset'),
                'nup'              => $this->request->getPost('nup'),
                'tahun_perolehan'  => $this->request->getPost('tahun_perolehan'),
                'merk_type'        => $this->request->getPost('merk_type'),
                'nilai_perolehan'  => $this->request->getPost('nilai_perolehan'),
                'keterangan'       => $this->request->getPost('keterangan'), // Data dari dropdown kondisi
                'metode_pengadaan' => $this->request->getPost('metode_pengadaan'),
                'sumber_pengadaan' => $this->request->getPost('sumber_pengadaan'),
                'foto'             => $fotoName,
            ]);

            session()->setFlashdata('success', 'Data aset berhasil disimpan ✅');
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }

        return redirect()->to(site_url('aset-komersial'));
    }
}
