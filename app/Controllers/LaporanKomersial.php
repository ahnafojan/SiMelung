<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AsetKomersialModel;
use App\Models\PetaniModel;
use App\Models\StokKopiModel;

class LaporanKomersial extends BaseController
{
    protected $petaniModel;
    protected $asetModel;
    protected $stokKopiModel;

    public function __construct()
    {
        // Hanya memuat model yang dibutuhkan untuk halaman dashboard
        $this->petaniModel = new PetaniModel();
        $this->asetModel = new AsetKomersialModel();
        $this->stokKopiModel = new StokKopiModel();
    }

    public function index()
    {
        // Mengambil data ringkasan untuk kartu statistik
        $petaniList = $this->petaniModel->findAll();
        $totalAset = $this->asetModel->countAll();
        $totalStokKopi = $this->stokKopiModel->selectSum('stok', 'total')->get()->getRow()->total ?? 0;

        $data = [
            'petaniList' => $petaniList,
            'totalAset'  => $totalAset,
            'totalStokKopi' => $totalStokKopi,
        ];
        $data['breadcrumbs'] = [
            [
                'title' => 'Dashboard',
                'url'   => site_url('/dashboard/dashboard_komersial'), // Sesuaikan URL dashboard Anda
                'icon'  => 'fas fa-fw fa-tachometer-alt'
            ],
            [
                'title' => 'Laporan Komersial',
                'url'   => '#',
                'icon'  => 'fas fa-fw fa-file-alt' // Ikon yang cocok untuk data master
            ]
        ];

        // Menampilkan view utama yang berisi kartu statistik dan tombol navigasi
        return view('admin_komersial/laporan/index', $data);
    }
}
