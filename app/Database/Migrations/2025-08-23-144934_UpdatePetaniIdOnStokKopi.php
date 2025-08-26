<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdatePetaniIdOnStokKopi extends Migration
{
    public function up()
    {
        $fields = [
            'petani_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'null'       => false,
            ],
        ];

        $this->forge->modifyColumn('stok_kopi', $fields);
    }

    public function down()
    {
        // rollback ke INT jika diperlukan
        $fields = [
            'petani_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
        ];

        $this->forge->modifyColumn('stok_kopi', $fields);
    }
}
