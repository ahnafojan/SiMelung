<?php

namespace App\Controllers;

use App\Models\JenisPohonModel;

class JenisPohon extends BaseController
{
    protected $jenisPohonModel;

    public function __construct()
    {
        $this->jenisPohonModel = new JenisPohonModel();
    }

    public function index()
    {
        $data['jenisPohon'] = $this->jenisPohonModel->findAll();
        return view('admin_komersial/petani/daftarpohon', $data);
    }

    public function store()
    {
        try {
            $this->jenisPohonModel->save([
                'nama_jenis' => $this->request->getPost('nama_jenis')
            ]);

            session()->setFlashdata('success', 'Jenis pohon berhasil ditambahkan');
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Terjadi kesalahan saat menambah data: ' . $e->getMessage());
        }

        return redirect()->to(site_url('/jenispohon'));
    }

    public function delete($id)
    {
        try {
            $this->jenisPohonModel->delete($id);
            session()->setFlashdata('success', 'Jenis pohon berhasil dihapus');
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }

        return redirect()->to(site_url('/jenispohon'));
    }
}
