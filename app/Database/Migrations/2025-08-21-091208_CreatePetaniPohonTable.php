<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePetaniPohonTable extends Migration
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
            'user_id' => [ // Relasi ke petani.user_id
                'type'       => 'VARCHAR',
                'constraint' => 10,
            ],
            'jenis_pohon_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'luas_lahan' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => true,
            ],
            'jumlah_pohon' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
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

        // Foreign key ke petani.user_id (VARCHAR)
        $this->forge->addForeignKey('user_id', 'petani', 'user_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('jenis_pohon_id', 'jenis_pohon', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('petani_pohon');
    }

    public function down()
    {
        $this->forge->dropTable('petani_pohon');
    }
}
