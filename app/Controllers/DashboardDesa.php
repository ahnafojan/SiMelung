<?php

namespace App\Controllers;

use App\Models\AsetKomersialModel;
use App\Models\AsetPariwisataModel;
use App\Models\KopiKeluarModel;
use App\Models\KopiMasukModel;
use App\Models\ObjekWisataModel;
use App\Models\PetaniModel;
use App\Models\UmkmModel;

class DashboardDesa extends BaseController
{
    public function index()
    {
        // Cek Session
        if (!session()->get('user_id')) {
            return redirect()->to('/login');
        }

        // Load Models
        $kopiMasukModel      = new KopiMasukModel();
        $kopiKeluarModel     = new KopiKeluarModel();
        $petaniModel         = new PetaniModel();
        $asetKomersialModel  = new AsetKomersialModel();
        $asetPariwisataModel = new AsetPariwisataModel();
        $objekWisataModel    = new ObjekWisataModel();
        $umkmModel           = new UmkmModel();

        // Filter
        $bulan = (int) ($this->request->getGet('bulan') ?? date('m'));
        $tahun = (int) ($this->request->getGet('tahun') ?? date('Y'));

        // Ambil data UMKM (hanya yang dipublikasikan, opsional)
        $umkmData = $umkmModel
            ->where('is_published', 1) // opsional: hanya tampilkan yang aktif
            ->orderBy('nama_umkm', 'ASC')
            ->findAll();

        // KPI Data
        $totalMasuk = (int) ($kopiMasukModel->selectSum('jumlah')->first()['jumlah'] ?? 0);
        $totalKeluar = (int) ($kopiKeluarModel->selectSum('jumlah')->first()['jumlah'] ?? 0);
        $stokBersih = $totalMasuk - $totalKeluar;
        $totalPetani = $petaniModel->countAll();
        $totalAset = $asetKomersialModel->countAll();
        $totalObjekWisata = $objekWisataModel->countAll();
        $totalAsetPariwisata = $asetPariwisataModel->countAll();
        $totalNilaiAsetPariwisata = $asetPariwisataModel->selectSum('nilai_perolehan')->first()['nilai_perolehan'] ?? 0;

        // PAGINATION - MANUAL (TANPA ERROR)
        $perPage = 5;
        $currentPage = (int) ($this->request->getGet('page_pariwisata') ?? 1);

        // Ambil semua data
        $allAsetsPariwisata = $asetPariwisataModel
            ->select('aset_pariwisata.*, objek_wisata.nama_wisata')
            ->join('aset_wisata', 'aset_wisata.aset_id = aset_pariwisata.id', 'left')
            ->join('objek_wisata', 'objek_wisata.id = aset_wisata.wisata_id', 'left')
            ->orderBy('aset_pariwisata.created_at', 'DESC')
            ->findAll();

        // Hitung pagination
        $totalData = count($allAsetsPariwisata);
        $totalPages = ceil($totalData / $perPage);

        // Validasi halaman
        if ($currentPage < 1) $currentPage = 1;
        if ($currentPage > $totalPages && $totalPages > 0) $currentPage = $totalPages;

        // Slice data untuk halaman saat ini
        $offset = ($currentPage - 1) * $perPage;
        $asetsPariwisata = array_slice($allAsetsPariwisata, $offset, $perPage);

        // Data Grafik
        $kopiMasuk = $kopiMasukModel
            ->select("DATE(tanggal) as tgl, SUM(jumlah) as total")
            ->where('MONTH(tanggal)', $bulan)->where('YEAR(tanggal)', $tahun)
            ->groupBy('DATE(tanggal)')->orderBy('tanggal', 'ASC')->findAll();

        $kopiKeluar = $kopiKeluarModel
            ->select("DATE(tanggal) as tgl, SUM(jumlah) as total")
            ->where('MONTH(tanggal)', $bulan)->where('YEAR(tanggal)', $tahun)
            ->groupBy('DATE(tanggal)')->orderBy('tanggal', 'ASC')->findAll();

        $tanggalList = [];
        foreach ($kopiMasuk as $row) {
            $tanggalList[$row['tgl']] = ['masuk' => (int) $row['total'], 'keluar' => 0];
        }
        foreach ($kopiKeluar as $row) {
            if (!isset($tanggalList[$row['tgl']])) {
                $tanggalList[$row['tgl']] = ['masuk' => 0, 'keluar' => (int) $row['total']];
            } else {
                $tanggalList[$row['tgl']]['keluar'] = (int) $row['total'];
            }
        }
        ksort($tanggalList);

        $labels = array_keys($tanggalList);
        $dataMasuk = array_column($tanggalList, 'masuk');
        $dataKeluar = array_column($tanggalList, 'keluar');

        $stokPerJenis = $kopiMasukModel
            ->select('jenis_pohon.nama_jenis, SUM(kopi_masuk.jumlah) as total')
            ->join('petani_pohon', 'petani_pohon.id = kopi_masuk.petani_pohon_id', 'left')
            ->join('jenis_pohon', 'jenis_pohon.id = petani_pohon.jenis_pohon_id', 'left')
            ->groupBy('jenis_pohon.nama_jenis')->findAll();

        $jenisLabels = [];
        $jenisTotals = [];
        foreach ($stokPerJenis as $row) {
            $jenisLabels[] = $row['nama_jenis'] ?? 'Tidak Diketahui';
            $jenisTotals[] = (int) $row['total'];
        }

        // Years untuk filter
        $startYear = 2020;
        $maxMasuk = $kopiMasukModel->selectMax('tanggal')->first()['tanggal'] ?? null;
        $maxKeluar = $kopiKeluarModel->selectMax('tanggal')->first()['tanggal'] ?? null;
        $maxYearDb = max(
            $maxMasuk ? date('Y', strtotime($maxMasuk)) : date('Y'),
            $maxKeluar ? date('Y', strtotime($maxKeluar)) : date('Y')
        );
        $currentYear = date('Y');
        $endYear = max($currentYear, $maxYearDb);
        $years = range($startYear, $endYear);

        // Data untuk View
        $data = [
            'stokBersih' => $stokBersih,
            'totalMasuk' => $totalMasuk,
            'totalKeluar' => $totalKeluar,
            'totalPetani' => $totalPetani,
            'totalAset' => $totalAset,
            'totalObjekWisata' => $totalObjekWisata,
            'totalAsetPariwisata' => $totalAsetPariwisata,
            'totalNilaiAsetPariwisata' => $totalNilaiAsetPariwisata,
            'asetsPariwisata' => $asetsPariwisata,
            'umkmData' => $umkmData,
            'totalData' => $totalData,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'perPage' => $perPage,
            'labels' => json_encode($labels),
            'dataMasuk' => json_encode($dataMasuk),
            'dataKeluar' => json_encode($dataKeluar),
            'jenisLabels' => json_encode($jenisLabels),
            'jenisTotals' => json_encode($jenisTotals),
            'years' => $years,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'breadcrumbs' => [
                [
                    'title' => 'Dashboard',
                    'url'   => '#', // Halaman aktif, tidak perlu link
                    'icon'  => 'fas fa-fw fa-tachometer-alt'
                ]
            ]
        ];

        return view('dashboard/dashboard_desa', $data);
    }
}
