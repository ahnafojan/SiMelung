<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PetaniModel;
use App\Models\KopiMasukModel;
use App\Models\KopiKeluarModel;
use App\Models\StokKopiModel;
use App\Models\JenisPohonModel;
use CodeIgniter\API\ResponseTrait;
use App\Models\HargaJenisKopiModel;

/**
 * @property \CodeIgniter\HTTP\CLIRequest $request
 */
class BumdesRekapKopi extends BaseController
{
    use ResponseTrait;

    protected $petaniModel;
    protected $kopiMasukModel;
    protected $kopiKeluarModel;
    protected $stokKopiModel;
    protected $jenisPohonModel;

    public function __construct()
    {
        $this->petaniModel   = new PetaniModel();
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

        $pageMasuk     = $this->request->getGet('page_masuk') ?? 1;
        $perPageMasuk  = $this->request->getGet('per_page_masuk') ?? 10;

        $pageKeluar    = $this->request->getGet('page_keluar') ?? 1;
        $perPageKeluar = $this->request->getGet('per_page_keluar') ?? 10;

        $pageStok      = $this->request->getGet('page_stok') ?? 1;
        $perPageStok   = $this->request->getGet('per_page_stok') ?? 10;

        $petaniList = $this->petaniModel->select('user_id, nama as nama_petani')->findAll();

        // Ambil data masuk & keluar
        [$rekapPetani, $pagerKopiMasuk]   = $this->getRekapKopiMasuk($filter, $perPageMasuk, $pageMasuk);
        [$rekapPenjualan, $pagerKopiKeluar] = $this->getRekapKopiKeluar($filter, $perPageKeluar, $pageKeluar);

        // Cegah kalkulasi stok kalau petani dipilih tapi tidak punya data masuk
        $stokAkhirPerJenis = [];
        $totalStokGlobal = 0;
        $totalNilaiStokGlobal = 0;
        $pagerStokAkhir = null;

        // FILTER KHUSUS STOK: abaikan petani, pakai tanggal saja
        $filterStok = $filter;
        $filterStok['petani'] = '';

        // Stok selalu dihitung (tidak tergantung petani)
        // dan hanya dipengaruhi start_date & end_date
        [$stokAkhirPerJenis, $pagerStokAkhir] = $this->getStokAkhir($filterStok, $perPageStok, $pageStok);

        $allStokData = $this->getStokAkhir($filterStok, 0, 1, false);
        $totalStokGlobal = array_sum(array_column($allStokData, 'stok_akhir'));

        // kalau kamu juga pakai total nilai stok global
        $totalNilaiStokGlobal = array_sum(array_column($allStokData, 'nilai_stok'));


        $data = [
            'petaniList'            => $petaniList,
            'filter'                => $filter,

            'rekapPetani'           => $rekapPetani,
            'pagerKopiMasuk'        => $pagerKopiMasuk,
            'perPageMasuk'          => $perPageMasuk,

            'rekapPenjualan'        => $rekapPenjualan,
            'pagerKopiKeluar'       => $pagerKopiKeluar,
            'perPageKeluar'         => $perPageKeluar,

            'stokAkhirPerJenis'     => $stokAkhirPerJenis,
            'pagerStokAkhir'        => $pagerStokAkhir,
            'perPageStok'           => $perPageStok,

            'totalStokGlobal'       => $totalStokGlobal,
            'totalNilaiStokGlobal'  => $totalNilaiStokGlobal,
        ];

        $data['breadcrumbs'] = [
            [
                'title' => 'Dashboard',
                'url'   => site_url('dashboard/dashboard_bumdes'),
                'icon'  => 'fas fa-fw fa-tachometer-alt'
            ],
            [
                'title' => 'Laporan BUMDES',
                'url'   => site_url('bumdes/laporan'),
                'icon'  => 'fas fa-fw fa-file-alt'
            ],
            [
                'title' => 'Laporan Rekap Kopi',
                'url'   => '#',
                'icon'  => 'fas fa-fw fa-file-alt'
            ]
        ];

        return view('bumdes/laporan/rekap_kopi', $data);
    }

    // ===================== REKAP KOPI MASUK =====================
    public function getRekapKopiMasuk($filter, $perPage = null, $page = null, $paginate = true)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('kopi_masuk');

