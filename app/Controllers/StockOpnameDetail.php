<?php

namespace App\Controllers;

use App\Models\LokasiProdukModel;
use App\Models\StockOpnameModel;
use App\Models\StockOpnameDetailModel;
use App\Models\ProdukModel;
use CodeIgniter\RESTful\ResourcePresenter;
use \Hermawan\DataTables\DataTable;

class StockOpnameDetail extends ResourcePresenter
{
    protected $helpers = ['form', 'stok_helper', 'nomor_auto_helper'];

    public function newStokProduk($idStok)
    {
        $modelProduk    = new ProdukModel();
        $modelStok      = new StockOpnameModel();
        $produk         = $modelProduk->findAll();
        $stok           = $modelStok->find($idStok);
        // $stok           = $modelStok->select('id')->where(['id' => $idStok]);

        $data = [
            'produk'    => $produk,
            'stok'      => $stok,
        ];
        return view('stockopname/listStokOpname', $data);
    }


    public function StokbyProduk()
    {
        $idProduk = $this->request->getVar('idProduk');

        $db             = \Config\Database::connect();
        $builderLokasi  = $db->table('lokasi_produk')->selectSum('stok')->where('id_produk', $idProduk);
        $listLokasi     = $builderLokasi->get()->getRowArray();
        // $builderProduk  = $db->table('produk')->select('stok')->where('id', $idProduk);
        // $listProduk     = $builderProduk->get()->getRowArray();

        echo $listLokasi['stok'];
    }


    public function getListProdukStock()
    {
        if ($this->request->isAJAX()) {

            $idStockOpname = $this->request->getPost('idStockOpname');

            $modelStokDetail   = new StockOpnameDetailModel();
            $modelStok         = new StockOpnameModel();
            $stok              = $modelStok->find($idStockOpname);
            $stokProduk        = $modelStokDetail->getListProdukStock($idStockOpname);

            if ($stokProduk) {
                $data = [
                    'stokProduk'      => $stokProduk,
                    'stok'            => $stok,
                ];

                $json = [
                    'list' => view('stockopname/list_produk', $data),
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
        if ($this->request->isAJAX()) {
            $validasi = [
                'idProduk'       => [
                    'rules'  => 'required',
                    'errors' => [
                        'required'  => '{field} harus diisi.'
                    ]
                ],
                'stokFisik'  => [
                    'rules'  => 'required',
                    'errors' => [
                        'required'  => 'Jumlah stok fisik harus diisi.'
                    ]
                ],
            ];

            if (!$this->validate($validasi)) {
                $validation = \Config\Services::validation();

                $error = [
                    'error_idProduk'    => $validation->getError('idProduk'),
                    'error_stok'        => $validation->getError('stokFisik'),
                ];

                $json = [
                    'error' => $error
                ];
            } else {
                $modelStokDetail   = new StockOpnameDetailModel();

                $idStockOpname  = $this->request->getPost('idStokOpname');
                $idProduk       = $this->request->getPost('idProduk');
                $jumlahFisik    = $this->request->getPost('stokFisik');
                $jumlahVirtual  = $this->request->getPost('stokVirtual');

                $cekProduk      = $modelStokDetail->where([
                    'id_produk'         => $idProduk, 
                    'id_stock_opname'   => $idStockOpname
                ])->first();

                if($cekProduk)
                {
                    $data_update = [
                        'id'              => $cekProduk['id'],
                        'id_stock_opname' => $idStockOpname,
                        'id_produk'       => $idProduk,
                        'jumlah_fisik'    => $jumlahFisik + $cekProduk['jumlah_fisik'],
                        'jumlah_virtual'  => $jumlahVirtual,
                        'selisih'         => $jumlahVirtual - ($jumlahFisik + $cekProduk['jumlah_fisik']),
                    ];
                    $modelStokDetail->save($data_update);
                } else {
                    $data = [
                        'id_stock_opname' => $idStockOpname,
                        'id_produk'       => $idProduk,
                        'jumlah_fisik'    => $jumlahFisik,
                        'jumlah_virtual'  => $jumlahVirtual,
                        'selisih'         => $jumlahVirtual - $jumlahFisik,
                    ];
    
                    $modelStokDetail->insert($data);
                }

                // return redirect()->back();
                $json = [
                    'success' => 'Berhasil menambah data stok',
                ];
            }

            echo json_encode($json);
        } else {
            return 'Tidak bisa load';
        }
    }


    public function update($id = null)
    {
        // $data = json_decode(file_get_contents('php://input'), true);

        $modelStokDetail    = new StockOpnameDetailModel();
        // $jumlahFisik        = $data['new_jumlah_fisik'];   
        $idStockOpname  = $this->request->getPost('id_stock'); 
        $jumlahFisik    = $this->request->getPost('new_jumlah_fisik');
        $jumlahVirtual  = $this->request->getPost('jumlah_virtual');

        $data_update_produk = [
            'id'            => $id,
            'jumlah_fisik'  => $jumlahFisik,
            'selisih'       => $jumlahVirtual - $jumlahFisik
        ];
        $modelStokDetail->save($data_update_produk);
        // return redirect()->back();
        
        $json = [
            'notif' => 'Berhasil update list stok produk',
            'coba'=> $jumlahFisik
        ];

        echo json_encode($json);
    }


    public function delete($id = null)
    {
        $modelStokDetail = new StockOpnameDetailModel();

        $modelStokDetail->delete($id);
        return redirect()->back();
    }


    public function checkListProduk()
    {
        if ($this->request->isAJAX()) {
            $idStockOpname = $this->request->getVar('idStockOpname');
            $modelStokDetail = new StockOpnameDetailModel();
            $stok = $modelStokDetail->where(['id_stock_opname' => $idStockOpname])->findAll();

            if ($stok) {
                $json = ['ok' => 'ok'];
            } else {
                $json = ['null' => null];
            }
            echo json_encode($json);
        } else {
            return 'Tidak bisa load';
        }
    }


    public function updateStatusStock()
    {
        $modelStok  = new StockOpnameModel();
        $id         = $this->request->getPost('idStokOpname');
    
        $data = [
            'id'        => $id,
            'status'    => 'Selesai'
        ];

        $modelStok->save($data);

        session()->setFlashdata('pesan', 'Status pemesanan berhasil diupdate ke Selesai.');
        return redirect()->to('/stockopname');
    }
}
