<!-- halaman/edit_barang_masuk.php -->
<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include '../koneksi/koneksi.php';

$errorMessage = '';
$success = false;

// Ambil ID barang dari parameter URL
$barang_id = $_GET['id'] ?? null;

if (!$barang_id) {
    header("Location: barang.php");
    exit();
}

// Ambil data barang berdasarkan ID
$queryBarang = "SELECT b.*, m.id_suplier FROM barang b JOIN masuk m ON b.id = m.barang_id WHERE b.id = ?";
$stmtBarang = $koneksi->prepare($queryBarang);
$stmtBarang->bind_param("i", $barang_id);
$stmtBarang->execute();
$barang = $stmtBarang->get_result()->fetch_assoc();

if (!$barang) {
    header("Location: barang.php");
    exit();
}

// Ambil data dari tabel kategori, ukuran, warna, dan suplier
$queryKategori = "SELECT id, nama_kategori FROM kategori";
$resultKategori = $koneksi->query($queryKategori);

$queryUkuran = "SELECT id, nama_ukuran FROM ukuran";
$resultUkuran = $koneksi->query($queryUkuran);

$queryWarna = "SELECT id, nama_warna FROM warna";
$resultWarna = $koneksi->query($queryWarna);

$querySuplier = "SELECT id, nama FROM suplier";
$resultSuplier = $koneksi->query($querySuplier);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_barang = $_POST['nama_barang'];
    $kategori_id = $_POST['kategori'];
    $ukuran_id = $_POST['ukuran'];
    $warna_id = $_POST['warna'];
    $suplier_id = $_POST['suplier'];
    $tanggal_masuk = $_POST['tanggal_masuk'];
    $jumlah = $_POST['jumlah'];
    $deskripsi = $_POST['deskripsi'];

    // Handle file upload
    $foto = $barang['foto'];
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = '../gambar_barang/';
        $foto = basename($_FILES['foto']['name']);
        $uploadFile = $uploadDir . $foto;

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $uploadFile)) {
            // File uploaded successfully
        } else {
            $errorMessage = "Gagal mengunggah foto.";
        }
    }

    // Query untuk memperbarui barang
    $queryUpdateBarang = "UPDATE barang SET nama = ?, kategori_id = ?, ukuran_id = ?, warna_id = ?, stok = ?, foto = ?, deskripsi = ? WHERE id = ?";
    $stmtUpdateBarang = $koneksi->prepare($queryUpdateBarang);
    if ($stmtUpdateBarang === false) {
        $errorMessage = "Error preparing update barang statement: " . $koneksi->error;
    } else {
        $stmtUpdateBarang->bind_param("siiisssi", $nama_barang, $kategori_id, $ukuran_id, $warna_id, $jumlah, $foto, $deskripsi, $barang_id);
    }

    // Query untuk memperbarui data ke tabel masuk
    $queryUpdateMasuk = "UPDATE masuk SET tanggal_masuk = ?, jumlah = ?, id_suplier = ? WHERE barang_id = ?";
    $stmtUpdateMasuk = $koneksi->prepare($queryUpdateMasuk);
    if ($stmtUpdateMasuk === false) {
        $errorMessage = "Error preparing update masuk statement: " . $koneksi->error;
    } else {
        $stmtUpdateMasuk->bind_param("siii", $tanggal_masuk, $jumlah, $suplier_id, $barang_id);
    }

    if (empty($errorMessage)) {
        $koneksi->begin_transaction();

        if ($stmtUpdateBarang->execute() && $stmtUpdateMasuk->execute()) {
            $koneksi->commit();
            $success = true;
        } else {
            $koneksi->rollback();
            $errorMessage = "Gagal memperbarui barang masuk: " . $stmtUpdateBarang->error . " " . $stmtUpdateMasuk->error;
        }

        $stmtUpdateBarang->close();
        $stmtUpdateMasuk->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barang Masuk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <?php include '../komponen/navbar.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <?php include '../komponen/sidebar.php'; ?>
            <div class="col-md-9 offset-md-3 mt-4">
                <h1>Edit Barang Masuk</h1>
                <form id="editBarangMasukForm" method="POST" action="" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="nama_barang" class="form-label">Nama Barang</label>
                        <input type="text" class="form-control" id="nama_barang" name="nama_barang" value="<?php echo htmlspecialchars($barang['nama']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="kategori" class="form-label">Kategori</label>
                        <select class="form-select" id="kategori" name="kategori" required>
                            <option value="">Pilih Kategori</option>
                            <?php while ($row = $resultKategori->fetch_assoc()): ?>
                                <option value="<?php echo $row['id']; ?>" <?php echo ($row['id'] == $barang['kategori_id']) ? 'selected' : ''; ?>><?php echo $row['nama_kategori']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="ukuran" class="form-label">Ukuran</label>
                        <select class="form-select" id="ukuran" name="ukuran" required>
                            <option value="">Pilih Ukuran</option>
                            <?php while ($row = $resultUkuran->fetch_assoc()): ?>
                                <option value="<?php echo $row['id']; ?>" <?php echo ($row['id'] == $barang['ukuran_id']) ? 'selected' : ''; ?>><?php echo $row['nama_ukuran']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="warna" class="form-label">Warna</label>
                        <select class="form-select" id="warna" name="warna" required>
                            <option value="">Pilih Warna</option>
                            <?php while ($row = $resultWarna->fetch_assoc()): ?>
                                <option value="<?php echo $row['id']; ?>" <?php echo ($row['id'] == $barang['warna_id']) ? 'selected' : ''; ?>><?php echo $row['nama_warna']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="suplier" class="form-label">Suplier</label>
                        <select class="form-select" id="suplier" name="suplier" required>
                            <option value="">Pilih Suplier</option>
                            <?php while ($row = $resultSuplier->fetch_assoc()): ?>
                                <option value="<?php echo $row['id']; ?>" <?php echo ($row['id'] == $barang['id_suplier']) ? 'selected' : ''; ?>><?php echo $row['nama']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
                        <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk" value="<?php echo htmlspecialchars($barang['tanggal_masuk']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="jumlah" class="form-label">Jumlah</label>
                        <input type="number" class="form-control" id="jumlah" name="jumlah" value="<?php echo htmlspecialchars($barang['stok']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required><?php echo htmlspecialchars($barang['deskripsi']); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="foto" class="form-label">Foto</label>
                        <input type="file" class="form-control" id="foto" name="foto">
                        <?php if (!empty($barang['foto'])): ?>
                            <img src="../gambar_barang/<?php echo htmlspecialchars($barang['foto']); ?>" alt="Foto Barang" style="max-width: 100px;">
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($success): ?>
                Swal.fire('Sukses', 'Barang masuk berhasil diperbarui', 'success').then(() => {
                    window.location.href = 'barang.php';
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
