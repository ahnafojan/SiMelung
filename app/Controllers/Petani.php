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
        return view('admin_komerisal/petani/index', $data);
    }

    public function create()
    {
        return view('admin_komersial/petani/create');
    }

    public function store()
    {
        $this->petaniModel->save([
            'user_id' => $this->request->getPost('user_id'),
            'nama'    => $this->request->getPost('nama'),
            'alamat'  => $this->request->getPost('alamat'),
            'no_hp'   => $this->request->getPost('no_hp'),
        ]);

        return redirect()->to('admin_komersial/petani');
    }

    public function edit($id)
    {
        $data['petani'] = $this->petaniModel->find($id);
        return view('admin_komerisal/petani/edit', $data);
    }

    public function update($id)
    {
        $this->petaniModel->update($id, [
            'user_id' => $this->request->getPost('user_id'),
            'nama'    => $this->request->getPost('nama'),
            'alamat'  => $this->request->getPost('alamat'),
            'no_hp'   => $this->request->getPost('no_hp'),
        ]);

        return redirect()->to('admin_komersial/petani');
    }

    public function delete($id)
    {
        $this->petaniModel->delete($id);
        return redirect()->to('admin_komersial/petani');
    }
}
