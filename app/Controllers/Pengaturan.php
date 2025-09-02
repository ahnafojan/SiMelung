<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PengaturanModel;

class Pengaturan extends BaseController
{
    protected $pengaturanModel;

    public function __construct()
    {
        $this->pengaturanModel = new PengaturanModel();
        helper('form');
    }

    public function index()
    {
        $pengaturan = $this->pengaturanModel->findAll();
        $dataPengaturan = [];
        foreach ($pengaturan as $item) {
            $dataPengaturan[$item['meta_key']] = $item['meta_value'];
        }

        $data = [
            'title' => 'Pengaturan Laporan',
            'pengaturan' => $dataPengaturan
        ];
        return view('pengaturan/index', $data);
    }

    public function update()
    {
        $dataToUpdate = $this->request->getPost();

        // Loop dan update setiap pengaturan
        foreach ($dataToUpdate as $key => $value) {
            $this->pengaturanModel->where('meta_key', $key)->set(['meta_value' => $value])->update();
        }

        return redirect()->to('/pengaturan')->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
