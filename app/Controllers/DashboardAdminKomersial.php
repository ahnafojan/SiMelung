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
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $kopiMasukModel  = new KopiMasukModel();
        $kopiKeluarModel = new KopiKeluarModel();
        $petaniModel = new PetaniModel();
        $asetModel = new AsetKomersialModel();


        // Hitung total kopi masuk
        $totalMasuk = $kopiMasukModel
            ->selectSum('jumlah')->first()['jumlah'] ?? 0;

        // Hitung total kopi keluar
        $totalKeluar = $kopiKeluarModel->selectSum('jumlah')->first()['jumlah'] ?? 0;

        // Hitung stok bersih
        $stokBersih = $totalMasuk - $totalKeluar;

        //total petani
        $totalPetani = $petaniModel->countAll();
        //total aset
        $totalAset = $asetModel->countAll();

        // Data Grafik Bulan Ini
        $bulanIni = date('m');
        $tahunIni = date('Y');

        // Data kopi masuk per tanggal
        $kopiMasuk = $kopiMasukModel
            ->select("DATE(tanggal) as tgl, SUM(jumlah) as total")
            ->where('MONTH(tanggal)', $bulanIni)
            ->where('YEAR(tanggal)', $tahunIni)
            ->groupBy('DATE(tanggal)')
            ->orderBy('tanggal', 'ASC')
            ->findAll();

        // Data kopi keluar per tanggal
        $kopiKeluar = $kopiKeluarModel
            ->select("DATE(tanggal) as tgl, SUM(jumlah) as total")
            ->where('MONTH(tanggal)', $bulanIni)
            ->where('YEAR(tanggal)', $tahunIni)
            ->groupBy('DATE(tanggal)')
            ->orderBy('tanggal', 'ASC')
            ->findAll();

        // Gabungkan tanggal dari masuk & keluar
        $tanggalList = [];
        foreach ($kopiMasuk as $row) {
            $tanggalList[$row['tgl']] = ['masuk' => $row['total'], 'keluar' => 0];
        }
        foreach ($kopiKeluar as $row) {
            if (!isset($tanggalList[$row['tgl']])) {
                $tanggalList[$row['tgl']] = ['masuk' => 0, 'keluar' => $row['total']];
            } else {
                $tanggalList[$row['tgl']]['keluar'] = $row['total'];
            }
        }

        ksort($tanggalList); // Urutkan berdasarkan tanggal

        $labels     = array_keys($tanggalList);
        $dataMasuk  = array_column($tanggalList, 'masuk');
        $dataKeluar = array_column($tanggalList, 'keluar');

        // Kirim ke view
        $data = [
            'stokBersih'  => $stokBersih,
            'totalMasuk'  => $totalMasuk,
            'totalKeluar' => $totalKeluar,
            'totalPetani' => $totalPetani,
            'totalAset'   => $totalAset,
            'labels'      => json_encode($labels),
            'dataMasuk'   => json_encode($dataMasuk),
            'dataKeluar'  => json_encode($dataKeluar)
        ];

        return view('dashboard/dashboard_komersial', $data);
    }
}
