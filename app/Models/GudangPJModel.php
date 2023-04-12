<?php

namespace App\Models;

use CodeIgniter\Model;

class GudangPJModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'gudang_penanggungjawab';
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


    public function getPJByGudang($id_gudang)
    {
        $data =  $this->db->table($this->table)
            ->select('gudang_penanggungjawab.*, karyawan.nama_lengkap as nama_pj')
            ->join('users', 'gudang_penanggungjawab.id_user = users.id', 'left')
            ->join('karyawan', 'users.id_karyawan = karyawan.id', 'left')
            ->where('gudang_penanggungjawab.id_gudang', $id_gudang)
            ->get()
            ->getResultArray();

        return $data;
    }


    public function getGudangByPJ($id_user)
    {
        $data =  $this->db->table($this->table)
            ->select('gudang_penanggungjawab.*, gudang.nama as nama_gudang')
            ->join('gudang', 'gudang_penanggungjawab.id_gudang = gudang.id', 'left')
            ->where('gudang_penanggungjawab.id_user', $id_user)
            ->get()
            ->getRowArray();

        return $data;
    }
}
