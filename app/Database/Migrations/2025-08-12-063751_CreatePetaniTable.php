<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePetaniTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [           // Primary key auto increment
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [      // Custom ID like P001
                'type'           => 'VARCHAR',
                'constraint'     => '10',
                'unique'         => true,
            ],
            'nama' => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
            ],
            'alamat' => [
                'type'           => 'TEXT',
                'null'           => true,
            ],
            'no_hp' => [
                'type'           => 'VARCHAR',
                'constraint'     => '20',
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
        $this->forge->createTable('petani');
    }

    public function down()
    {
        $this->forge->dropTable('petani');
    }
}
