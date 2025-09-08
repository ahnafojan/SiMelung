<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'LandingPage::index');
//BUMDES
$routes->get('DashboardBumdes', 'DashboardBumdes::index');
$routes->get('dashboard', 'DashboardBumdes::index');
$routes->get('dashboard/dashboard_bumdes', 'DashboardBumdes::index');


$routes->get('PersetujuanKomersial', 'PersetujuanKomersial::index');

//Komersial)
$routes->get('dashboard', 'DashboardAdminKomersial::index');
$routes->get('dashboard/dashboard_komersial', 'DashboardAdminKomersial::index');
$routes->get('DashboardAdminKomersial', 'DashboardAdminKomersial::index');

$routes->get('DashboardAdminUmkm', 'DashboardAdminUmkm::index');
$routes->get('Log_aktivitas', 'Log_aktivitas::index');
$routes->get('Profile', 'Profile::index');

//Bumdes
$routes->group('bumdes', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->group('laporan', function ($routes) {
        $routes->get('/', 'LaporanBumdes::index');
        $routes->get('kopi', 'BumdesRekapKopi::index');
        $routes->get('petani', 'BumdesRekapPetani::index');
        $routes->get('aset', 'BumdesRekapAset::index');
        $routes->get('pariwisata', 'BumdesRekapPariwisata::index');
        $routes->get('umkm', 'BumdesRekapUmkm::index');
    });

    /**
     * ----------------------------------------------------------------
     * Ekspor Laporan Bumdes
     * ----------------------------------------------------------------
     */
    $routes->group('export', function ($routes) {
        // Rute Ekspor Petani
        $routes->group('petani', function ($routes) {
            $routes->get('pdf', 'ExportLaporanBumdes::pdfPetani');
            $routes->get('excel', 'ExportLaporanBumdes::excelPetani');
        });

        // Rute Ekspor Laporan Kopi
        $routes->group('kopi', function ($routes) {
            $routes->get('masuk/excel', 'ExportLaporanBumdes::excelMasuk');
            $routes->get('masuk/pdf', 'ExportLaporanBumdes::exportRekapMasukPdf');
            $routes->get('keluar/excel', 'ExportLaporanBumdes::exportRekapKeluarExcel');
            $routes->get('keluar/pdf', 'ExportLaporanBumdes::pdfKeluar');
            $routes->get('stok/excel', 'ExportLaporanBumdes::excelStok');
            $routes->get('stok/pdf', 'ExportLaporanBumdes::pdfStok');
        });

        // Rute Ekspor Laporan Aset (YANG HILANG)
        $routes->group('aset', function ($routes) {
            $routes->get('excel', 'ExportLaporanBumdes::excelAset');
            $routes->get('pdf', 'ExportLaporanBumdes::pdfAset');
        });
    });
});
//Pengaturan BUMDES
$routes->get('/pengaturan/bumdes', 'Pengaturan::bumdes');       // Untuk menampilkan halaman form
$routes->post('/pengaturan/bumdes/update', 'Pengaturan::updatebumdes');



//Pengaturan keuangan
$routes->get('/pengaturan', 'Pengaturan::index');
$routes->post('/pengaturan/update', 'Pengaturan::update');

//Pengaturan Komersial
$routes->get('/pengaturan/komersial', 'Pengaturan::komersial');       // Untuk menampilkan halaman form
$routes->post('/pengaturan/komersial/update', 'Pengaturan::updateKomersial'); // Untuk menyimpan data form

//Pengaturan Pariwisata
$routes->get('/pengaturan/pariwisata', 'Pengaturan::pariwisata');       // Untuk menampilkan halaman form
$routes->post('/pengaturan/pariwisata/update', 'Pengaturan::updatePariwisata');


//Pengaturan UMKM




//desa
$routes->get('LaporanArusKas', 'LaporanArusKas::index');
$routes->get('LaporanLabaRugi', 'LaporanLabaRugi::index');
$routes->get('LaporanModal', 'LaporanModal::index');
$routes->get('LaporanNeraca', 'LaporanNeraca::index');

