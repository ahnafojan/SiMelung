<?php

namespace App\Controllers;

use App\Models\PariwisataModel;
use CodeIgniter\Controller;

class Pariwisata extends Controller
{
    public function index()
    {
        $model = new PariwisataModel();
        $data['pariwisata'] = $model->findAll();
        return view('admin_pariwisata/index', $data);
    }

    public function create()
    {
        return view('admin_pariwisata/create');
    }

    public function store()
    {
        $model = new PariwisataModel();

        $data = [
            'nama'      => $this->request->getPost('nama'),
            'lokasi'    => $this->request->getPost('lokasi'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'gambar'    => $this->request->getPost('gambar')
        ];

        $model->save($data);

        return redirect()->to('/pariwisata');
    }

    public function edit($id)
    {
        $model = new PariwisataModel();
        $data['pariwisata'] = $model->find($id);
        return view('admin_pariwisata/edit', $data);
    }

    public function update($id)
    {
        $model = new PariwisataModel();

        $data = [
            'nama'      => $this->request->getPost('nama'),
            'lokasi'    => $this->request->getPost('lokasi'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'gambar'    => $this->request->getPost('gambar')
        ];

        $model->update($id, $data);

        return redirect()->to('/pariwisata');
    }

    public function delete($id)
    {
        $model = new PariwisataModel();
        $model->delete($id);
        return redirect()->to('/pariwisata');
    }
}
