<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddJenisPohonToKopiMasuk extends Migration
{
    public function up()
    {
        // Pastikan foreign key belum ada, lalu tambahkan
        $this->forge->addForeignKey('jenis_pohon_id', 'jenis_pohon', 'id', 'CASCADE', 'CASCADE');
        $this->forge->processIndexes('kopi_masuk'); //
    }

    public function down()
    {
        // Drop foreign key kalau rollback
        $this->db->query('ALTER TABLE kopi_masuk DROP FOREIGN KEY kopi_masuk_jenis_pohon_id_foreign');
    }
}
