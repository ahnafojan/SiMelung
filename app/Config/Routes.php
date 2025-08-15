<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'LandingPage::index');
$routes->get('DashboardDesa', 'DashboardDesa::index');
$routes->get('DashboardBumdes', 'DashboardBumdes::index');
$routes->get('DashboardAdminKomersial', 'DashboardAdminKomersial::index');
$routes->get('DashboardAdminUmkm', 'DashboardAdminUmkm::index');
$routes->get('Log_aktivitas', 'Log_aktivitas::index');
$routes->get('Profile', 'Profile::index');

//Bumdes
$routes->get('LaporanBumdes', 'LaporanBumdes::index');

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
    $routes->post('delete/(:num)', 'Petani::delete/$1');
    $routes->get('edit/(:num)', 'Petani::edit/$1');
});
//kopimasuk
// kopi masuk
$routes->get('kopi-masuk', 'KopiMasuk::index');
$routes->post('kopi-masuk/create', 'KopiMasuk::store');
$routes->post('kopi-masuk/update/(:num)', 'KopiMasuk::update/$1');
$routes->post('kopi-masuk/delete/(:num)', 'KopiMasuk::delete/$1');


//kopikeluar
// CRUD Kopi Keluar
$routes->get('kopikeluar', 'KopiKeluar::index'); // List data
$routes->post('kopikeluar/store', 'KopiKeluar::store'); // Simpan data baru
$routes->get('kopikeluar/edit/(:num)', 'KopiKeluar::edit/$1'); // Form edit
$routes->post('kopikeluar/update/(:num)', 'KopiKeluar::update/$1'); // Update data
$routes->get('kopikeluar/delete/(:num)', 'KopiKeluar::delete/$1'); // Hapus data



//umkm
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

//Laporan Komeersial

$routes->get('admin-komersial/laporan', 'LaporanKomersial::index');
$routes->get('LaporanKomersial', 'LaporanKomersial::index');
$routes->get('admin-komersial/laporan/export', 'LaporanKomersial::export');
$routes->get('admin-komersial/laporan/export-pdf', 'LaporanKomersial::exportPdf');










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
    $routes->get('dashboard/dashboard_desa', 'DashboardController::desa');
    $routes->get('dashboard/dashboard_bumdes', 'DashboardController::bumdes');
    $routes->get('keuangan/dashboard', 'DashboardController::keuangan');
    $routes->get('dashboard/dashboard_komersial', 'DashboardController::komersial');
    $routes->get('dashboard/dashboard_umkm', 'DashboardController::umkm');
    $routes->get('pariwisata/dashboard', 'DashboardController::pariwisata');
    //tambah user
    $routes->get('admin-user', 'AdminUserController::index');
    $routes->post('admin-user/create', 'AdminUserController::create');
    $routes->post('admin-user/edit', 'AdminUserController::edit');
    $routes->post('admin-user/delete', 'AdminUserController::delete');
});
