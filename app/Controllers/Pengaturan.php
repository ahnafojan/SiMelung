<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PengaturanModel;
use App\Models\LogAktivitasModel;

class Pengaturan extends BaseController
{
    protected $pengaturanModel;

    public function __construct()
    {
        $this->pengaturanModel = new PengaturanModel();
        helper('form');
    }

    private function logAktivitas($aktivitas, $deskripsi, $bku_id = null)
    {
        $logModel = new LogAktivitasModel();
        $logModel->save([
            'username'  => session()->get('username') ?? 'System', // Ambil username dari session
            'aktivitas' => $aktivitas,
            'deskripsi' => $deskripsi,
            'bku_id'    => $bku_id
        ]);
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

    public function index_keuangan()
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
        return view('admin_keuangan/pengaturan/index', $data);
    }

    public function update()
    {
        $dataToUpdate = $this->request->getPost();

        foreach ($dataToUpdate as $key => $value) {
            if ($key === 'csrf_test_name') {
                continue;
            }

            $exists = $this->pengaturanModel->where('meta_key', $key)->first();

            if ($exists) {
                $this->pengaturanModel->where('meta_key', $key)
                    ->set(['meta_value' => $value])
                    ->update();
            } else {
                $this->pengaturanModel->insert([
                    'meta_key'   => $key,
                    'meta_value' => $value
                ]);
            }
        }
        $this->logAktivitas('UPDATE', 'Memperbarui pengaturan laporan Tanda tangan');
        return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui.');
    }

    public function komersial()
    {
        $pengaturanModel = new PengaturanModel();
        $data = [
            'title' => 'Pengaturan Laporan Komersial',
            'pengaturan' => $pengaturanModel->getAllAsArray()
        ];
        return view('pengaturan/komersial', $data);
    }

    public function updateKomersial()
    {
        $pengaturanModel = new PengaturanModel();
        $allPostData = $this->request->getPost();

        foreach ($allPostData as $key => $value) {
            if ($key === 'csrf_test_name') {
                continue;
            }
            $pengaturanModel->where('meta_key', $key)->delete();
            $pengaturanModel->insert([
                'meta_key' => $key,
                'meta_value' => $value
            ]);
        }
        $this->logAktivitas('UPDATE', 'Memperbarui pengaturan laporan Komersial');
        return redirect()->back()->with('success', 'Pengaturan untuk laporan komersial berhasil diperbarui!');
    }

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

    /**
     * [DIPERBAIKI] Filter dihapus agar semua data dari form bisa disimpan.
     */
    public function updateBumdes()
    {
        $dataToUpdate = $this->request->getPost();

        foreach ($dataToUpdate as $key => $value) {
            if ($key === 'csrf_test_name') {
                continue;
            }
            $exists = $this->pengaturanModel->where('meta_key', $key)->first();
            if ($exists) {
                $this->pengaturanModel->where('meta_key', $key)->set(['meta_value' => $value])->update();
            } else {
                $this->pengaturanModel->insert(['meta_key' => $key, 'meta_value' => $value]);
            }
        }
        $this->logAktivitas('UPDATE', 'Memperbarui pengaturan laporan BUMDES.');
        return redirect()->back()->with('success', 'Pengaturan BUMDES berhasil diperbarui.');
    }

    public function pariwisata()
    {
        $pengaturan = $this->pengaturanModel->findAll();
        $dataPengaturan = [];
        foreach ($pengaturan as $item) {
            $dataPengaturan[$item['meta_key']] = $item['meta_value'];
        }

        $data = [
            'title' => 'Pengaturan Laporan Pariwisata',
            'pengaturan' => $dataPengaturan
        ];
        return view('pengaturan/pariwisata', $data);
    }

    /**
     * [DIPERBAIKI] Filter dihapus agar semua data dari form bisa disimpan.
     */
    public function updatePariwisata()
    {
        $dataToUpdate = $this->request->getPost();

        foreach ($dataToUpdate as $key => $value) {
            if ($key === 'csrf_test_name') {
                continue;
            }
            $exists = $this->pengaturanModel->where('meta_key', $key)->first();
            if ($exists) {
                $this->pengaturanModel->where('meta_key', $key)->set(['meta_value' => $value])->update();
            } else {
                $this->pengaturanModel->insert(['meta_key' => $key, 'meta_value' => $value]);
            }
        }
        $this->logAktivitas('UPDATE', 'Memperbarui pengaturan laporan Pariwisata.');
        return redirect()->back()->with('success', 'Pengaturan Pariwisata berhasil diperbarui.');
    }
}
