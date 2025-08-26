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

    public function index()
    {
        return view('admin_komersial/aset/master_aset');
    }

    public function store()
    {
        try {
            // Handle upload foto
            $fotoFile = $this->request->getFile('foto');
            $fotoName = null;

            if ($fotoFile && $fotoFile->isValid() && !$fotoFile->hasMoved()) {
                // Nama file unik
                $fotoName = $fotoFile->getRandomName();
                // Simpan ke folder public/uploads/foto_aset
                $fotoFile->move(FCPATH . 'uploads/foto_aset', $fotoName);
            }

            // Simpan data ke DB
            $this->masterAsetModel->save([
                'nama_aset'        => $this->request->getPost('nama_aset'),
                'kode_aset'        => $this->request->getPost('kode_aset'),
                'nup'              => $this->request->getPost('nup'),
                'tahun_perolehan'  => $this->request->getPost('tahun_perolehan'),
                'merk_type'        => $this->request->getPost('merk_type'),
                'nilai_perolehan'  => $this->request->getPost('nilai_perolehan'),
                'keterangan'       => $this->request->getPost('keterangan'),
                'metode_pengadaan' => $this->request->getPost('metode_pengadaan'),
                'sumber_pengadaan' => $this->request->getPost('sumber_pengadaan'),
                'foto'             => $fotoName, // simpan nama file foto
            ]);

            session()->setFlashdata('success', 'Data aset berhasil disimpan ✅');
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }

        return redirect()->to(site_url('aset-komersial'));
    }
}
