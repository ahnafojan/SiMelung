<?php

namespace App\Controllers;

use App\Models\PetaniModel;
use CodeIgniter\Controller;

class Petani extends Controller
{
    protected $petaniModel;

    public function __construct()
    {
        $this->petaniModel = new PetaniModel();
        helper(['form', 'url']);
    }

    // Tampil daftar petani
    public function index()
    {
        $data['petani'] = $this->petaniModel->orderBy('id', 'ASC')->findAll();
        echo view('admin_komersial/petani/index', $data);
    }

    // Simpan data petani baru
    public function create()
    {
        try {
            $validation = \Config\Services::validation();

            $validation->setRules([
                'nama'          => 'required|min_length[3]',
                'alamat'        => 'required',
                'no_hp'         => 'required|numeric',
                'jenis_pohon'   => 'permit_empty',
                'usia'          => 'permit_empty|numeric',
                'tempat_lahir'  => 'permit_empty',
                'tanggal_lahir' => 'permit_empty|valid_date',
                'foto'          => 'permit_empty|uploaded[foto]|is_image[foto]|max_size[foto,2048]',
            ]);

            if (!$this->validate($validation->getRules())) {
                return redirect()->to(site_url('petani'))->with('errors', $this->validator->getErrors());
            }

            // Generate user_id otomatis
            $lastPetani = $this->petaniModel->orderBy('id', 'DESC')->first();
            $newUserId = $lastPetani
                ? 'P' . str_pad(((int) substr($lastPetani['user_id'], 1)) + 1, 3, '0', STR_PAD_LEFT)
                : 'P001';

            // Upload foto
            $fotoName = null;
            $fotoFile = $this->request->getFile('foto');
            if ($fotoFile && $fotoFile->isValid()) {
                $fotoName = $fotoFile->getRandomName();
                $fotoFile->move('uploads/foto_petani', $fotoName);
            }

            // Simpan ke database
            $this->petaniModel->save([
                'user_id'       => $newUserId,
                'nama'          => $this->request->getPost('nama'),
                'alamat'        => $this->request->getPost('alamat'),
                'no_hp'         => $this->request->getPost('no_hp'),
                'jenis_pohon'   => $this->request->getPost('jenis_pohon'),
                'usia'          => $this->request->getPost('usia'),
                'tempat_lahir'  => $this->request->getPost('tempat_lahir'),
                'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
                'foto'          => $fotoName
            ]);

            session()->setFlashdata('success', 'Data petani berhasil ditambahkan');
        } catch (\Exception $e) {
            // Jika ada error, tangkap dan kirimkan pesan ke flashdata
            session()->setFlashdata('error', 'Terjadi kesalahan saat menambah data: ' . $e->getMessage());
        }

        return redirect()->to('/petani');
    }


    // Update data petani
    public function postUpdate()
    {
        $id = $this->request->getPost('id');
        $petaniLama = $this->petaniModel->find($id);

        if (!$petaniLama) {
            session()->setFlashdata('error', 'Data petani tidak ditemukan');
            return redirect()->to('/petani');
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama'          => 'required|min_length[3]',
            'alamat'        => 'required',
            'no_hp'         => 'required|numeric',
            'jenis_pohon'   => 'permit_empty',
            'usia'          => 'permit_empty|numeric',
            'tempat_lahir'  => 'permit_empty',
            'tanggal_lahir' => 'permit_empty|valid_date',
            'foto'          => 'permit_empty|is_image[foto]|max_size[foto,5000]',
        ]);

        if (!$this->validate($validation->getRules())) {
            return redirect()->to('/petani')->with('errors', $this->validator->getErrors());
        }

        $dataUpdate = [
            'nama'          => $this->request->getPost('nama'),
            'alamat'        => $this->request->getPost('alamat'),
            'no_hp'         => $this->request->getPost('no_hp'),
            'jenis_pohon'   => $this->request->getPost('jenis_pohon'),
            'usia'          => $this->request->getPost('usia'),
            'tempat_lahir'  => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
        ];

        // Cek jika ada foto baru
        $fotoFile = $this->request->getFile('foto');
        if ($fotoFile && $fotoFile->isValid() && !$fotoFile->hasMoved()) {
            $fotoName = $fotoFile->getRandomName();
            $fotoFile->move(FCPATH . 'uploads/foto_petani', $fotoName);

            // Hapus foto lama jika ada
            if (!empty($petaniLama['foto']) && file_exists(FCPATH . 'uploads/foto_petani/' . $petaniLama['foto'])) {
                unlink(FCPATH . 'uploads/foto_petani/' . $petaniLama['foto']);
            }

            $dataUpdate['foto'] = $fotoName;
        } else {
            // Tetap gunakan foto lama
            $dataUpdate['foto'] = $petaniLama['foto'];
        }

        try {
            $this->petaniModel->update($id, $dataUpdate);
            session()->setFlashdata('success', 'Data petani berhasil diperbarui');
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Terjadi kesalahan saat memperbarui data');
        }

        return redirect()->to('/petani');
    }


    // Hapus data petani
    // app/Controllers/Petani.php

    public function delete() // 1. Hapus parameter $id dari sini
    {
        // 2. Ambil ID dari form POST, bukan dari URL
        $id = $this->request->getPost('id');

        // 3. Ubah pengecekan untuk ID yang tidak ada dari form
        if (!$id) {
            // Jika tidak ada ID yang dikirim melalui form, kembali dengan error
            return redirect()->to('/petani')->with('error', 'Gagal menghapus, ID petani tidak valid.');
        }

        try {
            $petani = $this->petaniModel->find($id);

            if ($petani) {
                // Hapus foto dari folder jika ada (Logika ini sudah benar)
                if (!empty($petani['foto']) && file_exists(FCPATH . 'uploads/foto_petani/' . $petani['foto'])) {
                    unlink(FCPATH . 'uploads/foto_petani/' . $petani['foto']);
                }

                // Hapus data petani dari database
                $this->petaniModel->delete($id);

                session()->setFlashdata('success', 'Data petani berhasil dihapus.');
            } else {
                session()->setFlashdata('error', 'Data petani tidak ditemukan.');
            }
        } catch (\Exception $e) {
            // Log error jika perlu: log_message('error', $e->getMessage());
            session()->setFlashdata('error', 'Terjadi kesalahan pada server saat menghapus data.');
        }

        return redirect()->to('/petani');
    }
}
