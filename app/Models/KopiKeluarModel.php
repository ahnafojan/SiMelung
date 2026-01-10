<?php

namespace App\Models;

use CodeIgniter\Model;

class KopiKeluarModel extends Model
{
    protected $table = 'kopi_keluar';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'stok_kopi_id',
        'tujuan',
        'jumlah',
        'tanggal',
        'keterangan',
        'harga_saat_transaksi',
        'total_harga_jual'
    ];
    protected $useTimestamps = true;
    protected $returnType = 'array';

    /**
     * Mengambil semua data kopi keluar dengan relasi dan pagination.
     *
     * @param int $perPage Jumlah data per halaman.
     * @return array
     */
    public function getAllWithPagination($perPage = 10)
    {
        return $this->select('
            kopi_keluar.*, 
            jenis_pohon.nama_jenis as nama_pohon, 
            stok_kopi.stok as sisa_stok_jenis,
            petani.nama as nama_petani
        ')
            ->join('stok_kopi', 'kopi_keluar.stok_kopi_id = stok_kopi.id', 'left')
            ->join('jenis_pohon', 'jenis_pohon.id = stok_kopi.jenis_pohon_id', 'left')
            ->join('petani', 'petani.user_id = stok_kopi.petani_id', 'left')
            ->orderBy('kopi_keluar.tanggal', 'DESC')
            ->orderBy('kopi_keluar.id', 'DESC')
            ->paginate($perPage, 'kopikeluar');
    }

    /**
     * Mengambil data transaksi kopi keluar dengan filter tanggal dan pagination.
     * Menggunakan pendekatan CodeIgniter native pagination untuk robustness.
     *
     * @param string $tanggalAwal
     * @param string $tanggalAkhir
     * @param int    $perPage
     * @return array
     */
    public function getTransaksiWithPagination($tanggalAwal, $tanggalAkhir, $perPage = 10)
    {
        // Gunakan query builder dari model dengan pagination native
        $data = $this->select('
                kopi_keluar.id,
                kopi_keluar.stok_kopi_id,
                kopi_keluar.tujuan,
                kopi_keluar.jumlah,
                kopi_keluar.tanggal,
                kopi_keluar.harga_saat_transaksi as harga_jual_per_kg,
                kopi_keluar.total_harga_jual,
                stok_kopi.jenis_pohon_id,
                jenis_pohon.nama_jenis as nama_jenis_pohon
            ')
            ->join('stok_kopi', 'kopi_keluar.stok_kopi_id = stok_kopi.id', 'left')
            ->join('jenis_pohon', 'stok_kopi.jenis_pohon_id = jenis_pohon.id', 'left')
            ->where('kopi_keluar.tanggal >=', $tanggalAwal)
            ->where('kopi_keluar.tanggal <=', $tanggalAkhir)
            ->orderBy('kopi_keluar.tanggal', 'DESC')
            ->orderBy('kopi_keluar.id', 'DESC')
            ->paginate($perPage, 'pendapatan'); // Gunakan grup 'pendapatan'

        return $data;
    }

    /**
     * Mengambil data transaksi untuk perhitungan tanpa pagination.
     * Digunakan untuk summary dan chart.
     *
     * @param string $tanggalAwal
     * @param string $tanggalAkhir
     * @return array
     */
    public function getTransaksiForCalculation($tanggalAwal, $tanggalAkhir)
    {
        return $this->select('
                kopi_keluar.id,
                kopi_keluar.stok_kopi_id,
                 kopi_keluar.tujuan,
                kopi_keluar.jumlah,
                kopi_keluar.tanggal,
                 kopi_keluar.harga_saat_transaksi as harga_jual_per_kg,
                kopi_keluar.total_harga_jual,
                stok_kopi.jenis_pohon_id,
                jenis_pohon.nama_jenis as nama_jenis_pohon 
            ')
            ->join('stok_kopi', 'kopi_keluar.stok_kopi_id = stok_kopi.id', 'left')
            ->join('jenis_pohon', 'stok_kopi.jenis_pohon_id = jenis_pohon.id', 'left')
            ->where('kopi_keluar.tanggal >=', $tanggalAwal)
            ->where('kopi_keluar.tanggal <=', $tanggalAkhir)
            ->groupBy('kopi_keluar.id')
            ->orderBy('kopi_keluar.tanggal', 'ASC')
            ->orderBy('kopi_keluar.id', 'ASC')
            ->findAll();
    }

    /**
     * Mendapatkan harga beli per kg berdasarkan jenis_pohon_id dan tanggal transaksi.
     * Method helper untuk menghindari query berulang.
     *
     * @param int    $jenisPohonId
     * @param string $tanggal
     * @return float|null
     */
    public function getHargaBeliPerKg($jenisPohonId, $tanggal)
    {
        $hargaModel = new \App\Models\HargaJenisKopiModel();
        $harga = $hargaModel->getLatestPrice($jenisPohonId, $tanggal);

        return $harga ? (float)$harga['harga_beli_per_kg'] : null;
    }

    /**
     * Mendapatkan total penjualan dalam rentang tanggal.
     *
     * @param string $tanggalAwal
     * @param string $tanggalAkhir
     * @return float
     */
    public function getTotalPenjualan($tanggalAwal, $tanggalAkhir)
    {
        $result = $this->selectSum('total_harga_jual')
            ->where('tanggal >=', $tanggalAwal)
            ->where('tanggal <=', $tanggalAkhir)
            ->first();

        return $result['total_harga_jual'] ?? 0;
    }

    /**
     * Mendapatkan jumlah transaksi dalam rentang tanggal.
     *
     * @param string $tanggalAwal
     * @param string $tanggalAkhir
     * @return int
     */
    public function getJumlahTransaksi($tanggalAwal, $tanggalAkhir)
    {
        return $this->where('tanggal >=', $tanggalAwal)
            ->where('tanggal <=', $tanggalAkhir)
            ->countAllResults();
    }
}
