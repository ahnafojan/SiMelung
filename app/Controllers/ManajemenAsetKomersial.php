<?php

namespace App\Controllers;

use App\Models\AsetKomersialModel;
use App\Models\PermissionRequestModel;
use CodeIgniter\Controller;

class ManajemenAsetKomersial extends Controller
{
    protected $asetModel;
    protected $permissionModel;

    public function __construct()
    {
        $this->asetModel = new AsetKomersialModel();
        $this->permissionModel = new PermissionRequestModel();
        helper(['date']);
    }

    /**
     * Menampilkan daftar aset dan mengirimkan data untuk dropdown modal.
     */
    public function index()
    {
        // Daftar kategori aset untuk dropdown di modal edit.
        // Pastikan daftar ini sama dengan yang ada di controller form Tambah Aset.
        $data['kategoriAset'] = [
            'Mesin Giling',
            'Mesin Pengupas Kopi',
            'Mesin Pengering Kopi',
            'Gudang Penyimpanan',
            'Kendaraan Operasional',
            'Peralatan Pertanian',
        ];

        $data['asets'] = $this->asetModel->findAll();
        if (!empty($data['asets'])) {
            foreach ($data['asets'] as &$aset) {
                // Logika hak akses Anda
                $aset['can_edit'] = $this->hasActivePermission($aset['id_aset'], 'edit');
                $aset['can_delete'] = $this->hasActivePermission($aset['id_aset'], 'delete');
            }
        }
        return view('admin_komersial/aset/manajemen_aset', $data);
    }

    /**
     * Memperbarui data aset dari form modal edit.
     */
    public function update($id)
    {
        if (!$this->hasActivePermission($id, 'edit')) {
            session()->setFlashdata('error', 'Akses ditolak. Anda tidak memiliki izin untuk mengedit data ini.');
            return redirect()->to(site_url('/ManajemenAsetKomersial'));
        }

        try {
            $aset = $this->asetModel->find($id);
            if (!$aset) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Data tidak ditemukan');
            }

            // --- LOGIKA BARU UNTUK DROPDOWN NAMA ASET ---
            $kategoriDipilih = $this->request->getPost('kategori_aset');
            $namaAsetFinal = $kategoriDipilih;

            if ($kategoriDipilih === 'Lainnya') {
                $namaAsetLainnya = $this->request->getPost('nama_aset_lainnya');
                // Validasi: pastikan nama lainnya tidak kosong
                if (empty($namaAsetLainnya)) {
                    session()->setFlashdata('error', 'Nama Aset Lainnya wajib diisi jika kategori "Lainnya" dipilih.');
                    return redirect()->back();
                }
                $namaAsetFinal = $namaAsetLainnya;
            }
            // --- AKHIR LOGIKA BARU ---

            // Handle foto baru
            $fotoFile = $this->request->getFile('foto');
            $fotoName = $aset['foto']; // default tetap foto lama

            if ($fotoFile && $fotoFile->isValid() && !$fotoFile->hasMoved()) {
                $fotoName = $fotoFile->getRandomName();
                $fotoFile->move(FCPATH . 'uploads/foto_aset', $fotoName);

                if (!empty($aset['foto']) && file_exists(FCPATH . 'uploads/foto_aset/' . $aset['foto'])) {
                    unlink(FCPATH . 'uploads/foto_aset/' . $aset['foto']);
                }
            }

            $this->asetModel->update($id, [
                'nama_aset'        => $namaAsetFinal, // Menggunakan nama aset final
                'kode_aset'        => $this->request->getPost('kode_aset'),
                'nup'              => $this->request->getPost('nup'),
                'tahun_perolehan'  => $this->request->getPost('tahun_perolehan'),
                'merk_type'        => $this->request->getPost('merk_type'),
                'nilai_perolehan'  => $this->request->getPost('nilai_perolehan'),
                'keterangan'       => $this->request->getPost('keterangan'), // Sudah benar dari dropdown kondisi
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
        if (!$this->hasActivePermission($id, 'delete')) {
            session()->setFlashdata('error', 'Akses ditolak. Anda tidak memiliki izin untuk menghapus data ini.');
            return redirect()->to(site_url('/ManajemenAsetKomersial'));
        }
        try {
            $aset = $this->asetModel->find($id);
            if (!$aset) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Data tidak ditemukan');
            }

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

    public function requestAccess()
    {
        if ($this->request->isAJAX()) {
            $asetId = $this->request->getPost('aset_id');
            $action = $this->request->getPost('action_type');
            $requesterId = session()->get('user_id');

            if (empty($requesterId)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Sesi tidak valid.'])->setStatusCode(401);
            }

            $existing = $this->permissionModel->where([
                'requester_id' => $requesterId,
                'target_id'    => $asetId,
                'action_type'  => $action,
                'target_type'  => 'aset',
                'status'       => 'pending'
            ])->first();

            if ($existing) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Anda sudah memiliki permintaan yang sama.']);
            }

            $this->permissionModel->save([
                'requester_id' => $requesterId,
                'target_id'    => $asetId,
                'target_type'  => 'aset',
                'action_type'  => $action,
                'status'       => 'pending',
            ]);

            return $this->response->setJSON(['status' => 'success', 'message' => 'Permintaan izin berhasil dikirim.']);
        }
        return redirect()->back();
    }

    // 8. [FUNGSI BARU] Helper untuk mengecek izin aktif
    private function hasActivePermission($asetId, $action)
    {
        $requesterId = session()->get('user_id');
        if (empty($requesterId)) {
            return false;
        }

        $permission = $this->permissionModel->where([
            'requester_id' => $requesterId,
            'target_id'    => $asetId,
            'target_type'  => 'aset',
            'action_type'  => $action,
            'status'       => 'approved',
            'expires_at >' => date('Y-m-d H:i:s')
        ])->first();

        return $permission ? true : false;
    }
}