        $builder->select('
            petani.nama as nama_petani,
            jenis_pohon.nama_jenis as jenis_kopi,
            kopi_masuk.tanggal as tanggal_transaksi,
            SUM(kopi_masuk.jumlah) AS total_masuk,
            SUM(kopi_masuk.total_harga) AS total_nilai_masuk,
            COUNT(kopi_masuk.id) AS jumlah_transaksi
        ');

        $builder->join('petani', 'petani.user_id = kopi_masuk.petani_user_id', 'left');
        $builder->join('petani_pohon', 'petani_pohon.id = kopi_masuk.petani_pohon_id', 'left');
        $builder->join('jenis_pohon', 'jenis_pohon.id = petani_pohon.jenis_pohon_id', 'left');

        if (!empty($filter['start_date'])) $builder->where('kopi_masuk.tanggal >=', $filter['start_date']);
        if (!empty($filter['end_date']))   $builder->where('kopi_masuk.tanggal <=', $filter['end_date']);
        if (!empty($filter['petani']))     $builder->where('kopi_masuk.petani_user_id', $filter['petani']);

        $builder->where('jenis_pohon.nama_jenis IS NOT NULL');

        $builder->groupBy('petani.nama');
        $builder->groupBy('jenis_pohon.nama_jenis');
        $builder->groupBy('kopi_masuk.tanggal');

        $builder->orderBy('kopi_masuk.tanggal', 'DESC');

        if ($paginate) {
            $totalBuilder = clone $builder;
            $total = count($totalBuilder->get()->getResultArray());
            $builder->limit($perPage, ($page - 1) * $perPage);
        }

        $data = $builder->get()->getResultArray();

        foreach ($data as &$row) {
            $row['rata_rata_setoran'] = ($row['jumlah_transaksi'] > 0)
                ? ($row['total_masuk'] / $row['jumlah_transaksi'])
                : 0;
        }

        if ($paginate) {
            $pager = service('pager');
            $pager->makeLinks($page, $perPage, $total, 'default_full', 0, 'masuk');
            return [$data, $pager];
        }

        return $data;
    }

    // ===================== REKAP KOPI KELUAR =====================
    public function getRekapKopiKeluar($filter, $perPage = null, $page = null, $paginate = true)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('kopi_keluar');

        $builder->select('
            kopi_keluar.tanggal,
            kopi_keluar.jumlah as jumlah_kg,
            kopi_keluar.tujuan as tujuan_pembeli,
            kopi_keluar.keterangan,
            jenis_pohon.id as jenis_pohon_id,
            jenis_pohon.nama_jenis as jenis_kopi,
            petani.nama as nama_petani,
            kopi_keluar.harga_saat_transaksi as harga_jual_per_kg
        ');

        $builder->join('stok_kopi', 'stok_kopi.id = kopi_keluar.stok_kopi_id', 'left');
        $builder->join('jenis_pohon', 'jenis_pohon.id = stok_kopi.jenis_pohon_id', 'left');
        $builder->join('petani', 'petani.user_id = stok_kopi.petani_id', 'left');

        if (!empty($filter['start_date'])) $builder->where('kopi_keluar.tanggal >=', $filter['start_date']);
        if (!empty($filter['end_date']))   $builder->where('kopi_keluar.tanggal <=', $filter['end_date']);
        if (!empty($filter['petani']))     $builder->where('stok_kopi.petani_id', $filter['petani']);

        $builder->orderBy('kopi_keluar.tanggal', 'DESC');

        if ($paginate) {
            $total = $builder->countAllResults(false);
            $builder->limit($perPage, ($page - 1) * $perPage);
        }

        $data = $builder->get()->getResultArray();

        // Hitung harga beli & margin (sama seperti komersial)
        $hargaModel = new HargaJenisKopiModel();
        $cacheHargaBeli = [];

        foreach ($data as &$row) {
            $jenisId = (int)($row['jenis_pohon_id'] ?? 0);
            $tgl     = $row['tanggal'] ?? date('Y-m-d');

            $cacheKey = $jenisId . '|' . $tgl;

            if (!array_key_exists($cacheKey, $cacheHargaBeli)) {
                $hargaTerbaru = $hargaModel->getLatestPrice($jenisId, $tgl);
                $cacheHargaBeli[$cacheKey] = (float)($hargaTerbaru['harga_beli_per_kg'] ?? 0);
            }

            $hargaBeli = $cacheHargaBeli[$cacheKey];
            $hargaJual = (float)($row['harga_jual_per_kg'] ?? 0);
            $qty       = (float)($row['jumlah_kg'] ?? 0);

            $row['total_harga_petani'] = $hargaBeli * $qty;
            $row['keuntungan_bumdes']  = ($hargaJual - $hargaBeli) * $qty;

            if ($row['keuntungan_bumdes'] < 0) $row['keuntungan_bumdes'] = 0;
        }

        if ($paginate) {
            $pager = service('pager');
            $pager->makeLinks($page, $perPage, $total, 'default_full', 0, 'keluar');
            return [$data, $pager];
        }

        return $data;
    }

