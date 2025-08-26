<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPengadaanDanFotoToMasterAset extends Migration
{
    public function up()
    {
        $this->forge->addColumn('master_aset', [
            'metode_pengadaan' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
                'after'      => 'keterangan'
            ],
            'sumber_pengadaan' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'after'      => 'metode_pengadaan'
            ],
            'foto' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'after'      => 'sumber_pengadaan'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('master_aset', ['metode_pengadaan', 'sumber_pengadaan', 'foto']);
    }
}
