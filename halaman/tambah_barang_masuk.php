<!-- halaman/tambah_barang_masuk.php -->
<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include '../koneksi/koneksi.php';

$errorMessage = '';
$success = false;

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
    $foto = '';
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

    // Query untuk menambahkan barang baru
    $queryBarang = "INSERT INTO barang (nama, kategori_id, ukuran_id, warna_id, stok, foto, deskripsi) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmtBarang = $koneksi->prepare($queryBarang);
    if ($stmtBarang === false) {
        $errorMessage = "Error preparing barang statement: " . $koneksi->error;
    } else {
        $stmtBarang->bind_param("siiisss", $nama_barang, $kategori_id, $ukuran_id, $warna_id, $jumlah, $foto, $deskripsi);
    }

    // Query untuk menambahkan data ke tabel masuk
    $queryMasuk = "INSERT INTO masuk (barang_id, tanggal_masuk, jumlah, id_suplier) VALUES ((SELECT id FROM barang WHERE nama = ? LIMIT 1), ?, ?, ?)";
    $stmtMasuk = $koneksi->prepare($queryMasuk);
    if ($stmtMasuk === false) {
        $errorMessage = "Error preparing masuk statement: " . $koneksi->error;
    } else {
        $stmtMasuk->bind_param("ssii", $nama_barang, $tanggal_masuk, $jumlah, $suplier_id);
    }

    if (empty($errorMessage)) {
        $koneksi->begin_transaction();

        if ($stmtBarang->execute() && $stmtMasuk->execute()) {
            $koneksi->commit();
            $success = true;
        } else {
            $koneksi->rollback();
            $errorMessage = "Gagal menambahkan barang masuk: " . $stmtBarang->error . " " . $stmtMasuk->error;
        }

        $stmtBarang->close();
        $stmtMasuk->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Barang Masuk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <?php include '../komponen/navbar.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <?php include '../komponen/sidebar.php'; ?>
            <div class="col-md-9 offset-md-3 mt-4">
                <h1>Tambah Barang Masuk</h1>
                <form id="tambahBarangMasukForm" method="POST" action="" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="nama_barang" class="form-label">Nama Barang</label>
                        <input type="text" class="form-control" id="nama_barang" name="nama_barang" required>
                    </div>
                    <div class="mb-3">
                        <label for="kategori" class="form-label">Kategori</label>
                        <select class="form-select" id="kategori" name="kategori" required>
                            <option value="">Pilih Kategori</option>
                            <?php while ($row = $resultKategori->fetch_assoc()): ?>
                                <option value="<?php echo $row['id']; ?>"><?php echo $row['nama_kategori']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="ukuran" class="form-label">Ukuran</label>
                        <select class="form-select" id="ukuran" name="ukuran" required>
                            <option value="">Pilih Ukuran</option>
                            <?php while ($row = $resultUkuran->fetch_assoc()): ?>
                                <option value="<?php echo $row['id']; ?>"><?php echo $row['nama_ukuran']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="warna" class="form-label">Warna</label>
                        <select class="form-select" id="warna" name="warna" required>
                            <option value="">Pilih Warna</option>
                            <?php while ($row = $resultWarna->fetch_assoc()): ?>
                                <option value="<?php echo $row['id']; ?>"><?php echo $row['nama_warna']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="suplier" class="form-label">Suplier</label>
                        <select class="form-select" id="suplier" name="suplier" required>
                            <option value="">Pilih Suplier</option>
                            <?php while ($row = $resultSuplier->fetch_assoc()): ?>
                                <option value="<?php echo $row['id']; ?>"><?php echo $row['nama']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
                        <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk" required>
                    </div>
                    <div class="mb-3">
                        <label for="jumlah" class="form-label">Jumlah</label>
                        <input type="number" class="form-control" id="jumlah" name="jumlah" required>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="foto" class="form-label">Foto</label>
                        <input type="file" class="form-control" id="foto" name="foto">
                    </div>
                    <button type="submit" class="btn btn-primary">Tambah Barang Masuk</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($success): ?>
                Swal.fire('Sukses', 'Barang masuk berhasil ditambahkan', 'success').then(() => {
                    window.location.href = 'barang_masuk.php';
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
