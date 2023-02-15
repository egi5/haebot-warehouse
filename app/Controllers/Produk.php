<?php

namespace App\Controllers;

use App\Models\GudangModel;
use App\Models\ProdukModel;
use App\Models\ProdukPlanModel;
use CodeIgniter\RESTful\ResourcePresenter;
use \Hermawan\DataTables\DataTable;

class Produk extends ResourcePresenter
{
    protected $helpers = ['form', 'stok_helper'];


    public function index()
    {
        return view('data_master/produk/index');
    }


    public function getDataProduk()
    {
        if ($this->request->isAJAX()) {

            $modelProduk = new ProdukModel();
            $data = $modelProduk->where(['deleted_at' => null])->select('id, nama, jenis, harga_beli, harga_jual, stok');

            return DataTable::of($data)
                ->addNumbering('no')
                ->add('aksi', function ($row) {
                    return '
                    <a title="Stok Virtual" class="px-2 py-0 btn btn-sm btn-outline-dark" href="' . site_url() . 'produk/' . $row->id . '">
                        <i class="fa-fw fa-solid fa-magnifying-glass"></i>
                    </a>

                    <a title="Edit" class="px-2 py-0 btn btn-sm btn-outline-primary" onclick="showModalEdit(' . $row->id . ')">
                        <i class="fa-fw fa-solid fa-pen"></i>
                    </a>

                    <form id="form_delete" method="POST" class="d-inline">
                        ' . csrf_field() . '
                        <input type="hidden" name="_method" value="DELETE">
                    </form>
                    <button onclick="confirm_delete(' . $row->id . ')" title="Hapus" type="button" class="px-2 py-0 btn btn-sm btn-outline-danger"><i class="fa-fw fa-solid fa-trash"></i></button>
                    ';
                }, 'last')
                ->toJson(true);
        } else {
            return "Tidak bisa load data.";
        }
    }


