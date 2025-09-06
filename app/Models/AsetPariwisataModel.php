<?php

namespace App\Models;

use CodeIgniter\Model;

class AsetPariwisataModel extends Model
{
    protected $table            = 'aset_pariwisata';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    // ====================================================================
    // PERUBAHAN PALING PENTING ADA DI SINI
    // Menonaktifkan fitur Soft Deletes yang kemungkinan menyebabkan query gagal.
    // ====================================================================
    protected $useSoftDeletes   = false;
    // ====================================================================

    // Pastikan semua kolom yang ingin diisi melalui form ada di sini.
    protected $allowedFields    = [
        'nama_pariwisata',
        'nama_aset', // Kolom yang sebenarnya digunakan
        'kode_aset',
        'nup',
        'tahun_perolehan',
        'nilai_perolehan',
        'keterangan',
        'metode_pengadaan',
        'sumber_pengadaan',
        'foto_aset'
    ];

    // Fitur Timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Anda tidak perlu mendefinisikan koneksi DB di sini jika sudah
    // diatur di file .env, jadi bagian ini bisa di-nonaktifkan atau dihapus
    // untuk menjaga kode tetap bersih.
    /*
    protected $db;
    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }
    */

    public function saveAsetAndRelation(array $asetData, int $wisataId)
    {
        $this->db->transStart();

        $asetId = $this->insert($asetData, true); // Dapatkan ID setelah insert

        if ($asetId) {
            $this->db->table('aset_wisata')->insert([
                'aset_id'   => $asetId,
                'wisata_id' => $wisataId
            ]);
        } else {
            $this->db->transRollback();
            return false;
        }

        $this->db->transComplete();
        return $this->db->transStatus();
    }

    public function updateAsetAndRelation(int $asetId, array $asetData, int $newWisataId)
    {
        $this->db->transStart();

        parent::update($asetId, $asetData);

        // Cek dulu apakah relasi sudah ada atau belum
        $relationExists = $this->db->table('aset_wisata')->where('aset_id', $asetId)->get()->getRow();

        if ($relationExists) {
            $this->db->table('aset_wisata')
                ->where('aset_id', $asetId)
                ->update(['wisata_id' => $newWisataId]);
        } else {
            $this->db->table('aset_wisata')->insert([
                'aset_id'   => $asetId,
                'wisata_id' => $newWisataId
            ]);
        }

        $this->db->transComplete();
        return $this->db->transStatus();
    }

    public function deleteAsetAndRelation(int $asetId)
    {
        $this->db->transStart();

        $this->db->table('aset_wisata')->where('aset_id', $asetId)->delete();
        parent::delete($asetId);

        $this->db->transComplete();
        return $this->db->transStatus();
    }
}
