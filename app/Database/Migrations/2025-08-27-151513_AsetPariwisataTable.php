<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AsetPariwisata extends Migration
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
            'no' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_pariwisata' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'nama_aset' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'kode_aset' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'nup' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'tahun_perolehan' => [
                'type' => 'YEAR',
            ],
            'nilai_perolehan' => [
                'type' => 'BIGINT',
            ],
            'keterangan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'metode_pengadaan' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'sumber_pengadaan' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
            ],
            'foto_aset' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        // id sebagai primary key
        $this->forge->addKey('id', true);
        // no sebagai auto_increment tambahan (opsional, bisa dipakai untuk nomor urut di view)
        $this->forge->addKey('no');
        $this->forge->createTable('aset_pariwisata');
    }

    public function down()
    {
        $this->forge->dropTable('aset_pariwisata');
    }
}
