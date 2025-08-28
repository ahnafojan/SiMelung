<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PetaniModel;
use App\Models\JenisPohonModel;
use CodeIgniter\API\ResponseTrait;
// Tambahkan library untuk export
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * @property \CodeIgniter\HTTP\IncomingRequest $request
 */
class KomersialRekapPetani extends BaseController
{
    use ResponseTrait;
    protected $petaniModel;
    protected $jenisPohonModel;

    public function __construct()
    {
        $this->petaniModel = new PetaniModel();
        $this->jenisPohonModel = new JenisPohonModel();
    }

    /**
     * Menampilkan halaman utama dan menangani request AJAX.
     */
    public function index()
    {
        // 1. Mengambil nilai filter dari URL
        $filters = [
            'search'     => $this->request->getGet('search') ?? '',
            'jenis_kopi' => $this->request->getGet('jenis_kopi') ?? '',
        ];
        $perPage = $this->request->getGet('per_page') ?? 10;

        // 2. Membangun dan menjalankan query dengan paginasi
        $petaniQuery = $this->_getFilteredPetaniQuery($filters);
        $rekapPetaniTerdaftar = $petaniQuery->paginate($perPage, 'petani');
        $petaniPager = $this->petaniModel->pager;

        // 3. Workaround pager untuk versi lama CodeIgniter
        $queryParams = $this->request->getGet();
        unset($queryParams['page_petani']);
        if (!empty($queryParams)) {
            $petaniPager->setPath(current_url() . '?' . http_build_query($queryParams));
        } else {
            $petaniPager->setPath(current_url());
        }

        // 4. Cek jika ini adalah request AJAX dari filter
        if ($this->request->isAJAX()) {

            // Buat potongan HTML untuk daftar petani
            $list_view_html = $this->_buildPetaniListView($rekapPetaniTerdaftar, $petaniPager);

            // Kembalikan JSON dengan format yang benar sesuai harapan JavaScript
            return $this->response->setJSON([
                'list_view'  => $list_view_html,
                'pagination' => $petaniPager->links('petani', 'default_full'),
                'total'      => $petaniPager->getTotal('petani')
            ]);
        }

        // 5. Kode di bawah ini hanya untuk pemuatan halaman awal (non-AJAX)
        foreach ($rekapPetaniTerdaftar as &$petani) {
            $petani['jenis_kopi_list'] = $petani['jenis_kopi_list'] ?? 'Tidak Terdata';
        }
        $daftarJenisKopi = $this->jenisPohonModel->select('nama_jenis')->distinct()->orderBy('nama_jenis', 'ASC')->findAll();

        $data = [
            'rekapPetaniTerdaftar' => $rekapPetaniTerdaftar,
            'petaniPager'          => $petaniPager,
            'filters'              => $filters,
            'perPage'              => $perPage,
            'daftarJenisKopi'      => $daftarJenisKopi,
        ];
        $data['petaniListView'] = $this->_buildPetaniListView($rekapPetaniTerdaftar, $petaniPager);

        return view('admin_komersial/laporan/petani', $data);
    }

    public function _getFilteredPetaniQuery(array $filters)
    {
        $query = $this->petaniModel
            ->select('
                petani.id, 
                petani.user_id, 
                petani.nama, 
                petani.alamat, 
                petani.no_hp, 
                GROUP_CONCAT(DISTINCT jenis_pohon.nama_jenis SEPARATOR ", ") as jenis_kopi_list
            ')
            ->join('petani_pohon', 'petani_pohon.user_id = petani.user_id', 'left')
            ->join('jenis_pohon', 'jenis_pohon.id = petani_pohon.jenis_pohon_id', 'left');

        if (!empty($filters['search'])) {
            $query->like('petani.nama', $filters['search']);
        }

        $query->groupBy('petani.id, petani.user_id, petani.nama, petani.alamat, petani.no_hp');

        if (!empty($filters['jenis_kopi'])) {
            $query->havingLike('jenis_kopi_list', $filters['jenis_kopi']);
        }

        return $query;
    }

    /**
     * [PRIVATE] Membangun HTML untuk daftar petani (AJAX).
     */
    private function _buildPetaniListView($petaniData, $pager)
    {
        $html = '';
        if (empty($petaniData)) {
            return '<div class="card-body"><div class="empty-state"><i class="fas fa-users-slash"></i><h5>Data Petani Tidak Ditemukan</h5><p class="mb-0">Coba ubah atau reset filter Anda.</p></div></div>';
        }

        // Tampilan Mobile
        $mobileHtml = '<div class="card-body view-mobile p-0"><div id="petani-list-mobile">';
        foreach ($petaniData as $petani) {
            $mobileHtml .= '<div class="request-card">' .
                '<div class="card-header-info"><div class="requester-info"><div class="icon-circle"><i class="fas fa-user"></i></div>' .
                '<div><div class="requester-name">' . esc($petani['nama']) . '</div><div class="request-time">User ID: ' . esc($petani['user_id']) . '</div></div></div></div>' .
                '<hr class="my-2"><div class="card-body-details">' .
                '<p class="mb-1"><strong><i class="fas fa-map-marker-alt fa-fw mr-2"></i>Alamat:</strong> ' . esc($petani['alamat']) . '</p>' .
                '<p class="mb-1"><strong><i class="fas fa-phone fa-fw mr-2"></i>No. HP:</strong> ' . esc($petani['no_hp']) . '</p>' .
                '<p class="mb-0"><strong><i class="fas fa-seedling fa-fw mr-2"></i>Jenis Kopi:</strong> ' . esc($petani['jenis_kopi_list']) . '</p>' .
                '</div></div>';
        }
        $mobileHtml .= '</div></div>';

        // Tampilan Desktop
        $desktopHtml = '<div class="card-body view-desktop p-0"><div class="table-responsive"><table class="table table-custom mb-0" width="100%">';
        $desktopHtml .= '<thead><tr><th>No</th><th>Nama Petani</th><th>Alamat</th><th>No. HP</th><th>Jenis Kopi</th></tr></thead><tbody>';
        $page = $pager->getCurrentPage('petani');
        $perPage = $pager->getPerPage('petani');
        $no = 1 + (($page - 1) * $perPage);
        foreach ($petaniData as $petani) {
            $desktopHtml .= '<tr><td>' . $no++ . '</td>' .
                '<td><div class="d-flex align-items-center"><div class="icon-circle bg-primary mr-3"><i class="fas fa-user text-white"></i></div>' .
                '<div><div class="font-weight-bold text-gray-800">' . esc($petani['nama']) . '</div><div class="small text-muted">User ID: ' . esc($petani['user_id']) . '</div></div></div></td>' .
                '<td>' . esc($petani['alamat']) . '</td><td>' . esc($petani['no_hp']) . '</td><td>' . esc($petani['jenis_kopi_list']) . '</td></tr>';
        }
        $desktopHtml .= '</tbody></table></div></div>';

        return $mobileHtml . $desktopHtml;
    }
}
