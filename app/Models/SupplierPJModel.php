<?php

namespace App\Models;

use CodeIgniter\Model;

class SupplierPJModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'supplier_penanggungjawab';
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


    public function getPJBySupplier($id_supplier)
    {
        $data =  $this->db->table($this->table)
            ->select('supplier_penanggungjawab.*, karyawan.nama_lengkap as nama_pj')
            ->join('users', 'supplier_penanggungjawab.id_user = users.id', 'left')
            ->join('karyawan', 'users.id_karyawan = karyawan.id', 'left')
            ->where('supplier_penanggungjawab.id_supplier', $id_supplier)
            ->get()
            ->getResultArray();

        return $data;
    }
}
