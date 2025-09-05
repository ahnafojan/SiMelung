<?php

namespace App\Models;

use CodeIgniter\Model;

class AsetPariwisataModel extends Model
{
    protected $table            = 'aset_pariwisata';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'nama_pariwisata', // Diperbaiki: dari 'nama_aset' menjadi 'nama_pariwisata'
        'kode_aset',
        'nup',
        'tahun_perolehan',
        'nilai_perolehan',
        'keterangan',
        'metode_pengadaan',
        'sumber_pengadaan',
        'foto_aset'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    public function saveAsetAndRelation(array $asetData, int $wisataId)
    {
        $this->db->transStart();

        if (!$this->insert($asetData)) {
            $this->db->transRollback();
            return false;
        }

        $asetId = $this->getInsertID();

        $this->db->table('aset_wisata')->insert([
            'aset_id'   => $asetId,
            'wisata_id' => $wisataId
        ]);

        $this->db->transComplete();
        return $this->db->transStatus();
    }

    public function updateAsetAndRelation(int $asetId, array $asetData, int $newWisataId, int $oldWisataId)
    {
        $this->db->transStart();

        parent::update($asetId, $asetData);

        if ($newWisataId !== $oldWisataId) {
            $this->db->table('aset_wisata')
                ->where('aset_id', $asetId)
                ->update(['wisata_id' => $newWisataId]);
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
