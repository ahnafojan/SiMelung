<?php

namespace App\Controllers;

use App\Models\PetaniModel;

class Petani extends BaseController
{
    protected $petaniModel;

    public function __construct()
    {
        $this->petaniModel = new PetaniModel();
    }

    public function index()
    {
        $data['petani'] = $this->petaniModel->findAll();
        return view('petani/index', $data);
    }

    public function create()
    {
        return view('petani/create');
    }

    public function store()
    {
        $this->petaniModel->save([
            'user_id' => $this->request->getPost('user_id'),
            'nama'    => $this->request->getPost('nama'),
            'alamat'  => $this->request->getPost('alamat'),
            'no_hp'   => $this->request->getPost('no_hp'),
        ]);

        return redirect()->to('/petani');
    }

    public function edit($id)
    {
        $data['petani'] = $this->petaniModel->find($id);
        return view('petani/edit', $data);
    }

    public function update($id)
    {
        $this->petaniModel->update($id, [
            'user_id' => $this->request->getPost('user_id'),
            'nama'    => $this->request->getPost('nama'),
            'alamat'  => $this->request->getPost('alamat'),
            'no_hp'   => $this->request->getPost('no_hp'),
        ]);

        return redirect()->to('/petani');
    }

    public function delete($id)
    {
        $this->petaniModel->delete($id);
        return redirect()->to('/petani');
    }
}
