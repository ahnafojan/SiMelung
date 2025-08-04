<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
/**$routes->get('/', 'LandingPage::index');*/
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

/*petani*/
$routes->get('petani', 'Petani::index');
$routes->get('petani/create', 'Petani::create');
$routes->post('petani/store', 'Petani::store');
$routes->get('petani/edit/(:num)', 'Petani::edit/$1');
$routes->post('petani/update/(:num)', 'Petani::update/$1');
$routes->get('petani/delete/(:num)', 'Petani::delete/$1');
