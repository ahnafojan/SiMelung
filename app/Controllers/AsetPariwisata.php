<?php

namespace App\Controllers;

use App\Models\AsetPariwisataModel;

class AsetPariwisata extends BaseController
{
    protected $asetModel;

    public function __construct()
    {
        $this->asetModel = new AsetPariwisataModel();
    }

    // Daftar aset
    public function index()
    {
        $data['asets'] = $this->asetModel->findAll();
        return view('admin_pariwisata/aset_pariwisata', $data);
    }

    // Form tambah aset
    public function create()
    {
        return view('aset_pariwisata/create', [
            'validation' => \Config\Services::validation()
        ]);
    }

    // Simpan aset
    public function store()
    {
        // validasi dan simpan...
    }
}
