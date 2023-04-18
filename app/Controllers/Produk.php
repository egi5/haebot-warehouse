<?php

namespace App\Controllers;

use App\Models\LokasiProdukModel;
use App\Models\ProdukModel;
use App\Models\ProdukPlanModel;
use CodeIgniter\RESTful\ResourcePresenter;
use \Hermawan\DataTables\DataTable;

class Produk extends ResourcePresenter
{
    protected $helpers = ['form', 'stok_helper'];


    public function index()
    {
        return view('produk/index');
    }


    public function getDataProduk()
    {
        if ($this->request->isAJAX()) {

            $modelProduk = new ProdukModel();
            $data = $modelProduk->where(['deleted_at' => null])->select('id, nama, tipe, harga_beli, harga_jual, stok');

            return DataTable::of($data)
                ->addNumbering('no')
                ->add('aksi', function ($row) {
                    return '
                    <a title="Detail" class="px-2 py-0 btn btn-sm btn-outline-dark" onclick="showModalDetail(' . $row->id . ')">
                        <i class="fa-fw fa-solid fa-magnifying-glass"></i>
                    </a>';
                }, 'last')
                ->toJson(true);
        } else {
            return "Tidak bisa load data.";
        }
    }


    public function show($id = null)
    {
        if ($this->request->isAJAX()) {
            $modelProduk = new ProdukModel();
            $modelProdukPlan = new ProdukPlanModel();
            $modelLokasiProduk = new LokasiProdukModel();

            $produk = $modelProduk->getProduk($id);
            $lokasi_produk = $modelLokasiProduk->getLokasiProduk($id);

            if ($produk['tipe'] == 'SET' || $produk['tipe'] == 'SINGLE') {

                $produkPlan = $modelProdukPlan->where(['id_produk_jadi' => $id])->findAll();
                if ($produkPlan) {

                    $list_plan = array_column($produkPlan, 'id_produk_bahan');
                    array_push($list_plan, $id);

                    $builder = $modelProduk->builder();
                    $builder->select('*');
                    $builder->whereNotIn('id', $list_plan);
                    $builder->orderBy('tipe', 'asc');
                    $all_plan = $builder->get()->getResultArray();

                    $virtual_stok = hitung_virtual_stok_dari_bahan($id);

                    $bisa_membuat = min(array_column($virtual_stok, 'bisa_membuat'));

                    $data = [
                        'validation'    => \Config\Services::validation(),
                        'tipe'          => $produk['tipe'],
                        'produk'        => $produk,
                        'lokasi_produk' => $lokasi_produk,
                        'all_plan'      => $all_plan,
                        'virtual_stok'  => $virtual_stok,
                        'bisa_membuat'  => $bisa_membuat,
                        'bisa_dipecah'  => 0,
                        'result'        => 'ok',
                    ];
                } else {
                    $all_plan = $modelProduk->findAll();
                    $data = [
                        'validation'    => \Config\Services::validation(),
                        'tipe'          => $produk['tipe'],
                        'produk'        => $produk,
                        'lokasi_produk' => $lokasi_produk,
                        'all_plan'      => $all_plan,
                        'virtual_stok'  => '',
                        'bisa_membuat'  => 0,
                        'bisa_dipecah'  => 0,
                        'result'        => 'tidak memiliki komponen.',
                    ];
                }
            } else {

                $produkPlan = $modelProdukPlan->where(['id_produk_bahan' => $id])->findAll();

                if ($produkPlan) {

                    $list_plan = array_column($produkPlan, 'id_produk_jadi');
                    array_push($list_plan, $id);

                    $builder = $modelProduk->builder();
                    $builder->select('*');
                    $builder->whereNotIn('id', $list_plan);
                    $builder->orderBy('tipe', 'asc');
                    $all_plan = $builder->get()->getResultArray();

                    $virtual_stok = hitung_virtual_stok_dari_set($id);

                    $bisa_dipecah = 0;

                    foreach ($virtual_stok as $stok) {
                        $bisa_dipecah += $stok['bisa_dipecah'];
                    }
                    $data = [
                        'validation'    => \Config\Services::validation(),
                        'tipe'          => $produk['tipe'],
                        'produk'        => $produk,
                        'lokasi_produk' => $lokasi_produk,
                        'all_plan'      => $all_plan,
                        'virtual_stok'  => $virtual_stok,
                        'bisa_membuat'  => 0,
                        'bisa_dipecah'  => $bisa_dipecah,
                        'result'        => 'ok',
                    ];
                } else {
                    $all_plan = $modelProduk->findAll();
                    $data = [
                        'validation'    => \Config\Services::validation(),
                        'tipe'          => $produk['tipe'],
                        'produk'        => $produk,
                        'lokasi_produk' => $lokasi_produk,
                        'all_plan'      => $all_plan,
                        'virtual_stok'  => '',
                        'bisa_membuat'  => 0,
                        'bisa_dipecah'  => 0,
                        'result'        => 'tidak memiliki set.',
                    ];
                }
            }

            $json = [
                'data' => view('produk/show', $data),
            ];

            echo json_encode($json);
        } else {
            return 'Tidak bisa load';
        }
    }
}
