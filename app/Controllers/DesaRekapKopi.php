<?php

namespace App\Controllers;

// PENTING: Panggil parent controller-nya
use App\Controllers\KomersialRekapKopi;

/**
 * Controller ini untuk menampilkan rekap kopi di level Desa.
 * Dia mewarisi semua method dari KomersialRekapKopi,
 * jadi kita tidak perlu menulis ulang logika query database.
 */
class DesaRekapKopi extends KomersialRekapKopi // <-- Kunci utamanya ada di sini
{
    /**
     * Override fungsi index dari parent.
     * Logikanya tetap sama, tapi view yang dipanggil berbeda.
     */
    public function index()
    {
        $filter = [
            'start_date' => $this->request->getGet('start_date') ?? '',
            'end_date'   => $this->request->getGet('end_date') ?? '',
            'petani'     => $this->request->getGet('petani') ?? ''
        ];

        $pageMasuk = $this->request->getGet('page_masuk') ?? 1;
        $perPageMasuk = $this->request->getGet('per_page_masuk') ?? 10;
        $pageKeluar = $this->request->getGet('page_keluar') ?? 1;
        $perPageKeluar = $this->request->getGet('per_page_keluar') ?? 10;
        $pageStok = $this->request->getGet('page_stok') ?? 1;
        $perPageStok = $this->request->getGet('per_page_stok') ?? 10;

        $petaniList = $this->petaniModel->select('user_id, nama as nama_petani')->findAll();

        // Memanggil method dari PARENT untuk mengambil data
        list($rekapPetani, $pagerKopiMasuk) = $this->getRekapKopiMasuk($filter, $perPageMasuk, $pageMasuk);
        list($rekapPenjualan, $pagerKopiKeluar) = $this->getRekapKopiKeluar($filter, $perPageKeluar, $pageKeluar);
        list($stokAkhirPerJenis, $pagerStokAkhir) = $this->getStokAkhir($filter, $perPageStok, $pageStok);

        $allStokData = $this->getStokAkhir($filter, 0, 1, false);
        $totalStokGlobal = array_sum(array_column($allStokData, 'stok_akhir'));

        $data = [
            'title'             => 'Rekap Kopi Desa', // Title yang berbeda
            'petaniList'        => $petaniList,
            'filter'            => $filter,
            'rekapPetani'       => $rekapPetani,
            'rekapPenjualan'    => $rekapPenjualan,
            'stokAkhirPerJenis' => $stokAkhirPerJenis,
            'totalStokGlobal'   => $totalStokGlobal,
            'pagerKopiMasuk'    => $pagerKopiMasuk,
            'perPageMasuk'      => $perPageMasuk,
            'pagerKopiKeluar'   => $pagerKopiKeluar,
            'perPageKeluar'     => $perPageKeluar,
            'pagerStokAkhir'    => $pagerStokAkhir,
            'perPageStok'       => $perPageStok,
        ];
        $data['breadcrumbs'] = [
            [
                'title' => 'Dashboard',
                'url'   => site_url('dashboard/dashboard_desa'), // Sesuaikan URL dashboard Anda
                'icon'  => 'fas fa-fw fa-tachometer-alt'
            ],
            [
                'title' => 'Laporan Rekap Kopi',
                'url'   => '#',
                'icon'  => 'fas fa-fw fa-coffee fa-lg' // Ikon yang cocok untuk data master
            ]
        ];

        // PERBEDAAN UTAMA: Mengarahkan ke view yang berbeda untuk Desa
        // Pastikan Anda membuat file view ini.
        return view('desa/laporan_komersial/rekap_kopi', $data);
    }
}
