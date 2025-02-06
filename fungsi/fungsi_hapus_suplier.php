<!-- fungsi/fungsi_hapus_suplier.php -->
<?php
session_start();
include '../koneksi/koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk menghapus supplier
    $query = "DELETE FROM suplier WHERE id = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['hapus_sukses'] = true;
    } else {
        $_SESSION['hapus_error'] = "Gagal menghapus supplier: " . $stmt->error;
    }

    $stmt->close();
}

header("Location: ../halaman/suplier.php");
exit();
?>
