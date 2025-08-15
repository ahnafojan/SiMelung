<?php

namespace App\Controllers;

use App\Models\KopiKeluarModel;

class KopiKeluar extends BaseController
{
    protected $kopiKeluarModel;

    public function __construct()
    {
        $this->kopiKeluarModel = new KopiKeluarModel();
    }

    public function index()
    {
        $data['kopikeluar'] = $this->kopiKeluarModel->findAll();
        return view('admin_komersial/kopi/kopi-keluar', $data);
    }

    public function store()
    {
        $this->kopiKeluarModel->save([
            'tujuan'     => $this->request->getPost('tujuan'),
            'jumlah'     => $this->request->getPost('jumlah'),
            'tanggal'    => $this->request->getPost('tanggal'),
            'keterangan' => $this->request->getPost('keterangan'),
        ]);

        return redirect()->to('/kopikeluar')->with('success', 'Data berhasil ditambahkan');
    }

    public function edit($id)
    {
        $data = $this->kopiKeluarModel->find($id);
        return $this->response->setJSON($data);
    }

    public function update($id)
    {
        $this->kopiKeluarModel->update($id, [
            'tujuan'     => $this->request->getPost('tujuan'),
            'jumlah'     => $this->request->getPost('jumlah'),
            'tanggal'    => $this->request->getPost('tanggal'),
            'keterangan' => $this->request->getPost('keterangan'),
        ]);

        return redirect()->to('/kopikeluar')->with('success', 'Data berhasil diperbarui');
    }

    public function delete($id)
    {
        $this->kopiKeluarModel->delete($id);
        return redirect()->to('/kopikeluar')->with('success', 'Data berhasil dihapus');
    }
}
