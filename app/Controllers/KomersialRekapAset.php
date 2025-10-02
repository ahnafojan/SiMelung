<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AsetKomersialModel;
use CodeIgniter\API\ResponseTrait;

/**
 * @property \CodeIgniter\HTTP\IncomingRequest $request 
 */
class KomersialRekapAset extends BaseController
{
    use ResponseTrait;

    protected $asetModel;
    protected $pager;

    public function __construct()
    {
        $this->asetModel = new AsetKomersialModel();
        $this->pager = \Config\Services::pager();
    }

    public function index()
    {
        $filterTahunAset = $this->request->getGet('tahun_aset') ?? 'semua';
        $perPageAset     = $this->request->getGet('per_page_aset') ?? 10;

        // --- Logika untuk mendapatkan data ---
        $asetModelBuilder = $this->asetModel;
        if ($filterTahunAset && $filterTahunAset != 'semua') {
            $asetModelBuilder->where('tahun_perolehan', $filterTahunAset);
        }

        $dataAset = $asetModelBuilder->orderBy('tahun_perolehan', 'DESC')->paginate($perPageAset, 'aset');
        $pagerAset = $this->asetModel->pager;

        // --- Workaround untuk pager agar filter tetap ada di link paginasi ---
        $queryParams = $this->request->getGet();
        unset($queryParams['page_aset']);
        if (!empty($queryParams)) {
            $pagerAset->setPath(current_url() . '?' . http_build_query($queryParams));
        } else {
            $pagerAset->setPath(current_url());
        }

        // --- Logika untuk merespons AJAX ---
        if ($this->request->isAJAX()) {

            $table_partial = view('admin_komersial/laporan/_aset_table_partial', [
                'aset'      => $dataAset,
                'pagerAset' => $pagerAset // pager tetap dikirim ke partial jika dibutuhkan
            ]);

            // [PERUBAHAN] Hitung statistik dari objek pager yang sudah ada (lebih efisien)
            $totalItems = $pagerAset->getTotal('aset');
            $totalNilai = $this->asetModel->getTotalNilaiByFilter($filterTahunAset); // Asumsi ada fungsi ini di model

            return $this->response->setJSON([
                'table_partial' => $table_partial,
                // [BARU] Tambahkan key pagination dan total, sama seperti controller Petani
                'pagination'    => $pagerAset->links('aset', 'custom_pagination_template'), // Gunakan template custom
                'total'         => $totalItems,
                'stats' => [
                    'total_aset'   => number_format($totalItems, 0, ',', '.'),
                    'total_nilai'  => number_format($totalNilai, 0, ',', '.'),
                    'filter_aktif' => $filterTahunAset == 'semua' ? 'Semua Tahun' : 'Tahun ' . $filterTahunAset,
                    'per_page'     => $perPageAset . ' Item'
                ]
            ]);
        }

        // --- Kode untuk pemuatan halaman biasa (non-AJAX) ---
        $daftarTahunAset = $this->asetModel->getTahunPerolehan();
        $data = [
            'aset'          => $dataAset,
            'pagerAset'     => $pagerAset,
            'daftarTahun'   => $daftarTahunAset,
            'filterTahun'   => $filterTahunAset,
            'perPageAset'   => $perPageAset,
        ];
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => site_url('/dashboard/dashboard_komersial'), 'icon' => 'fas fa-fw fa-tachometer-alt'],
            ['title' => 'Laporan Komersial', 'url' => site_url('admin-komersial/laporan'), 'icon' => 'fas fa-fw fa-file-alt'],
            ['title' => 'Laporan Rekap Aset', 'url' => '#', 'icon' => 'fas fa-fw fa-tools']
        ];

        return view('admin_komersial/laporan/aset', $data);
    }
}
