<!-- halaman/barang.php -->
<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include '../koneksi/koneksi.php';

// Ambil data barang dengan menggabungkan tabel kategori, ukuran, dan warna
$queryBarang = "
    SELECT
        b.id,
        b.nama,
        k.nama_kategori,
        u.nama_ukuran,
        w.nama_warna,
        b.stok,
        b.foto,
        b.deskripsi
    FROM
        barang b
    JOIN
        kategori k ON b.kategori_id = k.id
    JOIN
        ukuran u ON b.ukuran_id = u.id
    JOIN
        warna w ON b.warna_id = w.id
";
$resultBarang = $koneksi->query($queryBarang);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .btn-tambah {
            margin-bottom: 1rem; /* Sesuaikan jarak antara tombol dan tabel */
        }
    </style>
</head>
<body>
    <?php include '../komponen/navbar.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <?php include '../komponen/sidebar.php'; ?>
            <div class="col-md-9 offset-md-3 mt-4">
                <h1>Daftar Barang</h1>
                <a href="tambah_barang.php" class="btn btn-primary btn-tambah">
                    <i class="fas fa-plus"></i> Tambah Barang
                </a>
                <a href="../fungsi/cetak_semua_barang.php" class="btn btn-secondary btn-tambah" target="_blank">
                    <i class="fas fa-print"></i> Cetak Daftar Barang
                </a>
                <table class="table table-striped table-bordered mt-2">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Ukuran</th>
                            <th>Warna</th>
                            <th>Stok</th>
                            <th>Foto</th>
                            <th>Deskripsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        while ($row = $resultBarang->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $no++ . "</td>";
                            echo "<td>" . $row['nama'] . "</td>";
                            echo "<td>" . $row['nama_kategori'] . "</td>";
                            echo "<td>" . $row['nama_ukuran'] . "</td>";
                            echo "<td>" . $row['nama_warna'] . "</td>";
                            echo "<td>" . $row['stok'] . "</td>";
                            echo "<td>";
                            if (!empty($row['foto'])) {
                                echo '<img src="../gambar_barang/' . $row['foto'] . '" alt="Foto Barang" style="width: 50px; height: auto;">';
                            } else {
                                echo "Tidak ada foto";
                            }
                            echo "</td>";
                            echo "<td>" . $row['deskripsi'] . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
