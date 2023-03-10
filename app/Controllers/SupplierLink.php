<?php

namespace App\Controllers;

use App\Models\SupplierLinkModel;
use CodeIgniter\RESTful\ResourcePresenter;

class SupplierLink extends ResourcePresenter
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
        $modelSupplierLink = new SupplierLinkModel();
        $id_supplier = $this->request->getPost('id_supplier');

        $data = [
            'id_supplier' => $this->request->getPost('id_supplier'),
            'nama' => $this->request->getPost('nama'),
            'link' => $this->request->getPost('link'),
        ];
        $modelSupplierLink->save($data);

        session()->setFlashdata('pesan', 'Link Baru berhasil ditambahkan.');

        return redirect()->to('/supplier/' . $id_supplier . '/edit');
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


    public function delete($id = null)
    {
        $id_supplier = $this->request->getPost('id_supplier');

        $modelSupplierLink = new SupplierLinkModel();

        $modelSupplierLink->delete($id);

        session()->setFlashdata('pesan', 'Link berhasil dihapus.');
        return redirect()->to('/supplier/' . $id_supplier . '/edit');
    }
}
