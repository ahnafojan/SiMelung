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
        $data = [
            'title' => 'Pengaturan Laporan',
            'ketua_bumdes' => $this->pengaturanModel->where('meta_key', 'ketua_bumdes')->first()['meta_value'] ?? '',
            'bendahara_bumdes' => $this->pengaturanModel->where('meta_key', 'bendahara_bumdes')->first()['meta_value'] ?? '',
            'lokasi_laporan' => $this->pengaturanModel->where('meta_key', 'lokasi_laporan')->first()['meta_value'] ?? '',
        ];
        // Pastikan nama folder view adalah 'pengaturan' (huruf kecil)
        return view('pengaturan/index', $data);
    }

    public function update()
    {
        $dataToUpdate = [
            'ketua_bumdes' => $this->request->getPost('ketua_bumdes'),
            'bendahara_bumdes' => $this->request->getPost('bendahara_bumdes'),
            'lokasi_laporan' => $this->request->getPost('lokasi_laporan'),
        ];

        foreach ($dataToUpdate as $key => $value) {
            // Gunakan method saveMetaValue dari model jika ada, ini lebih bersih
            $existing = $this->pengaturanModel->where('meta_key', $key)->first();
            if ($existing) {
                $this->pengaturanModel->update($existing['id'], ['meta_value' => $value]);
            } else {
                $this->pengaturanModel->insert(['meta_key' => $key, 'meta_value' => $value]);
            }
        }

        // DIUBAH: Arahkan kembali ke halaman pengaturan yang benar
        return redirect()->to('/pengaturan')->with('success', 'Pengaturan berhasil diperbarui.');

        // ALTERNATIF YANG LEBIH BAIK:
        // Gunakan redirect()->back() agar kembali ke halaman sebelumnya secara otomatis
        // return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
