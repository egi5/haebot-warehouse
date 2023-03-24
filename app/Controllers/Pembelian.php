<?php

namespace App\Controllers;

use App\Models\PembelianDetailModel;
use App\Models\PembelianModel;
use App\Models\PemesananDetailModel;
use App\Models\PemesananModel;
use CodeIgniter\RESTful\ResourcePresenter;
use \Hermawan\DataTables\DataTable;

class Pembelian extends ResourcePresenter
{
    protected $helpers = ['form', 'nomor_auto_helper'];


    public function index()
    {
        return view('pembelian/index');
    }


    public function getDataPembelian()
    {
        if ($this->request->isAJAX()) {
            $db = \Config\Database::connect();
            $data =  $db->table('pembelian')
                ->select('pembelian.id, pembelian.no_pembelian, pembelian.tanggal, supplier.nama as supplier, pembelian.total_harga_produk, pembelian.status, pembelian.status_pembayaran')
                ->join('supplier', 'pembelian.id_supplier = supplier.id', 'left')
                ->where('pembelian.deleted_at', null)
                ->where('pembelian.status !=', 'fixing')
                ->orderBy('pembelian.id', 'desc');

            return DataTable::of($data)
                ->addNumbering('no')
                ->add('aksi', function ($row) {
                    return '
                            <a title="Detail" class="px-2 py-0 btn btn-sm btn-outline-dark" onclick="showModalDetail(\'' . $row->no_pembelian . '\')">
                                <i class="fa-fw fa-solid fa-magnifying-glass"></i>
                            </a>';
                }, 'last')
                ->toJson(true);
        } else {
            return "Tidak bisa load data.";
        }
    }


    public function show($no = null)
    {
        if ($this->request->isAJAX()) {
            $modelPembelian = new PembelianModel();
            $pembelian = $modelPembelian->getPembelian($no);
            $modelPembelianDetail = new PembelianDetailModel();
            $pembelian_detail = $modelPembelianDetail->getListProdukPembelian($pembelian['id']);

            $data = [
                'pembelian' => $pembelian,
                'pembelian_detail' => $pembelian_detail,
            ];

            $json = [
                'data' => view('pembelian/show', $data),
            ];

            echo json_encode($json);
        } else {
            return 'Tidak bisa load';
        }
    }


    public function create()
    {
        $modelPembelian = new PembelianModel();
        $modelPemesanan = new PemesananModel();
        $modelPembelianDetail = new PembelianDetailModel();
        $modelPemesananDetail = new PemesananDetailModel();
        $pemesanan = $modelPemesanan->getPemesanan($this->request->getPost('no_pemesanan'));

        date_default_timezone_set('Asia/Jakarta');
        $no_pembelian = nomor_pembelian_auto(date('Y-m-d'));

        $data = [
            'id_pemesanan'          => $pemesanan['id'],
            'id_supplier'           => $pemesanan['id_supplier'],
            'id_user'               => $pemesanan['id_user'],
            'no_pembelian'          => $no_pembelian,
            'tanggal'               => date('Y-m-d'),
            'total_harga_produk'    => $pemesanan['total_harga_produk'],
            'status'                => 'Fixing',
        ];
        $modelPembelian->save($data);
        $id_pembelian = $modelPembelian->getInsertID();

        $listProdukPemesanan = $modelPemesananDetail->where(['id_pemesanan' => $pemesanan['id']])->findAll();
        foreach ($listProdukPemesanan as $produk) {
            $data_produk = [
                'id_pembelian'          => $id_pembelian,
                'id_produk'             => $produk['id_produk'],
                'qty'                   => $produk['qty'],
                'harga_satuan'          => $produk['harga_satuan'],
                'total_harga'           => $produk['total_harga'],
            ];
            $modelPembelianDetail->save($data_produk);
        }

        $data_update_pemesanan = [
            'id'                    => $pemesanan['id'],
            'status'                => 'Fixing'
        ];
        $modelPemesanan->save($data_update_pemesanan);

        return redirect()->to('/list_pembelian/' . $no_pembelian);
    }


