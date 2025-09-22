<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PetaniModel;
use App\Models\KopiMasukModel;
use App\Models\KopiKeluarModel;
use App\Models\StokKopiModel;
use App\Models\JenisPohonModel;
use CodeIgniter\API\ResponseTrait;

/**
 * @property \CodeIgniter\HTTP\CLIRequest $request 
 */
class KomersialRekapKopi extends BaseController
{
    use ResponseTrait;

    protected $petaniModel;
    protected $kopiMasukModel;
    protected $kopiKeluarModel;
    protected $stokKopiModel;
    protected $jenisPohonModel;

    public function __construct()
    {
        $this->petaniModel = new PetaniModel();
        $this->kopiMasukModel = new KopiMasukModel();
        $this->kopiKeluarModel = new KopiKeluarModel();
        $this->stokKopiModel = new StokKopiModel();
        $this->jenisPohonModel = new JenisPohonModel();
    }

    public function index()
    {
        $filter = [
            'start_date' => $this->request->getGet('start_date') ?? '',
            'end_date'   => $this->request->getGet('end_date') ?? '',
            'petani'     => $this->request->getGet('petani') ?? ''
        ];

        $pageMasuk = $this->request->getGet('page_masuk') ?? 1;
        $perPageMasuk = $this->request->getGet('per_page_masuk') ?? 10;
        $pageKeluar = $this->request->getGet('page_keluar') ?? 1;
        $perPageKeluar = $this->request->getGet('per_page_keluar') ?? 10;
        $pageStok = $this->request->getGet('page_stok') ?? 1;
        $perPageStok = $this->request->getGet('per_page_stok') ?? 10;

        $petaniList = $this->petaniModel->select('user_id, nama as nama_petani')->findAll();

        // Ambil data kopi masuk dan keluar terlebih dahulu
        list($rekapPetani, $pagerKopiMasuk) = $this->getRekapKopiMasuk($filter, $perPageMasuk, $pageMasuk);
        list($rekapPenjualan, $pagerKopiKeluar) = $this->getRekapKopiKeluar($filter, $perPageKeluar, $pageKeluar);

        // [AWAL PERUBAHAN] - Logika untuk mencegah kalkulasi stok yang tidak perlu
        // 1. Inisialisasi variabel stok dengan nilai default (kosong/nol)
        $stokAkhirPerJenis = [];
        $totalStokGlobal = 0;
        $pagerStokAkhir = null;

        // 2. Buat kondisi: true jika petani dipilih TAPI tidak punya data kopi masuk
        $petaniDipilihTanpaData = !empty($filter['petani']) && empty($rekapPetani);

        // 3. Hanya jalankan perhitungan stok jika kondisi di atas TIDAK terpenuhi
        if (!$petaniDipilihTanpaData) {
            list($stokAkhirPerJenis, $pagerStokAkhir) = $this->getStokAkhir($filter, $perPageStok, $pageStok);

            // Pastikan $stokAkhirPerJenis tidak kosong sebelum menghitung total global
            if (!empty($stokAkhirPerJenis)) {
                $allStokData = $this->getStokAkhir($filter, 0, 1, false);
                $totalStokGlobal = array_sum(array_column($allStokData, 'stok_akhir'));
            }
        }
        // [AKHIR PERUBAHAN]

        $data = [
            'petaniList'        => $petaniList,
            'filter'            => $filter,
            'rekapPetani'       => $rekapPetani,
            'rekapPenjualan'    => $rekapPenjualan,
            'stokAkhirPerJenis' => $stokAkhirPerJenis,
            'totalStokGlobal'   => $totalStokGlobal,
            'pagerKopiMasuk'    => $pagerKopiMasuk,
            'perPageMasuk'      => $perPageMasuk,
            'pagerKopiKeluar'   => $pagerKopiKeluar,
            'perPageKeluar'     => $perPageKeluar,
            'pagerStokAkhir'    => $pagerStokAkhir,
            'perPageStok'       => $perPageStok,
        ];
        $data['breadcrumbs'] = [
            [
                'title' => 'Dashboard',
                'url'   => site_url('/dashboard/dashboard_komersial'),
                'icon'  => 'fas fa-fw fa-tachometer-alt'
            ],
            [
                'title' => 'Laporan Komersial',
                'url'   => site_url('admin-komersial/laporan'),
                'icon'  => 'fas fa-fw fa-file-alt'
            ],
            [
                'title' => 'Laporan Rekap Kopi',
                'url'   => '#',
                'icon'  => 'fas fa-fw fa-file-alt'
            ]
        ];

        return view('admin_komersial/laporan/rekap_kopi', $data);
    }

