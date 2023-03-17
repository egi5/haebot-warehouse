<?php

namespace App\Controllers;

use App\Models\GudangModel;
use App\Models\PembelianDetailModel;
use App\Models\PembelianModel;
use App\Models\ProdukModel;
use CodeIgniter\RESTful\ResourcePresenter;

class Pembelian_detail extends ResourcePresenter
{
    protected $helpers = ['user_admin_helper'];


    public function List_pembelian($no_pembelian)
    {
        $modelProduk = new ProdukModel();
        $produk = $modelProduk->findAll();
        $modelGudang = new GudangModel();
        $gudang = $modelGudang->findAll();

        $pembelianModel = new PembelianModel();
        $data = [
            'pembelian'             => $pembelianModel->getPembelian($no_pembelian),
            'produk'                => $produk,
            'gudang'                => $gudang,
        ];
        return view('pembelian/detail', $data);
    }


    public function getListProdukPembelian()
    {
        if ($this->request->isAJAX()) {

            $modelPembelian = new PembelianModel();
            $modelPembelianDetail = new PembelianDetailModel();

            $id_pembelian = $this->request->getVar('id_pembelian');
            $pembelian = $modelPembelian->find($id_pembelian);
            $produk_pembelian = $modelPembelianDetail->getListProdukPembelian($id_pembelian);

            if ($produk_pembelian) {
                $data = [
                    'produk_pembelian'      => $produk_pembelian,
                    'pembelian'             => $pembelian
                ];

                $json = [
                    'list' => view('pembelian/list_produk', $data),
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


    public function create()
    {
        $id_produk = $this->request->getPost('id_produk');
        $id_pembelian = $this->request->getPost('id_pembelian');

        $modelProduk = new ProdukModel();
        $produk = $modelProduk->find($id_produk);

        $modelPembelianDetail = new PembelianDetailModel();
        $cek_produk = $modelPembelianDetail->where(['id_produk' => $id_produk, 'id_pembelian' => $id_pembelian])->first();

        if ($cek_produk) {
            $data_update = [
                'id'                    => $cek_produk['id'],
                'id_pembelian'          => $id_pembelian,
                'id_produk'             => $this->request->getPost('id_produk'),
                'qty'                   => $cek_produk['qty'] + $this->request->getPost('qty'),
                'harga_satuan'          => $cek_produk['harga_satuan'],
                'total_harga'           => $cek_produk['total_harga'] + ($cek_produk['harga_satuan'] * $this->request->getPost('qty')),
            ];
            $modelPembelianDetail->save($data_update);
        } else {
            $data = [
                'id_pembelian'          => $id_pembelian,
                'id_produk'             => $this->request->getPost('id_produk'),
                'qty'                   => $this->request->getPost('qty'),
                'harga_satuan'          => $produk['harga_beli'],
                'total_harga'           => ($produk['harga_beli'] * $this->request->getPost('qty')),
            ];
            $modelPembelianDetail->save($data);
        }

        $modelPembelian = new PembelianModel();
        $sum = $modelPembelianDetail->sumTotalHargaProduk($id_pembelian);

        $data_update = [
            'id'                    => $id_pembelian,
            'total_harga_produk'    => $sum['total_harga'],
        ];
        $modelPembelian->save($data_update);

        $json = [
            'notif' => 'Berhasil menambah list produk pembelian',
        ];

        echo json_encode($json);
    }


    public function update($id = null)
    {

        $data = json_decode(file_get_contents('php://input'), true);

        $modelPembelianDetail = new PembelianDetailModel();
        $harga_satuan = str_replace(".", "", $data['new_harga_satuan']);
        $data_update_produk = [
            'id'                    => $id,
            'qty'                   => $data['new_qty'],
            'harga_satuan'          => $harga_satuan,
            'total_harga'           => $harga_satuan * $data['new_qty'],
        ];
        $modelPembelianDetail->save($data_update_produk);

        $modelPembelian = new PembelianModel();
        $sum = $modelPembelianDetail->sumTotalHargaProduk($data['id_pembelian']);
        $data_update_pembelian = [
            'id'                    => $data['id_pembelian'],
            'total_harga_produk'    => $sum['total_harga'],
        ];
        $modelPembelian->save($data_update_pembelian);

        $json = [
            'notif' => 'Berhasil update list produk pembelian',
        ];

        echo json_encode($json);
    }


    public function delete($id = null)
    {
        $id_pembelian = $this->request->getPost('id_pembelian');
        $modelPembelian = new PembelianModel();
        $no_pembelian = $modelPembelian->find($id_pembelian)['no_pembelian'];

        $modelPembelianDetail = new PembelianDetailModel();

        $modelPembelianDetail->delete($id);

        $modelPembelian = new PembelianModel();
        $sum = $modelPembelianDetail->sumTotalHargaProduk($id_pembelian);

        $data_update = [
            'id'                    => $id_pembelian,
            'total_harga_produk'    => $sum['total_harga'],
        ];
        $modelPembelian->save($data_update);

        session()->setFlashdata('pesan', 'List Produk berhasil dihapus.');
        return redirect()->to('/list_pembelian/' . $no_pembelian);
    }
}
