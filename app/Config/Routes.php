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
$routes->get('LaporanBumdes', 'LaporanBumdes::index');

//desa
$routes->get('LaporanArusKas', 'LaporanArusKas::index');
$routes->get('LaporanLabaRugi', 'LaporanLabaRugi::index');
$routes->get('LaporanModal', 'LaporanModal::index');
$routes->get('LaporanNeraca', 'LaporanNeraca::index');

//komersial
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





//routes untuk login
$routes->get('/', 'AuthController::login'); // Jadikan login sebagai halaman utama
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
});