    // ===================== STOK AKHIR =====================
    public function getStokAkhir($filter, $perPage = null, $page = null, $paginate = true)
    {
        $db = \Config\Database::connect();

        // 1) Total masuk per jenis
        $builderMasuk = $db->table('kopi_masuk');
        $builderMasuk->select('
            jenis_pohon.nama_jenis,
            SUM(kopi_masuk.jumlah) AS total_masuk
        ');
        $builderMasuk->join('petani_pohon', 'petani_pohon.id = kopi_masuk.petani_pohon_id', 'left');
        $builderMasuk->join('jenis_pohon', 'jenis_pohon.id = petani_pohon.jenis_pohon_id', 'left');

        if (!empty($filter['start_date'])) $builderMasuk->where('kopi_masuk.tanggal >=', $filter['start_date']);
        if (!empty($filter['end_date']))   $builderMasuk->where('kopi_masuk.tanggal <=', $filter['end_date']);


        $builderMasuk->where('jenis_pohon.nama_jenis IS NOT NULL');
        $builderMasuk->groupBy('jenis_pohon.nama_jenis');

        $dataMasuk = $builderMasuk->get()->getResultArray();
        $totalMasuk = array_column($dataMasuk, 'total_masuk', 'nama_jenis');

        // 2) Total keluar per jenis
        $builderKeluar = $db->table('kopi_keluar');
        $builderKeluar->select('
            jenis_pohon.nama_jenis,
            SUM(kopi_keluar.jumlah) AS total_keluar
        ');
        $builderKeluar->join('stok_kopi', 'stok_kopi.id = kopi_keluar.stok_kopi_id', 'left');
        $builderKeluar->join('jenis_pohon', 'jenis_pohon.id = stok_kopi.jenis_pohon_id', 'left');

        if (!empty($filter['start_date'])) $builderKeluar->where('kopi_keluar.tanggal >=', $filter['start_date']);
        if (!empty($filter['end_date']))   $builderKeluar->where('kopi_keluar.tanggal <=', $filter['end_date']);


        $builderKeluar->where('jenis_pohon.nama_jenis IS NOT NULL');
        $builderKeluar->groupBy('jenis_pohon.nama_jenis');

        $dataKeluar = $builderKeluar->get()->getResultArray();
        $totalKeluar = array_column($dataKeluar, 'total_keluar', 'nama_jenis');

        // 3) Harga jual terbaru per jenis (berdasarkan tanggal filter end_date)
        $hargaJualModel = new HargaJenisKopiModel();
        $jenisKopiList  = $this->jenisPohonModel->select('id, nama_jenis')->findAll();

        $hargaJualPerJenis = [];
        foreach ($jenisKopiList as $jenis) {
            $tanggalAcuan = $filter['end_date'] ?: date('Y-m-d');
            $hargaTerbaru = $hargaJualModel->getLatestPrice($jenis['id'], $tanggalAcuan);
            $hargaJualPerJenis[$jenis['nama_jenis']] = (float)($hargaTerbaru['harga_jual_per_kg'] ?? 0);
        }

        // 4) Kalkulasi stok akhir + nilai stok
        $stokAkhir = [];
        foreach ($jenisKopiList as $jenis) {
            $namaJenis = $jenis['nama_jenis'];

            $masuk  = (float)($totalMasuk[$namaJenis] ?? 0);
            $keluar = (float)($totalKeluar[$namaJenis] ?? 0);

            $hasilStok = max(0, $masuk - $keluar);

            $hargaJual = (float)($hargaJualPerJenis[$namaJenis] ?? 0);
            $nilaiStok = $hasilStok * $hargaJual;

            $stokAkhir[] = [
                'jenis_kopi'        => $namaJenis,
                'stok_akhir'        => $hasilStok,
                'harga_jual_per_kg' => $hargaJual,
                'nilai_stok'        => $nilaiStok,
            ];
        }

        // Urutkan by nama jenis
        usort($stokAkhir, function ($a, $b) {
            return strcasecmp($a['jenis_kopi'], $b['jenis_kopi']);
        });

        // 5) Pagination manual
        if (!$paginate) return $stokAkhir;

        $total = count($stokAkhir);
        $paginatedData = array_slice($stokAkhir, ($page - 1) * $perPage, $perPage);

        $pager = service('pager');
        $pager->makeLinks($page, $perPage, $total, 'default_full', 0, 'stok');

        return [$paginatedData, $pager];
    }
}
