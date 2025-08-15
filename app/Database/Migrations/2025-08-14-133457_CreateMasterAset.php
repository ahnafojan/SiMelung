<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMasterAset extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_aset' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true
            ],
            'nama_aset' => [
                'type'       => 'VARCHAR',
                'constraint' => '255'
            ],
            'kode_aset' => [
                'type'       => 'VARCHAR',
                'constraint' => '100'
            ],
            'nup' => [
                'type'       => 'VARCHAR',
                'constraint' => '50'
            ],
            'tahun_perolehan' => [
                'type'       => 'YEAR',
                'null'       => true
            ],
            'merk_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true
            ],
            'nilai_perolehan' => [
                'type'       => 'BIGINT',
                'null'       => true
            ],
            'keterangan' => [
                'type'       => 'TEXT',
                'null'       => true
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ]);
        $this->forge->addKey('id_aset', true);
        $this->forge->createTable('master_aset');
    }

    public function down()
    {
        $this->forge->dropTable('master_aset');
    }
}
