<!-- halaman/suplier.php -->
<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include '../koneksi/koneksi.php';

// Ambil data suplier dari tabel suplier
$querySuplier = "SELECT id, nama, kontak, alamat FROM suplier";
$resultSuplier = $koneksi->query($querySuplier);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Supplier</title>
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
                <h1>Daftar Supplier</h1>
                <a href="tambah_suplier.php" class="btn btn-primary btn-tambah">
                    <i class="fas fa-plus"></i> Tambah Supplier
                </a>
                <table class="table table-striped table-bordered mt-2">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Supplier</th>
                            <th>Kontak</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        while ($row = $resultSuplier->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $no++ . "</td>";
                            echo "<td>" . $row['nama'] . "</td>";
                            echo "<td>" . $row['kontak'] . "</td>";
                            echo "<td>" . $row['alamat'] . "</td>";
                            echo "<td>";
                            echo "<a href='edit_suplier.php?id=" . $row['id'] . "' class='btn btn-sm btn-warning'>
                                    <i class='fas fa-edit'></i> Edit
                                  </a> ";
                            echo "<button class='btn btn-sm btn-danger' onclick='hapusSuplier(" . $row['id'] . ")'>
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
        function hapusSuplier(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda akan menghapus supplier ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '../fungsi/fungsi_hapus_suplier.php?id=' + id;
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            <?php if (isset($_SESSION['hapus_sukses'])): ?>
                Swal.fire('Sukses', 'Supplier berhasil dihapus', 'success');
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
