<?php

namespace App\Controllers;

use App\Models\LokasiProdukModel;
use CodeIgniter\RESTful\ResourcePresenter;
use \Hermawan\DataTables\DataTable;

class LokasiProduk extends ResourcePresenter
{
    protected $helpers = ['form', 'stok_helper'];


    public function indexRuangan()
    {
        return view('ruanganrak/ruangan/indexProduk');
    }


    public function indexRak()
    {
        return view('ruanganrak/rak/indexProduk');
    }


    public function getDataRuanganProduk()
    {
        if ($this->request->isAJAX()) {
            $gudang       = getIdGudangByIdUser(user()->id);
            $idGudang     = $gudang['id_gudang'];
            $modelLokasi  = new LokasiProdukModel();
            $data         = $modelLokasi->select('ruangan.nama as ruangan, produk.nama as produk, SUM(lokasi_produk.stok) as stok')
                ->join('ruangan', 'lokasi_produk.id_ruangan = ruangan.id', 'left')
                ->join('produk', 'lokasi_produk.id_produk = produk.id', 'left')
                ->where('lokasi_produk.id_gudang', $idGudang)
                ->groupBy('lokasi_produk.id_produk, lokasi_produk.id_ruangan');

            return DataTable::of($data)
                ->addNumbering('no')
                ->toJson(true);
        } else {
            return "Tidak bisa load data.";
        }
    }


    public function getDataRakProduk()
    {
        if ($this->request->isAJAX()) {
            $gudang       = getIdGudangByIdUser(user()->id);
            $idGudang     = $gudang['id_gudang'];
            $modelLokasi  = new LokasiProdukModel();
            $data         = $modelLokasi->select('rak.nama as rak, ruangan.nama as ruangan, produk.nama as produk, SUM(lokasi_produk.stok) as stok')
                ->join('rak', 'lokasi_produk.id_rak = rak.id', 'left')
                ->join('ruangan', 'lokasi_produk.id_ruangan = ruangan.id', 'left')
                ->join('produk', 'lokasi_produk.id_produk = produk.id', 'left')
                ->where('lokasi_produk.id_gudang', $idGudang)
                ->groupBy('lokasi_produk.id_produk, lokasi_produk.id_rak');

            return DataTable::of($data)
                ->addNumbering('no')
                ->toJson(true);
        } else {
            return "Tidak bisa load data.";
        }
    }


    public function new()
    {
        $modelLokasi  = new LokasiProdukModel();
        $lokasi       = $modelLokasi->findAll();
        $gudang       = getIdGudangByIdUser(user()->id);
        $idGudang     = $gudang['id_gudang'];

        $db = \Config\Database::connect();
        $produk  = $db->table('produk');
        $ruangan = $db->table('ruangan');
        $rak     = $db->table('rak');

        $data = [
            'lokasiproduk'    => $lokasi,
            'produk'          => $produk->get()->getResultArray(),
            'ruangan'         => $ruangan->where('id_gudang', $idGudang)->get()->getResultArray(),
            'rak'             => $rak->where('id_gudang', $idGudang)->get()->getResultArray(),
        ];

        $json = [
            'data'       => view('ruanganrak/lokasiproduk/add', $data),
        ];

        echo json_encode($json);
    }


    public function RakbyRuangan()
    {
        $idRuangan = $this->request->getVar('idRuangan');

        $db      = \Config\Database::connect();
        $builderRak = $db->table('rak');
        $builderRak->select('*');
        $builderRak->where('id_ruangan', $idRuangan);
        $builderRak->orderBy('nama');
        $listRak = $builderRak->get()->getResult();

        if ($listRak) {
            foreach ($listRak as $rak) {
                echo " <option value='$rak->id'> $rak->nama </option> ";
            }
        } else {
            echo " <option selected value=''>Rak Tidak Ditemukan</option> ";
        }
    }


    public function StokbyProduk()
    {
        $idProduk = $this->request->getVar('idProduk');

        $db             = \Config\Database::connect();
        $builderProduk  = $db->table('produk')->select('stok')->where('id', $idProduk);
        $listProduk     = $builderProduk->get()->getRowArray();

        $builderLokasi  = $db->table('lokasi_produk')->selectSum('stok')->where('id_produk', $idProduk);
        $listLokasi     = $builderLokasi->get()->getRowArray();

        $sisaStok = $listProduk['stok'] - $listLokasi['stok'];

        if ($sisaStok) {
            echo $sisaStok;
        } else {
            echo "Stok habis";
        }
    }


    public function create()
    {
        if ($this->request->isAJAX()) {
            $stokAwal = $this->request->getPost('stokAwal');

            $validasi = [
                'idProduk'       => [
                    'rules'  => 'required',
                    'errors' => [
                        'required'  => 'Produk harus dipilih.',
                    ]
                ],
                'idRuangan'       => [
                    'rules'  => 'required',
                    'errors' => [
                        'required'  => 'Ruangan produk harus dipilih.',
                    ]
                ],
                'idRak'       => [
                    'rules'  => 'required',
                    'errors' => [
                        'required'  => 'Rak produk harus dipilih.',
                    ]
                ],
                'stok'  => [
                    'rules'  => 'required|less_than_equal_to[' . $stokAwal . ']',
                    'errors' => [
                        'required'  => 'Stok harus diisi.',
                        'less_than_equal_to' => 'Jumlah melebihi stok yg tersedia'
                    ]
                ],
            ];

            if (!$this->validate($validasi)) {
                $validation = \Config\Services::validation();

                $error = [
                    'error_idProduk'   => $validation->getError('idProduk'),
                    'error_idRuangan'  => $validation->getError('idRuangan'),
                    'error_idRak'      => $validation->getError('idRak'),
                    'error_stok'       => $validation->getError('stok'),
                ];

                $json = [
                    'error' => $error
                ];
            } else {
                $modelLokasi    = new LokasiProdukModel();
                $gudang         = getIdGudangByIdUser(user()->id);
                $idGudang       = $gudang['id_gudang'];

                $idProduk       = $this->request->getPost('idProduk');
                $idRuangan      = $this->request->getPost('idRuangan');
                $idRak          = $this->request->getPost('idRak');
                $stok           = $this->request->getPost('stok');

                $lokasiProduk   = $modelLokasi->selectSum('stok')->where([
                    'id_produk'       => $this->request->getPost('idProduk'),
                    'id_ruangan'      => $this->request->getPost('idRuangan'),
                    'id_rak'          => $this->request->getPost('idRak')
                ])->get()->getRowArray();

                if ($lokasiProduk['stok'] != 0) {
                    $modelLokasi->set('stok', $stok + $lokasiProduk['stok']);
                    $modelLokasi->where([
                        'id_produk'       => $this->request->getPost('idProduk'),
                        'id_ruangan'      => $this->request->getPost('idRuangan'),
                        'id_rak'          => $this->request->getPost('idRak')
                    ]);
                    $modelLokasi->update();
                } else {
                    $data = [
                        'id_produk'    => $idProduk,
                        'id_gudang'    => $idGudang,
                        'id_ruangan'   => $idRuangan,
                        'id_rak'       => $idRak,
                        'stok'         => $stok,
                    ];

                    $modelLokasi->save($data);
                }

                $json = [
                    'success' => 'Berhasil menambah data produk',
                ];
            }

            echo json_encode($json);
        } else {
            return 'Tidak bisa load';
        }
    }
}
