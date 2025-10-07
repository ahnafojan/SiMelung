<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UmkmModel;
use App\Models\PengaturanModel;

class DashboardAdminUmkm extends BaseController
{
    protected $umkmModel;

    public function __construct()
    {
        $this->umkmModel = new UmkmModel();
    }

    public function index()
    {
        // 1. Ambil semua data UMKM
        $allUmkm = $this->umkmModel->findAll();
        $totalUmkm = count($allUmkm); // Data statistik: Total UMKM

        // 2. Hitung jumlah UMKM per Kategori
        // PERBAIKAN: Menggunakan nama kolom 'kategori'
        $umkmPerKategori = $this->umkmModel
            ->select('kategori, COUNT(*) as jumlah')
            ->groupBy('kategori')
            ->findAll(); // Data statistik: Rincian per kategori

        // Data untuk Chart/Grafik
        $kategoriLabels = []; // Data statistik: Label untuk grafik
        $kategoriData = []; // Data statistik: Jumlah untuk grafik
        foreach ($umkmPerKategori as $item) {
            // Pastikan kategori tidak kosong sebelum ditambahkan ke chart
            if (!empty($item['kategori'])) {
                $kategoriLabels[] = $item['kategori'];
                $kategoriData[] = $item['jumlah'];
            }
        }

        // Variabel UMKM Aktif, Karyawan, dan Pendapatan Dihapus

        $data = [
            'title'             => 'Dashboard Admin UMKM',
            'totalUmkm'         => $totalUmkm,
            'umkmData'          => $allUmkm,
            'kategoriLabels'    => $kategoriLabels,
            'kategoriData'      => $kategoriData,
            'umkmPerKategori'   => $umkmPerKategori,
        ];

        return view('dashboard/dashboard_umkm', $data);
    }
}
