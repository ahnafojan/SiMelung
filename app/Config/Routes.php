<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'LandingPage::index');

$routes->get('/', 'DashboardDesa::index');
$routes->get('/', 'DashboardBumdes::index');
$routes->get('/', 'DashboardAdminKomersial::index');
$routes->get('/', 'DashboardAdminUmkm::index');

$routes->get('/', 'LandingPage::index');
$routes->get('/', 'Dashboard::index');
$routes->get('Petani', 'Petani::index');
$routes->get('Kopimasuk', 'Kopimasuk::index');
$routes->get('Kopikeluar', 'Kopikeluar::index');
$routes->get('Aset', 'Aset::index');
$routes->get('MasterAset', 'MasterAset::index');
$routes->get('Laporan', 'Laporan::index');

$routes->get('Log_aktivitas', 'Log_aktivitas::index');
$routes->get('Profile', 'Profile::index');
$routes->get('Umkm', 'Umkm::index');
$routes->get('Akunuser', 'Akunuser::index');

//Bumdes

// Dashboard
$routes->get('DashboardDesa', 'DashboardDesa::index');
$routes->get('DashboardBumdes', 'DashboardBumdes::index');
$routes->get('dashboard', 'DashboardAdminKomersial::index');
$routes->get('dashboard/dashboard_komersial', 'DashboardAdminKomersial::index');
$routes->get('DashboardAdminKomersial', 'DashboardAdminKomersial::index');
$routes->get('DashboardAdminUmkm', 'DashboardAdminUmkm::index');
$routes->get('/dashboard/dashboard_pariwisata', 'DashboardPariwisata::index');

// Laporan umum
$routes->get('Log_aktivitas', 'Log_aktivitas::index');
$routes->get('Profile', 'Profile::index');
$routes->get('LaporanBumdes', 'LaporanBumdes::index');
$routes->get('LaporanArusKas', 'LaporanArusKas::index');
$routes->get('LaporanLabaRugi', 'LaporanLabaRugi::index');
$routes->get('LaporanModal', 'LaporanModal::index');
$routes->get('LaporanNeraca', 'LaporanNeraca::index');

// Komersial
$routes->get('Petani', 'Petani::index');
$routes->get('LaporanKomersial', 'LaporanKomersial::index');
$routes->get('AsetKomersial', 'AsetKomersial::index');
$routes->get('Kopimasuk', 'Kopimasuk::index');
$routes->get('Kopikeluar', 'Kopikeluar::index');
$routes->get('MasterAsetKomersial', 'MasterAsetKomersial::index');

/* Petani */
$routes->get('petani', 'Petani::index');
$routes->get('petani/create', 'Petani::create');
$routes->post('petani/store', 'Petani::store');
$routes->get('petani/edit/(:num)', 'Petani::edit/$1');
$routes->post('petani/update/(:num)', 'Petani::update/$1');
$routes->get('petani/delete/(:num)', 'Petani::delete/$1');

//Pariwisata //

$routes->get('pariwisata', 'Pariwisata::index');
$routes->get('pariwisata/create', 'Pariwisata::create');
$routes->post('pariwisata/store', 'Pariwisata::store');
$routes->get('pariwisata/edit/(:num)', 'Pariwisata::edit/$1');
$routes->post('pariwisata/update/(:num)', 'Pariwisata::update/$1');
$routes->get('pariwisata/delete/(:num)', 'Pariwisata::delete/$1');

//pariwisata
$routes->get('pariwisata', 'PariwisataController::index');
$routes->get('pariwisata/create', 'PariwisataController::create');
$routes->post('pariwisata/save', 'PariwisataController::save');





//routes untuk login
$routes->get('/', 'AuthController::login'); // Jadikan login sebagai halaman utama
$routes->get('ManajemenAsetKomersial', 'ManajemenAsetKomersial::index');
$routes->get('MasterAsetKomersial', 'MasterAsetKomersial::index');

