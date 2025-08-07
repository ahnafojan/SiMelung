<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
//$routes->get('/', 'LandingPage::index');
$routes->get('/', 'DashboardSuper::index');
//$routes->get('/', 'DashboardAdminKomersial::index');
//$routes->get('/', 'DashboardAdminUmkm::index');
$routes->get('Log_aktivitas', 'Log_aktivitas::index');
$routes->get('Profile', 'Profile::index');
$routes->get('Umkm', 'Umkm::index');
$routes->get('Akunuser', 'Akunuser::index');

//superadmin
$routes->get('LaporanSuper', 'LaporanSuper::index');

//komersial
$routes->get('Petani', 'Petani::index');
$routes->get('LaporanKomersial', 'LaporanKomersial::index');
$routes->get('AsetKomersial', 'AsetKomersial::index');
$routes->get('Kopimasuk', 'Kopimasuk::index');
$routes->get('Kopikeluar', 'Kopikeluar::index');
$routes->get('MasterAsetKomersial', 'MasterAsetKomersial::index');
/*petani*/
$routes->get('petani', 'Petani::index');
$routes->get('petani/create', 'Petani::create');
$routes->post('petani/store', 'Petani::store');
$routes->get('petani/edit/(:num)', 'Petani::edit/$1');
$routes->post('petani/update/(:num)', 'Petani::update/$1');
$routes->get('petani/delete/(:num)', 'Petani::delete/$1');
