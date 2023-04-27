<?php

namespace App\Controllers;

use App\Models\LokasiProdukModel;
use App\Models\StockOpnameModel;
use App\Models\StockOpnameDetailModel;
use App\Models\ProdukModel;
use CodeIgniter\RESTful\ResourcePresenter;
use \Hermawan\DataTables\DataTable;

class StockOpname extends ResourcePresenter
{
    protected $helpers = ['form', 'stok_helper', 'nomor_auto_helper'];


    public function index()
    {
        return view('stockopname/index');
    }


    public function getDataStok()
    {
        if ($this->request->isAJAX()) {
            $gudang       = getIdGudangByIdUser(user()->id);
            $idGudang     = $gudang['id_gudang'];

            $db = \Config\Database::connect();
            $data =  $db->table('stock_opname')
                        ->select('stock_opname.id, stock_opname.nomor as nomor, stock_opname.tanggal as tanggal, stock_opname.status')
                        ->where('stock_opname.id_gudang', $idGudang);

            return DataTable::of($data)
                ->addNumbering('no')
                ->add('aksi', function ($row) {
                    if ($row->status == 'Proses') {
                        return '
                    <a title="List Stok" class="px-2 py-0 btn btn-sm btn-outline-primary" href="' . base_url() . '/list_stok/' . $row->id . '">
                        <i class="fa-fw fa-solid fa-circle-arrow-right"></i>
                    </a>';
                    } else {
                        return '
                    <a title="Detail" class="px-2 py-0 btn btn-sm btn-outline-dark" onclick="showModalDetail(\'' . $row->id . '\')">
                        <i class="fa-fw fa-solid fa-magnifying-glass"></i>
                    </a>';
                    }
                }, 'last')
                ->toJson(true);
        } else {
            return "Tidak bisa load data.";
        }
    }


    public function show($id = null)
    {
        if ($this->request->isAJAX()) {
            $modelStok          = new StockOpnameModel();
            $stockopname        = $modelStok->getStock($id);

            $modelStokDetail    = new StockOpnameDetailModel();
            $stockopnamedetail  = $modelStokDetail->getListProdukStock($stockopname['id']);

            $data = [
                'stockopname'       => $stockopname,
                'stockopnamedetail' => $stockopnamedetail,
            ];

            $json = [
                'data' => view('stockopname/show', $data),
            ];

            echo json_encode($json);
        } else {
            return 'Tidak bisa load';
        }
    }


    public function new()
    {
        if ($this->request->isAJAX()) {
            date_default_timezone_set('Asia/Jakarta');
            $modelStok   = new StockOpnameModel();
            $stok        = $modelStok->findAll();

            $data = [
                'stok'              => $stok,
                'nomor_stok_auto'   => nomor_stockopname_auto(date('Y-m-d'))
            ];

            $json = [
                'data'       => view('stockopname/add', $data),
            ];

            echo json_encode($json);
        } else {
            return 'Tidak bisa load';
        }
    }


    public function create()
    {
        if ($this->request->isAJAX()) {
            $validasi = [
                'nomor'       => [
                    'rules'  => 'required|is_unique[stock_opname.nomor]',
                    'errors' => [
                        'required'  => '{field} harus diisi.',
                        'is_unique' => '{field} sudah ada dalam database'
                    ]
                ],
                'tanggal'  => [
                    'rules'  => 'required',
                    'errors' => [
                        'required'  => '{field} harus diisi.'
                    ]
                ],
            ];

            if (!$this->validate($validasi)) {
                $validation = \Config\Services::validation();

                $error = [
                    'error_nomor'    => $validation->getError('nomor'),
                    'error_tanggal'  => $validation->getError('tanggal'),
                ];

                $json = [
                    'error' => $error
                ];
            } else {

                date_default_timezone_set('Asia/Jakarta');
                $modelStok   = new StockOpnameModel();
                $stok        = $modelStok->findAll();

                $gudang         = getIdGudangByIdUser(user()->id);
                $idGudang       = $gudang['id_gudang'];

                $data = [
                    'id_gudang' => $idGudang,
                    'id_pj'     => user()->id,
                    'nomor'     => $this->request->getPost('nomor'),
                    'tanggal'   => $this->request->getPost('tanggal'),
                ];

                $modelStok->insert($data);
                $idStok = $modelStok->insertID();


                $json = [
                    'success' => 'Berhasil menambah data stok',
                    'idStok'  => $idStok
                ];
            }

            echo json_encode($json);
        } else {
            return 'Tidak bisa load';
        }
    }
}
