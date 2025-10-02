<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BkuBulananModel;
// Namespace yang tidak perlu sudah dihapus (Dompdf, PhpSpreadsheet, dll)

class LaporanBkuTahunan extends BaseController
{
    /**
     * Menampilkan halaman utama Laporan BKU Tahunan (read-only).
     */
    public function index()
    {
        $bkuModel = new BkuBulananModel();

        $data = [
            'title' => 'Laporan BKU Tahunan',
            'daftar_tahun' => $bkuModel->select('tahun')->distinct()->orderBy('tahun', 'DESC')->findAll()
        ];

        $tahunDipilih = $this->request->getGet('tahun');

        if ($tahunDipilih) {
            $data['tahunDipilih'] = $tahunDipilih;
            // Panggil helper method untuk mendapatkan data yang sudah dihitung
            $data['hasil'] = $this->getLaporanTahunanData($tahunDipilih);
        }
        $data['breadcrumbs'] = [
            [
                'title' => 'Dashboard',
                'url'   => site_url('dashboard/dashboard_desa'), // Sesuaikan URL dashboard Anda
                'icon'  => 'fas fa-fw fa-tachometer-alt'
            ],
            [
                'title' => 'Laporan BKU Tahunan',
                'url'   => '#',
                'icon'  => 'fas fa-fw fa-chart-pie fa-lg' // Ikon yang cocok untuk data master
            ]
        ];

        return view('desa/laporan_keuangan/laporan_BKU_tahunan', $data);
    }

    /**
     * Method helper untuk mengambil dan menghitung data laporan tahunan.
     * Tidak ada perubahan fungsionalitas, hanya menyajikan data.
     */
    private function getLaporanTahunanData($tahun)
    {
        $bkuModel = new BkuBulananModel();
        $db = \Config\Database::connect();

        // 1. Ambil saldo awal tahun
        $laporanBulanPertama = $bkuModel->where('tahun', $tahun)->orderBy('bulan', 'ASC')->first();
        $saldoAwalTahun = $laporanBulanPertama['saldo_bulan_lalu'] ?? 0;

        // 2. Jumlahkan HANYA 'penghasilan_bulan_ini' selama setahun
        $totalPenghasilanSetahun = $bkuModel->selectSum('penghasilan_bulan_ini')->where('tahun', $tahun)->first()['penghasilan_bulan_ini'] ?? 0;

        // 3. Total Pendapatan Tahunan yang valid
        $totalPendapatanTahunan = $saldoAwalTahun + $totalPenghasilanSetahun;

        // 4. Hitung total pengeluaran per kategori
        $builder = $db->table('detail_pengeluaran as dp');
        $builder->select('mkp.nama_kategori, SUM(dp.jumlah) as total_per_kategori');
        $builder->join('bku_bulanan as bb', 'bb.id = dp.bku_id');
        $builder->join('master_kategori_pengeluaran as mkp', 'mkp.id = dp.master_kategori_id');
        $builder->where('bb.tahun', $tahun);
        $builder->groupBy('dp.master_kategori_id');
        $pengeluaranPerKategori = $builder->get()->getResultArray();

        // 5. Hitung total semua pengeluaran
        $totalPengeluaran = array_sum(array_column($pengeluaranPerKategori, 'total_per_kategori'));

        // 6. Hitung saldo akhir tahun
        $saldoAkhirTahun = $totalPendapatanTahunan - $totalPengeluaran;

        return [
            'totalPendapatan' => $totalPendapatanTahunan,
            'pengeluaranPerKategori' => $pengeluaranPerKategori,
            'totalPengeluaran' => $totalPengeluaran,
            'saldoAkhirTahun' => $saldoAkhirTahun,
        ];
    }

    // Method cetakPdf(), cetakExcel(), dan logAktivitas() telah dihapus.
}
