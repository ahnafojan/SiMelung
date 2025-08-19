<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PariwisataTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama'        => [
                'type'           => 'VARCHAR',
                'constraint'     => 255,
            ],
            'lokasi'      => [
                'type'           => 'VARCHAR',
                'constraint'     => 255,
            ],
            'deskripsi'   => [
                'type'           => 'TEXT',
                'null'           => true,
            ],
            'gambar'      => [
                'type'           => 'VARCHAR',
                'constraint'     => 255,
                'null'           => true,
            ],
            'created_at'  => [
                'type'           => 'DATETIME',
                'null'           => true,
                'default'        => null,
            ],
            'updated_at'  => [
                'type'           => 'DATETIME',
                'null'           => true,
                'default'        => null,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('pariwisata', true);
    }

    public function down()
    {
        $this->forge->dropTable('pariwisata', true);
    }
}
