<?php

namespace App\Models;

use CodeIgniter\Model;

class KopiMasukModel extends Model
{
    protected $table            = 'kopi_masuk';
    protected $primaryKey       = 'id'; // Sesuai gambar, id adalah primary key
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'petani_user_id',      // Sesuai gambar
        'petani_pohon_id',     // Sesuai gambar
        'jumlah',              // Sesuai gambar
        'tanggal',             // Sesuai gambar (date)
        'keterangan',          // Sesuai gambar
        'harga_saat_transaksi', // Ditambahkan sesuai gambar
        'total_harga',         // Ditambahkan sesuai gambar
        // Jangan lupa field timestamps jika ingin otomatis diisi oleh CI
        'created_at',
        'updated_at'
    ];

    // Dates
    // Menggunakan preferensi Anda untuk useTimestamps
    protected $useTimestamps = true; // Aktifkan untuk created_at dan updated_at
    protected $dateFormat    = 'datetime'; // Format datetime sesuai gambar
    protected $createdField  = 'created_at'; // Nama kolom untuk created_at
    protected $updatedField  = 'updated_at'; // Nama kolom untuk updated_at
    // Karena tidak ada deleted_at di gambar, kita set ke null atau hapus saja
    protected $deletedField  = null; // Atau bisa dihapus jika tidak digunakan

    /**
     * Mengambil semua data kopi masuk dengan join ke tabel lain
     * dan mengaktifkan pagination.
     *
     * @param int $perPage Jumlah data yang ditampilkan per halaman.
     * @return array Data yang sudah dipaginasi.
     */
    public function getAllWithPagination($perPage)
    {
        // Builder untuk query join, disesuaikan dengan struktur join Anda
        $builder = $this->select('
                kopi_masuk.id,
                kopi_masuk.petani_user_id,
                kopi_masuk.petani_pohon_id,
                kopi_masuk.jumlah,
                kopi_masuk.tanggal,
                kopi_masuk.keterangan,
                kopi_masuk.harga_saat_transaksi, 
                kopi_masuk.total_harga,          
                petani.nama as nama_petani,
                jenis_pohon.nama_jenis as nama_pohon,
                stok_kopi.stok
            ')
            ->join('petani_pohon', 'petani_pohon.id = kopi_masuk.petani_pohon_id', 'left')
            ->join('petani', 'petani.user_id = kopi_masuk.petani_user_id', 'left')
            ->join('jenis_pohon', 'jenis_pohon.id = petani_pohon.jenis_pohon_id', 'left')
            ->join('stok_kopi', 'stok_kopi.petani_id = kopi_masuk.petani_user_id AND stok_kopi.jenis_pohon_id = petani_pohon.jenis_pohon_id', 'left')
            ->orderBy('kopi_masuk.tanggal', 'DESC') // Urutkan berdasarkan tanggal transaksi
            ->orderBy('kopi_masuk.id', 'DESC');     // Urutkan berdasarkan ID sebagai fallback

        // Panggil fungsi paginate dari builder
        return $builder->paginate($perPage);
    }

    /**
     * Mengambil semua data tanpa pagination, disesuaikan dengan kode Anda.
     */
    public function getAll()
    {
        return $this->select('
                kopi_masuk.id,
                kopi_masuk.petani_user_id,
                kopi_masuk.petani_pohon_id,
                kopi_masuk.jumlah,
                kopi_masuk.tanggal,
                kopi_masuk.keterangan,
                kopi_masuk.harga_saat_transaksi, 
                kopi_masuk.total_harga,          
                petani.nama as nama_petani,
                jenis_pohon.nama_jenis as nama_pohon,
                stok_kopi.stok as stok
            ')
            ->join('petani_pohon', 'petani_pohon.id = kopi_masuk.petani_pohon_id', 'left')
            ->join('petani', 'petani.user_id = kopi_masuk.petani_user_id', 'left')
            ->join('jenis_pohon', 'jenis_pohon.id = petani_pohon.jenis_pohon_id', 'left')
            ->join('stok_kopi', 'stok_kopi.petani_id = kopi_masuk.petani_user_id 
            AND stok_kopi.jenis_pohon_id = petani_pohon.jenis_pohon_id', 'left')
            ->orderBy('kopi_masuk.tanggal', 'DESC')
            ->orderBy('kopi_masuk.id', 'DESC')
            ->findAll();
    }
}
