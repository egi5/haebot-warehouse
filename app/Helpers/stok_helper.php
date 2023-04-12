<?php

use App\Models\GudangPJModel;
use App\Models\ProdukModel;
use App\Models\ProdukPlanModel;


function hitung_virtual_stok_dari_bahan($id)
{
    $modelProduk = new ProdukModel();

    $db = \Config\Database::connect();
    $q = "SELECT produk_plan.*, produk.nama as nama_produk FROM produk_plan 
        JOIN produk ON produk_plan.id_produk_bahan = produk.id
        WHERE produk_plan.id_produk_jadi = $id";
    $list_bahan = $db->query($q)->getResultArray();

    foreach ($list_bahan as $index => $bahan) {
        $produk_bahan = $modelProduk->find($bahan['id_produk_bahan']);

        $list_bahan[$index]['stok_bahan'] = $produk_bahan['stok'];

        if ($produk_bahan['stok'] >= $bahan['qty_bahan']) {
            $list_bahan[$index]['bisa_membuat'] = floor($produk_bahan['stok'] / $bahan['qty_bahan']);
        } else {
            $list_bahan[$index]['bisa_membuat'] = 0;
        }
    }

    return $list_bahan;
}


function hitung_virtual_stok_dari_set($id)
{
    $modelProduk = new ProdukModel();

    $db = \Config\Database::connect();
    $q = "SELECT produk_plan.*, produk.nama as nama_produk FROM produk_plan 
        JOIN produk ON produk_plan.id_produk_jadi = produk.id
        WHERE produk_plan.id_produk_bahan = $id";
    $produk_plan = $db->query($q)->getResultArray();

    foreach ($produk_plan as $index => $sg) {
        $produk_jadi = $modelProduk->find($sg['id_produk_jadi']);

        $produk_plan[$index]['stok_jadi'] = $produk_jadi['stok'];

        $produk_plan[$index]['bisa_dipecah'] = floor($produk_jadi['stok'] * $sg['qty_bahan']);
    }

    return $produk_plan;
}


function getIdGudangByIdUser($id_user)
{
    $modelGudangPJ = new GudangPJModel();
    return $modelGudangPJ->getGudangByPJ($id_user);
}
