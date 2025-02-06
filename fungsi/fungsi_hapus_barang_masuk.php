<?php
session_start();
include '../koneksi/koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil jumlah barang masuk sebelum menghapus
    $queryAmbilJumlah = "SELECT jumlah, barang_id FROM masuk WHERE id = ?";
    $stmtAmbilJumlah = $koneksi->prepare($queryAmbilJumlah);
    $stmtAmbilJumlah->bind_param("i", $id);
    $stmtAmbilJumlah->execute();
    $resultAmbilJumlah = $stmtAmbilJumlah->get_result();
    $masuk = $resultAmbilJumlah->fetch_assoc();
    $jumlahMasuk = $masuk['jumlah'];
    $barang_id = $masuk['barang_id'];

    // Periksa apakah sudah ada barang yang keluar
    $queryCekKeluar = "SELECT SUM(jumlah) AS total_keluar FROM keluar WHERE barang_id = ?";
    $stmtCekKeluar = $koneksi->prepare($queryCekKeluar);
    $stmtCekKeluar->bind_param("i", $barang_id);
    $stmtCekKeluar->execute();
    $resultCekKeluar = $stmtCekKeluar->get_result();
    $keluar = $resultCekKeluar->fetch_assoc();
    $totalKeluar = $keluar['total_keluar'];

    if ($totalKeluar > 0) {
        // Jika sudah ada barang yang keluar, beri notifikasi
        $_SESSION['hapus_error'] = "Tidak dapat menghapus barang masuk karena sudah ada barang yang keluar.";
    } else {
        // Query untuk menghapus data barang masuk
        $queryHapusMasuk = "DELETE FROM masuk WHERE id = ?";
        $stmtHapusMasuk = $koneksi->prepare($queryHapusMasuk);
        $stmtHapusMasuk->bind_param("i", $id);

        // Query untuk mengurangi stok barang
        $queryUpdateStok = "UPDATE barang SET stok = stok - ? WHERE id = ?";
        $stmtUpdateStok = $koneksi->prepare($queryUpdateStok);
        $stmtUpdateStok->bind_param("ii", $jumlahMasuk, $barang_id);

        $koneksi->begin_transaction();

        if ($stmtHapusMasuk->execute() && $stmtUpdateStok->execute()) {
            // Periksa apakah stok menjadi 0 setelah pengurangan
            $queryCekStok = "SELECT stok, foto FROM barang WHERE id = ?";
            $stmtCekStok = $koneksi->prepare($queryCekStok);
            $stmtCekStok->bind_param("i", $barang_id);
            $stmtCekStok->execute();
            $resultCekStok = $stmtCekStok->get_result();
            $barang = $resultCekStok->fetch_assoc();
            $stokBarang = $barang['stok'];
            $fotoBarang = $barang['foto'];

            if ($stokBarang == 0) {
                // Jika stok menjadi 0, hapus barang dari tabel barang
                $queryHapusBarang = "DELETE FROM barang WHERE id = ?";
                $stmtHapusBarang = $koneksi->prepare($queryHapusBarang);
                $stmtHapusBarang->bind_param("i", $barang_id);
                $stmtHapusBarang->execute();

                // Hapus file gambar dari folder jika ada
                if (!empty($fotoBarang) && file_exists("../gambar_barang/" . $fotoBarang)) {
                    unlink("../gambar_barang/" . $fotoBarang);
                }
            }

            $koneksi->commit();
            $_SESSION['hapus_sukses'] = true;
        } else {
            $koneksi->rollback();
            $_SESSION['hapus_error'] = "Gagal menghapus data barang masuk: " . $stmtHapusMasuk->error . " " . $stmtUpdateStok->error;
        }

        $stmtHapusMasuk->close();
        $stmtUpdateStok->close();
    }

    $stmtAmbilJumlah->close();
    $stmtCekKeluar->close();
}

header("Location: ../halaman/barang_masuk.php");
exit();
?>
