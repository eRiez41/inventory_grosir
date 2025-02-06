<!-- halaman/barang_keluar.php -->
<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include '../koneksi/koneksi.php';

// Ambil data barang keluar dari tabel keluar
$queryKeluar = "SELECT k.id, b.nama AS nama_barang, k.jumlah, k.penerima, k.tanggal_keluar
                FROM keluar k
                JOIN barang b ON k.barang_id = b.id";
$resultKeluar = $koneksi->query($queryKeluar);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Barang Keluar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                <h1>Daftar Barang Keluar</h1>
                <a href="tambah_barang_keluar.php" class="btn btn-primary btn-tambah">
                    <i class="fas fa-plus"></i> Tambah Barang Keluar
                </a>
                <a href="../fungsi/cetak_barang_keluar.php" class="btn btn-secondary btn-tambah" target="_blank">
                    <i class="fas fa-print"></i> Cetak Daftar Barang Keluar
                </a>
                <table class="table table-striped table-bordered mt-2">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th>Penerima</th>
                            <th>Tanggal Keluar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        while ($row = $resultKeluar->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $no++ . "</td>";
                            echo "<td>" . $row['nama_barang'] . "</td>";
                            echo "<td>" . $row['jumlah'] . "</td>";
                            echo "<td>" . $row['penerima'] . "</td>";
                            echo "<td>" . $row['tanggal_keluar'] . "</td>";
                            echo "<td>";
                            echo "<a href='edit_barang_keluar.php?id=" . $row['id'] . "' class='btn btn-sm btn-warning'>
                                    <i class='fas fa-edit'></i> Edit
                                  </a> ";
                            echo "<button class='btn btn-sm btn-danger' onclick='hapusBarangKeluar(" . $row['id'] . ")'>
                                    <i class='fas fa-trash'></i> Hapus
                                  </button>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function hapusBarangKeluar(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda akan menghapus data barang keluar ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '../fungsi/fungsi_hapus_barang_keluar.php?id=' + id;
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            <?php if (isset($_SESSION['hapus_sukses'])): ?>
                Swal.fire('Sukses', 'Data barang keluar berhasil dihapus', 'success');
                <?php unset($_SESSION['hapus_sukses']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['hapus_error'])): ?>
                Swal.fire('Gagal', '<?php echo $_SESSION['hapus_error']; ?>', 'error');
                <?php unset($_SESSION['hapus_error']); ?>
            <?php endif; ?>
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
