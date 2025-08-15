<?php

namespace App\Controllers;

use App\Models\KopiMasukModel;
use App\Models\PetaniModel;
use CodeIgniter\Controller;

class KopiMasuk extends Controller
{
    protected $kopiMasukModel;
    protected $petaniModel;

    public function __construct()
    {
        $this->kopiMasukModel = new KopiMasukModel();
        $this->petaniModel    = new PetaniModel();
        helper(['form', 'url']);
    }

    public function index()
    {
        $data['kopiMasuk'] = $this->kopiMasukModel
            ->select('kopi_masuk.*, petani.nama as nama_petani')
            ->join('petani', 'petani.user_id = kopi_masuk.petani_user_id')
            ->orderBy('kopi_masuk.id', 'DESC')
            ->findAll();

        $data['petani'] = $this->petaniModel->orderBy('nama', 'ASC')->findAll();

        return view('admin_komersial/kopi/kopi-masuk', $data);
    }

    public function store()
    {
        $this->kopiMasukModel->save([
            'petani_user_id' => $this->request->getPost('petani_user_id'),
            'jumlah'         => $this->request->getPost('jumlah'),
            'tanggal'        => $this->request->getPost('tanggal'),
            'keterangan'     => $this->request->getPost('keterangan'),
        ]);

        return redirect()->to(site_url('kopi-masuk'))->with('success', 'Data berhasil ditambahkan');
    }

    public function edit($id)
    {
        return $this->response->setJSON($this->kopiMasukModel->find($id));
    }

    public function update($id)
    {
        if (!$this->kopiMasukModel->find($id)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Data tidak ditemukan');
        }

        $this->kopiMasukModel->update($id, [
            'petani_user_id' => $this->request->getPost('petani_user_id'),
            'jumlah'         => $this->request->getPost('jumlah'),
            'tanggal'        => $this->request->getPost('tanggal'),
            'keterangan'     => $this->request->getPost('keterangan'),
        ]);

        return redirect()->to(site_url('kopi-masuk'))->with('success', 'Data kopi masuk berhasil diperbarui');
    }

    public function delete($id)
    {
        $this->kopiMasukModel->delete($id);
        return redirect()->to(site_url('kopi-masuk'))->with('success', 'Data berhasil dihapus');
    }
}
