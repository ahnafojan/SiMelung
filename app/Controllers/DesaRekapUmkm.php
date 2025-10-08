<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UmkmModel;

class DesaRekapUmkm extends BaseController
{
    /**
     * Menampilkan halaman utama untuk memilih laporan (index).
     */
    public function index()
    {
        // 1. Inisialisasi Model UMKM
        $umkmModel = new UmkmModel();
        // 2. Ambil semua data UMKM
        $dataUmkm = $umkmModel->findAll();

        $data = [
            'title'      => 'Laporan Data UMKM',
            'umkmData'   => $dataUmkm, // <= Data UMKM dikirim ke view
        ];
        $data['breadcrumbs'] = [
            [
                'title' => 'Dashboard',
                'url'   => site_url('dashboard/dashboard_desa'), // Sesuaikan URL dashboard Anda
                'icon'  => 'fas fa-fw fa-tachometer-alt'
            ],
            [
                'title' => 'Laporan UMKM',
                'url'   => '#',
                'icon'  => 'fas fa-store'
            ]
        ];

        return view('desa/laporan_umkm/umkm', $data);
    }
}
