<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FullDatabaseKeuangan extends Migration
{
    public function up()
    {
        // =================================================================
        // Tabel Master (Tidak memiliki dependensi)
        // =================================================================

        // Tabel Master Pendapatan
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nama_pendapatan' => ['type' => 'VARCHAR', 'constraint' => 255],
            'deskripsi' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('master_pendapatan');

        // Tabel Master Kategori Pengeluaran
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nama_kategori' => ['type' => 'VARCHAR', 'constraint' => 255],
            'persentase' => ['type' => 'DECIMAL', 'constraint' => '5,2'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('master_kategori_pengeluaran');

        // Tabel Master Neraca
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nama_komponen' => ['type' => 'VARCHAR', 'constraint' => 255],
            'kategori' => [
                'type' => 'ENUM',
                'constraint' => ['aktiva_lancar', 'aktiva_tetap', 'hutang_lancar', 'hutang_jangka_panjang', 'modal'],
                'null' => false
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('master_neraca');

        // Tabel Master Laba Rugi
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nama_komponen' => ['type' => 'VARCHAR', 'constraint' => 255],
            'kategori' => [
                'type' => 'ENUM',
                'constraint' => ['pendapatan', 'biaya'],
                'null' => false
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('master_laba_rugi');

        // Tabel Master Perubahan Modal
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nama_komponen' => ['type' => 'VARCHAR', 'constraint' => '255'],
            'kategori' => ['type' => 'ENUM("penambahan", "pengurangan")', 'default' => 'penambahan'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('master_perubahan_modal');

        // Tabel Master Arus Kas
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nama_komponen' => ['type' => 'VARCHAR', 'constraint' => '255'],
            'kategori' => ['type' => 'ENUM', 'constraint' => ['masuk', 'keluar'], 'null' => false],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('master_arus_kas');


        // =================================================================
        // Tabel Transaksi Utama
        // =================================================================

        // Tabel Utama BKU Bulanan
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'bulan' => ['type' => 'INT', 'constraint' => 2],
            'tahun' => ['type' => 'INT', 'constraint' => 4],
            'total_pendapatan' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'total_pengeluaran' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'saldo_akhir' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['bulan', 'tahun']);
        $this->forge->createTable('bku_bulanan');

        // Menambahkan kolom ke bku_bulanan
        $bku_fields_update = [
            'saldo_bulan_lalu' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
                'default' => 0.00,
                'after' => 'tahun'
            ],
            'penghasilan_bulan_ini' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
                'default' => 0.00,
                'after' => 'saldo_bulan_lalu'
            ]
        ];
        $this->forge->addColumn('bku_bulanan', $bku_fields_update);

        // Tabel Laba Rugi Tahunan
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'tahun' => ['type' => 'YEAR', 'constraint' => '4'],
            'total_pendapatan' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'total_biaya' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'laba_rugi_bersih' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('laba_rugi_tahun');

        // Menambahkan kolom ke laba_rugi_tahun
        $lrt_fields_update = [
            'saldo_modal_akhir' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0.00,
                'after' => 'laba_rugi_bersih'
            ],
        ];
        $this->forge->addColumn('laba_rugi_tahun', $lrt_fields_update);

        // Tabel Rekap Arus Kas
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'tahun' => ['type' => 'YEAR', 'constraint' => 4, 'unique' => true],
            'total_kas_masuk' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'total_kas_keluar' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'saldo_akhir' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('rekap_arus_kas');


        // =================================================================
        // Tabel Detail (Memiliki dependensi/foreign key)
        // =================================================================

        // Tabel Detail Pendapatan
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'bku_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'master_pendapatan_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'jumlah' => ['type' => 'DECIMAL', 'constraint' => '15,2'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('bku_id', 'bku_bulanan', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('master_pendapatan_id', 'master_pendapatan', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('detail_pendapatan');

        // Tabel Detail Pengeluaran
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'bku_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'master_kategori_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'deskripsi_pengeluaran' => ['type' => 'VARCHAR', 'constraint' => 255],
            'jumlah' => ['type' => 'DECIMAL', 'constraint' => '15,2'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('bku_id', 'bku_bulanan', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('master_kategori_id', 'master_kategori_pengeluaran', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('detail_pengeluaran');

        // Tabel Detail Alokasi
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'bku_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'master_kategori_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'persentase_saat_itu' => ['type' => 'DECIMAL', 'constraint' => '5,2'],
            'jumlah_alokasi' => ['type' => 'DECIMAL', 'constraint' => '15,2'],
            'jumlah_realisasi' => ['type' => 'DECIMAL', 'constraint' => '15,2'],
            'sisa_alokasi' => ['type' => 'DECIMAL', 'constraint' => '15,2'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('bku_id', 'bku_bulanan', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('master_kategori_id', 'master_kategori_pengeluaran', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('detail_alokasi');

        // Tabel Detail Neraca
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'tahun' => ['type' => 'INT', 'constraint' => 4],
            'master_neraca_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'jumlah' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('master_neraca_id', 'master_neraca', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addUniqueKey(['tahun', 'master_neraca_id']);
        $this->forge->createTable('detail_neraca');

        // Tabel Detail Laba Rugi
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'tahun' => ['type' => 'INT', 'constraint' => 4],
            'master_laba_rugi_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'jumlah' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('master_laba_rugi_id', 'master_laba_rugi', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addUniqueKey(['tahun', 'master_laba_rugi_id']);
        $this->forge->createTable('detail_laba_rugi');

        // Tabel Detail Perubahan Modal
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'tahun' => ['type' => 'YEAR', 'constraint' => '4'],
            'master_perubahan_modal_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'jumlah' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('master_perubahan_modal_id', 'master_perubahan_modal', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('detail_perubahan_modal');

        // Tabel Detail Arus Kas
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'tahun' => ['type' => 'YEAR', 'constraint' => '4'],
            'master_arus_kas_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'jumlah' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('master_arus_kas_id', 'master_arus_kas', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('detail_arus_kas');


        // =================================================================
        // Tabel Lainnya (Contoh: Log)
        // =================================================================

        // Tabel Log Aktivitas
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'username' => ['type' => 'VARCHAR', 'constraint' => 100, 'comment' => 'User yang melakukan aksi'],
            'aktivitas' => ['type' => 'VARCHAR', 'constraint' => 50, 'comment' => 'Jenis aksi: MEMBUAT, MENGUPDATE, MENGHAPUS'],
            'deskripsi' => ['type' => 'TEXT', 'comment' => 'Deskripsi detail dari aktivitas'],
            'bku_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true, 'comment' => 'ID BKU Bulanan terkait'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('log_aktivitas');
    }

    public function down()
    {
        // Urutan penghapusan dibalik dari urutan pembuatan untuk menghindari error foreign key.
        // Hapus tabel-tabel yang memiliki foreign key terlebih dahulu.

        $this->forge->dropTable('log_aktivitas', true);
        $this->forge->dropTable('detail_arus_kas', true);
        $this->forge->dropTable('detail_perubahan_modal', true);
        $this->forge->dropTable('detail_laba_rugi', true);
        $this->forge->dropTable('detail_neraca', true);
        $this->forge->dropTable('detail_alokasi', true);
        $this->forge->dropTable('detail_pengeluaran', true);
        $this->forge->dropTable('detail_pendapatan', true);

        // Hapus tabel-tabel utama/transaksi
        $this->forge->dropTable('rekap_arus_kas', true);
        $this->forge->dropTable('laba_rugi_tahun', true);
        $this->forge->dropTable('bku_bulanan', true);

        // Hapus tabel-tabel master
        $this->forge->dropTable('master_arus_kas', true);
        $this->forge->dropTable('master_perubahan_modal', true);
        $this->forge->dropTable('master_laba_rugi', true);
        $this->forge->dropTable('master_neraca', true);
        $this->forge->dropTable('master_kategori_pengeluaran', true);
        $this->forge->dropTable('master_pendapatan', true);
    }
}
