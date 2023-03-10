<?php

namespace App\Models;

use CodeIgniter\Model;

class SupplierAlamatModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'supplier_alamat';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = false;
    protected $allowedFields    = [];

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

    public function getAlamatBySupplier($id_supplier)
    {
        $data =  $this->db->table($this->table)
            ->select('supplier_alamat.*, provinsi.nama as provinsi, kota.nama as kota, kecamatan.nama as kecamatan, kelurahan.nama as kelurahan')
            ->join('provinsi', 'supplier_alamat.id_provinsi = provinsi.id', 'left')
            ->join('kota', 'supplier_alamat.id_kota = kota.id', 'left')
            ->join('kecamatan', 'supplier_alamat.id_kecamatan = kecamatan.id', 'left')
            ->join('kelurahan', 'supplier_alamat.id_kelurahan = kelurahan.id', 'left')
            ->where('supplier_alamat.id_supplier', $id_supplier)
            ->get()
            ->getResultArray();

        return $data;
    }
}
