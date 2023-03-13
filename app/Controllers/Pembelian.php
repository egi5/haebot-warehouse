<?php

namespace App\Controllers;

use App\Models\PembelianDetailModel;
use App\Models\PembelianModel;
use App\Models\SupplierModel;
use CodeIgniter\RESTful\ResourcePresenter;
use \Hermawan\DataTables\DataTable;

class Pembelian extends ResourcePresenter
{
    protected $helpers = ['form', 'nomor_auto_helper'];

    public function index()
    {
        return view('pembelian/pembelian/index');
    }


    public function getDataPembelian()
    {
        if ($this->request->isAJAX()) {
            $db = \Config\Database::connect();
            $data =  $db->table('pembelian')
                ->select('pembelian.id, pembelian.no_pembelian, pembelian.tanggal, supplier.nama as supplier, pembelian.total_harga_produk, pembelian.status')
                ->join('supplier', 'pembelian.id_supplier = supplier.id', 'left')
                ->where('pembelian.deleted_at', null);

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
                'data' => view('pembelian/pembelian/show', $data),
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
                'data' => view('pembelian/pembelian/add'),
            ];

            echo json_encode($json);
        } else {
            return 'Tidak bisa load';
        }
    }


    // public function create()
    // {
    //     if ($this->request->isAJAX()) {
    //         $validasi = [
    //             'no_pemesanan' => [
    //                 'rules' => 'required|is_unique[pemesanan.no_pemesanan]',
    //                 'errors' => [
    //                     'required' => 'Nomor pemesanan harus diisi.',
    //                     'is_unique' => 'Nomor pemesanan sudah ada dalam database.'
    //                 ]
    //             ],
    //             'tanggal' => [
    //                 'rules' => 'required',
    //                 'errors' => [
    //                     'required' => 'tanggal pemesanan harus diisi.',
    //                 ]
    //             ],
    //             'id_supplier' => [
    //                 'rules' => 'required',
    //                 'errors' => [
    //                     'required' => 'Supplier harus dipilih.',
    //                 ]
    //             ],
    //         ];

    //         if (!$this->validate($validasi)) {
    //             $validation = \Config\Services::validation();

    //             $error = [
    //                 'error_no_pemesanan' => $validation->getError('no_pemesanan'),
    //                 'error_tanggal' => $validation->getError('tanggal'),
    //                 'error_id_supplier' => $validation->getError('id_supplier'),
    //             ];

    //             $json = [
    //                 'error' => $error
    //             ];
    //         } else {

    //             'nomor_pembelian_auto'  => nomor_pembelian_auto(date('Y-m-d'))
    //             $modelPemesanan = new PemesananModel();
    //             $data = [
    //                 'no_pemesanan'          => $this->request->getPost('no_pemesanan'),
    //                 'tanggal'               => $this->request->getPost('tanggal'),
    //                 'id_supplier'           => $this->request->getPost('id_supplier'),
    //             ];
    //             $modelPemesanan->save($data);

    //             $json = [
    //                 'success' => 'Berhasil menambah data produk',
    //                 'no_pemesanan' => $this->request->getPost('no_pemesanan'),
    //             ];
    //         }

    //         echo json_encode($json);
    //     } else {
    //         return 'Tidak bisa load';
    //     }
    // }


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
