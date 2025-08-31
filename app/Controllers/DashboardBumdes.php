<?php

namespace App\Controllers;

use App\Models\KopiMasukModel;
use App\Models\KopiKeluarModel;
use App\Models\PetaniModel;
use App\Models\AsetKomersialModel;
use App\Models\UserModel;

class DashboardBumdes extends BaseController
{
    public function index()
    {
        // Cek sesi pengguna
        if (!session()->get('user_id')) {
            return redirect()->to('/login');
        }

        // ================== Load Model (Best Practice) ==================
        $kopiMasukModel  = model(KopiMasukModel::class);
        $kopiKeluarModel = model(KopiKeluarModel::class);
        $petaniModel     = model(PetaniModel::class);
        $userModel       = model(UserModel::class); // Dipertahankan jika dibutuhkan di tempat lain
        $asetModel       = model(AsetKomersialModel::class);

        // ================== Ambil Filter Bulan dan Tahun ==================
        $bulan = (int) ($this->request->getGet('bulan') ?? date('m'));
        $tahun = (int) ($this->request->getGet('tahun') ?? date('Y'));

        // ================== Hitung Total Keseluruhan (untuk Kartu Statistik) ==================
        $totalMasuk  = (int) ($kopiMasukModel->selectSum('jumlah')->first()['jumlah'] ?? 0);
        $totalKeluar = (int) ($kopiKeluarModel->selectSum('jumlah')->first()['jumlah'] ?? 0);
        $stokBersih  = $totalMasuk - $totalKeluar;

        $totalPetani = $petaniModel->countAll();
        $totalAset   = $asetModel->countAll();
        $totalUser   = $userModel->countAll();

        // ================== Data Grafik Masuk & Keluar per Hari (Berdasarkan Filter) ==================
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

        // Menggabungkan data masuk dan keluar untuk chart
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
        ksort($tanggalList); // Urutkan berdasarkan tanggal

        $labels     = array_keys($tanggalList);
        $dataMasuk  = array_column($tanggalList, 'masuk');
        $dataKeluar = array_column($tanggalList, 'keluar');

        // ================== Data Distribusi Stok per Jenis Kopi (LOGIKA DISAMAKAN DENGAN KOMERSIAL) ==================
        // Logika ini hanya menghitung total kopi masuk per jenis, bukan stok bersih.
        // Tujuannya adalah agar halaman bisa berjalan tanpa error.
        $stokPerJenis = $kopiMasukModel
            ->select('jenis_pohon.nama_jenis, SUM(kopi_masuk.jumlah) as total')
            ->join('petani_pohon', 'petani_pohon.id = kopi_masuk.petani_pohon_id', 'left')
            ->join('jenis_pohon', 'jenis_pohon.id = petani_pohon.jenis_pohon_id', 'left')
            ->groupBy('jenis_pohon.nama_jenis')
            ->findAll();

        // Pisahkan label & data untuk pie chart
        $jenisLabels = [];
        $jenisTotals = [];
        foreach ($stokPerJenis as $row) {
            $jenisLabels[] = $row['nama_jenis'] ?? 'Tidak Diketahui';
            $jenisTotals[] = (int) $row['total'];
        }

        // ================== Dropdown Tahun Dinamis ==================
        $startYear = 2020;
        $endYear   = date('Y');

        // Ambil tahun maksimal dari data jika ada, untuk memastikan range tahun relevan
        $maxYearMasuk  = $kopiMasukModel->selectMax('YEAR(tanggal)', 'max_year')->first()['max_year'];
        $maxYearKeluar = $kopiKeluarModel->selectMax('YEAR(tanggal)', 'max_year')->first()['max_year'];
        $maxYearDb     = max((int)$maxYearMasuk, (int)$maxYearKeluar);

        if ($maxYearDb > $endYear) {
            $endYear = $maxYearDb;
        }

        $years = range($startYear, $endYear);

        // ================== Kirim Data ke View ==================
        $data = [
            'stokBersih'  => $stokBersih,
            'totalMasuk'  => $totalMasuk,
            'totalKeluar' => $totalKeluar,
            'totalPetani' => $totalPetani,
            'totalAset'   => $totalAset,
            'totalUser'   => $totalUser,
            'labels'      => json_encode($labels),
            'dataMasuk'   => json_encode($dataMasuk),
            'dataKeluar'  => json_encode($dataKeluar),
            'years'       => $years,
            'bulan'       => $bulan,
            'tahun'       => $tahun,
            'jenisLabels' => json_encode($jenisLabels),
            'jenisTotals' => json_encode($jenisTotals),
        ];

        return view('dashboard/dashboard_bumdes', $data);
    }
}
