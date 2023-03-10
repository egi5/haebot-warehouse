<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdukModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'produk';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_kategori', 'id_gudang', 'id_ruangan', 'id_rak', 'sku', 'hs_code', 'nama', 'slug', 'satuan',
        'tipe', 'jenis', 'hg_produk_penyusun', 'harga_beli', 'harga_jual', 'stok',
        'berat', 'panjang', 'lebar', 'tinggi', 'minimal_penjualan', 'kelipatan_penjualan',
        'status_marketing', 'note', 'created_at', 'updated_at', 'deleted_at'
    ];

    // Dates
    protected $useTimestamps = true;
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


    public function getProduk($id_produk)
    {
        $data =  $this->db->table($this->table)
            ->select('produk.*, produk_kategori.nama as kategori, gudang.nama as gudang')
            ->join('produk_kategori', 'produk.id_kategori = produk_kategori.id', 'left')
            ->join('gudang', 'produk.id_gudang = gudang.id', 'left')
            ->where('produk.id', $id_produk)
            ->get()
            ->getRowArray();

        return $data;
    }
}
