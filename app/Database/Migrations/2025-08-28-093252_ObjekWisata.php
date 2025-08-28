<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAsetWisata extends Migration
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
            'aset_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'wisata_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
        ]);

        $this->forge->addKey('id', true); // primary key
        $this->forge->addForeignKey('aset_id', 'aset_pariwisata', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('wisata_id', 'wisata', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('aset_wisata');
    }

    public function down()
    {
        $this->forge->dropTable('aset_wisata');
    }
}
