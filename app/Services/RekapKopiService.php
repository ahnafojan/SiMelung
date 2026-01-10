<?php

namespace App\Services;

use Config\Database;

class RekapKopiService
{
    /**
     * Helper method untuk sanitasi nilai numerik
     * Memastikan nilai yang dikembalikan selalu numerik dan tidak null
     * 
     * @param mixed $value Nilai yang akan disanitasi
     * @param float|int $default Nilai default jika value tidak valid
     * @return float|int
     */
    private function sanitizeNumericValue($value, $default = 0)
    {
        if (is_null($value) || $value === '' || $value === false) {
            return $default;
        }

        if (is_numeric($value)) {
            return (float) $value;
        }

        return $default;
    }

    /**
     * Sanitasi array data untuk memastikan semua field numerik valid
     * 
     * @param array $data Array data yang akan disanitasi
     * @param array $numericFields Daftar field yang harus numerik
     * @return array
     */
    private function sanitizeArrayData(array $data, array $numericFields): array
    {
        foreach ($data as &$row) {
            foreach ($numericFields as $field) {
                if (isset($row[$field])) {
                    $row[$field] = $this->sanitizeNumericValue($row[$field]);
                }
            }
        }
        return $data;
    }

    /**
     * Get Rekap Kopi Masuk per Petani dengan harga
     * PERBAIKAN: Sesuaikan dengan kolom yang benar di tabel kopi_masuk
     * 
     * @param array $filter Filter berdasarkan tanggal dan petani
     * @param int|null $perPage
     * @param int|null $page
     * @param bool $paginate
     * @return array
     */
    public function getRekapKopiMasuk($filter, $perPage = null, $page = null, $paginate = true)
    {
        $db = Database::connect();
        $builder = $db->table('kopi_masuk');

        $builder->select('
        petani.nama as nama_petani,
        jenis_pohon.nama_jenis as jenis_kopi,
        kopi_masuk.tanggal as tanggal_transaksi,
        SUM(kopi_masuk.jumlah) AS total_masuk,
        SUM(kopi_masuk.total_harga) AS total_nilai_masuk,
        COUNT(kopi_masuk.id) AS jumlah_transaksi
    ');

        $builder->join('petani', 'petani.user_id = kopi_masuk.petani_user_id', 'left');
        $builder->join('petani_pohon', 'petani_pohon.id = kopi_masuk.petani_pohon_id', 'left');
        $builder->join('jenis_pohon', 'jenis_pohon.id = petani_pohon.jenis_pohon_id', 'left');

        // ✅ PERBAIKAN: Apply filters dengan kolom yang benar
        if (!empty($filter['start_date'])) {
            $builder->where('kopi_masuk.tanggal >=', $filter['start_date']);
        }
        if (!empty($filter['end_date'])) {
            $builder->where('kopi_masuk.tanggal <=', $filter['end_date']);
        }

        // ✅ PERBAIKAN CRITICAL: Gunakan kopi_masuk.petani_user_id, BUKAN stok_kopi.petani_id
        if (!empty($filter['petani'])) {
            $builder->where('kopi_masuk.petani_user_id', $filter['petani']);
        }

        $builder->where('jenis_pohon.nama_jenis IS NOT NULL');

        // Group by per petani + jenis + tanggal
        $builder->groupBy('petani.nama');
        $builder->groupBy('jenis_pohon.nama_jenis');
        $builder->groupBy('kopi_masuk.tanggal');

        // Urutkan tanggal terbaru di atas
        $builder->orderBy('kopi_masuk.tanggal', 'DESC');

        if ($paginate) {
            $totalBuilder = clone $builder;
            $total = count($totalBuilder->get()->getResultArray());
            $builder->limit($perPage, ($page - 1) * $perPage);
        }

        $data = $builder->get()->getResultArray();

        // Hitung rata-rata setoran per transaksi
        foreach ($data as &$row) {
            $row['rata_rata_setoran'] = ($row['jumlah_transaksi'] > 0)
                ? ($row['total_masuk'] / $row['jumlah_transaksi'])
                : 0;
        }

        if ($paginate) {
            $pager = service('pager');
            $pager->makeLinks($page, $perPage, $total, 'default_full', 0, 'masuk');
            return [$data, $pager];
        }

        return $data;
    }

    /**
     * Get Rekap Kopi Keluar dengan harga jual
     * 
     * @param array $filter Filter berdasarkan tanggal
     * @param int|null $perPage
     * @param int|null $page
     * @param bool $paginate
     * @return array
     */
    public function getRekapKopiKeluar($filter, $perPage = null, $page = null, $paginate = true)
    {
        $db = Database::connect();
        $builder = $db->table('kopi_keluar');

        $builder->select('
        kopi_keluar.tanggal,
        kopi_keluar.jumlah as jumlah_kg,
        kopi_keluar.tujuan as tujuan_pembeli,
        kopi_keluar.keterangan,
        jenis_pohon.id as jenis_pohon_id,
        jenis_pohon.nama_jenis as jenis_kopi,
        petani.nama as nama_petani,
        kopi_keluar.harga_saat_transaksi as harga_jual_per_kg
    ');

        $builder->join('stok_kopi', 'stok_kopi.id = kopi_keluar.stok_kopi_id', 'left');
        $builder->join('jenis_pohon', 'jenis_pohon.id = stok_kopi.jenis_pohon_id', 'left');
        $builder->join('petani', 'petani.user_id = stok_kopi.petani_id', 'left');

        // Apply filters
        if (!empty($filter['start_date'])) {
            $builder->where('kopi_keluar.tanggal >=', $filter['start_date']);
        }
        if (!empty($filter['end_date'])) {
            $builder->where('kopi_keluar.tanggal <=', $filter['end_date']);
        }
        if (!empty($filter['petani'])) {
            $builder->where('stok_kopi.petani_id', $filter['petani']);
        }

        $builder->orderBy('kopi_keluar.tanggal', 'DESC');

        if ($paginate) {
            $total = $builder->countAllResults(false);
            $builder->limit($perPage, ($page - 1) * $perPage);
        }

        $data = $builder->get()->getResultArray();

        // Ambil harga beli via model (berdasarkan tanggal transaksi)
        $hargaModel = new \App\Models\HargaJenisKopiModel();

        // Cache: biar 1 jenis + tanggal tidak query berulang
        $cacheHargaBeli = [];

        foreach ($data as &$row) {
            $jenisId = (int)($row['jenis_pohon_id'] ?? 0);
            $tgl     = $row['tanggal'] ?? date('Y-m-d');

            $cacheKey = $jenisId . '|' . $tgl;

            if (!array_key_exists($cacheKey, $cacheHargaBeli)) {
                $hargaTerbaru = $hargaModel->getLatestPrice($jenisId, $tgl);
                $cacheHargaBeli[$cacheKey] = (float)($hargaTerbaru['harga_beli_per_kg'] ?? 0);
            }

            $hargaBeli = $cacheHargaBeli[$cacheKey];
            $hargaJual = (float)($row['harga_jual_per_kg'] ?? 0);
            $qty       = (float)($row['jumlah_kg'] ?? 0);

            // Kolom baru yang kamu minta
            $row['total_harga_petani'] = $hargaBeli * $qty;
            $row['keuntungan_bumdes']  = ($hargaJual - $hargaBeli) * $qty;

            // optional: kalau mau tampil minus, hapus baris ini
            if ($row['keuntungan_bumdes'] < 0) $row['keuntungan_bumdes'] = 0;
        }

        if ($paginate) {
            $pager = service('pager');
            $pager->makeLinks($page, $perPage, $total, 'default_full', 0, 'keluar');
            return [$data, $pager];
        }

        return $data;
    }

    /**
     * Get Stok Akhir dengan nilai stok
     * PERBAIKAN: Sesuaikan dengan kolom yang benar
     * 
     * @param array $filter Filter berdasarkan tanggal
     * @return array
     */
    public static function getStokAkhir(array $filter = [])
    {
        $db = Database::connect();

        // Hitung total masuk dengan harga beli rata-rata dari kopi_masuk
        $subMasuk = $db->table('kopi_masuk')
            ->select('
                jenis_pohon.nama_jenis as jenis_kopi,
                jenis_pohon.id as jenis_pohon_id, 
                SUM(kopi_masuk.jumlah) as total_masuk,
                AVG(COALESCE(kopi_masuk.harga_saat_transaksi, 0)) as harga_beli_avg
            ')
            ->join('petani_pohon', 'petani_pohon.id = kopi_masuk.petani_pohon_id', 'left')
            ->join('jenis_pohon', 'jenis_pohon.id = petani_pohon.jenis_pohon_id', 'left');

        if (!empty($filter['start_date'])) {
            $subMasuk->where('kopi_masuk.tanggal >=', $filter['start_date']);
        }
        if (!empty($filter['end_date'])) {
            $subMasuk->where('kopi_masuk.tanggal <=', $filter['end_date']);
        }

        $subMasuk->groupBy('jenis_pohon.nama_jenis, jenis_pohon.id');
        $resultMasuk = $subMasuk->get()->getResultArray();

        $stok = [];
        $hargaAvg = [];

        foreach ($resultMasuk as $row) {
            $jenis = $row['jenis_kopi'] ?? 'Unknown';
            $stok[$jenis] = (float) ($row['total_masuk'] ?? 0);
            $hargaAvg[$jenis] = (float) ($row['harga_beli_avg'] ?? 0);
        }

        // Hitung total keluar dari kopi_keluar
        $subKeluar = $db->table('kopi_keluar')
            ->select('
                jenis_pohon.nama_jenis as jenis_kopi, 
                SUM(kopi_keluar.jumlah) as total_keluar
            ')
            ->join('stok_kopi', 'stok_kopi.id = kopi_keluar.stok_kopi_id', 'left')
            ->join('jenis_pohon', 'jenis_pohon.id = stok_kopi.jenis_pohon_id', 'left');

        if (!empty($filter['start_date'])) {
            $subKeluar->where('kopi_keluar.tanggal >=', $filter['start_date']);
        }
        if (!empty($filter['end_date'])) {
            $subKeluar->where('kopi_keluar.tanggal <=', $filter['end_date']);
        }

        $subKeluar->groupBy('jenis_pohon.nama_jenis');
        $resultKeluar = $subKeluar->get()->getResultArray();

        // Kurangi stok dengan data keluar
        foreach ($resultKeluar as $row) {
            $jenis = $row['jenis_kopi'] ?? 'Unknown';
            if (isset($stok[$jenis])) {
                $stok[$jenis] -= (float) ($row['total_keluar'] ?? 0);
            }
        }

        // Format hasil akhir
        $final = [];
        foreach ($stok as $jenis => $jumlah) {
            $harga = $hargaAvg[$jenis] ?? 0;
            $stokValue = max(0, (float) $jumlah); // Pastikan tidak negatif

            $final[] = [
                'jenis_kopi' => $jenis,
                'stok_akhir' => $stokValue,
                'nilai_stok' => $stokValue * (float) $harga,
            ];
        }

        // Sort by jenis_kopi
        usort($final, function ($a, $b) {
            return strcmp($a['jenis_kopi'], $b['jenis_kopi']);
        });

        return $final;
    }

    /**
     * Get summary statistics untuk dashboard
     * 
     * @param array $filter
     * @return array
     */
    public function getSummaryStatistics(array $filter = []): array
    {
        $rekapMasuk = $this->getRekapKopiMasuk($filter);
        $rekapKeluar = $this->getRekapKopiKeluar($filter);
        $stokAkhir = self::getStokAkhir($filter);

        return [
            'total_masuk_kg' => array_sum(array_column($rekapMasuk, 'total_masuk')),
            'total_nilai_masuk' => array_sum(array_column($rekapMasuk, 'total_nilai_masuk')),
            'total_keluar_kg' => array_sum(array_column($rekapKeluar, 'jumlah_kg')),
            'total_nilai_keluar' => array_sum(array_column($rekapKeluar, 'total_nilai_jual')),
            'total_stok_kg' => array_sum(array_column($stokAkhir, 'stok_akhir')),
            'total_nilai_stok' => array_sum(array_column($stokAkhir, 'nilai_stok')),
            'jumlah_petani' => count($rekapMasuk),
            'jumlah_jenis_kopi' => count($stokAkhir),
        ];
    }

    /**
     * Get detail transaksi kopi masuk untuk satu petani
     * 
     * @param string $petaniUserId
     * @param array $filter
     * @return array
     */
    public function getDetailTransaksiMasuk(string $petaniUserId, array $filter = []): array
    {
        $db = Database::connect();
        $builder = $db->table('kopi_masuk');

        $builder->select('
            kopi_masuk.id,
            kopi_masuk.tanggal,
            kopi_masuk.jumlah,
            kopi_masuk.harga_saat_transaksi,
            kopi_masuk.total_harga,
            kopi_masuk.keterangan,
            jenis_pohon.nama_jenis,
            petani.nama as nama_petani
        ');

        $builder->join('petani_pohon', 'petani_pohon.id = kopi_masuk.petani_pohon_id', 'left');
        $builder->join('jenis_pohon', 'jenis_pohon.id = petani_pohon.jenis_pohon_id', 'left');
        $builder->join('petani', 'petani.user_id = kopi_masuk.petani_user_id', 'left');

        $builder->where('kopi_masuk.petani_user_id', $petaniUserId);

        if (!empty($filter['start_date'])) {
            $builder->where('kopi_masuk.tanggal >=', $filter['start_date']);
        }
        if (!empty($filter['end_date'])) {
            $builder->where('kopi_masuk.tanggal <=', $filter['end_date']);
        }

        $builder->orderBy('kopi_masuk.tanggal', 'DESC');

        return $builder->get()->getResultArray();
    }

    /**
     * Get detail transaksi kopi keluar untuk satu jenis kopi
     * 
     * @param int $jenisPohonId
     * @param array $filter
     * @return array
     */
    public function getDetailTransaksiKeluar(int $jenisPohonId, array $filter = []): array
    {
        $db = Database::connect();
        $builder = $db->table('kopi_keluar');

        $builder->select('
            kopi_keluar.id,
            kopi_keluar.tanggal,
            kopi_keluar.jumlah,
            kopi_keluar.harga_saat_transaksi,
            kopi_keluar.total_harga_jual,
            kopi_keluar.tujuan,
            kopi_keluar.keterangan,
            jenis_pohon.nama_jenis
        ');

        $builder->join('stok_kopi', 'stok_kopi.id = kopi_keluar.stok_kopi_id', 'left');
        $builder->join('jenis_pohon', 'jenis_pohon.id = stok_kopi.jenis_pohon_id', 'left');

        $builder->where('stok_kopi.jenis_pohon_id', $jenisPohonId);

        if (!empty($filter['start_date'])) {
            $builder->where('kopi_keluar.tanggal >=', $filter['start_date']);
        }
        if (!empty($filter['end_date'])) {
            $builder->where('kopi_keluar.tanggal <=', $filter['end_date']);
        }

        $builder->orderBy('kopi_keluar.tanggal', 'DESC');

        return $builder->get()->getResultArray();
    }
}
