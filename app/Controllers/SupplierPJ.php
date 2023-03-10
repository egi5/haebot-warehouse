<?php

namespace App\Controllers;

use App\Models\SupplierPJModel;
use CodeIgniter\RESTful\ResourcePresenter;
use Myth\Auth\Models\PermissionModel;

class SupplierPJ extends ResourcePresenter
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
        $modelSupplierPJ = new SupplierPJModel();
        $id_supplier = $this->request->getPost('id_supplier');

        $jml_admin = $modelSupplierPJ->where(['id_supplier' => $id_supplier])->countAllResults();

        $data = [
            'id_supplier' => $this->request->getPost('id_supplier'),
            'id_user' => $this->request->getPost('id_user'),
            'urutan' => $jml_admin + 1,
        ];
        $modelSupplierPJ->save($data);

        $modelPermissions = new PermissionModel();
        $modelPermissions->addPermissionToUser(11, intval($this->request->getPost('id_user')));

        session()->setFlashdata('pesan', 'Admin Supplier berhasil ditambahkan.');

        return redirect()->to('/supplier/' . $id_supplier . '/edit');
    }


    public function edit($id = null)
    {
        $modelSupplierPJ = new SupplierPJModel();
        echo json_encode($modelSupplierPJ->find($id));
    }


    public function update($id = null)
    {
        $modelSupplierPJ = new SupplierPJModel();
        $id_supplier = $this->request->getPost('id_supplier');

        $data = [
            'id'            => $id,
            'urutan'        => $this->request->getPost('edit-urutan'),
        ];
        $modelSupplierPJ->save($data);

        session()->setFlashdata('pesan', 'Admin Supplier berhasil diedit.');

        return redirect()->to('/supplier/' . $id_supplier . '/edit');
    }


    public function remove($id = null)
    {
        //
    }


    public function delete($id = null)
    {
        $modelSupplierPJ = new SupplierPJModel();
        $id_supplier = $this->request->getPost('id_supplier');
        $pj = $modelSupplierPJ->find($id);
        $urutan_pj = $pj['urutan'];

        $pj_bawahnya = $modelSupplierPJ->where(['id_supplier' => $id_supplier, 'urutan >' => $urutan_pj])->findAll();

        foreach ($pj_bawahnya as $pjb) {
            $modelSupplierPJ->save([
                'id'        => $pjb['id'],
                'urutan'    => $pjb['urutan'] - 1,
            ]);
        }

        $modelSupplierPJ->delete($id);

        $modelPermissions = new PermissionModel();
        $modelPermissions->removePermissionFromUser(11, intval($pj['id_user']));

        session()->setFlashdata('pesan', 'Admin Supplier berhasil dihapus.');
        return redirect()->to('/supplier/' . $id_supplier . '/edit');
    }
}
