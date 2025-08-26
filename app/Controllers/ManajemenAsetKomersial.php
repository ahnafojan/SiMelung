<?php

namespace App\Controllers;

use App\Models\AsetKomersialModel;
use CodeIgniter\Controller;

class ManajemenAsetKomersial extends Controller
{
    protected $asetModel;

    public function __construct()
    {
        $this->asetModel = new AsetKomersialModel();
    }

    public function index()
    {
        $data['asets'] = $this->asetModel->findAll();
        return view('admin_komersial/aset/manajemen_aset', $data);
    }

    public function update($id)
    {
        try {
            $aset = $this->asetModel->find($id);
            if (!$aset) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Data tidak ditemukan');
            }

            // Handle foto baru
            $fotoFile = $this->request->getFile('foto');
            $fotoName = $aset['foto']; // default tetap foto lama

            if ($fotoFile && $fotoFile->isValid() && !$fotoFile->hasMoved()) {
                $fotoName = $fotoFile->getRandomName();
                $fotoFile->move(FCPATH . 'uploads/foto_aset', $fotoName);

                // Hapus foto lama jika ada
                if (!empty($aset['foto']) && file_exists(FCPATH . 'uploads/foto_aset/' . $aset['foto'])) {
                    unlink(FCPATH . 'uploads/foto_aset/' . $aset['foto']);
                }
            }

            $this->asetModel->update($id, [
                'nama_aset'        => $this->request->getPost('nama_aset'),
                'kode_aset'        => $this->request->getPost('kode_aset'),
                'nup'              => $this->request->getPost('nup'),
                'tahun_perolehan'  => $this->request->getPost('tahun_perolehan'),
                'merk_type'        => $this->request->getPost('merk_type'),
                'nilai_perolehan'  => $this->request->getPost('nilai_perolehan'),
                'keterangan'       => $this->request->getPost('keterangan'),
                'metode_pengadaan' => $this->request->getPost('metode_pengadaan'),
                'sumber_pengadaan' => $this->request->getPost('sumber_pengadaan'),
                'foto'             => $fotoName,
            ]);

            session()->setFlashdata('success', 'Data aset berhasil diperbarui ✅');
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }

        return redirect()->to(site_url('/ManajemenAsetKomersial'));
    }

    public function delete($id)
    {
        try {
            $aset = $this->asetModel->find($id);
            if (!$aset) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Data tidak ditemukan');
            }

            // Hapus foto dari server
            if (!empty($aset['foto']) && file_exists(FCPATH . 'uploads/foto_aset/' . $aset['foto'])) {
                unlink(FCPATH . 'uploads/foto_aset/' . $aset['foto']);
            }

            $this->asetModel->delete($id);
            session()->setFlashdata('success', 'Data Aset berhasil dihapus ✅');
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }

        return redirect()->to(site_url('/ManajemenAsetKomersial'));
    }
}
