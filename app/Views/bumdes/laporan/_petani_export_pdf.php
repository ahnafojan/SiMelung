<!DOCTYPE html>
<html>

<head>
    <title><?= esc($title) ?></title>
    <style>
        body {
            font-family: sans-serif;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .table th {
            background-color: #f2f2f2;
            text-align: left;
        }
    </style>
</head>

<body>
    <h1><?= esc($title) ?></h1>
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>User ID</th>
                <th>Nama Petani</th>
                <th>Alamat</th>
                <th>No. HP</th>
                <th>Jenis Kopi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($petaniData as $no => $petani): ?>
                <tr>
                    <td><?= $no + 1 ?></td>
                    <td><?= esc($petani['user_id']) ?></td>
                    <td><?= esc($petani['nama']) ?></td>
                    <td><?= esc($petani['alamat']) ?></td>
                    <td><?= esc($petani['no_hp']) ?></td>
                    <td><?= esc($petani['jenis_kopi_list'] ?? 'Tidak Terdata') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>