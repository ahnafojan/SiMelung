<?= $this->extend('layouts/main_layout') ?>
<?= $this->section('content') ?>

<div class="container-fluid mt-4">
  <h3 class="mb-3">Daftar Pariwisata</h3>
  <a href="<?= base_url('pariwisata/create') ?>" class="btn btn-success mb-3">+ Tambah Pariwisata</a>

  <div class="card shadow-sm">
    <div class="card-body">
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Lokasi</th>
            <th>Harga Tiket</th>
            <th>Fasilitas</th>
            <th>Foto</th>
          </tr>
        </thead>
        <tbody>
          <?php $no=1; foreach($pariwisata as $p): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= esc($p['nama']) ?></td>
            <td><?= esc($p['lokasi']) ?></td>
            <td><?= esc(number_format($p['harga_tiket'],0,',','.')) ?></td>
            <td><?= esc($p['fasilitas']) ?></td>
            <td>
              <?php if($p['foto']): ?>
                <img src="<?= base_url('uploads/'.$p['foto']) ?>" width="100">
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
