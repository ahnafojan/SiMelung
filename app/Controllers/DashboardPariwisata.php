<?php

namespace App\Controllers;

use App\Models\AsetPariwisataModel;
use App\Models\ObjekWisataModel;

class DashboardPariwisata extends BaseController
{
    /**
     * Menampilkan halaman dashboard utama dengan statistik lengkap.
     */
    public function index()
    {
        $asetModel   = new AsetPariwisataModel();
        $wisataModel = new ObjekWisataModel();

        // 1. Data untuk Kartu Statistik
        $jumlahAset = (clone $asetModel)->countAllResults();
        $jumlahWisata = (clone $wisataModel)->countAllResults();

        $totalNilaiResult = (clone $asetModel)->selectSum('nilai_perolehan')->get()->getRow();
        $totalNilai = $totalNilaiResult->nilai_perolehan ?? 0;

        $rataRataNilai = ($jumlahAset > 0) ? ($totalNilai / $jumlahAset) : 0;

        // 2. Data untuk Grafik "Aset per Tahun"
        $asetPerTahun = (clone $asetModel)->select('tahun_perolehan, COUNT(*) as jumlah')
            ->where('tahun_perolehan IS NOT NULL')
            ->groupBy('tahun_perolehan')
            ->orderBy('tahun_perolehan', 'ASC')
            ->findAll();

        // 3. Data untuk Grafik "Aset per Lokasi"
        $asetPerLokasi = (clone $asetModel)->select('objek_wisata.nama_wisata, COUNT(aset_pariwisata.id) as jumlah')
            ->join('aset_wisata', 'aset_wisata.aset_id = aset_pariwisata.id', 'left')
            ->join('objek_wisata', 'objek_wisata.id = aset_wisata.wisata_id', 'left')
            ->where('objek_wisata.nama_wisata IS NOT NULL') // Hanya tampilkan yang punya lokasi
            ->groupBy('objek_wisata.id')
            ->orderBy('jumlah', 'DESC')
            ->findAll();

        // 4. Data untuk Tabel "Aset Terbaru"
        $asetTerbaru = (clone $asetModel)->select('
            aset_pariwisata.nama_aset,
            aset_pariwisata.nilai_perolehan,
            aset_pariwisata.created_at,
            objek_wisata.nama_wisata
        ')
            ->join('aset_wisata', 'aset_wisata.aset_id = aset_pariwisata.id', 'left')
            ->join('objek_wisata', 'objek_wisata.id = aset_wisata.wisata_id', 'left')
            ->orderBy('aset_pariwisata.created_at', 'DESC')
            ->limit(5)
            ->findAll();

        // Menggabungkan semua data ke dalam satu array untuk dikirim ke view
        $data = [
            'title'           => 'Dashboard Aset Pariwisata',
            'jumlah_aset'     => $jumlahAset,
            'jumlah_wisata'   => $jumlahWisata,
            'total_nilai'     => $totalNilai,
            'rata_rata_nilai' => $rataRataNilai,
            'aset_per_tahun'  => $asetPerTahun,
            'aset_per_lokasi' => $asetPerLokasi,
            'aset_terbaru'    => $asetTerbaru,
        ];

        return view('dashboard/dashboard_pariwisata', $data);
    }
}