//komersial
$routes->get('Petani', 'Petani::index');
$routes->get('Kopimasuk', 'Kopimasuk::index');
$routes->get('Kopikeluar', 'Kopikeluar::index');
$routes->get('ManajemenAsetKomersial', 'ManajemenAsetKomersial::index');


/*petani*/
$routes->setAutoRoute(false);

// Group route untuk Petani
$routes->group('petani', function ($routes) {
    $routes->get('/', 'Petani::index');
    $routes->post('create', 'Petani::create');
    $routes->post('update', 'Petani::postUpdate');
    $routes->post('delete', 'Petani::delete'); // ID akan diambil dari form data
    $routes->get('edit/(:num)', 'Petani::edit/$1');
});
//permissiom
$routes->post('persetujuanKomersial/respond', 'PersetujuanKomersial::respond');
$routes->post('petani/requestAccess', 'Petani::requestAccess');
$routes->post('kopi-masuk/requestAccess', 'KopiMasuk::requestAccess');
$routes->post('petanipohon/requestAccess', 'PetaniPohon::requestAccess');
$routes->post('kopikeluar/requestAccess', 'KopiKeluar::requestAccess');
$routes->post('jenispohon/requestAccess', 'JenisPohon::requestAccess');
$routes->post('ManajemenAsetKomersial/requestAccess', 'ManajemenAsetKomersial::requestAccess');

$routes->group('petanipohon', function ($routes) {
    $routes->get('(:segment)', 'PetaniPohon::index/$1');
    $routes->get('index/(:segment)', 'PetaniPohon::index/$1');
    $routes->post('store', 'PetaniPohon::store');
    // delete pohon hanya pakai id
    $routes->post('delete', 'PetaniPohon::delete');
});


//master pohon
$routes->group('jenispohon', function ($routes) {
    $routes->get('/', 'JenisPohon::index');
    $routes->post('store', 'JenisPohon::store');
    $routes->post('update/(:num)', 'JenisPohon::update/$1'); // Rute untuk update
    $routes->post('delete/(:num)', 'JenisPohon::delete/$1'); // Disarankan POST untuk delete
});


// kopi masuk
$routes->get('kopi-masuk', 'KopiMasuk::index');
$routes->post('kopi-masuk/create', 'KopiMasuk::store');
$routes->post('kopi-masuk/update/(:num)', 'KopiMasuk::update/$1');
$routes->post('kopi-masuk/delete/(:num)', 'KopiMasuk::delete/$1');
// app/Config/Routes.php
$routes->get('get-jenis-pohon/(:any)', 'KopiMasuk::getJenisPohon/$1');
$routes->get('stok-kopi', 'KopiMasuk::stok');


//kopikeluar
// CRUD Kopi Keluar
$routes->get('kopikeluar', 'KopiKeluar::index'); // List data
$routes->post('kopikeluar/store', 'KopiKeluar::store'); // Simpan data baru
$routes->get('kopikeluar/edit/(:num)', 'KopiKeluar::edit/$1'); // Form edit
$routes->post('kopikeluar/update/(:num)', 'KopiKeluar::update/$1'); // Update data
$routes->post('kopikeluar/delete/(:num)', 'KopiKeluar::delete/$1'); // Hapus data
$routes->get('kopikeluar/getJenisKopi/(:num)', 'KopiKeluar::getJenisKopi/$1');

//ADMIN UMKM
$routes->get('umkm', 'Umkm::index');
$routes->get('Umkm', 'Umkm::index'); // tambahan biar U besar juga jalan// menampilkan daftar
$routes->post('umkm/store', 'Umkm::store');    // tambah UMKM
$routes->get('umkm/edit/(:num)', 'Umkm::edit/$1'); // form edit (bisa juga modal)
$routes->post('umkm/update/(:num)', 'Umkm::update/$1'); // update
$routes->get('umkm/delete/(:num)', 'Umkm::delete/$1');  // hapus






