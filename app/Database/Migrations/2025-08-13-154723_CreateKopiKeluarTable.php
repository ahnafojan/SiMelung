<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKopiKeluarTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'tujuan' => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'jumlah' => [
                'type'           => 'DECIMAL',
                'constraint'     => '10,2',
            ],
            'tanggal' => [
                'type'           => 'DATE',
            ],
            'keterangan' => [
                'type'           => 'TEXT',
                'null'           => true,
            ],
            'created_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
            ],
            'updated_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('kopi_keluar');
    }

    public function down()
    {
        $this->forge->dropTable('kopi_keluar');
    }
}
