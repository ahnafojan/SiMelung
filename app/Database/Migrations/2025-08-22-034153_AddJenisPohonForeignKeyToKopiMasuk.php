<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddJenisPohonForeignKeyToKopiMasuk extends Migration
{
    public function up()
    {
        // Ubah kolom jenis_pohon_id jadi INT(10) UNSIGNED
        $this->forge->modifyColumn('kopi_masuk', [
            'jenis_pohon_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
        ]);

        // Tambah foreign key ke tabel jenis_pohon(id)
        $this->db->query('
            ALTER TABLE kopi_masuk
            ADD CONSTRAINT fk_kopi_masuk_jenis_pohon
            FOREIGN KEY (jenis_pohon_id) REFERENCES jenis_pohon(id)
            ON DELETE CASCADE ON UPDATE CASCADE
        ');
    }

    public function down()
    {
        // Hapus foreign key
        $this->db->query('ALTER TABLE kopi_masuk DROP FOREIGN KEY fk_kopi_masuk_jenis_pohon');
    }
}
