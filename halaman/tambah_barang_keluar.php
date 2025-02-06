<!-- halaman/tambah_barang_keluar.php -->
<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include '../koneksi/koneksi.php';

$errorMessage = '';
$success = false;

// Ambil data barang dari tabel barang
$queryBarang = "SELECT id, nama FROM barang";
$resultBarang = $koneksi->query($queryBarang);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $barang_id = $_POST['barang'];
    $jumlah = $_POST['jumlah'];
    $penerima = $_POST['penerima'];
    $tanggal_keluar = $_POST['tanggal_keluar'];

    // Query untuk menambahkan data ke tabel keluar
    $queryKeluar = "INSERT INTO keluar (barang_id, jumlah, penerima, tanggal_keluar) VALUES (?, ?, ?, ?)";
    $stmtKeluar = $koneksi->prepare($queryKeluar);
    $stmtKeluar->bind_param("iiss", $barang_id, $jumlah, $penerima, $tanggal_keluar);

    // Query untuk mengurangi stok barang
    $queryUpdateStok = "UPDATE barang SET stok = stok - ? WHERE id = ?";
    $stmtUpdateStok = $koneksi->prepare($queryUpdateStok);
    $stmtUpdateStok->bind_param("ii", $jumlah, $barang_id);

    $koneksi->begin_transaction();

    if ($stmtKeluar->execute() && $stmtUpdateStok->execute()) {
        $koneksi->commit();
        $success = true;
    } else {
        $koneksi->rollback();
        $errorMessage = "Gagal menambahkan barang keluar: " . $stmtKeluar->error . " " . $stmtUpdateStok->error;
    }

    $stmtKeluar->close();
    $stmtUpdateStok->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Barang Keluar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <?php include '../komponen/navbar.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <?php include '../komponen/sidebar.php'; ?>
            <div class="col-md-9 offset-md-3 mt-4">
                <h1>Tambah Barang Keluar</h1>
                <form id="tambahBarangKeluarForm" method="POST" action="">
                    <div class="mb-3">
                        <label for="barang" class="form-label">Pilih Barang</label>
                        <select class="form-select" id="barang" name="barang" required>
                            <option value="">Pilih Barang</option>
                            <?php while ($row = $resultBarang->fetch_assoc()): ?>
                                <option value="<?php echo $row['id']; ?>"><?php echo $row['nama']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="jumlah" class="form-label">Jumlah</label>
                        <input type="number" class="form-control" id="jumlah" name="jumlah" required>
                    </div>
                    <div class="mb-3">
                        <label for="penerima" class="form-label">Penerima</label>
                        <input type="text" class="form-control" id="penerima" name="penerima" required>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_keluar" class="form-label">Tanggal Keluar</label>
                        <input type="date" class="form-control" id="tanggal_keluar" name="tanggal_keluar" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Tambah Barang Keluar</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($success): ?>
                Swal.fire('Sukses', 'Barang keluar berhasil ditambahkan', 'success').then(() => {
                    window.location.href = 'barang_keluar.php';
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
