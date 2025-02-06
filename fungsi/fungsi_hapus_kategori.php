<!-- fungsi/fungsi_hapus_kategori.php -->
<?php
session_start();
include '../koneksi/koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk menghapus kategori
    $query = "DELETE FROM kategori WHERE id = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['hapus_sukses'] = true;
    } else {
        $_SESSION['hapus_error'] = "Gagal menghapus kategori: " . $stmt->error;
    }

    $stmt->close();
}

header("Location: ../halaman/kategori.php");
exit();
?>