// CRUD Petani
$routes->group('petani', function ($routes) {
    $routes->get('/', 'Petani::index');
    $routes->post('create', 'Petani::create');
    $routes->post('update', 'Petani::postUpdate');
    $routes->post('delete', 'Petani::delete');
    $routes->get('edit/(:num)', 'Petani::edit/$1');
});

// Jenis Pohon
$routes->group('jenispohon', function ($routes) {
    $routes->get('/', 'JenisPohon::index');
    $routes->post('store', 'JenisPohon::store');
    $routes->get('delete/(:num)', 'JenisPohon::delete/$1');
});

// Kopi Masuk
$routes->get('kopi-masuk', 'KopiMasuk::index');
$routes->post('kopi-masuk/create', 'KopiMasuk::store');
$routes->post('kopi-masuk/update/(:num)', 'KopiMasuk::update/$1');
$routes->post('kopi-masuk/delete/(:num)', 'KopiMasuk::delete/$1');
$routes->get('get-jenis-pohon/(:any)', 'KopiMasuk::getJenisPohon/$1');
$routes->get('stok-kopi', 'KopiMasuk::stok');

// Kopi Keluar
$routes->get('kopikeluar', 'KopiKeluar::index');
$routes->post('kopikeluar/store', 'KopiKeluar::store');
$routes->get('kopikeluar/edit/(:num)', 'KopiKeluar::edit/$1');
$routes->post('kopikeluar/update/(:num)', 'KopiKeluar::update/$1');
$routes->post('kopikeluar/delete/(:num)', 'KopiKeluar::delete/$1');
$routes->get('kopikeluar/getJenisKopi/(:num)', 'KopiKeluar::getJenisKopi/$1');

// UMKM
$routes->get('umkm', 'Umkm::index');
$routes->post('umkm/store', 'Umkm::store');
$routes->get('umkm/edit/(:num)', 'Umkm::edit/$1');
$routes->post('umkm/update/(:num)', 'Umkm::update/$1');
$routes->get('umkm/delete/(:num)', 'Umkm::delete/$1');

// Dashboard Pariwisata
$routes->get('dashboard/pariwisata', 'DashboardController::pariwisata', ['filter' => 'auth']);
// Laporan Aset Pariwisata
// Aset Pariwisata
$routes->group('asetpariwisata', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'AsetPariwisata::index');       // Halaman daftar aset
    $routes->get('create', 'AsetPariwisata::create'); // Form tambah aset
    $routes->post('store', 'AsetPariwisata::store');  // Simpan aset baru
});

// Laporan Aset Pariwisata
$routes->group('laporanpariwisata', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'LaporanPariwisata::index');      
    $routes->get('exportExcel', 'LaporanPariwisata::exportExcel');
    $routes->get('exportPDF', 'LaporanPariwisata::exportPDF');
});

//pariwisata
$routes->get('pariwisata', 'PariwisataController::index');
$routes->get('pariwisata/create', 'PariwisataController::create');
$routes->post('pariwisata/save', 'PariwisataController::save');



// Auth
$routes->get('/login', 'AuthController::login');
$routes->post('/login/process', 'AuthController::processLogin');
$routes->get('/logout', 'AuthController::logout');

$routes->group('', ['filter' => 'auth'], function ($routes) {
    $routes->get('desa/dashboard', 'DashboardController::desa');
    $routes->get('bumdes/dashboard', 'DashboardController::bumdes');
    $routes->get('keuangan/dashboard', 'DashboardController::keuangan');
    $routes->get('umkm/dashboard', 'DashboardController::umkm');
    $routes->get('broker/dashboard', 'DashboardController::broker');
    $routes->get('pariwisata/dashboard', 'DashboardController::pariwisata');
    $routes->get('dashboard/dashboard_komersial', 'DashboardController::komersial');
    $routes->get('dashboard/dashboard_umkm', 'DashboardController::umkm');

    // admin user
    $routes->get('admin-user', 'AdminUserController::index');
    $routes->post('admin-user/create', 'AdminUserController::create');
    $routes->post('admin-user/edit', 'AdminUserController::edit');
    $routes->post('admin-user/delete', 'AdminUserController::delete');
});