    public function getRekapKopiMasuk($filter, $perPage = null, $page = null, $paginate = true)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('kopi_masuk');
        $builder->select('
            petani.nama as nama_petani,
            SUM(kopi_masuk.jumlah) AS total_masuk,
            MAX(kopi_masuk.tanggal) AS tanggal_terakhir,
            COUNT(kopi_masuk.id) AS jumlah_transaksi
        ');
        $builder->join('petani', 'petani.user_id = kopi_masuk.petani_user_id', 'left');

        if (!empty($filter['start_date'])) $builder->where('kopi_masuk.tanggal >=', $filter['start_date']);
        if (!empty($filter['end_date'])) $builder->where('kopi_masuk.tanggal <=', $filter['end_date']);
        if (!empty($filter['petani'])) $builder->where('kopi_masuk.petani_user_id', $filter['petani']);
        $builder->groupBy('petani.nama');

        // Logika Paginasi
        if ($paginate) {
            $totalBuilder = clone $builder;
            $total = $totalBuilder->countAllResults(false); // countAllResults() salah jika ada groupBy, jadi kita hitung manual
            if (is_array($total)) $total = count($total);

            $builder->limit($perPage, ($page - 1) * $perPage);
        }

        $data = $builder->get()->getResultArray();

        foreach ($data as &$row) {
            $row['rata_rata_setoran'] = $row['jumlah_transaksi'] > 0 ? $row['total_masuk'] / $row['jumlah_transaksi'] : 0;
        }

        if ($paginate) {
            $pager = service('pager');
            $pager->makeLinks($page, $perPage, $total, 'default_full', 0, 'masuk');
            return [$data, $pager];
        }

        return $data; // Kembalikan hanya data jika tidak paginasi
    }

    // Fungsi untuk mendapatkan data Rekap Kopi Keluar (Penjualan)
    public function getRekapKopiKeluar($filter, $perPage = null, $page = null, $paginate = true)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('kopi_keluar');
        $builder->select('
            kopi_keluar.tanggal,
            kopi_keluar.jumlah as jumlah_kg,
            kopi_keluar.tujuan as tujuan_pembeli,
            kopi_keluar.keterangan,
            jenis_pohon.nama_jenis as jenis_kopi
        ');
        $builder->join('stok_kopi', 'stok_kopi.id = kopi_keluar.stok_kopi_id', 'left');
        $builder->join('jenis_pohon', 'jenis_pohon.id = stok_kopi.jenis_pohon_id', 'left');
        $builder->orderBy('kopi_keluar.tanggal', 'DESC');

        if (!empty($filter['start_date'])) $builder->where('kopi_keluar.tanggal >=', $filter['start_date']);
        if (!empty($filter['end_date'])) $builder->where('kopi_keluar.tanggal <=', $filter['end_date']);

        if ($paginate) {
            $total = $builder->countAllResults(false);
            $builder->limit($perPage, ($page - 1) * $perPage);
        }

        $data = $builder->get()->getResultArray();

        if ($paginate) {
            $pager = service('pager');
            $pager->makeLinks($page, $perPage, $total, 'default_full', 0, 'keluar');
            return [$data, $pager];
        }

        return $data;
    }

