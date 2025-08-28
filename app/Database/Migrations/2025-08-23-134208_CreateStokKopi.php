<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStokKopi extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'petani_id' => [
                'type'       => 'INT',
                'constraint' => 11,   // ✅ samakan dengan petani.id
                'unsigned'   => true,
            ],
            'jenis_pohon_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'stok' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['petani_id', 'jenis_pohon_id']); 

        // Relasi ke tabel petani
        $this->forge->addForeignKey('petani_id', 'petani', 'id', 'CASCADE', 'CASCADE');
        // Relasi ke tabel jenis_pohon
        $this->forge->addForeignKey('jenis_pohon_id', 'jenis_pohon', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('stok_kopi');
    }

    public function down()
    {
        $this->forge->dropTable('stok_kopi');
    }
}
