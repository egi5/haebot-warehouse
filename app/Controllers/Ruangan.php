<?php

namespace App\Controllers;

use App\Models\GudangModel;
use App\Models\RuanganModel;
use CodeIgniter\RESTful\ResourcePresenter;
use \Hermawan\DataTables\DataTable;

class Ruangan extends ResourcePresenter
{
    protected $helpers = ['form', 'stok_helper'];


    public function index()
    {
        return view('ruanganrak/ruangan/index');
    }


    public function getDataRuangan()
    {
        if ($this->request->isAJAX()) {
            $gudang       = getIdGudangByIdUser(user()->id);
            $idGudang     = $gudang['id_gudang'];
            $modelRuangan = new RuanganModel();
            $data         = $modelRuangan->select('id, nama, kode')->where('id_gudang', $idGudang);

            return DataTable::of($data)
                ->addNumbering('no')
                ->add('aksi', function ($row) {
                    return '
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
        //
    }


    public function new()
    {
        if ($this->request->isAJAX()) {
            $modelRuangan   = new RuanganModel();
            $ruangan        = $modelRuangan->findAll();

            $data = [
                'ruangan'   => $ruangan
            ];

            $json = [
                'data'       => view('RuanganRak/ruangan/add', $data),
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
                'nama'       => [
                    'rules'  => 'required',
                    'errors' => [
                        'required'  => '{field} ruangan harus diisi.',
                    ]
                ],
                'kodeRuangan'  => [
                    'rules'  => 'required',
                    'errors' => [
                        'required'  => 'Kode Ruangan harus diisi.'
                    ]
                ],
            ];

            if (!$this->validate($validasi)) {
                $validation = \Config\Services::validation();

                $error = [
                    'error_nama'  => $validation->getError('nama'),
                    'error_kode'  => $validation->getError('kodeRuangan'),
                ];

                $json = [
                    'error' => $error
                ];
            } else {
                $modelRuangan   = new RuanganModel();
                $gudang         = getIdGudangByIdUser(user()->id);
                $idGudang       = $gudang['id_gudang'];

                $data = [
                    'id_gudang'    => $idGudang,
                    'nama'         => $this->request->getPost('nama'),
                    'kode'         => $this->request->getPost('kodeRuangan'),
                ];

                $modelRuangan->insert($data);

                $json = [
                    'success' => 'Berhasil menambah data ruangan'
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
            $modelRuangan   = new RuanganModel();
            $ruangan        = $modelRuangan->find($id);

            $data = [
                'ruangan'      => $ruangan
            ];

            $json = [
                'data'   => view('ruanganrak/ruangan/edit', $data),
            ];

            echo json_encode($json);
        } else {
            return 'Tidak bisa load';
        }
    }


    public function update($id = null)
    {
        if ($this->request->isAJAX()) {
            $validasi = [
                'nama'       => [
                    'rules'  => 'required',
                    'errors' => [
                        'required'  => '{field} ruangan harus diisi.',
                    ]
                ],
                'kodeRuangan'  => [
                    'rules'  => 'required',
                    'errors' => [
                        'required'  => 'kode ruangan harus diisi.',
                    ]
                ],
            ];

            if (!$this->validate($validasi)) {
                $validation = \Config\Services::validation();

                $error = [
                    'error_nama'    => $validation->getError('nama'),
                    'error_kode'    => $validation->getError('kodeRuangan'),
                ];

                $json = [
                    'error' => $error
                ];
            } else {
                $modelRuangan   = new RuanganModel();

                $data = [
                    'id'           => $id,
                    'nama'         => $this->request->getPost('nama'),
                    'kode'         => $this->request->getPost('kodeRuangan'),
                ];
                $modelRuangan->save($data);

                $json = [
                    'success' => 'Data Berhasil di update'
                ];
            }
            echo json_encode($json);
        } else {
            return 'Tidak bisa load';
        }
    }


    public function delete($id = null)
    {
        $modelRuangan   = new RuanganModel();

        $modelRuangan->delete($id);

        session()->setFlashdata('pesan', 'Data berhasil dihapus.');
        return redirect()->to('/ruangan');
    }

    // public function getIdGudang()
    // {
    //     $gudangPJ = getIdGudangByIdUser(user()->id);

    //     dd($gudangPJ);
    // }
}
