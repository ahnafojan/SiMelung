<?php

namespace App\Controllers;

use App\Models\KopiKeluarModel;
use App\Models\HargaJenisKopiModel;
use CodeIgniter\Controller;
use CodeIgniter\I18n\Time;

class PendapatanKopi extends Controller
{
    protected $kopiKeluarModel;
    protected $hargaJenisKopiModel;

    public function __construct()
    {
        $this->kopiKeluarModel = new KopiKeluarModel();
        $this->hargaJenisKopiModel = new HargaJenisKopiModel();
        helper(['form', 'url', 'date']);
    }

    public function index()
    {
        // Cek autentikasi
        if (!session()->get('user_id')) {
            session()->setFlashdata('error', 'Anda harus login untuk mengakses halaman ini.');
            return redirect()->to('/login');
        }

        // Ambil filter dari GET
        $tanggalAwal = $this->request->getGet('tanggal_awal');
        $tanggalAkhir = $this->request->getGet('tanggal_akhir');
        $perPage = (int)($this->request->getGet('per_page') ?? 10);

        // Set default tanggal jika kosong
        if (empty($tanggalAwal)) {
            $tanggalAwal = date('Y-m-01'); // Awal bulan ini
        }
        if (empty($tanggalAkhir)) {
            $tanggalAkhir = date('Y-m-d'); // Hari ini
        }

        // Validasi format tanggal
        if (!$this->isValidDate($tanggalAwal) || !$this->isValidDate($tanggalAkhir)) {
            session()->setFlashdata('error', 'Format tanggal tidak valid.');
            return redirect()->to('/pendapatan-kopi');
        }

        // Pastikan tanggal_awal <= tanggal_akhir
        if ($tanggalAwal > $tanggalAkhir) {
            [$tanggalAwal, $tanggalAkhir] = [$tanggalAkhir, $tanggalAwal];
        }

        // --- Ambil Data Transaksi untuk Tabel (dengan pagination) ---
        $transaksi = $this->kopiKeluarModel->getTransaksiWithPagination($tanggalAwal, $tanggalAkhir, $perPage);

        // Tambahkan harga_beli_per_kg ke setiap transaksi
        foreach ($transaksi as &$t) {
            $hargaBeli = $this->kopiKeluarModel->getHargaBeliPerKg($t['jenis_pohon_id'], $t['tanggal']);
            $t['harga_beli_per_kg'] = $hargaBeli ?? 0;
        }
        unset($t); // Unset reference

        // Ambil pager dari model
        $pager = $this->kopiKeluarModel->pager;
        $currentPage = $pager->getCurrentPage('pendapatan');

        // --- Hitung Ringkasan (gunakan data tanpa pagination) ---
        $summary = $this->calculateSummary($tanggalAwal, $tanggalAkhir);

        // --- Siapkan Data untuk Grafik ---
        $chartData = $this->getChartData($tanggalAwal, $tanggalAkhir);

        // --- Kirim Data ke View ---
        $data = [
            'tanggalAwal'      => $tanggalAwal,
            'tanggalAkhir'     => $tanggalAkhir,
            'totalPendapatan'  => $summary['total_pendapatan'],
            'totalBiaya'       => $summary['total_biaya'],
            'labaBersih'       => $summary['laba_bersih'],
            'jumlahTransaksi'  => $summary['jumlah_transaksi'],
            'transaksi'        => $transaksi,
            'pager'            => $pager,
            'currentPage'      => $currentPage,
            'perPage'          => $perPage,
            'chartData'        => $chartData,
        ];
        $data['breadcrumbs'] = [
            [
                'title' => 'Dashboard',
                'url'   => site_url('/dashboard/dashboard_komersial'),
                'icon'  => 'fas fa-fw fa-tachometer-alt'
            ],
            [
                'title' => 'Pendapatan Kopi',
                'url'   => '#',
                'icon'  => 'fas fa-seedling'
            ]
        ];

        return view('admin_komersial/kopi/pendapatan', $data);
    }

