<!-- halaman/edit_ukuran.php -->
<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include '../koneksi/koneksi.php';

$errorMessage = '';
$success = false;

$id = $_GET['id'];
$queryUkuran = "SELECT * FROM ukuran WHERE id = ?";
$stmt = $koneksi->prepare($queryUkuran);
$stmt->bind_param("i", $id);
$stmt->execute();
$ukuran = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_ukuran = $_POST['nama_ukuran'];

    // Query untuk memperbarui data ukuran
    $query = "UPDATE ukuran SET nama_ukuran = ? WHERE id = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("si", $nama_ukuran, $id);

    if ($stmt->execute()) {
        $success = true;
    } else {
        $errorMessage = "Gagal memperbarui ukuran: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Ukuran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <?php include '../komponen/navbar.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <?php include '../komponen/sidebar.php'; ?>
            <div class="col-md-9 offset-md-3 mt-4">
                <h1>Edit Ukuran</h1>
                <form id="editUkuranForm" method="POST" action="">
                    <div class="mb-3">
                        <label for="nama_ukuran" class="form-label">Nama Ukuran</label>
                        <input type="text" class="form-control" id="nama_ukuran" name="nama_ukuran" value="<?php echo $ukuran['nama_ukuran']; ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($success): ?>
                Swal.fire('Sukses', 'Ukuran berhasil diperbarui', 'success').then(() => {
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