    public function checkProdukPembelian()
    {
        $id_pembelian = $this->request->getVar('id_pembelian');
        $modelPembelianDetail = new PembelianDetailModel();
        $produk = $modelPembelianDetail->where(['id_pembelian' => $id_pembelian])->findAll();

        if ($produk) {
            $json = ['ok' => 'ok'];
        } else {
            $json = ['null' => null];
        }
        echo json_encode($json);
    }


    public function simpanPembelian()
    {
        if ($this->request->isAJAX()) {
            $modelPembelian = new PembelianModel();
            $modelPemesanan = new PemesananModel();

            $data_update_pembelian = [
                'id'                    => $this->request->getVar('id_pembelian'),
                'id_supplier'           => $this->request->getVar('id_supplier'),
                'id_gudang'             => $this->request->getVar('id_gudang'),
                'no_pembelian'          => $this->request->getVar('no_pembelian'),
                'tanggal'               => $this->request->getVar('tanggal'),
                'panjang'               => $this->request->getVar('panjang'),
                'lebar'                 => $this->request->getVar('lebar'),
                'tinggi'                => $this->request->getVar('tinggi'),
                'berat'                 => $this->request->getVar('berat'),
                'carton_koli'           => $this->request->getVar('carton_koli'),
                'catatan'               => $this->request->getVar('catatan'),
            ];
            $modelPembelian->save($data_update_pembelian);

            $pembelian = $modelPembelian->find($this->request->getVar('id_pembelian'));
            $data_update_pemesanan = [
                'id'                    => $pembelian['id_pemesanan'],
                'id_supplier'           => $this->request->getVar('id_supplier'),
            ];
            $modelPemesanan->save($data_update_pemesanan);

            $json = ['ok' => 'ok'];
            echo json_encode($json);
        } else {
            return 'Tidak bisa load';
        }
    }


    public function buatPembelian()
    {
        date_default_timezone_set('Asia/Jakarta');
        $id_pembelian = $this->request->getVar('id_pembelian');

        $modelPemesanan = new PemesananModel();
        $modelPembelian = new PembelianModel();
        $pembelian = $modelPembelian->find($id_pembelian);

        $modelPembelianDetail = new PembelianDetailModel();
        $sum = $modelPembelianDetail->sumTotalHargaProduk($id_pembelian);

        $data_update = [
            'id'                    => $pembelian['id'],
            'id_user'               => $this->request->getVar('id_admin'),
            'no_pembelian'          => $this->request->getVar('no_pembelian'),
            'tanggal'               => $this->request->getVar('tanggal'),
            'id_supplier'           => $this->request->getVar('supplier'),
            'id_gudang'             => $this->request->getVar('gudang'),
            'total_harga_produk'    => $sum['total_harga'],
            'panjang'               => $this->request->getVar('panjang'),
            'lebar'                 => $this->request->getVar('lebar'),
            'tinggi'                => $this->request->getVar('tinggi'),
            'berat'                 => $this->request->getVar('berat'),
            'carton_koli'           => $this->request->getVar('carton_koli'),
            'catatan'               => $this->request->getVar('catatan'),
            'status'                => 'Diproses'
        ];
        $modelPembelian->save($data_update);

        $data_update_pemesanan = [
            'id'                    => $pembelian['id_pemesanan'],
            'id_supplier'           => $this->request->getVar('supplier'),
            'status'                => 'Pembelian'
        ];
        $modelPemesanan->save($data_update_pemesanan);

        session()->setFlashdata('pesan', 'Berhasil membuat tagihan pembelian.');
        return redirect()->to('/pembelian');
    }


    public function delete($id = null)
    {
        $modelPembelian = new PembelianModel();
        $pembelian = $modelPembelian->find($id);

        $modelPembelianDetail = new PembelianDetailModel();
        $modelPembelianDetail->where(['id_pembelian' => $id])->delete();

        $modelPemesanan = new PemesananModel();
        $pemesanan = $modelPemesanan->where(['id' => $pembelian['id_pemesanan']])->first();
        $modelPemesanan->save(
            [
                'id' => $pemesanan['id'],
                'status' => 'Dihapus',
            ]
        );

        $modelPembelian->delete($id);

        session()->setFlashdata('pesan', 'Data pembelian berhasil dihapus.');
        return redirect()->to('/pembelian');
    }
}
