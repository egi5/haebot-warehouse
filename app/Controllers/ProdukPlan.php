<?php

namespace App\Controllers;

use App\Models\ProdukPlanModel;
use CodeIgniter\RESTful\ResourcePresenter;

class ProdukPlan extends ResourcePresenter
{
    /**
     * Present a view of resource objects
     *
     * @return mixed
     */
    public function index()
    {
        //
    }

    /**
     * Present a view to present a specific resource object
     *
     * @param mixed $id
     *
     * @return mixed
     */
    public function show($id = null)
    {
        //
    }

    /**
     * Present a view to present a new single resource object
     *
     * @return mixed
     */
    public function new()
    {
        //
    }

    /**
     * Process the creation/insertion of a new resource object.
     * This should be a POST.
     *
     * @return mixed
     */
    public function create()
    {
        $id_produk_redirect = $this->request->getPost('id_produk_redirect');
        $modelProdukPlan = new ProdukPlanModel();

        $data = [
            'id_produk_jadi' => $this->request->getPost('id_produk_jadi'),
            'id_produk_bahan' => $this->request->getPost('id_produk_bahan'),
            'qty_bahan' => $this->request->getPost('qty_bahan'),
        ];

        $modelProdukPlan->save($data);

        session()->setFlashdata('pesan', 'Produk penyusun/pecahan berhasil ditambahkan.');

        return redirect()->to('/produk/' . $id_produk_redirect . '/edit');
    }

    /**
     * Present a view to edit the properties of a specific resource object
     *
     * @param mixed $id
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        //
    }

    /**
     * Process the updating, full or partial, of a specific resource object.
     * This should be a POST.
     *
     * @param mixed $id
     *
     * @return mixed
     */
    public function update($id = null)
    {
        //
    }

    /**
     * Present a view to confirm the deletion of a specific resource object
     *
     * @param mixed $id
     *
     * @return mixed
     */
    public function remove($id = null)
    {
        //
    }

    /**
     * Process the deletion of a specific resource object
     *
     * @param mixed $id
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        //
    }
}
