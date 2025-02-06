<!-- halaman/tambah_warna.php -->
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
    $nama_warna = $_POST['nama_warna'];

    // Query untuk menambahkan warna baru
    $query = "INSERT INTO warna (nama_warna) VALUES (?)";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("s", $nama_warna);

    if ($stmt->execute()) {
        $success = true;
    } else {
        $errorMessage = "Gagal menambahkan warna: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Warna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <?php include '../komponen/navbar.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <?php include '../komponen/sidebar.php'; ?>
            <div class="col-md-9 offset-md-3 mt-4">
                <h1>Tambah Warna</h1>
                <form id="tambahWarnaForm" method="POST" action="">
                    <div class="mb-3">
                        <label for="nama_warna" class="form-label">Nama Warna</label>
                        <input type="text" class="form-control" id="nama_warna" name="nama_warna" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Tambah Warna</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($success): ?>
                Swal.fire('Sukses', 'Warna berhasil ditambahkan', 'success').then(() => {
                    window.location.href = 'warna.php';
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
