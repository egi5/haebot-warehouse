<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

$routes->get('/', 'AuthController::login');

$routes->group('', ['filter' => 'isLoggedIn'], function ($routes) {

    $routes->get('/dashboard', 'Dashboard::index', ['filter' => 'permission:Dashboard']);
    $routes->get('ruangan-rak', 'Menu::RuanganRak');
    $routes->get('ruangan', 'Ruangan::index');
    $routes->get('produkruangan', 'LokasiProduk::indexRuangan');
    $routes->get('rak', 'Rak::index');
    $routes->get('produkrak', 'LokasiProduk::indexRak');
    $routes->get('stockopname', 'StockOpname::index');

    // GetData
    $routes->get('/wilayah/kota_by_provinsi', 'GetWilayah::KotaByProvinsi');
    $routes->get('/wilayah/kecamatan_by_kota', 'GetWilayah::KecamatanByKota');
    $routes->get('/wilayah/kelurahan_by_kecamatan', 'GetWilayah::KelurahanByKecamatan');


    // Produk
    $routes->resource('produk', ['filter' => 'permission:Data Master,Penanggung Jawab Gudang']);
    $routes->get('getdataproduk', 'Produk::getDataProduk', ['filter' => 'permission:Data Master,Penanggung Jawab Gudang']);
    $routes->post('produkplan', 'ProdukPlan::create', ['filter' => 'permission:Data Master,Penanggung Jawab Gudang']);


    // Ruangan
    $routes->resource('ruangan');
    $routes->get('getdataruangan', 'Ruangan::getDataRuangan');


    // Rak
    $routes->resource('rak');
    $routes->get('getdatarak', 'Rak::getDataRak');


    // Lokasi Produk
    $routes->get('getdataruanganproduk', 'LokasiProduk::getDataRuanganProduk');
    $routes->get('getdatarakproduk', 'LokasiProduk::getDataRakProduk');
    $routes->get('lokasiproduk/new', 'LokasiProduk::new');
    $routes->post('lokasiproduk/', 'LokasiProduk::create');
    $routes->get('lokasiproduk/rak_byruangan', 'LokasiProduk::RakbyRuangan');
    $routes->get('lokasiproduk/stok_byidproduk', 'LokasiProduk::StokbyProduk');


    // Stock Opname
    // $routes->resource('stockopname', ['filter' => 'permission:Data Master,Penanggung Jawab Gudang']);
    $routes->get('stockopname/new', 'StockOpname::new');
    $routes->post('stockopname/', 'StockOpname::create');
    $routes->get('getdatastok', 'StockOpname::getDataStok');
    $routes->get('detailstock/(:num)', 'StockOpname::show/$1');


    // Stock Opname Detail
    $routes->get('/list_stok/(:any)', 'StockOpnameDetail::newStokProduk/$1');
    $routes->get('stockopname/stokbyproduk', 'StockOpnameDetail::StokbyProduk');
    $routes->post('stockopnamedetail/', 'StockOpnameDetail::create');
    $routes->post('list_stok_produk', 'StockOpnameDetail::getListProdukStock');
    $routes->delete('stok_produk_delete/(:any)', 'StockOpnameDetail::delete/$1');
    $routes->post('stok_produk_update/(:any)', 'StockOpnameDetail::update/$1');
    $routes->post('check_list_produk', 'StockOpnameDetail::checkListProduk');
    $routes->post('simpanstok', 'StockOpnameDetail::updateStatusStock');
});

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
