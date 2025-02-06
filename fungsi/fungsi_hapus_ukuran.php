<!-- fungsi/fungsi_hapus_ukuran.php -->
<?php
session_start();
include '../koneksi/koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk menghapus ukuran
    $query = "DELETE FROM ukuran WHERE id = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['hapus_sukses'] = true;
    } else {
        $_SESSION['hapus_error'] = "Gagal menghapus ukuran: " . $stmt->error;
    }

    $stmt->close();
}

header("Location: ../halaman/ukuran.php");
exit();
?>
