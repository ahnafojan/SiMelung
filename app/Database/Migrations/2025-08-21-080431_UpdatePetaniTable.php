<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdatePetaniTable extends Migration
{
    public function up()
    {
        $this->forge->dropColumn('petani', [
            'jenis_pohon',
            'jumlah_pohon_kopi',
            'luas_lahan_kopi',
        ]);
    }

    public function down()
    {
        $this->forge->addColumn('petani', [
            'luas_lahan_kopi' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => true,
                'after'      => 'no_hp',
            ],
            'jumlah_pohon_kopi' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
                'after'      => 'luas_lahan_kopi',
            ],
            'jenis_pohon' => [
                'type'       => 'ENUM',
                'constraint' => ['Javanica', 'Robusta', 'Lainnya'],
                'null'       => true,
                'after'      => 'jumlah_pohon_kopi',
            ],
        ]);
    }
}