    /**
     * Fungsi untuk menghitung ringkasan pendapatan, biaya, dan laba.
     * Menggunakan data lengkap tanpa pagination.
     *
     * @param string $tanggalAwal
     * @param string $tanggalAkhir
     * @return array
     */
    private function calculateSummary($tanggalAwal, $tanggalAkhir)
    {
        // Ambil semua transaksi untuk perhitungan (tanpa pagination)
        $transaksi = $this->kopiKeluarModel->getTransaksiForCalculation($tanggalAwal, $tanggalAkhir);

        $totalPendapatan = 0;
        $totalBiaya = 0;
        $jumlahTransaksi = count($transaksi);

        foreach ($transaksi as $keluar) {
            // Hitung pendapatan
            $totalPendapatan += (float)($keluar['total_harga_jual'] ?? 0);

            // Hitung biaya berdasarkan harga beli
            $hargaBeli = $this->kopiKeluarModel->getHargaBeliPerKg($keluar['jenis_pohon_id'], $keluar['tanggal']);
            if ($hargaBeli) {
                $totalBiaya += (float)$keluar['jumlah'] * $hargaBeli;
            }
        }

        $labaBersih = $totalPendapatan - $totalBiaya;

        return [
            'total_pendapatan' => $totalPendapatan,
            'total_biaya'      => $totalBiaya,
            'laba_bersih'      => $labaBersih,
            'jumlah_transaksi' => $jumlahTransaksi
        ];
    }

    /**
     * Fungsi untuk mengambil data harian untuk grafik.
     *
     * @param string $tanggalAwal
     * @param string $tanggalAkhir
     * @return array
     */
    private function getChartData($tanggalAwal, $tanggalAkhir)
    {
        // Generate array tanggal antara awal dan akhir
        $dates = $this->generateDateRange($tanggalAwal, $tanggalAkhir);

        // Inisialisasi data grafik
        $pendapatanPerHari = array_fill_keys($dates, 0);
        $biayaPerHari = array_fill_keys($dates, 0);

        // Ambil transaksi untuk perhitungan chart
        $transaksi = $this->kopiKeluarModel->getTransaksiForCalculation($tanggalAwal, $tanggalAkhir);

        // Hitung per hari
        foreach ($transaksi as $keluar) {
            $tanggal = $keluar['tanggal'];

            if (isset($pendapatanPerHari[$tanggal])) {
                // Tambahkan pendapatan
                $pendapatanPerHari[$tanggal] += (float)($keluar['total_harga_jual'] ?? 0);

                // Hitung dan tambahkan biaya
                $hargaBeli = $this->kopiKeluarModel->getHargaBeliPerKg($keluar['jenis_pohon_id'], $keluar['tanggal']);
                if ($hargaBeli) {
                    $biayaPerHari[$tanggal] += (float)$keluar['jumlah'] * $hargaBeli;
                }
            }
        }

        // Hitung laba harian
        $labaPerHari = [];
        foreach ($dates as $date) {
            $labaPerHari[$date] = $pendapatanPerHari[$date] - $biayaPerHari[$date];
        }

        // Format label tanggal untuk Chart.js (lebih user-friendly)
        $formattedLabels = array_map(function ($date) {
            return date('d M', strtotime($date));
        }, $dates);

        // Return data untuk Chart.js
        return [
            'labels' => $formattedLabels,
            'pendapatan' => array_values($pendapatanPerHari),
            'biaya' => array_values($biayaPerHari),
            'laba' => array_values($labaPerHari),
        ];
    }

    /**
     * Generate array tanggal dari tanggal awal ke tanggal akhir.
     *
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    private function generateDateRange($startDate, $endDate)
    {
        $dates = [];
        $current = new Time($startDate);
        $end = new Time($endDate);

        while ($current->getTimestamp() <= $end->getTimestamp()) {
            $dates[] = $current->format('Y-m-d');
            $current = $current->addDays(1);
        }

        return $dates;
    }

    /**
     * Validasi format tanggal.
     *
     * @param string $date
     * @return bool
     */
    private function isValidDate($date)
    {
        $d = \DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }
}