// Routes Master Aset Komersial
$routes->get('aset-komersial', 'AsetKomersial::index');
$routes->post('aset-komersial', 'AsetKomersial::store');

$routes->get('AsetKomersial', 'AsetKomersial::index');
$routes->post('AsetKomersial', 'AsetKomersial::store');

//manajemen aset komersial
$routes->post('ManajemenAsetKomersial/update/(:num)', 'ManajemenAsetKomersial::update/$1');
$routes->get('ManajemenAsetKomersial/delete/(:num)', 'ManajemenAsetKomersial::delete/$1');

//LAPORAN KOMERSIAL
$routes->group('admin-komersial/laporan', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('/', 'LaporanKomersial::index');
});
$routes->get('admin-komersial/laporan', 'LaporanKomersial::index');
// Rute untuk Laporan Rekap Kopi
$routes->get('admin-komersial/laporan/kopi', 'KomersialRekapKopi::index');

// Rute untuk Laporan Rekap Petani
$routes->get('admin-komersial/laporan/petani', 'KomersialRekapPetani::index');
$routes->get('admin-komersial/laporan-petani/export/excel', 'ExportLaporanKomersial::excelPetani');
$routes->get('admin-komersial/laporan-petani/export/pdf', 'ExportLaporanKomersial::pdfPetani');

// Rute untuk Laporan Rekap Aset
$routes->get('admin-komersial/laporan/aset', 'KomersialRekapAset::index');
$routes->get('admin-komersial/export/aset/excel', 'ExportLaporanKomersial::excelAset');
$routes->get('admin-komersial/export/aset/pdf', 'ExportLaporanKomersial::pdfAset');

// Ekspor Kopi Masuk
$routes->get('admin-komersial/export/masuk/excel', 'ExportLaporanKomersial::excelMasuk');
$routes->get('admin-komersial/export/masuk/pdf', 'ExportLaporanKomersial::exportRekapMasukPdf');

// Ekspor Kopi Keluar
$routes->get('admin-komersial/export/keluar/excel', 'ExportLaporanKomersial::exportRekapKeluarExcel');
$routes->get('admin-komersial/export/keluar/pdf', 'ExportLaporanKomersial::pdfKeluar');

// Ekspor Stok
$routes->get('admin-komersial/export/stok/excel', 'ExportLaporanKomersial::excelStok');
$routes->get('admin-komersial/export/stok/pdf', 'ExportLaporanKomersial::pdfStok');

$routes->get('admin-komersial/export/petani/excel', 'ExportLaporanKomersial::excelPetani');
$routes->get('admin-komersial/export/petani/pdf', 'ExportLaporanKomersial::pdfPetani');

// Ekspor Laporan Aset (Rute Baru)
$routes->get('admin-komersial/export/aset/excel', 'ExportLaporanKomersial::excelAset');
$routes->get('admin-komersial/export/aset/pdf', 'ExportLaporanKomersial::pdfAset');
//END

//role
$routes->get('/choose-role', 'AuthController::chooseRole', ['filter' => 'auth']);
$routes->post('/set-role', 'AuthController::setRole', ['filter' => 'auth']);
$routes->get('/switch-role/(:any)', 'AuthController::switchRole/$1', ['filter' => 'auth']);

//routes untuk login
//$routes->get('/', 'AuthController::login'); // Jadikan login sebagai halaman utama
$routes->get('/login', 'AuthController::login');
$routes->post('/login/process', 'AuthController::processLogin');
$routes->get('/logout', 'AuthController::logout');

$routes->group('', ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard/dashboard_desa', 'DashboardDesa::index');
    $routes->get('dashboard/dashboard_bumdes', 'DashboardController::bumdes');
    $routes->get('keuangan/dashboard', 'DashboardController::keuangan');
    $routes->get('dashboard/dashboard_komersial', 'DashboardController::komersial');
    $routes->get('dashboard/dashboard_umkm', 'DashboardController::umkm');
    //tambah user
    $routes->get('admin-user', 'AdminUserController::index');
    $routes->post('admin-user/create', 'AdminUserController::create');
    $routes->post('admin-user/edit', 'AdminUserController::edit');
    $routes->post('admin-user/delete', 'AdminUserController::delete');
});

