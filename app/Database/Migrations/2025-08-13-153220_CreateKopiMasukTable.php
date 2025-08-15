<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKopiMasukTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'petani_user_id' => [ // relasi ke petani.user_id
                'type'           => 'VARCHAR',
                'constraint'     => '10',
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
        $this->forge->addForeignKey('petani_user_id', 'petani', 'user_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('kopi_masuk');
    }

    public function down()
    {
        $this->forge->dropTable('kopi_masuk');
    }
}
