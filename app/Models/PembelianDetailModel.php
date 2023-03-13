<?php

namespace App\Models;

use CodeIgniter\Model;

class PembelianDetailModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'pembelian_detail';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_pembelian',
        'id_produk',
        'qty',
        'harga_satuan',
        'total_harga'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    function getListProdukPembelian($id_pembelian)
    {
        $data =  $this->db->table($this->table)
            ->select('pembelian_detail.*, produk.nama as produk, produk.sku as sku')
            ->join('produk', 'pembelian_detail.id_produk = produk.id', 'left')
            ->where('pembelian_detail.id_pembelian', $id_pembelian)
            ->get()
            ->getResultArray();

        return $data;
    }

    function sumTotalHargaProduk($id_pembelian)
    {
        $data =  $this->db->table($this->table)
            ->selectSum('total_harga')
            ->where('id_pembelian', $id_pembelian)
            ->get()
            ->getRowArray();

        return $data;
    }
}
