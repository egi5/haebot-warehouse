<?php

namespace App\Controllers;

use App\Models\PembelianDetailModel;
use App\Models\PembelianModel;
use App\Models\PemesananDetailModel;
use App\Models\PemesananModel;
use App\Models\SupplierModel;
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
                ->select('pembelian.id, pembelian.no_pembelian, pembelian.tanggal, supplier.nama as supplier, pembelian.total_harga_produk, pembelian.status')
                ->join('supplier', 'pembelian.id_supplier = supplier.id', 'left')
                ->where('pembelian.deleted_at', null)
                ->orderBy('pembelian.id', 'desc');

            return DataTable::of($data)
                ->addNumbering('no')
                ->add('aksi', function ($row) {
                    if ($row->status == 'Diproses') {
                        return '
                    <a title="Edit Pembelian" class="px-2 py-0 btn btn-sm btn-outline-primary" href="' . base_url() . '/list_pembelian/' . $row->no_pembelian . '">
                        <i class="fa-fw fa-solid fa-circle-arrow-right"></i>
                    </a>

                    <form id="form_delete" method="POST" class="d-inline">
                        ' . csrf_field() . '
                        <input type="hidden" name="_method" value="DELETE">
                    </form>
                    <button onclick="confirm_delete(' . $row->id . ')" title="Hapus" type="button" class="px-2 py-0 btn btn-sm btn-outline-danger"><i class="fa-fw fa-solid fa-trash"></i></button>
                    ';
                    } else {
                        return '
                    <a title="Detail" class="px-2 py-0 btn btn-sm btn-outline-dark" onclick="showModalDetail(\'' . $row->no_pembelian . '\')">
                        <i class="fa-fw fa-solid fa-magnifying-glass"></i>
                    </a>';
                    }
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


    public function new()
    {
        if ($this->request->isAJAX()) {

            $json = [
                'data' => view('pembelian/add'),
            ];

            echo json_encode($json);
        } else {
            return 'Tidak bisa load';
        }
    }


    public function check_pembelian()
    {
        $modelPembelian = new PembelianModel();
        $modelPemesanan = new PemesananModel();

        $no_pemesanan = $this->request->getVar('no_pemesanan');
        $pemesanan = $modelPemesanan->getPemesanan($no_pemesanan);

        if ($pemesanan) {
            $pembelian = $modelPembelian->where(['id_pemesanan' => $pemesanan['id']])->findAll();
            if ($pembelian) {
                $json = ['pemesanan_already_exist' => 'exist'];
            } else {
                $json = ['ok' => 'ok'];
            }
        } else {
            $json = ['not_found_pemesanan' => 'not_found_pemesanan'];
        }
        echo json_encode($json);
    }


    public function create()
    {
        $validasi = [
            'no_pemesanan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nomor pemesanan harus diisi.',
                ]
            ],
        ];

        if (!$this->validate($validasi)) {
            session()->setFlashdata('pesan', 'Maaf, terjadi error dengan no pemesanan.');
            return redirect()->to('/pembelian');
        }

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
            'origin'                => $pemesanan['origin'],
            'total_harga_produk'    => $pemesanan['total_harga_produk'],
            'status'                => 'Diproses',
        ];
        $modelPembelian->save($data);
        $id_pembelian = $modelPembelian->getInsertID();

        $listProdukPemesanan = $modelPemesananDetail->where(['id_pemesanan' => $pemesanan['id']])->findAll();
        foreach ($listProdukPemesanan as $produk) {
            $data_produk = [
                'id_pembelian'          => $id_pembelian,
                'id_produk'             => $produk['id'],
                'qty'                   => $produk['qty'],
                'harga_satuan'          => $produk['harga_satuan'],
                'total_harga'           => $produk['total_harga'],
            ];
            $modelPembelianDetail->save($data_produk);
        }

        return redirect()->to('/list_pembelian/' . $no_pembelian);
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


    // public function delete($id = null)
    // {
    //     $modelPemesananDetail = new PemesananDetailModel();
    //     $modelPemesananDetail->where(['id_pemesanan' => $id])->delete();

    //     $modelPemesanan = new PemesananModel();
    //     $modelPemesanan->delete($id);

    //     session()->setFlashdata('pesan', 'Data pemesanan berhasil dihapus.');
    //     return redirect()->to('/pemesanan');
    // }
}
