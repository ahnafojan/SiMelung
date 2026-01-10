<?php

namespace App\Models;

use CodeIgniter\Model;

class HargaJenisKopiModel extends Model
{
    protected $table            = 'harga_jenis_kopi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['jenis_pohon_id', 'harga_beli_per_kg', 'harga_jual_per_kg', 'tanggal_berlaku'];

    // Aktifkan timestamps hanya untuk created_at
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getLatestPrice($jenisPohonId, $date = null)
    {
        if ($date === null) {
            $date = date('Y-m-d');
        }

        return $this->select('id, jenis_pohon_id, harga_beli_per_kg, harga_jual_per_kg, tanggal_berlaku') // Pastikan 'id' disertakan
            ->where('jenis_pohon_id', $jenisPohonId)
            ->where('tanggal_berlaku <=', $date)
            ->orderBy('tanggal_berlaku', 'DESC')
            ->orderBy('id', 'DESC') // Jika ada beberapa entri dengan tanggal yang sama, ambil yang terbaru
            ->first();
    }

    // Method untuk mendapatkan semua histori harga untuk satu jenis pohon
    public function getHistoryByJenisPohonId($jenisPohonId, $limit = 10, $offset = 0)
    {
        return $this->where('jenis_pohon_id', $jenisPohonId)
            ->orderBy('tanggal_berlaku', 'DESC') // Urutkan dari terbaru
            ->orderBy('id', 'DESC') // Jika ada beberapa entri dengan tanggal yang sama
            ->findAll($limit, $offset);
    }

    // Method untuk mendapatkan entri harga spesifik berdasarkan ID
    public function getPriceById($id)
    {
        return $this->find($id);
    }

    // Method untuk memperbarui entri histori harga (opsional)
    // Jika logika bisnis Anda adalah menimpa entri histori lama
    public function updateHarga($id, $data)
    {
        // Validasi $data sebelum update jika diperlukan
        return $this->update($id, $data);
    }

    // Method untuk menghapus entri histori harga (opsional)
    public function deleteHarga($id)
    {
        // Pastikan $id valid dan milik jenis pohon yang sesuai sebelum menghapus
        // Jika menggunakan soft delete, gunakan $this->delete($id);
        // Jika hard delete, gunakan $this->db->table($this->table)->where('id', $id)->delete();
        return $this->delete($id);
    }

    // Method untuk mendapatkan entri harga terbaru untuk semua jenis pohon
    // Digunakan di controller JenisPohon untuk tabel utama
    public function getLatestPricesForAllJenis()
    {
        // Gunakan subquery untuk mendapatkan tanggal_berlaku terbaru per jenis_pohon_id
        $subQuery = $this->db->table('harga_jenis_kopi')
            ->select('jenis_pohon_id, MAX(tanggal_berlaku) as latest_date')
            ->groupBy('jenis_pohon_id')
            ->getCompiledSelect();

        // Gabungkan hasil subquery dengan tabel harga_jenis_kopi
        $builder = $this->db->table('harga_jenis_kopi hjk')
            ->join("($subQuery) sq", 'hjk.jenis_pohon_id = sq.jenis_pohon_id AND hjk.tanggal_berlaku = sq.latest_date', 'inner');

        return $builder->select('hjk.jenis_pohon_id, hjk.harga_beli_per_kg, hjk.harga_jual_per_kg')->get()->getResultArray();
    }
}
