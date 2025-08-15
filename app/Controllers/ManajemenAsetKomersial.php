<?php

namespace App\Controllers;

use App\Models\AsetKomersialModel;
use CodeIgniter\Controller;

class ManajemenAsetKomersial extends Controller
{
    protected $asetModel;

    public function __construct()
    {
        $this->asetModel = new AsetKomersialModel();
    }

    public function index()
    {
        $data['asets'] = $this->asetModel->findAll();
        return view('admin_komersial/aset/manajemen_aset', $data);
    }

    public function update($id)
    {
        $this->asetModel->update($id, [
            'nama_barang'       => $this->request->getPost('nama_barang'),
            'kode_aset'         => $this->request->getPost('kode_aset'),
            'nup'               => $this->request->getPost('nup'),
            'tahun_perolehan'   => $this->request->getPost('tahun_perolehan'),
            'merk_tipe'         => $this->request->getPost('merk_tipe'),
            'nilai_perolehan'   => $this->request->getPost('nilai_perolehan'),
            'keterangan'        => $this->request->getPost('keterangan'),
        ]);

        return redirect()->to('/ManajemenAsetKomersial')->with('success', 'Data berhasil diperbarui');
    }

    public function delete($id)
    {
        $this->asetModel->delete($id);
        return redirect()->to('/ManajemenAsetKomersial')->with('success', 'Data berhasil dihapus');
    }
}
