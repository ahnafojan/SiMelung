<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BkuBulananModel;
use App\Models\MasterArusKasModel;
use App\Models\DetailArusKasModel;

class LaporanArusKas extends BaseController
{
    protected $bkuModel;
    protected $masterModel;
    protected $detailModel;
    protected $db;

    public function __construct()
    {
        $this->bkuModel = new BkuBulananModel();
        $this->masterModel = new MasterArusKasModel();
        $this->detailModel = new DetailArusKasModel();
        $this->db = \Config\Database::connect();
    }

    /**
     * Menampilkan halaman Laporan Arus Kas (mode read-only).
     */
    public function index()
    {
        $data = [
            'title' => 'Laporan Arus Kas Desa',
            'daftar_tahun' => $this->bkuModel->select('tahun')->distinct()->orderBy('tahun', 'DESC')->findAll(),
            // KUNCI: Menambahkan peran pengguna untuk mengontrol view
            'user_role' => 'kepala_desa'
        ];

        $tahunDipilih = $this->request->getGet('tahun');
        if ($tahunDipilih) {
            $data['tahunDipilih'] = $tahunDipilih;
            $laporanData = $this->getArusKasData($tahunDipilih);
            $data = array_merge($data, $laporanData);
        }
        $data['breadcrumbs'] = [
            [
                'title' => 'Dashboard',
                'url'   => site_url('dashboard/dashboard_desa'), // Sesuaikan URL dashboard Anda
                'icon'  => 'fas fa-fw fa-tachometer-alt'
            ],
            [
                'title' => 'Laporan Arus Kas',
                'url'   => '#',
                'icon'  => 'fas fa-fw fa-chart-pie fa-lg' // Ikon yang cocok untuk data master
            ]
        ];

        // Arahkan ke view yang sama, karena view sudah bisa menangani peran yang berbeda
        return view('desa/laporan_keuangan/laporan_aruskas', $data);
    }

    /**
     * Method ini mengambil data arus kas dari database.
     * Logikanya sama persis dengan controller admin untuk memastikan konsistensi data.
     */
    private function getArusKasData($tahun)
    {
        $pendapatanUtama = (int) ($this->bkuModel->selectSum('penghasilan_bulan_ini')->where('tahun', $tahun)->get()->getRow()->penghasilan_bulan_ini ?? 0);

        $builder = $this->db->table('detail_alokasi as da');
        $builder->select('mk.nama_kategori, SUM(da.jumlah_realisasi) as total_per_kategori')
            ->join('bku_bulanan as bb', 'bb.id = da.bku_id')
            ->join('master_kategori_pengeluaran as mk', 'mk.id = da.master_kategori_id')
            ->where('bb.tahun', $tahun)
            ->groupBy('mk.nama_kategori');
        $pengeluaranBKU = $builder->get()->getResultArray();
        $pengeluaranMap = array_column($pengeluaranBKU, 'total_per_kategori', 'nama_kategori');

        $pembelianBarang = (int) ($pengeluaranMap['PENGEMBANGAN'] ?? 0);
        $bebanGaji = (int) ($pengeluaranMap['HONOR'] ?? 0);
        $pad = (int) ($pengeluaranMap['PAD'] ?? 0);

        $komponenMasuk = $this->masterModel->where('kategori', 'masuk')->findAll();
        $komponenKeluar = $this->masterModel->where('kategori', 'keluar')->findAll();
        $nilaiTersimpan = $this->detailModel->where('tahun', $tahun)->findAll();
        $nilaiTersimpanMap = array_column($nilaiTersimpan, 'jumlah', 'master_arus_kas_id');

        $totalDinamisMasuk = 0;
        foreach ($komponenMasuk as &$item) {
            $jumlah = (int) ($nilaiTersimpanMap[$item['id']] ?? 0);
            $item['jumlah'] = $jumlah;
            $totalDinamisMasuk += $jumlah;
        }

        $totalDinamisKeluar = 0;
        foreach ($komponenKeluar as &$item) {
            $jumlah = (int) ($nilaiTersimpanMap[$item['id']] ?? 0);
            $item['jumlah'] = $jumlah;
            $totalDinamisKeluar += $jumlah;
        }

        $totalKasMasuk = $pendapatanUtama + $totalDinamisMasuk;
        $totalKasKeluar = $pembelianBarang + $bebanGaji + $pad + $totalDinamisKeluar;
        $saldoAkhir = $totalKasMasuk - $totalKasKeluar;

        return [
            'pendapatanUtama' => $pendapatanUtama,
            'pembelianBarang' => $pembelianBarang,
            'bebanGaji' => $bebanGaji,
            'pad' => $pad,
            'komponenMasuk' => $komponenMasuk,
            'komponenKeluar' => $komponenKeluar,
            'totalKasMasuk' => $totalKasMasuk,
            'totalKasKeluar' => $totalKasKeluar,
            'saldoAkhir' => $saldoAkhir,
            'tahun' => $tahun
        ];
    }
}
