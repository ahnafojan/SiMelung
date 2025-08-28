<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddJenisPohonToKopiMasuk extends Migration
{
    public function up()
    {
        // Tambah kolom jenis_pohon_id di tabel kopi_masuk
        $fields = [
            'jenis_pohon_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'id', // opsional
            ],
        ];
        $this->forge->addColumn('kopi_masuk', $fields);

        // Tambahkan foreign key
        $this->db->query('ALTER TABLE kopi_masuk 
            ADD CONSTRAINT kopi_masuk_jenis_pohon_id_foreign 
            FOREIGN KEY (jenis_pohon_id) REFERENCES jenis_pohon(id) 
            ON DELETE CASCADE ON UPDATE CASCADE');
    }

    public function down()
    {
        // Hapus foreign key
        $this->db->query('ALTER TABLE kopi_masuk 
            DROP FOREIGN KEY kopi_masuk_jenis_pohon_id_foreign');

        // Hapus kolom
        $this->forge->dropColumn('kopi_masuk', 'jenis_pohon_id');
    }
}
