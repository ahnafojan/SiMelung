<?php

namespace App\Controllers;

use App\Models\PetaniModel;
use CodeIgniter\Controller;

class Petani extends Controller
{
    protected $petaniModel;

    public function __construct()
    {
        $this->petaniModel = new PetaniModel();
        helper(['form', 'url']);
    }

    // Tampil daftar petani
    public function index()
    {
        $data['petani'] = $this->petaniModel->orderBy('id', 'ASC')->findAll();
        echo view('admin_komersial/petani/index', $data);
    }

    // Simpan data petani baru (dari form modal)
    public function create()
    {
        $validation = \Config\Services::validation();

        // Validasi sederhana
        $validation->setRules([
            'nama' => 'required|min_length[3]',
            'alamat' => 'required',
            'no_hp' => 'required|numeric',
        ]);

        if (!$this->validate($validation->getRules())) {
            return redirect()->to(site_url('petani'))->with('errors', $this->validator->getErrors());
        }

        // Generate user_id otomatis seperti P001, P002, dst
        $lastPetani = $this->petaniModel->orderBy('id', 'DESC')->first();
        if ($lastPetani) {
            // Ambil angka terakhir dan tambah 1
            $lastNumber = (int) substr($lastPetani['user_id'], 1);
            $newNumber = $lastNumber + 1;
            $newUserId = 'P' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
        } else {
            $newUserId = 'P001';
        }

        $this->petaniModel->save([
            'user_id' => $newUserId,
            'nama'    => $this->request->getPost('nama'),
            'alamat'  => $this->request->getPost('alamat'),
            'no_hp'   => $this->request->getPost('no_hp'),
        ]);

        return redirect()->to(site_url('petani'))->with('success', 'Data petani berhasil ditambahkan');
    }

    // Tampilkan form edit data petani
    public function edit($id = null)
    {
        $data['petani'] = $this->petaniModel->find($id);
        if (!$data['petani']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Data petani tidak ditemukan');
        }
        echo view('petani/edit', $data);
    }

    // Simpan update data petani
    public function postUpdate()
    {
        // Ambil ID dari input hidden form
        $id = $this->request->getPost('id');

        if (!$this->petaniModel->find($id)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Data petani tidak ditemukan');
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama'   => 'required|min_length[3]',
            'alamat' => 'required',
            'no_hp'  => 'required|numeric',
        ]);

        if (!$this->validate($validation->getRules())) {
            return redirect()->to(site_url('petani/edit/' . $id))->with('errors', $this->validator->getErrors());
        }

        $this->petaniModel->update($id, [
            'nama'   => $this->request->getPost('nama'),
            'alamat' => $this->request->getPost('alamat'),
            'no_hp'  => $this->request->getPost('no_hp'),
        ]);

        return redirect()->to(site_url('petani'))->with('success', 'Data petani berhasil diperbarui');
    }


    // Hapus data petani
    public function delete($id = null)
    {
        if ($id === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('ID petani tidak diberikan');
        }

        if ($this->petaniModel->find($id)) {
            $this->petaniModel->delete($id);
            return redirect()->to(site_url('petani'))->with('success', 'Data petani berhasil dihapus');
        }

        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Data petani tidak ditemukan');
    }
}
