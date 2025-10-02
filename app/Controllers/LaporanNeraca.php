<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BkuBulananModel;
use App\Models\MasterNeracaModel;
use App\Models\DetailNeracaModel;
use App\Models\LabaRugiTahunModel;

class LaporanNeraca extends BaseController
{
    /**
     * [DIUBAH] Method ini sekarang menghitung semua total dan subtotal 
     * untuk dikirim langsung ke view.
     */
    private function getNeracaData($tahun)
    {
        $masterNeracaModel = new MasterNeracaModel();
        $detailNeracaModel = new DetailNeracaModel();
        $labaRugiTahunModel = new LabaRugiTahunModel();

        $labaRugiData = $labaRugiTahunModel->where('tahun', $tahun)->first();
        $surplusDefisitDitahan = $labaRugiData['laba_rugi_bersih'] ?? 0;

        $semuaKomponen = $masterNeracaModel->orderBy('kategori, id')->findAll();
        $nilaiTersimpan = $detailNeracaModel->where('tahun', $tahun)->findAll();
        $nilaiTersimpanMap = array_column($nilaiTersimpan, 'jumlah', 'master_neraca_id');

        $komponen = [
            'aktiva_lancar' => [],
            'aktiva_tetap' => [],
            'hutang_lancar' => [],
            'hutang_jangka_panjang' => [],
            'modal' => []
        ];

        // Kalkulasi Total
        $totals = [
            'aktiva_lancar' => 0,
            'aktiva_tetap' => 0,
            'hutang_lancar' => 0,
            'hutang_jangka_panjang' => 0,
            'modal' => 0
        ];

        foreach ($semuaKomponen as $item) {
            $jumlah = $nilaiTersimpanMap[$item['id']] ?? 0;
            $item['jumlah'] = $jumlah;
            $komponen[$item['kategori']][] = $item;
            $totals[$item['kategori']] += $jumlah;
        }

        $totalModal = $totals['modal'] + $surplusDefisitDitahan;
        $totalAktiva = $totals['aktiva_lancar'] + $totals['aktiva_tetap'];
        $totalPasiva = $totals['hutang_lancar'] + $totals['hutang_jangka_panjang'] + $totalModal;

        return [
            'tahunDipilih' => $tahun,
            'komponen' => $komponen,
            'surplusDefisitDitahan' => $surplusDefisitDitahan,
            // [BARU] Mengirim semua data total ke view
            'total_aktiva_lancar' => $totals['aktiva_lancar'],
            'total_aktiva_tetap' => $totals['aktiva_tetap'],
            'total_aktiva' => $totalAktiva,
            'total_hutang_lancar' => $totals['hutang_lancar'],
            'total_hutang_jangka_panjang' => $totals['hutang_jangka_panjang'],
            'total_modal' => $totalModal,
            'total_pasiva' => $totalPasiva
        ];
    }

    public function index()
    {
        $bkuModel = new BkuBulananModel();
        $data = [
            'title' => 'Laporan Neraca Keuangan',
            'daftar_tahun' => $bkuModel->select('tahun')->distinct()->orderBy('tahun', 'DESC')->findAll()
        ];

        $tahunDipilih = $this->request->getGet('tahun');
        if ($tahunDipilih) {
            $neracaData = $this->getNeracaData($tahunDipilih);
            $data = array_merge($data, $neracaData);
        }
        $data['breadcrumbs'] = [
            [
                'title' => 'Dashboard',
                'url'   => site_url('dashboard/dashboard_desa'), // Sesuaikan URL dashboard Anda
                'icon'  => 'fas fa-fw fa-tachometer-alt'
            ],
            [
                'title' => 'Laporan Neraca Keuangan',
                'url'   => '#',
                'icon'  => 'fas fa-fw fa-chart-pie fa-lg' // Ikon yang cocok untuk data master
            ]
        ];

        return view('desa/laporan_keuangan/laporan_neraca', $data);
    }

    // FUNGSI simpan(), cetakPdf(), dan cetakExcel() DIHAPUS SELURUHNYA
}
