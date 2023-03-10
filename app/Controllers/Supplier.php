<?php

namespace App\Controllers;

use App\Models\ProvinsiModel;
use App\Models\SupplierAlamatModel;
use App\Models\SupplierCsModel;
use App\Models\SupplierLinkModel;
use App\Models\SupplierModel;
use App\Models\SupplierPJModel;
use App\Models\UserModel;
use CodeIgniter\RESTful\ResourcePresenter;

class Supplier extends ResourcePresenter
{
    protected $helpers = ['form'];


    public function index()
    {
        $modelSupplier = new SupplierModel();
        $supplier = $modelSupplier->getSuppliers();

        $data = [
            'supplier' => $supplier
        ];

        return view('supplier/index', $data);
    }


    public function show($id = null)
    {
        if ($this->request->isAJAX()) {
            $modelSupplier = new SupplierModel();
            $modelSupplierPJ = new SupplierPJModel();
            $modelSupplierAlamat = new SupplierAlamatModel();
            $modelSupplierLink = new SupplierLinkModel();
            $modelSupplierCs = new SupplierCsModel();

            $supplier = $modelSupplier->find($id);
            $pj = $modelSupplierPJ->getPJBySupplier($id);
            $alamat = $modelSupplierAlamat->getAlamatBySupplier($id);
            $link = $modelSupplierLink->where(['id_supplier' => $id])->findAll();
            $customer_service = $modelSupplierCs->where(['id_supplier' => $id])->findAll();

            $data = [
                'supplier' => $supplier,
                'pj' => $pj,
                'alamat' => $alamat,
                'link' => $link,
                'customer_service' => $customer_service,
            ];

            $json = [
                'data' => view('supplier/show', $data),
            ];

            echo json_encode($json);
        } else {
            return 'Tidak bisa load';
        }
    }


    public function new()
    {
        $data = ['validation' => \Config\Services::validation()];
        return view('supplier/add', $data);
    }


    public function create()
    {
        $validasi = [
            'origin' => [
                'rules' => 'required|is_unique[supplier.origin]',
                'errors' => [
                    'required' => '{field} supplier harus diisi.',
                    'is_unique' => 'origin supplier sudah ada dalam database.'
                ]
            ],
            'nama' => [
                'rules' => 'required|is_unique[supplier.nama]',
                'errors' => [
                    'required' => '{field} supplier harus diisi.',
                    'is_unique' => 'nama supplier sudah ada dalam database.'
                ]
            ],
            'pemilik' => [
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} supplier harus diisi.',
                ]
            ],
            'no_telp' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'No telepon supplier harus diisi.',
                ]
            ],
            'saldo' => [
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} awal supplier harus diisi.',
                ]
            ],
        ];

        if (!$this->validate($validasi)) {
            return redirect()->to('/supplier/new')->withInput();
        }

        $modelSupplier = new SupplierModel();

        $slug = url_title($this->request->getPost('nama'), '-', true);
        $saldo = str_replace(".", "", $this->request->getPost('saldo'));

        $data = [
            'origin' => $this->request->getPost('origin'),
            'nama' => $this->request->getPost('nama'),
            'slug' => $slug,
            'pemilik' => $this->request->getPost('pemilik'),
            'no_telp' => $this->request->getPost('no_telp'),
            'saldo' => $saldo,
            'status' => 'Active',
            'note' => $this->request->getPost('note'),
        ];
        $modelSupplier->save($data);

        session()->setFlashdata('pesan', 'Data berhasil ditambahkan.');

        return redirect()->to('/supplier');
    }


    public function edit($id = null)
    {
        $modelSupplier = new SupplierModel();
        $modelSupplierPJ = new SupplierPJModel();
        $modelSupplierAlamat = new SupplierAlamatModel();
        $modelSupplierLink = new SupplierLinkModel();
        $modelSupplierCs = new SupplierCsModel();
        $modelProvinsi = new ProvinsiModel();
        $modelUser = new UserModel();

        $supplier = $modelSupplier->getSuppliersWithAdmins($id);
        $pj = $modelSupplierPJ->getPJBySupplier($id);
        if ($pj) {
            $users = $modelUser->getUserPJWithKaryawanName(array_column($pj, 'id_user'));
        } else {
            $users = $modelUser->getAllUserWithKaryawanName();
        }
        $alamat = $modelSupplierAlamat->getAlamatBySupplier($id);
        $link = $modelSupplierLink->where(['id_supplier' => $id])->findAll();
        $customer_service = $modelSupplierCs->where(['id_supplier' => $id])->findAll();
        $provinsi = $modelProvinsi->orderBy('nama')->findAll();

        $data = [
            'validation' => \Config\Services::validation(),
            'supplier' => $supplier,
            'pj' => $pj,
            'alamat' => $alamat,
            'link' => $link,
            'customer_service' => $customer_service,
            'provinsi' => $provinsi,
            'users' => $users
        ];

        return view('supplier/edit', $data);
    }


    public function update($id = null)
    {
        $modelSupplier = new SupplierModel();
        $old_supplier = $modelSupplier->find($id);

        if ($old_supplier['nama'] == $this->request->getPost('nama')) {
            $rule_nama = 'required';
        } else {
            $rule_nama = 'required|is_unique[supplier.nama]';
        }

        if ($old_supplier['origin'] == $this->request->getPost('origin')) {
            $rule_origin = 'required';
        } else {
            $rule_origin = 'required|is_unique[supplier.origin]';
        }

        $validasi = [
            'origin' => [
                'rules' => $rule_origin,
                'errors' => [
                    'required' => '{field} supplier harus diisi.',
                    'is_unique' => 'origin supplier sudah ada dalam database.'
                ]
            ],
            'nama' => [
                'rules' => $rule_nama,
                'errors' => [
                    'required' => '{field} supplier harus diisi.',
                    'is_unique' => 'nama supplier sudah ada dalam database.'
                ]
            ],
            'pemilik' => [
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} supplier harus diisi.',
                ]
            ],
            'no_telp' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'No telepon supplier harus diisi.',
                ]
            ],
        ];

        if (!$this->validate($validasi)) {
            return redirect()->to('/supplier/' . $old_supplier["id"] . '/edit')->withInput();
        }

        $slug = url_title($this->request->getPost('nama'), '-', true);

        $data = [
            'id'        => $id,
            'origin'    => $this->request->getPost('origin'),
            'nama'      => $this->request->getPost('nama'),
            'slug'      => $slug,
            'pemilik'   => $this->request->getPost('pemilik'),
            'no_telp'   => $this->request->getPost('no_telp'),
            'status'    => $this->request->getPost('status'),
            'note'      => $this->request->getPost('note'),
        ];
        $modelSupplier->save($data);

        session()->setFlashdata('pesan', 'Data berhasil diedit.');

        return redirect()->to('/supplier');
    }


    public function delete($id = null)
    {
        $modelSupplier = new SupplierModel();

        $modelSupplier->delete($id);

        session()->setFlashdata('pesan', 'Data berhasil dihapus.');
        return redirect()->to('/supplier');
    }
}
