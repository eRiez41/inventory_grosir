<!-- fungsi/fungsi_hapus_barang_keluar.php -->
<?php
session_start();
include '../koneksi/koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil jumlah barang keluar sebelum menghapus
    $queryAmbilJumlah = "SELECT jumlah, barang_id FROM keluar WHERE id = ?";
    $stmtAmbilJumlah = $koneksi->prepare($queryAmbilJumlah);
    $stmtAmbilJumlah->bind_param("i", $id);
    $stmtAmbilJumlah->execute();
    $resultAmbilJumlah = $stmtAmbilJumlah->get_result();
    $keluar = $resultAmbilJumlah->fetch_assoc();
    $jumlahKeluar = $keluar['jumlah'];
    $barang_id = $keluar['barang_id'];

    // Query untuk menghapus data barang keluar
    $queryHapusKeluar = "DELETE FROM keluar WHERE id = ?";
    $stmtHapusKeluar = $koneksi->prepare($queryHapusKeluar);
    $stmtHapusKeluar->bind_param("i", $id);

    // Query untuk mengembalikan stok barang
    $queryUpdateStok = "UPDATE barang SET stok = stok + ? WHERE id = ?";
    $stmtUpdateStok = $koneksi->prepare($queryUpdateStok);
    $stmtUpdateStok->bind_param("ii", $jumlahKeluar, $barang_id);

    $koneksi->begin_transaction();

    if ($stmtHapusKeluar->execute() && $stmtUpdateStok->execute()) {
        $koneksi->commit();
        $_SESSION['hapus_sukses'] = true;
    } else {
        $koneksi->rollback();
        $_SESSION['hapus_error'] = "Gagal menghapus data barang keluar: " . $stmtHapusKeluar->error . " " . $stmtUpdateStok->error;
    }

    $stmtHapusKeluar->close();
    $stmtUpdateStok->close();
    $stmtAmbilJumlah->close();
}

header("Location: ../halaman/barang_keluar.php");
exit();
?>
