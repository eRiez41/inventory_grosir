<!-- halaman/tambah_ukuran.php -->
<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include '../koneksi/koneksi.php';

$errorMessage = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_ukuran = $_POST['nama_ukuran'];

    // Query untuk menambahkan ukuran baru
    $query = "INSERT INTO ukuran (nama_ukuran) VALUES (?)";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("s", $nama_ukuran);

    if ($stmt->execute()) {
        $success = true;
    } else {
        $errorMessage = "Gagal menambahkan ukuran: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Ukuran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <?php include '../komponen/navbar.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <?php include '../komponen/sidebar.php'; ?>
            <div class="col-md-9 offset-md-3 mt-4">
                <h1>Tambah Ukuran</h1>
                <form id="tambahUkuranForm" method="POST" action="">
                    <div class="mb-3">
                        <label for="nama_ukuran" class="form-label">Nama Ukuran</label>
                        <input type="text" class="form-control" id="nama_ukuran" name="nama_ukuran" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Tambah Ukuran</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($success): ?>
                Swal.fire('Sukses', 'Ukuran berhasil ditambahkan', 'success').then(() => {
                    window.location.href = 'ukuran.php';
                });
            <?php endif; ?>

            <?php if (!empty($errorMessage)): ?>
                Swal.fire('Gagal', '<?php echo $errorMessage; ?>', 'error');
            <?php endif; ?>
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
