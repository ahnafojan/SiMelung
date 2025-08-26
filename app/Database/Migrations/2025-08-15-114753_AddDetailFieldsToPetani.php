<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDetailFieldsToPetani extends Migration
{
    public function up()
    {
        $this->forge->addColumn('petani', [
            'usia' => [
                'type'       => 'INT',
                'constraint' => 3,
                'null'       => true,
                'after'      => 'nama',
            ],
            'tempat_lahir' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'after'      => 'usia',
            ],
            'tanggal_lahir' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'tempat_lahir',
            ],
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
            'foto' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'jenis_pohon',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('petani', [
            'usia',
            'tempat_lahir',
            'tanggal_lahir',
            'luas_lahan_kopi',
            'jumlah_pohon_kopi',
            'jenis_pohon',
            'foto'
        ]);
    }
}
