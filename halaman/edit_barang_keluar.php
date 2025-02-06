<!-- halaman/edit_barang_keluar.php -->
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
$queryKeluar = "SELECT * FROM keluar WHERE id = ?";
$stmtKeluar = $koneksi->prepare($queryKeluar);
$stmtKeluar->bind_param("i", $id);
$stmtKeluar->execute();
$keluar = $stmtKeluar->get_result()->fetch_assoc();

// Ambil data barang dari tabel barang
$queryBarang = "SELECT id, nama FROM barang";
$resultBarang = $koneksi->query($queryBarang);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $barang_id = $_POST['barang'];
    $jumlah = $_POST['jumlah'];
    $penerima = $_POST['penerima'];
    $tanggal_keluar = $_POST['tanggal_keluar'];

    // Ambil jumlah barang keluar sebelumnya
    $queryAmbilJumlahLama = "SELECT jumlah FROM keluar WHERE id = ?";
    $stmtAmbilJumlahLama = $koneksi->prepare($queryAmbilJumlahLama);
    $stmtAmbilJumlahLama->bind_param("i", $id);
    $stmtAmbilJumlahLama->execute();
    $resultAmbilJumlahLama = $stmtAmbilJumlahLama->get_result();
    $jumlahLama = $resultAmbilJumlahLama->fetch_assoc()['jumlah'];

    // Hitung selisih jumlah barang keluar
    $selisihJumlah = $jumlah - $jumlahLama;

    // Query untuk memperbarui data barang keluar
    $queryUpdateKeluar = "UPDATE keluar SET barang_id = ?, jumlah = ?, penerima = ?, tanggal_keluar = ? WHERE id = ?";
    $stmtUpdateKeluar = $koneksi->prepare($queryUpdateKeluar);
    $stmtUpdateKeluar->bind_param("iissi", $barang_id, $jumlah, $penerima, $tanggal_keluar, $id);

    // Query untuk memperbarui stok barang
    $queryUpdateStok = "UPDATE barang SET stok = stok - ? WHERE id = ?";
    $stmtUpdateStok = $koneksi->prepare($queryUpdateStok);
    $stmtUpdateStok->bind_param("ii", $selisihJumlah, $barang_id);

    $koneksi->begin_transaction();

    if ($stmtUpdateKeluar->execute() && $stmtUpdateStok->execute()) {
        $koneksi->commit();
        $success = true;
    } else {
        $koneksi->rollback();
        $errorMessage = "Gagal memperbarui barang keluar: " . $stmtUpdateKeluar->error . " " . $stmtUpdateStok->error;
    }

    $stmtUpdateKeluar->close();
    $stmtUpdateStok->close();
    $stmtAmbilJumlahLama->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barang Keluar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <?php include '../komponen/navbar.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <?php include '../komponen/sidebar.php'; ?>
            <div class="col-md-9 offset-md-3 mt-4">
                <h1>Edit Barang Keluar</h1>
                <form id="editBarangKeluarForm" method="POST" action="">
                    <div class="mb-3">
                        <label for="barang" class="form-label">Pilih Barang</label>
                        <select class="form-select" id="barang" name="barang" required>
                            <option value="">Pilih Barang</option>
                            <?php while ($row = $resultBarang->fetch_assoc()): ?>
                                <option value="<?php echo $row['id']; ?>" <?php if ($row['id'] == $keluar['barang_id']) echo 'selected'; ?>><?php echo $row['nama']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="jumlah" class="form-label">Jumlah</label>
                        <input type="number" class="form-control" id="jumlah" name="jumlah" value="<?php echo $keluar['jumlah']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="penerima" class="form-label">Penerima</label>
                        <input type="text" class="form-control" id="penerima" name="penerima" value="<?php echo $keluar['penerima']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_keluar" class="form-label">Tanggal Keluar</label>
                        <input type="date" class="form-control" id="tanggal_keluar" name="tanggal_keluar" value="<?php echo $keluar['tanggal_keluar']; ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($success): ?>
                Swal.fire('Sukses', 'Barang keluar berhasil diperbarui', 'success').then(() => {
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