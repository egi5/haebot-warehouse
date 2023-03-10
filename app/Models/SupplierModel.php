<?php

namespace App\Models;

use CodeIgniter\Model;

class SupplierModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'supplier';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'origin', 'nama', 'slug', 'pemilik', 'no_telp', 'saldo', 'status', 'note',
        'created_at', 'updated_at', 'deleted_at',
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


    public function getSuppliers()
    {
        $data =  $this->db->table($this->table)
            ->select('supplier.*, GROUP_CONCAT(karyawan.nama_lengkap SEPARATOR ",<br> ") as admin, GROUP_CONCAT(users.id SEPARATOR ", ") as id_admin')
            ->join('supplier_penanggungjawab', 'supplier.id = supplier_penanggungjawab.id_supplier', 'left')
            ->join('users', 'supplier_penanggungjawab.id_user = users.id', 'left')
            ->join('karyawan', 'users.id_karyawan = karyawan.id', 'left')
            ->where('supplier.deleted_at', null)
            ->groupBy('supplier.id')
            ->orderBy('supplier.id', 'desc')
            ->get()
            ->getResultArray();

        return $data;
    }


    public function getSuppliersWithAdmins($id_supplier)
    {
        $data =  $this->db->table($this->table)
            ->select('supplier.*, GROUP_CONCAT(supplier_penanggungjawab.id_user SEPARATOR ", ") as id_admin')
            ->join('supplier_penanggungjawab', 'supplier.id = supplier_penanggungjawab.id_supplier', 'left')
            ->where('supplier.id', $id_supplier)
            ->groupBy('supplier.id')
            ->get()
            ->getRowArray();

        return $data;
    }
}
