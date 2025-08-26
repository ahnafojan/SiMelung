<?php

namespace App\Controllers;

use App\Models\PetaniModel;
use App\Models\JenisPohonModel;
use App\Models\PetaniPohonModel;

class PetaniPohon extends BaseController
{
    protected $petaniModel;
    protected $jenisPohonModel;
    protected $petaniPohonModel;

    public function __construct()
    {
        $this->petaniModel = new PetaniModel();
        $this->jenisPohonModel = new JenisPohonModel();
        $this->petaniPohonModel = new PetaniPohonModel();
    }

    // Tampilkan detail pohon berdasarkan user_id
    public function index($user_id)
    {
        $petani = $this->petaniModel->where('user_id', $user_id)->first();
        if (!$petani) {
            return redirect()->to('/petani')->with('error', 'Petani tidak ditemukan');
        }

        $jenisPohon = $this->jenisPohonModel->findAll();

        // Join tabel supaya tampil jenis pohon
        $detailPohon = $this->petaniPohonModel
            ->select('petani_pohon.*, jenis_pohon.nama_jenis')
            ->join('jenis_pohon', 'jenis_pohon.id = petani_pohon.jenis_pohon_id')
            ->where('petani_pohon.user_id', $user_id)
            ->findAll();

        return view('admin_komersial/petani/petanipohon', [
            'petani'      => $petani,
            'jenisPohon'  => $jenisPohon,
            'detailPohon' => $detailPohon,
            'validation'  => \Config\Services::validation(),
        ]);
    }

    // Simpan pohon baru
    public function store()
    {
        $data = $this->request->getPost();

        $rules = [
            'user_id'        => 'required|string',
            'jenis_pohon_id' => 'required|integer',
            'luas_lahan'     => 'required|decimal|greater_than[0]',
            'jumlah_pohon'   => 'required|integer|greater_than[0]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Data tidak valid!')
                ->with('validation', $this->validator);
        }

        try {
            $this->petaniPohonModel->save([
                'user_id'        => $data['user_id'],
                'jenis_pohon_id' => $data['jenis_pohon_id'],
                'luas_lahan'     => $data['luas_lahan'],
                'jumlah_pohon'   => $data['jumlah_pohon'],
            ]);

            session()->setFlashdata('success', 'Data pohon Petani berhasil ditambahkan');
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Terjadi kesalahan saat menambah data pohon Petani: ' . $e->getMessage());
        }

        return redirect()->to('/petanipohon/' . $data['user_id']);
    }

    // Hapus data pohon
    public function delete()
    {
        $id = $this->request->getPost('id');

        if (!$id) {
            return redirect()->back()->with('error', 'ID pohon tidak ditemukan');
        }

        $pohonModel = new \App\Models\PetaniPohonModel();
        $pohon = $pohonModel->find($id);

        if (!$pohon) {
            return redirect()->back()->with('error', 'Data pohon tidak ditemukan');
        }

        $pohonModel->delete($id);

        return redirect()->back()->with('success', 'Data pohon berhasil dihapus');
    }
}
