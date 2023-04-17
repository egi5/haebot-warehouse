<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class LokasiProduk extends Migration
{
    public function up()
    {        //Rak
        $fields = [
            'id'               => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_produk'        => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'id_gudang'        => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'id_ruangan'       => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'id_rak'           => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'stok'             => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
        ];
        $this->forge->addField($fields);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_produk', 'produk', 'id', '', 'CASCADE');
        $this->forge->addForeignKey('id_gudang', 'gudang', 'id', '', 'CASCADE');
        $this->forge->addForeignKey('id_ruangan', 'ruangan', 'id', '', 'CASCADE');
        $this->forge->addForeignKey('id_rak', 'rak', 'id', '', 'CASCADE');
        $this->forge->createTable('lokasi_produk', true);
    }

    public function down()
    {
        $this->forge->dropTable('lokasi_produk');
    }
}
