<?php

namespace App\Controllers;

use App\Models\KopiMasukModel;
use App\Models\KopiKeluarModel;
use App\Models\PetaniModel;
use App\Models\AsetKomersialModel;

class DashboardAdminKomersial extends BaseController
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

        // =================================================
        // A) DATA FILTERED (hanya untuk card masuk/keluar + grafik + pie)
        // =================================================

        // Total kopi masuk FILTERED
        $totalMasukRow = $kopiMasukModel
            ->selectSum('jumlah', 'total')
            ->where('MONTH(tanggal)', $bulan)
            ->where('YEAR(tanggal)', $tahun)
            ->first();
        $totalMasuk = (float) ($totalMasukRow['total'] ?? 0);

        // Total kopi keluar FILTERED
        $totalKeluarRow = $kopiKeluarModel
            ->selectSum('jumlah', 'total')
            ->where('MONTH(tanggal)', $bulan)
            ->where('YEAR(tanggal)', $tahun)
            ->first();
        $totalKeluar = (float) ($totalKeluarRow['total'] ?? 0);

        // Grafik Masuk FILTERED
        $kopiMasuk = $kopiMasukModel
            ->select("DATE(tanggal) as tgl, SUM(jumlah) as total")
            ->where('MONTH(tanggal)', $bulan)
            ->where('YEAR(tanggal)', $tahun)
            ->groupBy('DATE(tanggal)')
            ->orderBy('tanggal', 'ASC')
            ->findAll();

        // Grafik Keluar FILTERED
        $kopiKeluar = $kopiKeluarModel
            ->select("DATE(tanggal) as tgl, SUM(jumlah) as total")
            ->where('MONTH(tanggal)', $bulan)
            ->where('YEAR(tanggal)', $tahun)
            ->groupBy('DATE(tanggal)')
            ->orderBy('tanggal', 'ASC')
            ->findAll();

        // Gabungkan data grafik
        $tanggalList = [];
        foreach ($kopiMasuk as $row) {
            $tanggalList[$row['tgl']] = [
                'masuk'  => (int) $row['total'],
                'keluar' => 0
            ];
        }
        foreach ($kopiKeluar as $row) {
            if (!isset($tanggalList[$row['tgl']])) {
                $tanggalList[$row['tgl']] = [
                    'masuk'  => 0,
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

        // Pie Chart Jenis Kopi FILTERED
        $stokPerJenis = $kopiMasukModel
            ->select('jenis_pohon.nama_jenis, SUM(kopi_masuk.jumlah) as total')
            ->join('petani_pohon', 'petani_pohon.id = kopi_masuk.petani_pohon_id', 'left')
            ->join('jenis_pohon', 'jenis_pohon.id = petani_pohon.jenis_pohon_id', 'left')
            ->where('MONTH(kopi_masuk.tanggal)', $bulan)
            ->where('YEAR(kopi_masuk.tanggal)', $tahun)
            ->groupBy('jenis_pohon.nama_jenis')
            ->findAll();

        $jenisLabels = [];
        $jenisTotals = [];
        foreach ($stokPerJenis as $row) {
            $jenisLabels[] = $row['nama_jenis'] ?? 'Tidak Diketahui';
            $jenisTotals[] = (int) ($row['total'] ?? 0);
        }

        // =================================================
        // B) DATA GLOBAL (TIDAK IKUT FILTER) untuk card tertentu
        // =================================================

        // Total Masuk GLOBAL
        $totalMasukGlobalRow = $kopiMasukModel->selectSum('jumlah', 'total')->first();
        $totalMasukGlobal = (float) ($totalMasukGlobalRow['total'] ?? 0);

        $totalKeluarGlobalRow = $kopiKeluarModel->selectSum('jumlah', 'total')->first();
        $totalKeluarGlobal = (float) ($totalKeluarGlobalRow['total'] ?? 0);

        $stokBersihGlobal = $totalMasukGlobal - $totalKeluarGlobal;


        // Petani GLOBAL (total terdaftar)
        $totalPetaniGlobal = $petaniModel->countAll();

        // Aset GLOBAL (total terdaftar)
        $totalAsetGlobal = $asetModel->countAll();

        // Tingkat distribusi GLOBAL
        $tingkatDistribusiGlobal = ($totalMasukGlobal > 0)
            ? ($totalKeluarGlobal / $totalMasukGlobal) * 100
            : 0;

        // ================== Dropdown Tahun Dinamis ==================
        $startYear = 2020;

        $maxMasuk  = $kopiMasukModel->selectMax('tanggal')->first()['tanggal'] ?? null;
        $maxKeluar = $kopiKeluarModel->selectMax('tanggal')->first()['tanggal'] ?? null;

        $maxYearDb = max(
            $maxMasuk ? (int) date('Y', strtotime($maxMasuk)) : (int) date('Y'),
            $maxKeluar ? (int) date('Y', strtotime($maxKeluar)) : (int) date('Y')
        );

        $currentYear = (int) date('Y');
        $endYear     = max($currentYear, $maxYearDb);

        $years = range($startYear, $endYear);

        // ================== Kirim Data ke View ==================
        $data = [
            // FILTERED (untuk card Kopi Masuk/Keluar + grafik)
            'totalMasuk'  => $totalMasuk,
            'totalKeluar' => $totalKeluar,

            'labels'      => json_encode($labels),
            'dataMasuk'   => json_encode($dataMasuk),
            'dataKeluar'  => json_encode($dataKeluar),

            'jenisLabels' => json_encode($jenisLabels),
            'jenisTotals' => json_encode($jenisTotals),

            // GLOBAL (untuk card yang tidak ikut filter)
            'stokBersihGlobal'          => $stokBersihGlobal,
            'totalPetaniGlobal'         => $totalPetaniGlobal,
            'totalAsetGlobal'           => $totalAsetGlobal,
            'tingkatDistribusiGlobal'   => $tingkatDistribusiGlobal,
            'totalMasukGlobal'          => $totalMasukGlobal,     // opsional kalau mau dipakai di view
            'totalKeluarGlobal'         => $totalKeluarGlobal,    // opsional kalau mau dipakai di view

            // filter UI
            'years'      => $years,
            'bulan'      => $bulan,
            'tahun'      => $tahun,

            'breadcrumbs' => [
                [
                    'title' => 'Dashboard',
                    'url'   => '#',
                    'icon'  => 'fas fa-fw fa-tachometer-alt'
                ]
            ]
        ];

        return view('dashboard/dashboard_komersial', $data);
    }
}
