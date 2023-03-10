<?php

namespace App\Controllers;

use App\Models\PemesananDetailModel;
use App\Models\PemesananModel;
use App\Models\ProdukModel;
use App\Models\SupplierModel;
use App\Models\UserModel;
use CodeIgniter\RESTful\ResourcePresenter;

class Pemesanan_detail extends ResourcePresenter
{
    protected $helpers = ['user_admin_helper'];

    public function index()
    {
    }


    public function List_pemesanan($no_pemesanan)
    {
        $modelSupplier = new SupplierModel();
        $supplier = $modelSupplier->findAll();
        $modelProduk = new ProdukModel();
        $produk = $modelProduk->findAll();
        $modelUser = new UserModel();
        $user = $modelUser->getAllUserWithKaryawanName();

        $pemesananModel = new PemesananModel();
        $data = [
            'pemesanan'             => $pemesananModel->getPemesanan($no_pemesanan),
            'supplier'              => $supplier,
            'produk'                => $produk,
            'user'                  => $user
        ];
        return view('pembelian/pemesanan/detail', $data);
    }

    public function getListProdukPemesanan()
    {
        if ($this->request->isAJAX()) {

            $id_pemesanan = $this->request->getVar('id_pemesanan');

            $modelPemesananDetail = new PemesananDetailModel();
            $produk_pemesanan = $modelPemesananDetail->getListProdukPemesanan($id_pemesanan);

            if ($produk_pemesanan) {
                $data = [
                    'produk_pemesanan'      => $produk_pemesanan,
                ];

                $json = [
                    'list' => view('pembelian/pemesanan/list_produk', $data),
                ];
            } else {
                $json = [
                    'list' => '<tr><td colspan="7" class="text-center">Belum ada list Produk.</td></tr>',
                ];
            }

            echo json_encode($json);
        } else {
            return 'Tidak bisa load';
        }
    }


    public function show($id = null)
    {
    }


    public function new()
    {
    }


    public function create()
    {
        $id_produk = $this->request->getPost('id_produk');
        $id_pemesanan = $this->request->getPost('id_pemesanan');

        $modelProduk = new ProdukModel();
        $produk = $modelProduk->find($id_produk);

        $modelPemesananDetail = new PemesananDetailModel();
        $cek_produk = $modelPemesananDetail->where(['id_produk' => $id_produk, 'id_pemesanan' => $id_pemesanan])->first();

        if ($cek_produk) {
            $data_update = [
                'id'                    => $cek_produk['id'],
                'id_pemesanan'          => $id_pemesanan,
                'id_produk'             => $this->request->getPost('id_produk'),
                'qty'                   => $cek_produk['qty'] + $this->request->getPost('qty'),
                'harga_satuan'          => $produk['harga_beli'],
                'total_harga'           => $cek_produk['total_harga'] + ($produk['harga_beli'] * $this->request->getPost('qty')),
            ];
            $modelPemesananDetail->save($data_update);
        } else {
            $data = [
                'id_pemesanan'          => $id_pemesanan,
                'id_produk'             => $this->request->getPost('id_produk'),
                'qty'                   => $this->request->getPost('qty'),
                'harga_satuan'          => $produk['harga_beli'],
                'total_harga'           => ($produk['harga_beli'] * $this->request->getPost('qty')),
            ];
            $modelPemesananDetail->save($data);
        }

        $json = [
            'notif' => 'Berhasil menambah list produk pemesanan',
        ];

        echo json_encode($json);
    }


    public function edit($id = null)
    {
        //
    }


    public function update($id = null)
    {
        //
    }


    public function remove($id = null)
    {
        //
    }


    public function delete($id = null)
    {
        $id_pemesanan = $this->request->getPost('id_pemesanan');
        $modelPemesanan = new PemesananModel();
        $no_pemesanan = $modelPemesanan->find($id_pemesanan)['no_pemesanan'];

        $modelPemesananDetail = new PemesananDetailModel();

        $modelPemesananDetail->delete($id);

        session()->setFlashdata('pesan', 'Data berhasil dihapus.');
        return redirect()->to('/list_pemesanan/' . $no_pemesanan);
    }



    public function check_list_produk()
    {
        $id_pemesanan = $this->request->getVar('id_pemesanan');
        $modelPemesananDetail = new PemesananDetailModel();
        $produk = $modelPemesananDetail->where(['id_pemesanan' => $id_pemesanan])->findAll();

        if ($produk) {
            $json = ['ok' => 'ok'];
        } else {
            $json = ['null' => null];
        }
        echo json_encode($json);
    }



    public function simpan_pemesanan()
    {
        $id_pemesanan = $this->request->getVar('id_pemesanan');

        $modelPemesanan = new PemesananModel();
        $pemesanan = $modelPemesanan->find($id_pemesanan);

        $modelPemesananDetail = new PemesananDetailModel();
        $sum = $modelPemesananDetail->sumTotalHargaProduk($id_pemesanan);

        $data_update = [
            'id'                    => $pemesanan['id'],
            'id_supplier'           => $this->request->getVar('id_supplier'),
            'id_user'               => $this->request->getVar('id_user'),
            'total_harga_produk'    => $sum['total_harga'],
            'status'                => 'Plan'
        ];
        $modelPemesanan->save($data_update);

        session()->setFlashdata('pesan', 'Data pemesanan berhasil disimpan.');
        return redirect()->to('/pemesanan');
    }
}
