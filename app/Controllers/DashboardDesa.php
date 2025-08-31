<?php

namespace App\Controllers;

use App\Models\KopiMasukModel;
use App\Models\KopiKeluarModel;
use App\Models\PetaniModel;
use App\Models\AsetKomersialModel;

class DashboardDesa extends BaseController
{
    public function index()
    {
        // ================== Cek Session ==================
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        // ================== Load Model ==================
        $kopiMasukModel  = new KopiMasukModel();
        $kopiKeluarModel = new KopiKeluarModel();
        $petaniModel     = new PetaniModel();
        $asetModel       = new AsetKomersialModel();

        // ================== Ambil Filter ==================
        $bulan = (int) ($this->request->getGet('bulan') ?? date('m'));
        $tahun = (int) ($this->request->getGet('tahun') ?? date('Y'));

        // ================== Hitung Total ==================
        $totalMasuk  = (int) ($kopiMasukModel->selectSum('jumlah')->first()['jumlah'] ?? 0);
        $totalKeluar = (int) ($kopiKeluarModel->selectSum('jumlah')->first()['jumlah'] ?? 0);
        $stokBersih  = $totalMasuk - $totalKeluar;
        $totalPetani = $petaniModel->countAll();
        $totalAset   = $asetModel->countAll();

        // ================== Data Grafik Masuk ==================
        $kopiMasuk = $kopiMasukModel
            ->select("DATE(tanggal) as tgl, SUM(jumlah) as total")
            ->where('MONTH(tanggal)', $bulan)
            ->where('YEAR(tanggal)', $tahun)
            ->groupBy('DATE(tanggal)')
            ->orderBy('tanggal', 'ASC')
            ->findAll();

        // ================== Data Grafik Keluar ==================
        $kopiKeluar = $kopiKeluarModel
            ->select("DATE(tanggal) as tgl, SUM(jumlah) as total")
            ->where('MONTH(tanggal)', $bulan)
            ->where('YEAR(tanggal)', $tahun)
            ->groupBy('DATE(tanggal)')
            ->orderBy('tanggal', 'ASC')
            ->findAll();

        // ================== Gabungkan Data ==================
        $tanggalList = [];
        foreach ($kopiMasuk as $row) {
            $tanggalList[$row['tgl']] = [
                'masuk' => (int) $row['total'],
                'keluar' => 0
            ];
        }
        foreach ($kopiKeluar as $row) {
            if (!isset($tanggalList[$row['tgl']])) {
                $tanggalList[$row['tgl']] = [
                    'masuk' => 0,
                    'keluar' => (int) $row['total']
                ];
            } else {
                $tanggalList[$row['tgl']]['keluar'] = (int) $row['total'];
            }
        }

        ksort($tanggalList);

        $labels     = array_keys($tanggalList);
        $dataMasuk  = array_column($tanggalList, 'masuk');
        $dataKeluar = array_column($tanggalList, 'keluar');

        // ================== Data Distribusi Stok per Jenis Kopi ==================
        $stokPerJenis = $kopiMasukModel
            ->select('jenis_pohon.nama_jenis, SUM(kopi_masuk.jumlah) as total')
            ->join('petani_pohon', 'petani_pohon.id = kopi_masuk.petani_pohon_id', 'left')
            ->join('jenis_pohon', 'jenis_pohon.id = petani_pohon.jenis_pohon_id', 'left')
            ->groupBy('jenis_pohon.nama_jenis')
            ->findAll();


        // Pisahkan label & data
        $jenisLabels = [];
        $jenisTotals = [];
        foreach ($stokPerJenis as $row) {
            $jenisLabels[] = $row['nama_jenis'] ?? 'Tidak Diketahui';
            $jenisTotals[] = (int) $row['total'];
        }


        // ================== Dropdown Tahun Dinamis ==================
        $startYear = 2020;

        $maxMasuk  = $kopiMasukModel->selectMax('tanggal')->first()['tanggal'] ?? null;
        $maxKeluar = $kopiKeluarModel->selectMax('tanggal')->first()['tanggal'] ?? null;

        $maxYearDb = max(
            $maxMasuk ? date('Y', strtotime($maxMasuk)) : date('Y'),
            $maxKeluar ? date('Y', strtotime($maxKeluar)) : date('Y')
        );

        $currentYear = date('Y');
        $endYear     = max($currentYear, $maxYearDb);

        $years = range($startYear, $endYear);

        // ================== Kirim Data ke View ==================
        $data = [
            'stokBersih'   => $stokBersih,
            'totalMasuk'   => $totalMasuk,
            'totalKeluar'  => $totalKeluar,
            'totalPetani'  => $totalPetani,
            'totalAset'    => $totalAset,
            'labels'       => json_encode($labels),
            'dataMasuk'    => json_encode($dataMasuk),
            'dataKeluar'   => json_encode($dataKeluar),
            'years'        => $years,
            'bulan'        => $bulan,
            'tahun'        => $tahun,
            'jenisLabels'  => json_encode($jenisLabels),
            'jenisTotals'  => json_encode($jenisTotals),
        ];

        return view('dashboard/dashboard_desa', $data);
    }
}
