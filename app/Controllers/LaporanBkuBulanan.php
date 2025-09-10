<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BkuBulananModel;
use App\Models\DetailAlokasiModel;
use App\Models\DetailPendapatanModel;
use App\Models\DetailPengeluaranModel;

// 'use' statement yang tidak perlu sudah dihapus

class LaporanBkuBulanan extends BaseController
{

    /**
     * Menampilkan halaman utama berisi daftar semua laporan BKU yang sudah dibuat.
     */
    public function index()
    {
        $bkuModel = new BkuBulananModel();

        $data = [
            'title'   => 'Daftar Laporan BKU Bulanan',
            // Ambil semua data, urutkan berdasarkan tahun dan bulan terbaru
            'laporan' => $bkuModel->orderBy('tahun', 'DESC')->orderBy('bulan', 'DESC')->findAll()
        ];
        $data['breadcrumbs'] = [
            [
                'title' => 'Dashboard',
                'url'   => site_url('dashboard/dashboard_desa'), // Sesuaikan URL dashboard Anda
                'icon'  => 'fas fa-fw fa-tachometer-alt'
            ],
            [
                'title' => 'Laporan BKU Bulanan',
                'url'   => '#',
                'icon'  => 'fas fa-fw fa-chart-pie fa-lg' // Ikon yang cocok untuk data master
            ]
        ];

        return view('desa/laporan_keuangan/laporan_BKU_bulanan', $data);
    }

    /**
     * Menampilkan halaman detail dari sebuah laporan BKU yang dipilih.
     * Fungsi ini bersifat read-only.
     */
    public function detail($id = null)
    {
        // Panggil semua model yang dibutuhkan
        $bkuModel = new BkuBulananModel();
        $detailPendapatanModel = new DetailPendapatanModel();
        $detailPengeluaranModel = new DetailPengeluaranModel();
        $detailAlokasiModel = new DetailAlokasiModel();

        $laporan = $bkuModel->find($id);
        if (!$laporan) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Laporan BKU tidak ditemukan.');
        }

        // Hitung ulang total pendapatan untuk memastikan akurasi
        $laporan['total_pendapatan'] = (float)($laporan['saldo_bulan_lalu'] ?? 0) + (float)($laporan['penghasilan_bulan_ini'] ?? 0);

        $rincianPendapatan = $detailPendapatanModel
            ->select('detail_pendapatan.*, master_pendapatan.nama_pendapatan')
            ->join('master_pendapatan', 'master_pendapatan.id = detail_pendapatan.master_pendapatan_id')
            ->where('detail_pendapatan.bku_id', $id)->findAll();

        $rincianPengeluaran = $detailPengeluaranModel
            ->select('detail_pengeluaran.*, master_kategori_pengeluaran.nama_kategori')
            ->join('master_kategori_pengeluaran', 'master_kategori_pengeluaran.id = detail_pengeluaran.master_kategori_id')
            ->where('detail_pengeluaran.bku_id', $id)->findAll();

        $rincianAlokasi = $detailAlokasiModel
            ->select('detail_alokasi.*, master_kategori_pengeluaran.nama_kategori')
            ->join('master_kategori_pengeluaran', 'master_kategori_pengeluaran.id = detail_alokasi.master_kategori_id')
            ->where('detail_alokasi.bku_id', $id)
            ->findAll();

        $data = [
            'title'              => 'Detail Laporan BKU',
            'laporan'            => $laporan,
            'rincianPendapatan'  => $rincianPendapatan,
            'rincianPengeluaran' => $rincianPengeluaran,
            'rincianAlokasi'     => $rincianAlokasi
        ];
        $data['breadcrumbs'] = [
            [
                'title' => 'Dashboard',
                'url'   => site_url('dashboard/dashboard_desa'), // Sesuaikan URL dashboard Anda
                'icon'  => 'fas fa-fw fa-tachometer-alt'
            ],
            [
                'title' => 'Laporan BKU Bulanan',
                'url'   =>  site_url('LaporanBkuBulanan'),
                'icon'  => 'fas fa-fw fa-chart-pie fa-lg' // Ikon yang cocok untuk data master
            ],
            [
                'title' => 'Detail Laporan BKU',
                'url'   => '#',
                'icon'  => 'fas fa-fw fa-chart-pie fa-lg' // Ikon yang cocok untuk data master
            ]
        ];

        // Pastikan view detail ada di path 'admin_keuangan/bku_bulanan/detail'
        // Jika belum, Anda perlu membuat view ini juga dalam mode read-only.
        return view('desa/laporan_keuangan/bku_detail', $data);
    }

    // --- SEMUA METHOD LAIN (new, create, edit, update, delete, cetakExcel, cetakPdf) DIHAPUS ---
}
