<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\HargaJenisKopiModel;
use App\Models\PetaniPohonModel;

class ApiHargaController extends Controller
{
    protected $hargaJenisKopiModel;
    protected $petaniPohonModel;

    public function __construct()
    {
        $this->hargaJenisKopiModel = new HargaJenisKopiModel();
        $this->petaniPohonModel = new PetaniPohonModel();
    }

    public function getHargaBeliterbaru()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request']);
        }

        $petaniPohonId = $this->request->getPost('petani_pohon_id');
        $tanggal = $this->request->getPost('tanggal');

        if (!$petaniPohonId || !$tanggal) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Petani pohon ID atau tanggal tidak valid']);
        }

        // Cari jenis_pohon_id dari petani_pohon_id
        $petaniPohon = $this->petaniPohonModel->find($petaniPohonId);
        if (!$petaniPohon) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data petani pohon tidak ditemukan']);
        }

        // Gunakan model HargaJenisKopiModel untuk mendapatkan harga terbaru
        $hargaTerbaru = $this->hargaJenisKopiModel->getLatestPrice($petaniPohon['jenis_pohon_id'], $tanggal);

        if (!$hargaTerbaru) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Tidak ada harga beli yang berlaku untuk jenis pohon ini pada tanggal yang dipilih.'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => [
                'harga_beli_per_kg' => $hargaTerbaru['harga_beli_per_kg'],
                'tanggal_berlaku' => $hargaTerbaru['tanggal_berlaku']
            ]
        ]);
    }
    // Di App\Controllers\ApiHargaController

    public function getHargaJual()
    {

        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request']);
        }

        $stokKopiId = $this->request->getPost('stok_kopi_id');
        $tanggal    = $this->request->getPost('tanggal');

        if (!$stokKopiId || !$tanggal) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak lengkap']);
        }

        // Ambil jenis_pohon_id dari stok_kopi
        $stokModel = new \App\Models\StokKopiModel();
        $stok = $stokModel->find($stokKopiId);
        if (!$stok) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Stok kopi tidak ditemukan']);
        }

        // Ambil harga terbaru
        $harga = $this->hargaJenisKopiModel->getLatestPrice($stok['jenis_pohon_id'], $tanggal);

        if (!$harga) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Tidak ada harga jual yang berlaku pada tanggal ' . date('d-m-Y', strtotime($tanggal))
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => [
                'harga_jual_per_kg' => $harga['harga_jual_per_kg'],
                'tanggal_berlaku' => $harga['tanggal_berlaku']
            ]
        ]);
        log_message('info', 'API Harga: stok_kopi_id=' . $stokKopiId . ', tanggal=' . $tanggal);
    }
}
