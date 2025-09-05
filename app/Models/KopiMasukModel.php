<?php

namespace App\Models;

use CodeIgniter\Model;

class KopiMasukModel extends Model
{
    protected $table            = 'kopi_masuk';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'petani_user_id',
        'petani_pohon_id',
        'jumlah',
        'tanggal',
        'keterangan'
    ];

    // Dates
    // Menggunakan preferensi Anda untuk useTimestamps
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

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
                petani.nama as nama_petani,
                jenis_pohon.nama_jenis as nama_pohon,
                stok_kopi.stok
            ')
            ->join('petani_pohon', 'petani_pohon.id = kopi_masuk.petani_pohon_id', 'left')
            // Menggunakan 'petani' sesuai kode Anda, bukan 'users as petani'
            ->join('petani', 'petani.user_id = kopi_masuk.petani_user_id', 'left')
            ->join('jenis_pohon', 'jenis_pohon.id = petani_pohon.jenis_pohon_id', 'left')
            ->join('stok_kopi', 'stok_kopi.petani_id = kopi_masuk.petani_user_id AND stok_kopi.jenis_pohon_id = petani_pohon.jenis_pohon_id', 'left')
            ->orderBy('kopi_masuk.tanggal', 'DESC')
            ->orderBy('kopi_masuk.id', 'DESC');

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
