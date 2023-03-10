<?php

namespace App\Controllers;

use App\Models\SupplierCsModel;
use CodeIgniter\RESTful\ResourcePresenter;

class SupplierCs extends ResourcePresenter
{
    public function index()
    {
        //
    }


    public function show($id = null)
    {
        //
    }


    public function new()
    {
        //
    }


    public function create()
    {
        $modelSupplierCs = new SupplierCsModel();
        $id_supplier = $this->request->getPost('id_supplier');

        $data = [
            'id_supplier' => $this->request->getPost('id_supplier'),
            'nama' => $this->request->getPost('nama'),
            'no_telp' => $this->request->getPost('no_telp'),
        ];
        $modelSupplierCs->save($data);

        session()->setFlashdata('pesan', 'Cs Baru berhasil ditambahkan.');

        return redirect()->to('/supplier/' . $id_supplier . '/edit');
    }


    public function edit($id = null)
    {
        $modelSupplierCs = new SupplierCsModel();
        echo json_encode($modelSupplierCs->find($id));
    }


    public function update($id = null)
    {
        $modelSupplierCs = new SupplierCsModel();
        $id_supplier = $this->request->getPost('id_supplier');

        $data = [
            'id' => $id,
            'id_supplier' => $this->request->getPost('id_supplier'),
            'nama' => $this->request->getPost('nama'),
            'no_telp' => $this->request->getPost('no_telp'),
        ];
        $modelSupplierCs->save($data);

        session()->setFlashdata('pesan', 'Data CS berhasil diedit.');

        return redirect()->to('/supplier/' . $id_supplier . '/edit');
    }


    public function remove($id = null)
    {
        //
    }


    public function delete($id = null)
    {
        $id_supplier = $this->request->getPost('id_supplier');

        $modelSupplierCs = new SupplierCsModel();

        $modelSupplierCs->delete($id);

        session()->setFlashdata('pesan', 'Cs berhasil dihapus.');
        return redirect()->to('/supplier/' . $id_supplier . '/edit');
    }
}