    public function show($id = null)
    {
        $modelProduk = new ProdukModel();
        $modelProdukPlan = new ProdukPlanModel();

        $produk = $modelProduk->getProduk($id);

        if ($produk['jenis'] == 'SET' || $produk['jenis'] == 'SINGLE') {

            $produkPlan = $modelProdukPlan->where(['id_produk_jadi' => $id])->findAll();
            if ($produkPlan) {

                $list_plan = array_column($produkPlan, 'id_produk_bahan');
                array_push($list_plan, $id);

                $builder = $modelProduk->builder();
                $builder->select('*');
                $builder->whereNotIn('id', $list_plan);
                $builder->orderBy('jenis', 'asc');
                $all_plan = $builder->get()->getResultArray();

                $virtual_stok = hitung_virtual_stok_dari_bahan($id);

                $bisa_membuat = min(array_column($virtual_stok, 'bisa_membuat'));

                $data = [
                    'jenis_produk'  => $produk['jenis'],
                    'produk'        => $produk,
                    'all_plan'      => $all_plan,
                    'virtual_stok'  => $virtual_stok,
                    'bisa_membuat'  => $bisa_membuat,
                    'bisa_dipecah'  => 0,
                    'result'        => 'ok',
                ];
            } else {
                $all_plan = $modelProduk->findAll();
                $data = [
                    'jenis_produk'  => $produk['jenis'],
                    'produk'        => $produk,
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
                $builder->orderBy('jenis', 'asc');
                $all_plan = $builder->get()->getResultArray();

                $virtual_stok = hitung_virtual_stok_dari_set($id);

                $bisa_dipecah = 0;

                foreach ($virtual_stok as $stok) {
                    $bisa_dipecah += $stok['bisa_dipecah'];
                }
                $data = [
                    'jenis_produk'  => $produk['jenis'],
                    'produk'        => $produk,
                    'all_plan'      => $all_plan,
                    'virtual_stok'  => $virtual_stok,
                    'bisa_membuat'  => 0,
                    'bisa_dipecah'  => $bisa_dipecah,
                    'result'        => 'ok',
                ];
            } else {
                $all_plan = $modelProduk->findAll();
                $data = [
                    'jenis_produk'  => $produk['jenis'],
                    'produk'        => $produk,
                    'all_plan'      => $all_plan,
                    'virtual_stok'  => '',
                    'bisa_membuat'  => 0,
                    'bisa_dipecah'  => 0,
                    'result'        => 'tidak memiliki set.',
                ];
            }
        }

        return view('data_master/produk/show', $data);
    }


    public function new()
    {
        if ($this->request->isAJAX()) {

            $modelGudang = new GudangModel();
            $gudang = $modelGudang->findAll();

            $db = \Config\Database::connect();
            $builder_produk_kategori = $db->table('produk_kategori');

            $data = [
                'gudang'        => $gudang,
                'kategori'      => $builder_produk_kategori->get()->getResultArray(),
            ];

            $json = [
                'data'          => view('data_master/produk/add', $data),
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
                'id_kategori' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'kategori produk harus diisi.',
                    ]
                ],
                'id_gudang' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'gudang harus diisi.',
                    ]
                ],
                'hs_code' => [
                    'rules' => 'required|is_unique[produk.hs_code]',
                    'errors' => [
                        'required' => '{field} produk harus diisi.',
                        'is_unique' => 'HS Code produk sudah ada dalam database.'
                    ]
                ],
                'sku' => [
                    'rules' => 'required|is_unique[produk.sku]',
                    'errors' => [
                        'required' => '{field} produk harus diisi.',
                        'is_unique' => 'SKU produk sudah ada dalam database.'
                    ]
                ],
                'nama' => [
                    'rules' => 'required|is_unique[produk.nama]',
                    'errors' => [
                        'required' => '{field} produk harus diisi.',
                        'is_unique' => 'nama produk sudah ada dalam database.'
                    ]
                ],
                'satuan' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} produk harus dipilih.',
                    ]
                ],
                'jenis' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} produk harus dipilih.',
                    ]
                ],
                'jenis_produk' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} produk harus dipilih.',
                    ]
                ],
                'harga_beli' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Harga beli produk harus diisi.',
                    ]
                ],
                'harga_jual' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Harga beli produk harus diisi.',
                    ]
                ],
                'stok' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Stok produk awal harus diisi.',
                    ]
                ],
                'berat' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'berat produk awal harus diisi.',
                    ]
                ],
                'panjang' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'panjang produk awal harus diisi.',
                    ]
                ],
                'lebar' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'lebar produk awal harus diisi.',
                    ]
                ],
                'tinggi' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'tinggi produk awal harus diisi.',
                    ]
                ],
                'status_marketing' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'status marketing produk awal harus diisi.',
                    ]
                ],
            ];

            if (!$this->validate($validasi)) {
                $validation = \Config\Services::validation();

                $error = [
                    'error_id_kategori' => $validation->getError('id_kategori'),
                    'error_id_gudang' => $validation->getError('id_gudang'),
                    'error_sku' => $validation->getError('sku'),
                    'error_hs_code' => $validation->getError('hs_code'),
                    'error_nama' => $validation->getError('nama'),
                    'error_satuan' => $validation->getError('satuan'),
                    'error_jenis' => $validation->getError('jenis'),
                    'error_jenis_produk' => $validation->getError('jenis_produk'),
                    'error_harga_beli' => $validation->getError('harga_beli'),
                    'error_harga_jual' => $validation->getError('harga_jual'),
                    'error_stok' => $validation->getError('stok'),
                    'error_berat' => $validation->getError('berat'),
                    'error_panjang' => $validation->getError('panjang'),
                    'error_lebar' => $validation->getError('lebar'),
                    'error_tinggi' => $validation->getError('tinggi'),
                    'error_status_marketing' => $validation->getError('status_marketing'),
                ];

                $json = [
                    'error' => $error
                ];
            } else {
                $modelProduk = new ProdukModel();

                $db = \Config\Database::connect();
                $builder_produk_kategori = $db->table('produk_kategori');

                if (strpos($this->request->getPost('id_kategori'), '-krisna-') !== false) {
                    $post_kategori = explode('-', $this->request->getPost('id_kategori'));
                    $the_id_kategori = $post_kategori[0];
                } else {
                    $builder_produk_kategori->insert(['nama' => $this->request->getPost('id_kategori')]);
                    $the_id_kategori = $db->insertID();
                }

                $slug = url_title($this->request->getPost('nama'), '-', true);

                $harga_beli = str_replace(".", "", $this->request->getPost('harga_beli'));
                $harga_jual = str_replace(".", "", $this->request->getPost('harga_jual'));

                $data = [
                    'id_kategori'           => $the_id_kategori,
                    'id_gudang'             => $this->request->getPost('id_gudang'),
                    'sku'                   => $this->request->getPost('sku'),
                    'hs_code'               => $this->request->getPost('hs_code'),
                    'nama'                  => $this->request->getPost('nama'),
                    'slug'                  => $slug,
                    'satuan'                => $this->request->getPost('satuan'),
                    'jenis'                 => $this->request->getPost('jenis'),
                    'jenis_produk'          => $this->request->getPost('jenis_produk'),
                    'hg_produk_penyusun'    => 0,
                    'harga_beli'            => $harga_beli,
                    'harga_jual'            => $harga_jual,
                    'stok'                  => $this->request->getPost('stok'),
                    'berat'                 => $this->request->getPost('berat'),
                    'panjang'               => $this->request->getPost('panjang'),
                    'lebar'                 => $this->request->getPost('lebar'),
                    'tinggi'                => $this->request->getPost('tinggi'),
                    'status_marketing'      => $this->request->getPost('status_marketing'),
                    'note'                  => ''
                ];
                $modelProduk->save($data);

                $json = [
                    'success' => 'Berhasil menambah data produk'
                ];
            }

            echo json_encode($json);
        } else {
            return 'Tidak bisa load';
        }
    }


    public function edit($id = null)
    {
        if ($this->request->isAJAX()) {
            $modelProduk = new ProdukModel();
            $produk = $modelProduk->find($id);

            $modelGudang = new GudangModel();
            $gudang = $modelGudang->findAll();

            $db = \Config\Database::connect();
            $builder_produk_kategori = $db->table('produk_kategori');

            $data = [
                'produk'        => $produk,
                'gudang'        => $gudang,
                'kategori'      => $builder_produk_kategori->get()->getResultArray()
            ];

            $json = [
                'data' => view('data_master/produk/edit', $data),
            ];

            echo json_encode($json);
        } else {
            return 'Tidak bisa load';
        }
    }


    public function update($id = null)
    {
        if ($this->request->isAJAX()) {
            $modelProduk = new ProdukModel();
            $old_produk = $modelProduk->find($id);

            if ($old_produk['nama'] == $this->request->getPost('nama')) {
                $rule_nama = 'required';
            } else {
                $rule_nama = 'required|is_unique[produk.nama]';
            }
            if ($old_produk['sku'] == $this->request->getPost('sku')) {
                $rule_sku = 'required';
            } else {
                $rule_sku = 'required|is_unique[produk.sku]';
            }
            if ($old_produk['hs_code'] == $this->request->getPost('hs_code')) {
                $rule_hs_code = 'required';
            } else {
                $rule_hs_code = 'required|is_unique[produk.hs_code]';
            }

            $validasi = [
                'id_kategori' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'kategori produk harus diisi.',
                    ]
                ],
                'id_gudang' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'gudang harus diisi.',
                    ]
                ],
                'hs_code' => [
                    'rules' => $rule_hs_code,
                    'errors' => [
                        'required' => '{field} produk harus diisi.',
                        'is_unique' => 'HS Code produk sudah ada dalam database.'
                    ]
                ],
                'sku' => [
                    'rules' => $rule_sku,
                    'errors' => [
                        'required' => '{field} produk harus diisi.',
                        'is_unique' => 'SKU produk sudah ada dalam database.'
                    ]
                ],
                'nama' => [
                    'rules' => $rule_nama,
                    'errors' => [
                        'required' => '{field} produk harus diisi.',
                        'is_unique' => 'nama produk sudah ada dalam database.'
                    ]
                ],
                'satuan' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} produk harus dipilih.',
                    ]
                ],
                'jenis' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} produk harus dipilih.',
                    ]
                ],
                'jenis_produk' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} produk harus dipilih.',
                    ]
                ],
                'harga_beli' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Harga beli produk harus diisi.',
                    ]
                ],
                'harga_jual' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Harga beli produk harus diisi.',
                    ]
                ],
                'stok' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Stok produk awal harus diisi.',
                    ]
                ],
                'berat' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'berat produk awal harus diisi.',
                    ]
                ],
                'panjang' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'panjang produk awal harus diisi.',
                    ]
                ],
                'lebar' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'lebar produk awal harus diisi.',
                    ]
                ],
                'tinggi' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'tinggi produk awal harus diisi.',
                    ]
                ],
                'status_marketing' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'status marketing produk awal harus diisi.',
                    ]
                ],
            ];

            if (!$this->validate($validasi)) {
                $validation = \Config\Services::validation();

                $error = [
                    'error_id_kategori' => $validation->getError('id_kategori'),
                    'error_id_gudang' => $validation->getError('id_gudang'),
                    'error_sku' => $validation->getError('sku'),
                    'error_hs_code' => $validation->getError('hs_code'),
                    'error_nama' => $validation->getError('nama'),
                    'error_satuan' => $validation->getError('satuan'),
                    'error_jenis' => $validation->getError('jenis'),
                    'error_jenis_produk' => $validation->getError('jenis_produk'),
                    'error_harga_beli' => $validation->getError('harga_beli'),
                    'error_harga_jual' => $validation->getError('harga_jual'),
                    'error_stok' => $validation->getError('stok'),
                    'error_berat' => $validation->getError('berat'),
                    'error_panjang' => $validation->getError('panjang'),
                    'error_lebar' => $validation->getError('lebar'),
                    'error_tinggi' => $validation->getError('tinggi'),
                    'error_status_marketing' => $validation->getError('status_marketing'),
                ];

                $json = [
                    'error' => $error
                ];
            } else {

                $db = \Config\Database::connect();
                $builder_produk_kategori = $db->table('produk_kategori');

                if (strpos($this->request->getPost('id_kategori'), '-krisna-') !== false) {
                    $post_kategori = explode('-', $this->request->getPost('id_kategori'));
                    $the_id_kategori = $post_kategori[0];
                } else {
                    $builder_produk_kategori->insert(['nama' => $this->request->getPost('id_kategori')]);
                    $the_id_kategori = $db->insertID();
                }

                $slug = url_title($this->request->getPost('nama'), '-', true);

                $harga_beli = str_replace(".", "", $this->request->getPost('harga_beli'));
                $harga_jual = str_replace(".", "", $this->request->getPost('harga_jual'));

                $data = [
                    'id'                    => $id,
                    'id_kategori'           => $the_id_kategori,
                    'id_gudang'             => $this->request->getPost('id_gudang'),
                    'sku'                   => $this->request->getPost('sku'),
                    'hs_code'               => $this->request->getPost('hs_code'),
                    'nama'                  => $this->request->getPost('nama'),
                    'slug'                  => $slug,
                    'satuan'                => $this->request->getPost('satuan'),
                    'jenis'                 => $this->request->getPost('jenis'),
                    'jenis_produk'          => $this->request->getPost('jenis_produk'),
                    'hg_produk_penyusun'    => 0,
                    'harga_beli'            => $harga_beli,
                    'harga_jual'            => $harga_jual,
                    'stok'                  => $this->request->getPost('stok'),
                    'berat'                 => $this->request->getPost('berat'),
                    'panjang'               => $this->request->getPost('panjang'),
                    'lebar'                 => $this->request->getPost('lebar'),
                    'tinggi'                => $this->request->getPost('tinggi'),
                    'status_marketing'      => $this->request->getPost('status_marketing'),
                    'note'                  => ''
                ];
                $modelProduk->save($data);

                $json = [
                    'success' => 'Berhasil mengedit data produk'
                ];
            }
            echo json_encode($json);
        } else {
            return 'Tidak bisa load';
        }
    }


    public function delete($id = null)
    {
        $modelProduk = new ProdukModel();

        $modelProduk->delete($id);

        session()->setFlashdata('pesan', 'Data berhasil dihapus.');
        return redirect()->to('/produk');
    }
}