//KEUANGAN
$routes->group('', ['filter' => 'auth'], function ($routes) {
    // Rute untuk Dashboard Keuangan
    $routes->get('/dashboard/dashboard_keuangan', 'DashboardKeuangan::index');
    // Rute untuk BKU Bulanan
    $routes->get('/bku-bulanan/detail/(:num)', 'BkuBulanan::detail/$1');
    $routes->resource('bku-bulanan', ['controller' => 'BkuBulanan', 'except' => 'show']);
    // Rute untuk BKU Tahunan
    $routes->get('/bku-tahunan', 'BkuTahunan::index');
    $routes->get('/bku-tahunan/new', 'BkuTahunan::new');
    $routes->get('/bku-tahunan/detail/(:num)', 'BkuTahunan::detail/$1');
    // Rute untuk BKU Arus Kas
    $routes->get('/master-arus-kas', 'MasterArusKas::index');
    $routes->post('/master-arus-kas/create', 'MasterArusKas::create');
    $routes->post('/master-arus-kas/update/(:num)', 'MasterArusKas::update/$1');
    $routes->get('/master-arus-kas/delete/(:num)', 'MasterArusKas::delete/$1');
    // Rute untuk Arus Kas
    $routes->get('/arus-kas', 'ArusKas::index');
    $routes->post('/arus-kas/simpan', 'ArusKas::simpan');
    // Rute untuk ekspor Arus Kas ke Excel dan PDF
    $routes->get('/arus-kas/export-excel/(:num)', 'ArusKas::exportExcel/$1');
    $routes->get('/arus-kas/export-pdf/(:num)', 'ArusKas::exportPdf/$1');
    // Rute untuk Neraca Keuangan
    $routes->get('/neraca-keuangan', 'NeracaKeuangan::index');
    $routes->post('/neraca-keuangan/simpan', 'NeracaKeuangan::simpan');
    $routes->get('/neraca-keuangan/cetak-pdf/(:num)', 'NeracaKeuangan::cetakPdf/$1');
    $routes->get('/neraca-keuangan/cetak-excel/(:num)', 'NeracaKeuangan::cetakExcel/$1');
    // Rute untuk Master Kategori Pengeluaran
    $routes->resource('master-kategori', ['controller' => 'MasterKategori']);
    // Rute untuk AJAX check nama kategori
    $routes->get('/master-kategori/check-nama', 'MasterKategori::checkNama');
    // Rute untuk Master Pendapatan
    $routes->resource('master-pendapatan', ['controller' => 'MasterPendapatan']);
    // Rute untuk AJAX check nama pendapatan
    $routes->get('/master-pendapatan/check-nama', 'MasterPendapatan::checkNama');
    // Rute untuk ekspor BKU Bulanan pdf
    $routes->get('/bku-bulanan/cetak-pdf/(:num)', 'BkuBulanan::cetakPdf/$1');
    // Rute untuk ekspor BKU Bulanan ke Excel
    $routes->get('/bku-bulanan/cetak-excel/(:num)', 'BkuBulanan::cetakExcel/$1');
    // Rute untuk mendapatkan saldo bulan lalu via AJAX
    $routes->get('/bku-bulanan/get-saldo-lalu', 'BkuBulanan::getSaldoBulanLalu');
    // Rute untuk Pengaturan Laporan
    $routes->get('/pengaturan-keuangan', 'Pengaturan::index_keuangan', ['filter' => 'auth']);
    $routes->post('/pengaturan-keuangan/update', 'Pengaturan::update', ['filter' => 'auth']);
    // Rute untuk ekspor BKU Tahunan pdf dan excel
    $routes->get('/bku-tahunan/cetak-pdf/(:num)', 'BkuTahunan::cetakPdf/$1');
    $routes->get('/bku-tahunan/cetak-excel/(:num)', 'BkuTahunan::cetakExcel/$1');
    // Rute untuk History Aktivitas
    $routes->get('/history', 'History::index', ['filter' => 'auth']);
    // Rute untuk Master Neraca
    // $routes->resource('master-neraca');
    $routes->resource('master-neraca', ['controller' => 'MasterNeraca']);
    // untuk mencetak paket lengkap laporan tahunan
    $routes->get('laporan/cetak-paket-lengkap/(:num)', 'LaporanController::cetakPaketLengkap/$1');
    // Rute untuk Master Laba Rugi
    $routes->resource('master-laba-rugi', ['controller' => 'MasterLabaRugi']);
    // Rute untuk Laporan Laba Rugi
    $routes->get('/laba-rugi', 'LabaRugi::index');
    $routes->post('/laba-rugi/simpan', 'LabaRugi::simpan');
    // Rute untuk ekspor Laporan Laba Rugi pdf dan excel
    $routes->get('/laba-rugi/cetak-pdf/(:num)', 'LabaRugi::cetakPdf/$1');
    $routes->get('/laba-rugi/cetak-excel/(:num)', 'LabaRugi::cetakExcel/$1');
    // Rute untuk Master Perubahan Modal
    $routes->get('/perubahan-modal', 'PerubahanModalController::index');
    $routes->post('/perubahan-modal/simpan', 'PerubahanModalController::simpan');
    // Rute untuk master perubahan modal (CRUD)
    $routes->resource('master-perubahan-modal', ['controller' => 'MasterPerubahanModalController']);
    // Rute untuk ekspor Laporan Perubahan Modal pdf dan excel
    $routes->get('/perubahan-modal/export-excel/(:num)', 'PerubahanModalController::exportExcel/$1');
    $routes->get('/perubahan-modal/export-pdf/(:num)', 'PerubahanModalController::exportPdf/$1');
    // Rute untuk Master laba rugi (CRUD)
    $routes->resource('master-laba-rugi');
});

