<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BkuBulananModel;
use App\Models\MasterLabaRugiModel;
use App\Models\DetailLabaRugiModel;

class LaporanLabaRugi extends BaseController
{
    /**
     * Menampilkan halaman laporan laba rugi (mode lihat saja).
     */
    public function index()
    {
        // Memanggil number helper untuk format Rupiah di view
        helper('number');

        $bkuModel = new BkuBulananModel();

        $data = [
            'title' => 'Laporan Laba Rugi',
            'daftar_tahun' => $bkuModel->select('tahun')->distinct()->orderBy('tahun', 'DESC')->findAll()
        ];

        $tahunDipilih = $this->request->getGet('tahun');

        if ($tahunDipilih) {
            $laporanData = $this->getLaporanLabaRugiData($tahunDipilih);

            // MELAKUKAN SEMUA KALKULASI DI SINI
            $totalPendapatan = $laporanData['pendapatanUsaha'];
            foreach ($laporanData['komponenPendapatan'] as $p) {
                $totalPendapatan += $p['jumlah'];
            }

            $totalBiaya = $laporanData['biayaBahanBaku'] + $laporanData['biayaGaji'] + $laporanData['pad'];
            foreach ($laporanData['komponenBiaya'] as $b) {
                $totalBiaya += $b['jumlah'];
            }

            $labaRugiBersih = $totalPendapatan - $totalBiaya;

            // Menambahkan hasil kalkulasi ke data yang akan dikirim ke view
            $data['totalPendapatan'] = $totalPendapatan;
            $data['totalBiaya'] = $totalBiaya;
            $data['labaRugiBersih'] = $labaRugiBersih;

            $data['tahunDipilih'] = $tahunDipilih;
            $data = array_merge($data, $laporanData);
        }
        $data['breadcrumbs'] = [
            [
                'title' => 'Dashboard',
                'url'   => site_url('dashboard/dashboard_desa'), // Sesuaikan URL dashboard Anda
                'icon'  => 'fas fa-fw fa-tachometer-alt'
            ],
            [
                'title' => 'Laporan Laba Rugi',
                'url'   => '#',
                'icon'  => 'fas fa-fw fa-chart-pie fa-lg' // Ikon yang cocok untuk data master
            ]
        ];

        // Pastikan path view ini sesuai dengan file view di bawah
        return view('desa/laporan_keuangan/laporan_labarugi', $data);
    }

    /**
     * Fungsi private untuk mengambil data mentah dari database.
     * Tidak ada perubahan di sini.
     */
    private function getLaporanLabaRugiData($tahun)
    {
        $bkuModel = new BkuBulananModel();
        $masterLabaRugiModel = new MasterLabaRugiModel();
        $detailLabaRugiModel = new DetailLabaRugiModel();
        $db = \Config\Database::connect();

        $totalPenghasilanSetahun = $bkuModel->selectSum('penghasilan_bulan_ini')->where('tahun', $tahun)->get()->getRow()->penghasilan_bulan_ini ?? 0;

        $builder = $db->table('detail_alokasi as da');
        $builder->select('mk.nama_kategori, SUM(da.jumlah_realisasi) as total_per_kategori');
        $builder->join('bku_bulanan as bb', 'bb.id = da.bku_id');
        $builder->join('master_kategori_pengeluaran as mk', 'mk.id = da.master_kategori_id');
        $builder->where('bb.tahun', $tahun);
        $builder->groupBy('mk.nama_kategori');
        $pengeluaranBKU = $builder->get()->getResultArray();
        $pengeluaranBKUMap = array_column($pengeluaranBKU, 'total_per_kategori', 'nama_kategori');

        $komponenPendapatan = $masterLabaRugiModel->where('kategori', 'pendapatan')->findAll();
        $komponenBiaya = $masterLabaRugiModel->where('kategori', 'biaya')->findAll();

        $nilaiTersimpan = $detailLabaRugiModel->where('tahun', $tahun)->findAll();
        $nilaiTersimpanMap = array_column($nilaiTersimpan, 'jumlah', 'master_laba_rugi_id');

        foreach ($komponenPendapatan as &$item) {
            $item['jumlah'] = $nilaiTersimpanMap[$item['id']] ?? 0;
        }
        foreach ($komponenBiaya as &$item) {
            $item['jumlah'] = $nilaiTersimpanMap[$item['id']] ?? 0;
        }

        return [
            'pendapatanUsaha' => $totalPenghasilanSetahun,
            'biayaBahanBaku' => $pengeluaranBKUMap['PENGEMBANGAN'] ?? 0,
            'biayaGaji' => $pengeluaranBKUMap['HONOR'] ?? 0,
            'pad' => $pengeluaranBKUMap['PAD'] ?? 0,
            'komponenPendapatan' => $komponenPendapatan,
            'komponenBiaya' => $komponenBiaya,
        ];
    }
}
