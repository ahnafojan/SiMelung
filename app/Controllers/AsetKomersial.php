<?php

namespace App\Controllers;

use App\Models\MasterAsetModel;
use CodeIgniter\Controller;

class AsetKomersial extends Controller
{
    public function index()
    {
        return view('admin_komersial/aset/master_aset');
    }

    public function store()
    {
        $model = new MasterAsetModel();

        $data = [
            'nama_aset'       => $this->request->getPost('nama_aset'),
            'kode_aset'       => $this->request->getPost('kode_aset'),
            'nup'             => $this->request->getPost('nup'),
            'tahun_perolehan' => $this->request->getPost('tahun_perolehan'),
            'merk_type'       => $this->request->getPost('merk_type'),
            'nilai_perolehan' => $this->request->getPost('nilai_perolehan'),
            'keterangan'      => $this->request->getPost('keterangan'),
        ];

        $model->insert($data);

        return redirect()->to('aset-komersial')->with('success', 'Data aset berhasil disimpan.');
    }
}
