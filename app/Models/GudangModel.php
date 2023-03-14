<?php

namespace App\Models;

use CodeIgniter\Model;

class GudangModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'gudang';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama', 'id_provinsi', 'id_kota', 'id_kecamatan', 'id_kelurahan',
        'detail_alamat', 'no_telp', 'keterangan', 'created_at', 'updated_at', 'deleted_at',
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


    public function getGudang()
    {
        $data =  $this->db->table($this->table)
            ->select('gudang.*, GROUP_CONCAT(karyawan.nama_lengkap SEPARATOR ",<br> ") as pj, GROUP_CONCAT(users.id SEPARATOR ", ") as id_pj')
            ->join('gudang_penanggungjawab', 'gudang.id = gudang_penanggungjawab.id_gudang', 'left')
            ->join('users', 'gudang_penanggungjawab.id_user = users.id', 'left')
            ->join('karyawan', 'users.id_karyawan = karyawan.id', 'left')
            ->where('gudang.deleted_at', null)
            ->groupBy('gudang.id')
            ->orderBy('gudang.id', 'desc')
            ->get()
            ->getResultArray();

        return $data;
    }


    public function getGudangWithPJ($id_gudang)
    {
        $data =  $this->db->table($this->table)
            ->select('gudang.*, GROUP_CONCAT(gudang_penanggungjawab.id_user SEPARATOR ", ") as id_pj')
            ->join('gudang_penanggungjawab', 'gudang.id = gudang_penanggungjawab.id_gudang', 'left')
            ->where('gudang.id', $id_gudang)
            ->groupBy('gudang.id')
            ->get()
            ->getRowArray();

        return $data;
    }


    public function getGudangWithAlamat($id_gudang)
    {
        $data =  $this->db->table($this->table)
            ->select('gudang.*, provinsi.nama as provinsi, kota.nama as kota, kecamatan.nama as kecamatan, kelurahan.nama as kelurahan')
            ->join('provinsi', 'gudang.id_provinsi = provinsi.id', 'left')
            ->join('kota', 'gudang.id_kota = kota.id', 'left')
            ->join('kecamatan', 'gudang.id_kecamatan = kecamatan.id', 'left')
            ->join('kelurahan', 'gudang.id_kelurahan = kelurahan.id', 'left')
            ->where('gudang.id', $id_gudang)
            ->get()
            ->getRowArray();

        return $data;
    }
}