// Dashboard Pariwisata
$routes->get('dashboard/dashboard_pariwisata', 'DashboardPariwisata::index', ['filter' => 'auth']);
$routes->group('asetpariwisata', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'AsetPariwisata::index');
    $routes->post('store', 'AsetPariwisata::store');
});
$routes->group('laporanasetpariwisata', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'LaporanAsetPariwisata::index');
    $routes->get('exportPDF/(:num)', 'LaporanAsetPariwisata::exportPDF/$1');
    $routes->get('exportExcel/(:num)', 'LaporanAsetPariwisata::exportExcel/$1');
});
$routes->group('objekwisata', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'ObjekWisata::index');
    $routes->post('store', 'ObjekWisata::store');
    $routes->get('delete/(:num)', 'ObjekWisata::delete/$1');
});
$routes->get('asetpariwisata/delete/(:num)', 'AsetPariwisata::delete/$1');
$routes->put('asetpariwisata/update/(:num)', 'AsetPariwisata::update/$1');
$routes->post('asetpariwisata/requestaccess', 'AsetPariwisata::requestAccess');
$routes->post('objekwisata/requestaccess', 'ObjekWisata::requestaccess');


//DESA
//komersial
$routes->get('DesaRekapKopi', 'DesaRekapKopi::index');
$routes->get('desa/laporan_komersial/aset', 'DesaRekapAset::index');
$routes->get('desa/laporan_komersial/petani', 'DesaRekapPetani::index');
//Pariwisata
$routes->get('desa/laporan_pariwisata/objekwisata', 'DesaRekapPariwisata::laporanObjekWisata');
// URL: http://domain-anda.com/desa/laporan_pariwisata/asetpariwisata
$routes->get('desa/laporan_pariwisata/asetpariwisata', 'DesaRekapPariwisata::laporanAset');
