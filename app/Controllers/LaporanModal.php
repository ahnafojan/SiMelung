<?php

namespace App\Controllers;

// Namespace yang tidak perlu sudah dihapus (PhpSpreadsheet, Dompdf)
use App\Models\MasterPerubahanModalModel;
use App\Models\DetailPerubahanModalModel;
use App\Models\LabaRugiTahunModel;
use App\Models\BkuBulananModel;

class LaporanModal extends BaseController
{
    protected $masterModel;
    protected $detailModel;
    protected $labaRugiModel;
    protected $bkuModel;

    public function __construct()
    {
        $this->masterModel = new MasterPerubahanModalModel();
        $this->detailModel = new DetailPerubahanModalModel();
        $this->labaRugiModel = new LabaRugiTahunModel();
        $this->bkuModel = new BkuBulananModel();
    }

    /**
     * Menampilkan halaman laporan perubahan modal (read-only).
     */
    public function index()
    {
        $daftarTahun = $this->bkuModel->select('tahun')->distinct()->orderBy('tahun', 'DESC')->findAll();
        $tahunTerbaru = !empty($daftarTahun) ? $daftarTahun[0]['tahun'] : date('Y');
        $tahunTerpilih = $this->request->getGet('tahun') ?? $tahunTerbaru;

        // Ambil semua data yang relevan untuk laporan
        $laporanData = $this->_getLaporanData($tahunTerpilih);

        $data = [
            'title'             => 'Laporan Perubahan Modal',
            'daftar_tahun'      => $daftarTahun,
            'tahun_terpilih'    => $tahunTerpilih,
            'komponen'          => $laporanData['semua_komponen'],
            'laba_rugi_bersih'  => $laporanData['laba_rugi_bersih'],
            'detail_map'        => $laporanData['detail_map'],
            'modal_akhir'       => $laporanData['modal_akhir'], // Kirim modal akhir ke view
        ];
        $data['breadcrumbs'] = [
            [
                'title' => 'Dashboard',
                'url'   => site_url('dashboard/dashboard_desa'), // Sesuaikan URL dashboard Anda
                'icon'  => 'fas fa-fw fa-tachometer-alt'
            ],
            [
                'title' => 'Laporan Perubahan Modal',
                'url'   => '#',
                'icon'  => 'fas fa-fw fa-chart-pie fa-lg' // Ikon yang cocok untuk data master
            ]
        ];

        // Ganti nama view jika diperlukan, misal: 'laporan_modal_readonly'
        return view('desa/laporan_keuangan/laporan_modal', $data);
    }

    /**
     * Metode privat untuk mengambil dan menghitung data laporan.
     * Tidak ada perubahan di sini, karena fungsinya sudah benar.
     */
    private function _getLaporanData($tahun)
    {
        $semuaKomponen = $this->masterModel->orderBy('kategori', 'ASC')->findAll();
        $labaRugiData = $this->labaRugiModel->where('tahun', $tahun)->first();
        $labaRugiBersih = (float) ($labaRugiData['laba_rugi_bersih'] ?? 0);
        $detailTersimpan = $this->detailModel->where('tahun', $tahun)->findAll();
        $detailMap = array_column($detailTersimpan, 'jumlah', 'master_perubahan_modal_id');

        $totalPenambahan = $labaRugiBersih;
        $totalPengurangan = 0;

        foreach ($semuaKomponen as $item) {
            $jumlah = (float) ($detailMap[$item['id']] ?? 0);
            if ($item['kategori'] == 'penambahan') {
                $totalPenambahan += $jumlah;
            } else {
                $totalPengurangan += $jumlah;
            }
        }

        return [
            'tahun'                 => $tahun,
            'laba_rugi_bersih'      => $labaRugiBersih,
            'semua_komponen'        => $semuaKomponen,
            'detail_map'            => $detailMap,
            'modal_akhir'           => $totalPenambahan - $totalPengurangan // Hasil kalkulasi
        ];
    }

    // FUNGSI simpan(), exportExcel(), dan exportPdf() DIHAPUS SELURUHNYA
}
