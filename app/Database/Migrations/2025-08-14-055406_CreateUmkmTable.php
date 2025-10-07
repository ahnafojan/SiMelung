<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUmkmTable extends Migration
{
    public function up()
    {
        // Mendefinisikan kolom-kolom untuk tabel 'umkm'
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true
            ],
            'nama_umkm' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'deskripsi' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            // Disesuaikan menjadi TEXT sesuai dengan struktur database di gambar
            'pemilik' => [
                'type' => 'TEXT', 
                'null' => true,
            ],
            'alamat' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'foto_umkm' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'is_published' => [
                'type'    => 'TINYINT',
                'constraint' => 1,
                'default' => 0, // Nilai default 0 (Not Null)
            ],
            'kontak' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            // Kolom created_at dan updated_at
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'gmaps_url' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'kategori' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
        ]);

        // Menetapkan 'id' sebagai primary key
        $this->forge->addKey('id', true);

        // Membuat tabel 'umkm'
        $this->forge->createTable('umkm');
    }

    public function down()
    {
        // Menghapus tabel 'umkm' jika migration di-rollback
        $this->forge->dropTable('umkm');
    }
}