    // Fungsi untuk mendapatkan data Stok Akhir per Jenis Kopi
    // Fungsi untuk mendapatkan data Stok Akhir per Jenis Kopi
    public function getStokAkhir($filter, $perPage = null, $page = null, $paginate = true)
    {
        $db = \Config\Database::connect();

        // --- 1. Ambil Total Kopi Masuk per Jenis Kopi ---
        $builderMasuk = $db->table('kopi_masuk');
        $builderMasuk->select('jenis_pohon.nama_jenis, SUM(kopi_masuk.jumlah) AS total_masuk');

        // PERBAIKAN: Gunakan dua JOIN untuk menghubungkan kopi_masuk ke jenis_pohon melalui petani_pohon
        $builderMasuk->join('petani_pohon', 'petani_pohon.id = kopi_masuk.petani_pohon_id', 'left');
        $builderMasuk->join('jenis_pohon', 'jenis_pohon.id = petani_pohon.jenis_pohon_id', 'left');

        if (!empty($filter['start_date'])) {
            $builderMasuk->where('kopi_masuk.tanggal >=', $filter['start_date']);
        }
        if (!empty($filter['end_date'])) {
            $builderMasuk->where('kopi_masuk.tanggal <=', $filter['end_date']);
        }
        if (!empty($filter['petani'])) {
            // Jika ada filter petani, kita perlu join ke tabel petani juga
            $builderMasuk->join('petani', 'petani.user_id = kopi_masuk.petani_user_id', 'left');
            $builderMasuk->where('kopi_masuk.petani_user_id', $filter['petani']);
        }

        $builderMasuk->where('jenis_pohon.nama_jenis IS NOT NULL');
        $builderMasuk->groupBy('jenis_pohon.nama_jenis');
        $dataMasuk = $builderMasuk->get()->getResultArray();
        $totalMasuk = array_column($dataMasuk, 'total_masuk', 'nama_jenis');

        // --- 2. Ambil Total Kopi Keluar per Jenis Kopi ---
        $builderKeluar = $db->table('kopi_keluar');
        $builderKeluar->select('jenis_pohon.nama_jenis, SUM(kopi_keluar.jumlah) AS total_keluar');
        $builderKeluar->join('stok_kopi', 'stok_kopi.id = kopi_keluar.stok_kopi_id', 'left');
        $builderKeluar->join('jenis_pohon', 'jenis_pohon.id = stok_kopi.jenis_pohon_id', 'left');

        if (!empty($filter['start_date'])) {
            $builderKeluar->where('kopi_keluar.tanggal >=', $filter['start_date']);
        }
        if (!empty($filter['end_date'])) {
            $builderKeluar->where('kopi_keluar.tanggal <=', $filter['end_date']);
        }
        // Filter petani tidak relevan untuk kopi keluar dalam konteks ini, jadi kita abaikan

        $builderKeluar->where('jenis_pohon.nama_jenis IS NOT NULL');
        $builderKeluar->groupBy('jenis_pohon.nama_jenis');
        $dataKeluar = $builderKeluar->get()->getResultArray();
        $totalKeluar = array_column($dataKeluar, 'total_keluar', 'nama_jenis');

        // --- 3. Kalkulasi Stok Akhir ---
        $stokAkhir = [];
        $jenisKopiList = $this->jenisPohonModel->select('nama_jenis')->findAll();

        foreach ($jenisKopiList as $jenis) {
            $namaJenis = $jenis['nama_jenis'];
            $masuk = $totalMasuk[$namaJenis] ?? 0;
            $keluar = $totalKeluar[$namaJenis] ?? 0;

            // Stok akhir tidak boleh negatif
            $hasilStok = max(0, $masuk - $keluar);

            $stokAkhir[] = [
                'jenis_kopi' => $namaJenis,
                'stok_akhir' => $hasilStok
            ];
        }

        // Urutkan array berdasarkan nama jenis kopi
        sort($stokAkhir);

        // --- 4. Logika Paginasi ---
        if (!$paginate) {
            return $stokAkhir; // Kembalikan semua data untuk proses export
        }

        $total = count($stokAkhir);
        $paginatedData = array_slice($stokAkhir, ($page - 1) * $perPage, $perPage);

        $pager = service('pager');
        $pager->makeLinks($page, $perPage, $total, 'default_full', 0, 'stok');

        return [$paginatedData, $pager];
    }
}
