<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RuanganRak extends Migration
{
    public function up()
    {
        //Ruangan
        $fields = [
            'id'               => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_gudang'        => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'default' => 0],
            'nama'             => ['type' => 'varchar', 'constraint' => 255],
            'kode'             => ['type' => 'varchar', 'constraint' => 30],
        ];
        $this->forge->addField($fields);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_gudang', 'gudang', 'id', '', 'CASCADE');
        $this->forge->createTable('ruangan', true);

        //Rak
        $fields = [
            'id'               => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_gudang'        => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'default' => 0],
            'id_ruangan'       => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'nama'             => ['type' => 'varchar', 'constraint' => 255],
            'kode'             => ['type' => 'varchar', 'constraint' => 30],
        ];
        $this->forge->addField($fields);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_gudang', 'gudang', 'id', '', 'CASCADE');
        $this->forge->addForeignKey('id_ruangan', 'ruangan', 'id', '', 'CASCADE');
        $this->forge->createTable('rak', true);
    }

    public function down()
    {
        $this->forge->dropTable('ruangan');
        $this->forge->dropTable('rak');
    }
}
