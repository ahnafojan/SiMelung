<?php

namespace App\Controllers;

use App\Models\PetaniModel;
use App\Models\KopiMasukModel;
use App\Models\KopiKeluarModel;
use App\Models\AsetKomersialModel;

class LandingPage extends BaseController
{
    public function index()
    {
        // ... BAGIAN 1 & 2 (Petani & Grafik Kopi) tidak ada perubahan ...
        // ----------------------------------------------------
        // BAGIAN 1: Mengambil Data Petani untuk Tabel
        // ----------------------------------------------------
        $petaniModel = new PetaniModel();
        $perPage = $this->request->getGet('per_page') ?? 10;
        $petani_list = $petaniModel
            ->select('petani.id, petani.nama, petani.foto, SUM(petani_pohon.luas_lahan) as total_lahan, GROUP_CONCAT(jenis_pohon.nama_jenis SEPARATOR ", ") as jenis_pohon_list')
            ->join('petani_pohon', 'petani_pohon.user_id = petani.user_id', 'left')
            ->join('jenis_pohon', 'jenis_pohon.id = petani_pohon.jenis_pohon_id', 'left')
            ->groupBy('petani.id')
            ->paginate($perPage);
        $pager = $petaniModel->pager;

        // ----------------------------------------------------
        // BAGIAN 2: Menyiapkan Data untuk Grafik Statistik Kopi
        // ----------------------------------------------------
        $kopiMasukModel = new KopiMasukModel();
        $kopiKeluarModel = new KopiKeluarModel();
        $currentYear = date('Y');
        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $dataKopiMasuk = array_fill(0, 12, 0);
        $dataKopiKeluar = array_fill(0, 12, 0);
        $masukData = $kopiMasukModel->select("SUM(jumlah) as total, MONTH(tanggal) as bulan")->where('YEAR(tanggal)', $currentYear)->groupBy('bulan')->findAll();
        foreach ($masukData as $row) {
            $dataKopiMasuk[$row['bulan'] - 1] = (int)$row['total'];
        }
        $keluarData = $kopiKeluarModel->select("SUM(jumlah) as total, MONTH(tanggal) as bulan")->where('YEAR(tanggal)', $currentYear)->groupBy('bulan')->findAll();
        foreach ($keluarData as $row) {
            $dataKopiKeluar[$row['bulan'] - 1] = (int)$row['total'];
        }

        // ----------------------------------------------------
        // BAGIAN 3: LOGIKA BARU - Menyiapkan Ringkasan Aset Secara Dinamis
        // ----------------------------------------------------
        $asetModel = new AsetKomersialModel();
        $aset_summary = [];

        // 1. Buat "Kamus Visual" untuk memetakan kata kunci ke ikon, warna, dan unit.
        // Ini membuat tampilan tetap konsisten.
        $asetMapping = [
            'Mesin Giling'         => ['icon' => 'fas fa-cogs',      'unit' => 'Unit', 'color_class' => 'bg-gradient-primary'],
            'Mesin Pengupas'       => ['icon' => 'fas fa-fan',       'unit' => 'Unit', 'color_class' => 'bg-gradient-coffee'],
            'Mesin Pengering'      => ['icon' => 'fas fa-wind',      'unit' => 'Unit', 'color_class' => 'bg-gradient-warning'],
            'Gudang'               => ['icon' => 'fas fa-warehouse', 'unit' => 'Unit', 'color_class' => 'bg-gradient-success'],
            'Kendaraan'            => ['icon' => 'fas fa-truck',     'unit' => 'Unit', 'color_class' => 'bg-gradient-info'],
            'Peralatan'            => ['icon' => 'fas fa-tools',     'unit' => 'Set',  'color_class' => 'bg-gradient-nature'],
            'default'              => ['icon' => 'fas fa-box',       'unit' => 'Unit', 'color_class' => 'bg-gradient-primary'] // Untuk aset "Lainnya"
        ];

        // 2. Ambil semua nama aset yang unik dari database.
        $uniqueAsetItems = $asetModel->distinct()->select('nama_aset')->findAll();

        foreach ($uniqueAsetItems as $item) {
            $namaAset = $item['nama_aset'];

            // 3. Ambil semua aset dengan nama ini untuk dihitung dan dicek kondisinya.
            $asetDalamKategori = $asetModel->where('nama_aset', $namaAset)->findAll();
            $jumlah = count($asetDalamKategori);

            if ($jumlah > 0) {
                // 4. Tentukan detail visual (ikon, warna, unit) dari "Kamus Visual".
                $detailVisual = $asetMapping['default']; // Gunakan default sebagai awalan
                foreach ($asetMapping as $keyword => $detail) {
                    if ($keyword !== 'default' && strpos($namaAset, $keyword) !== false) {
                        $detailVisual = $detail;
                        break; // Hentikan pencarian jika kata kunci ditemukan
                    }
                }

                // 5. Tentukan status berdasarkan kondisi.
                $butuhPerhatian = false;
                foreach ($asetDalamKategori as $aset) {
                    if (in_array($aset['keterangan'], ['Perlu Perawatan', 'Rusak', 'Dalam Perbaikan'])) {
                        $butuhPerhatian = true;
                        break;
                    }
                }
                $statusTeks = $butuhPerhatian ? 'Dalam Perawatan' : 'Kondisi Baik';
                $statusClass = $butuhPerhatian ? 'maintenance' : 'good';

                // 6. Kumpulkan data untuk dikirim ke view.
                $aset_summary[] = [
                    'nama'         => $namaAset,
                    'jumlah'       => $jumlah,
                    'icon'         => $detailVisual['icon'],
                    'status'       => $statusTeks,
                    'status_class' => $statusClass,
                    'unit'         => $detailVisual['unit'],
                    'color_class'  => $detailVisual['color_class']
                ];
            }
        }

        // ----------------------------------------------------
        // BAGIAN 4: Menggabungkan Semua Data untuk Dikirim ke View
        // ----------------------------------------------------
        $data = [
            'petani_list' => $petani_list,
            'pager'       => $pager,
            'perPage'     => $perPage,
            'chartLabels'     => json_encode($labels),
            'chartKopiMasuk'  => json_encode($dataKopiMasuk),
            'chartKopiKeluar' => json_encode($dataKopiKeluar),
            'chartYear'       => $currentYear,
            'aset_summary' => $aset_summary
        ];

        return view('landing/landing_page', $data);
    }
}
