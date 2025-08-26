<?php

namespace App\Controllers;

use App\Models\KopiKeluarModel;
use App\Models\KopiMasukModel;
use App\Models\StokKopiModel; // tambahkan model stok

class KopiKeluar extends BaseController
{
    protected $kopiKeluarModel;
    protected $kopiMasukModel;
    protected $stokKopiModel;

    public function __construct()
    {
        $this->kopiKeluarModel = new KopiKeluarModel();
        $this->kopiMasukModel  = new KopiMasukModel();
        $this->stokKopiModel   = new StokKopiModel();
    }

    public function index()
    {
        $kopikeluar = $this->kopiKeluarModel
            ->select('kopi_keluar.*, stok_kopi.stok as total_stok, jenis_pohon.nama_jenis as nama_pohon')
            ->join('stok_kopi', 'kopi_keluar.stok_kopi_id = stok_kopi.id', 'left')
            ->join('jenis_pohon', 'jenis_pohon.id = stok_kopi.jenis_pohon_id', 'left')
            ->orderBy('kopi_keluar.tanggal', 'ASC')
            ->findAll();

        $totalMasuk = $this->kopiMasukModel->selectSum('jumlah')->first()['jumlah'] ?? 0;
        $totalKeluar = $this->kopiKeluarModel->selectSum('jumlah')->first()['jumlah'] ?? 0;
        $stok = $totalMasuk - $totalKeluar;

        $data = [
            'kopikeluar' => $kopikeluar,
            'stokKopi'   => $this->stokKopiModel->getWithJenis(),
            'stok'       => $stok
        ];
        return view('admin_komersial/kopi/kopi-keluar', $data);
    }




    public function store()
    {
        try {
            $stokKopiId = $this->request->getPost('stok_kopi_id');
            $jumlah     = (float) $this->request->getPost('jumlah');

            // Simpan data kopi keluar
            $this->kopiKeluarModel->save([
                'stok_kopi_id' => $stokKopiId,
                'tujuan'       => $this->request->getPost('tujuan'),
                'jumlah'       => $jumlah,
                'tanggal'      => $this->request->getPost('tanggal'),
                'keterangan'   => $this->request->getPost('keterangan'),
            ]);

            // Kurangi stok pada tabel stok_kopi
            $this->stokKopiModel
                ->where('id', $stokKopiId)
                ->set('stok', 'stok - ' . $jumlah, false) // false = biar langsung operasi SQL
                ->update();

            session()->setFlashdata('success', 'Data kopi keluar berhasil ditambahkan dan stok berkurang');
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Terjadi kesalahan saat menambah data: ' . $e->getMessage());
        }

        return redirect()->to(site_url('/kopikeluar'));
    }

    public function edit($id)
    {
        $data = $this->kopiKeluarModel->find($id);
        return $this->response->setJSON($data);
    }

    public function update($id)
    {
        $this->kopiKeluarModel->update($id, [
            'stok_kopi_id' => $this->request->getPost('stok_kopi_id'),
            'tujuan'       => $this->request->getPost('tujuan'),
            'jumlah'       => $this->request->getPost('jumlah'),
            'tanggal'      => $this->request->getPost('tanggal'),
            'keterangan'   => $this->request->getPost('keterangan'),
        ]);

        return redirect()->to('/kopikeluar')->with('success', 'Data berhasil diperbarui');
    }

    public function delete($id)
    {
        $this->kopiKeluarModel->delete($id);
        return redirect()->to('/kopikeluar')->with('success', 'Data berhasil dihapus');
    }
}
