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
    // --- METHOD BARU UNTUK MENAMPILKAN FORM PENGATURAN KOMERSIAL ---
    public function komersial()
    {
        $pengaturanModel = new PengaturanModel();

        $data = [
            'title' => 'Pengaturan Laporan Komersial',
            'pengaturan' => $pengaturanModel->getAllAsArray() // Mengambil semua pengaturan
        ];

        // Memuat file view baru yang akan kita buat di langkah 3
        return view('pengaturan/komersial', $data);
    }

    // --- METHOD BARU UNTUK MENYIMPAN DATA PENGATURAN KOMERSIAL ---
    public function updateKomersial()
    {
        $pengaturanModel = new PengaturanModel();
        $allPostData = $this->request->getPost();

        foreach ($allPostData as $key => $value) {
            // Skip field csrf_test_name
            if ($key === 'csrf_test_name') {
                continue;
            }

            // Cari berdasarkan meta_key, lalu update meta_value
            // Ini akan mengupdate jika key sudah ada, atau membuat baru jika belum ada.
            $pengaturanModel->where('meta_key', $key)->delete();
            $pengaturanModel->insert([
                'meta_key' => $key,
                'meta_value' => $value
            ]);
        }

        session()->setFlashdata('success', 'Pengaturan untuk laporan komersial berhasil diperbarui!');
        return redirect()->to('/pengaturan/komersial');
    }
    // --- FUNGSI UNTUK PENGATURAN BUMDES ---
    public function bumdes()
    {
        $pengaturan = $this->pengaturanModel->findAll();
        $dataPengaturan = [];
        foreach ($pengaturan as $item) {
            $dataPengaturan[$item['meta_key']] = $item['meta_value'];
        }

        $data = [
            'title' => 'Pengaturan Laporan BUMDES',
            'pengaturan' => $dataPengaturan
        ];
        return view('pengaturan/bumdes', $data);
    }

    public function updateBumdes()
    {
        $dataToUpdate = $this->request->getPost();

        foreach ($dataToUpdate as $key => $value) {
            // Hanya update field yang relevan untuk BUMDES
            if (in_array($key, ['lokasi_laporan', 'ketua_bumdes'])) {
                // Cek jika key sudah ada
                $exists = $this->pengaturanModel->where('meta_key', $key)->first();
                if ($exists) {
                    $this->pengaturanModel->where('meta_key', $key)->set(['meta_value' => $value])->update();
                } else {
                    $this->pengaturanModel->insert(['meta_key' => $key, 'meta_value' => $value]);
                }
            }
        }

        return redirect()->to('/pengaturan/bumdes')->with('success', 'Pengaturan BUMDES berhasil diperbarui.');
    }
}
