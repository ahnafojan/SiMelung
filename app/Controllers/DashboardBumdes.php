<?php

namespace App\Controllers;

use App\Models\KopiMasukModel;
use App\Models\KopiKeluarModel;
use App\Models\PetaniModel;
use App\Models\AsetKomersialModel;
use App\Models\UserModel;
use App\Models\AsetPariwisataModel;   // ← Tambahkan
use App\Models\ObjekWisataModel;     // ← Tambahkan
use App\Models\UmkmModel;            // ← Tambahkan

class DashboardBumdes extends BaseController
{
    public function index()
    {
        // Cek sesi pengguna
        if (!session()->get('user_id')) {
            return redirect()->to('/login');
        }

        // ================== Load Model ==================
        $kopiMasukModel      = model(KopiMasukModel::class);
        $kopiKeluarModel     = model(KopiKeluarModel::class);
        $petaniModel         = model(PetaniModel::class);
        $userModel           = model(UserModel::class);
        $asetModel           = model(AsetKomersialModel::class);
        $asetPariwisataModel = model(AsetPariwisataModel::class); // ← Tambahkan
        $objekWisataModel    = model(ObjekWisataModel::class);    // ← Tambahkan
        $umkmModel           = model(UmkmModel::class);           // ← Tambahkan

        // ================== Ambil Filter Bulan dan Tahun ==================
        $bulan = (int) ($this->request->getGet('bulan') ?? date('m'));
        $tahun = (int) ($this->request->getGet('tahun') ?? date('Y'));

        // ================== Hitung Total Keseluruhan ==================
        $totalMasuk  = (int) ($kopiMasukModel->selectSum('jumlah')->first()['jumlah'] ?? 0);
        $totalKeluar = (int) ($kopiKeluarModel->selectSum('jumlah')->first()['jumlah'] ?? 0);
        $stokBersih  = $totalMasuk - $totalKeluar;

        $totalPetani = $petaniModel->countAll();
        $totalAset   = $asetModel->countAll();
        $totalUser   = $userModel->countAll();

        // ================== Ambil Data untuk Tabel Pariwisata ==================
        $asetsPariwisata = $asetPariwisataModel
            ->select('aset_pariwisata.*, objek_wisata.nama_wisata')
            ->join('aset_wisata', 'aset_wisata.aset_id = aset_pariwisata.id', 'left')
            ->join('objek_wisata', 'objek_wisata.id = aset_wisata.wisata_id', 'left')
            ->orderBy('aset_pariwisata.created_at', 'DESC')
            ->findAll();

        // ================== Ambil Data untuk Tabel UMKM ==================
        $umkmData = $umkmModel
            ->orderBy('nama_umkm', 'ASC')
            ->findAll();

        // ================== Data Grafik Masuk & Keluar per Hari ==================
        $kopiMasuk = $kopiMasukModel
            ->select("DATE(tanggal) as tgl, SUM(jumlah) as total")
            ->where('MONTH(tanggal)', $bulan)
            ->where('YEAR(tanggal)', $tahun)
            ->groupBy('DATE(tanggal)')
            ->orderBy('tanggal', 'ASC')
            ->findAll();

        $kopiKeluar = $kopiKeluarModel
            ->select("DATE(tanggal) as tgl, SUM(jumlah) as total")
            ->where('MONTH(tanggal)', $bulan)
            ->where('YEAR(tanggal)', $tahun)
            ->groupBy('DATE(tanggal)')
            ->orderBy('tanggal', 'ASC')
            ->findAll();

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

        $jenisLabels = [];
        $jenisTotals = [];
        foreach ($stokPerJenis as $row) {
            $jenisLabels[] = $row['nama_jenis'] ?? 'Tidak Diketahui';
            $jenisTotals[] = (int) $row['total'];
        }

        // ================== Dropdown Tahun Dinamis ==================
        $startYear = 2020;
        $endYear   = date('Y');

        $maxYearMasuk  = $kopiMasukModel->selectMax('YEAR(tanggal)', 'max_year')->first()['max_year'];
        $maxYearKeluar = $kopiKeluarModel->selectMax('YEAR(tanggal)', 'max_year')->first()['max_year'];
        $maxYearDb     = max((int)$maxYearMasuk, (int)$maxYearKeluar);

        if ($maxYearDb > $endYear) {
            $endYear = $maxYearDb;
        }

        $years = range($startYear, $endYear);

        // ================== Kirim Semua Data ke View ==================
        $data = [
            'stokBersih'        => $stokBersih,
            'totalMasuk'        => $totalMasuk,
            'totalKeluar'       => $totalKeluar,
            'totalPetani'       => $totalPetani,
            'totalAset'         => $totalAset,
            'totalUser'         => $totalUser,
            'asetsPariwisata'   => $asetsPariwisata, // ← Kirim data pariwisata
            'umkmData'          => $umkmData,        // ← Kirim data UMKM
            'labels'            => json_encode($labels),
            'dataMasuk'         => json_encode($dataMasuk),
            'dataKeluar'        => json_encode($dataKeluar),
            'years'             => $years,
            'bulan'             => $bulan,
            'tahun'             => $tahun,
            'jenisLabels'       => json_encode($jenisLabels),
            'jenisTotals'       => json_encode($jenisTotals),
            'breadcrumbs'       => [
                [
                    'title' => 'Dashboard',
                    'url'   => '#',
                    'icon'  => 'fas fa-fw fa-tachometer-alt'
                ]
            ]
        ];

        return view('dashboard/dashboard_bumdes', $data);
    }
}
