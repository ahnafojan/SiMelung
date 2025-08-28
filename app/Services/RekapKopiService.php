<?php

namespace App\Services;

use Config\Database;

class RekapKopiService
{
    public function getRekapKopiMasuk($filter)
    {
        $db = Database::connect();
        $builder = $db->table('kopi_masuk');
        $builder->select('
            petani.nama as nama_petani,
            SUM(kopi_masuk.jumlah) AS total_masuk,
            MAX(kopi_masuk.tanggal) AS tanggal_terakhir,
            COUNT(kopi_masuk.id) AS jumlah_transaksi
        ');
        $builder->join('petani', 'petani.user_id = kopi_masuk.petani_user_id', 'left');

        if (!empty($filter['start_date'])) $builder->where('kopi_masuk.tanggal >=', $filter['start_date']);
        if (!empty($filter['end_date'])) $builder->where('kopi_masuk.tanggal <=', $filter['end_date']);
        if (!empty($filter['petani'])) $builder->where('kopi_masuk.petani_user_id', $filter['petani']);
        $builder->groupBy('petani.nama');

        $data = $builder->get()->getResultArray();

        foreach ($data as &$row) {
            $row['rata_rata_setoran'] = $row['jumlah_transaksi'] > 0
                ? $row['total_masuk'] / $row['jumlah_transaksi']
                : 0;
        }

        return $data;
    }

    public function getRekapKopiKeluar($filter = [])
    {
        $db = Database::connect();
        $builder = $db->table('kopi_keluar');
        $builder->select('
        kopi_keluar.id,
        kopi_keluar.tanggal,
        kopi_keluar.jumlah,
        kopi_keluar.tujuan,
        kopi_keluar.keterangan,
        stok_kopi.id as stok_id,
        jenis_pohon.nama_jenis as jenis_kopi
    ');
        $builder->join('stok_kopi', 'stok_kopi.id = kopi_keluar.stok_kopi_id', 'left');
        $builder->join('jenis_pohon', 'jenis_pohon.id = stok_kopi.jenis_pohon_id', 'left');

        // filter tanggal jika ada
        if (!empty($filter['tanggal_awal']) && !empty($filter['tanggal_akhir'])) {
            $builder->where('kopi_keluar.tanggal >=', $filter['tanggal_awal']);
            $builder->where('kopi_keluar.tanggal <=', $filter['tanggal_akhir']);
        }

        return $builder->get()->getResultArray();
    }



    public static function getStokAkhir(array $filter = [])
    {
        $db = Database::connect();

        $subMasuk = $db->table('kopi_masuk')
            ->select('jenis_pohon.nama_jenis as jenis_kopi, SUM(kopi_masuk.jumlah) as total_masuk')
            ->join('jenis_pohon', 'jenis_pohon.id = kopi_masuk.jenis_pohon_id', 'left');

        if (!empty($filter['start_date'])) {
            $subMasuk->where('kopi_masuk.tanggal >=', $filter['start_date']);
        }
        if (!empty($filter['end_date'])) {
            $subMasuk->where('kopi_masuk.tanggal <=', $filter['end_date']);
        }

        $subMasuk->groupBy('jenis_pohon.nama_jenis');
        $resultMasuk = $subMasuk->get()->getResultArray();

        $stok = [];
        foreach ($resultMasuk as $row) {
            $stok[$row['jenis_kopi']] = $row['total_masuk'];
        }

        // hitung keluar
        $subKeluar = $db->table('kopi_keluar')
            ->select('jenis_pohon.nama_jenis as jenis_kopi, SUM(kopi_keluar.jumlah) as total_keluar')
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

        foreach ($resultKeluar as $row) {
            if (isset($stok[$row['jenis_kopi']])) {
                $stok[$row['jenis_kopi']] -= $row['total_keluar'];
            }
        }

        $final = [];
        foreach ($stok as $jenis => $jumlah) {
            $final[] = [
                'jenis_kopi' => $jenis,
                'stok_akhir' => $jumlah,
            ];
        }

        return $final;
    }
}
