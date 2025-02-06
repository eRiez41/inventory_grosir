<!-- halaman/dashboard.php -->
<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include '../koneksi/koneksi.php';

// Ambil total stok barang dari tabel barang
$queryStok = "SELECT SUM(stok) AS total_stok FROM barang";
$resultStok = $koneksi->query($queryStok);
$totalStok = $resultStok->fetch_assoc()['total_stok'];

// Ambil total barang masuk dari tabel masuk
$queryMasuk = "SELECT SUM(jumlah) AS total_masuk FROM masuk";
$resultMasuk = $koneksi->query($queryMasuk);
$barangMasuk = $resultMasuk->fetch_assoc()['total_masuk'];

// Ambil total barang keluar dari tabel keluar
$queryKeluar = "SELECT SUM(jumlah) AS total_keluar FROM keluar";
$resultKeluar = $koneksi->query($queryKeluar);
$barangKeluar = $resultKeluar->fetch_assoc()['total_keluar'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <?php include '../komponen/navbar.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <?php include '../komponen/sidebar.php'; ?>
            <div class="col-md-9 offset-md-3 mt-4">
                <h1>Selamat datang, <?php echo $_SESSION['username']; ?>!</h1>
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-boxes fa-3x mb-3"></i>
                                <h5 class="card-title">Total Stok Barang</h5>
                                <p class="card-text"><?php echo $totalStok ?? 0; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-download fa-3x mb-3"></i>
                                <h5 class="card-title">Barang Masuk</h5>
                                <p class="card-text"><?php echo $barangMasuk ?? 0; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-upload fa-3x mb-3"></i>
                                <h5 class="card-title">Barang Keluar</h5>
                                <p class="card-text"><?php echo $barangKeluar ?? 0; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('logoutButton').addEventListener('click', function() {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda akan logout!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Logout!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '../fungsi/logout.php';
                }
            });
        });
    </script>
</body>
</html>
