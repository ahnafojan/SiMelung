<?php

namespace App\Controllers;

use App\Models\UmkmModel;

class Umkm extends BaseController
{
    protected $umkmModel;

    public function __construct()
    {
        $this->umkmModel = new UmkmModel();
    }

    public function index()
    {
        $data['umkm'] = $this->umkmModel->orderBy('id', 'DESC')->findAll();
        return view('admin_umkm/umkm/index', $data);
    }

    public function store()
    {
        $this->umkmModel->save([
            'nama_umkm' => $this->request->getPost('nama_umkm'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'pemilik'   => $this->request->getPost('pemilik'),
            'alamat'    => $this->request->getPost('alamat'),
            'kontak'    => $this->request->getPost('kontak'),
        ]);

        return redirect()->to(site_url('umkm'))->with('success', 'UMKM berhasil ditambahkan');
    }

    public function update($id)
    {
        $this->umkmModel->update($id, [
            'nama_umkm' => $this->request->getPost('nama_umkm'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'pemilik'   => $this->request->getPost('pemilik'),
            'alamat'    => $this->request->getPost('alamat'),
            'kontak'    => $this->request->getPost('kontak'),
        ]);

        return redirect()->to(site_url('umkm'))->with('success', 'UMKM berhasil diupdate');
    }

    public function delete($id)
    {
        $this->umkmModel->delete($id);
        return redirect()->to(site_url('umkm'))->with('success', 'UMKM berhasil dihapus');
    }
}
