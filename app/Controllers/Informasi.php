<?php

namespace App\Controllers;

use App\Models\UmkmModel;

class Informasi extends BaseController
{
    public function index()
    {
        $umkmModel = new UmkmModel();
        $data['umkm'] = $umkmModel->findAll();

        // pastikan path view sesuai dengan file view Anda
        return view('admin_umkm/informasi/informasi', $data);
    }
}
