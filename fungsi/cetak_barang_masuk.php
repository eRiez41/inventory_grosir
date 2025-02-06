<?php
require('../library/fpdf.php');
include '../koneksi/koneksi.php';

class PDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Times', 'B', 16);
        $this->Cell(0, 10, 'Daftar Barang Masuk', 0, 1, 'C');
        $this->Cell(0, 10, 'El Sandal Grosir', 0, 1, 'C');
        $this->Ln(5);

        // Garis pemisah
        $this->Line(10, 30, 287, 30);
        $this->Ln(10);

        // Hitung lebar total kolom
        $total_width = 10 + 40 + 30 + 25 + 40;

        // Tentukan posisi X untuk menengahkan tabel
        $this->SetX((297 - $total_width) / 2);

        // Set warna latar belakang untuk header
        $this->SetFillColor(255, 255, 0); // Kuning

        // Set font untuk header tabel
        $this->SetFont('Times', 'B', 12);
        $this->Cell(10, 10, 'No', 1, 0, 'C', true); // Tengahkan judul kolom dengan warna latar
        $this->Cell(40, 10, 'Nama Barang', 1, 0, 'C', true); // Tengahkan judul kolom dengan warna latar
        $this->Cell(30, 10, 'Tanggal Masuk', 1, 0, 'C', true); // Tengahkan judul kolom dengan warna latar
        $this->Cell(25, 10, 'Jumlah', 1, 0, 'C', true); // Tengahkan judul kolom dengan warna latar
        $this->Cell(40, 10, 'Suplier', 1, 0, 'C', true); // Tengahkan judul kolom dengan warna latar
        $this->Ln();
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Times', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }
}

$pdf = new PDF('L'); // Set orientasi menjadi landscape
$pdf->AddPage();

// Hitung lebar total kolom
$total_width = 10 + 40 + 30 + 25 + 40;

// Tentukan margin kiri untuk menengahkan tabel
$pdf->SetLeftMargin((297 - $total_width) / 2);

$pdf->SetFont('Times', '', 10);

// Ambil data barang masuk dengan menggabungkan tabel barang dan suplier
$queryMasuk = "
    SELECT m.id, b.nama AS nama_barang, m.tanggal_masuk, m.jumlah, s.nama AS nama_suplier
    FROM masuk m
    JOIN barang b ON m.barang_id = b.id
    JOIN suplier s ON m.id_suplier = s.id
";
$resultMasuk = $koneksi->query($queryMasuk);

$no = 1;
while ($row = $resultMasuk->fetch_assoc()) {
    $pdf->Cell(10, 10, $no++, 1);
    $pdf->Cell(40, 10, $row['nama_barang'], 1);
    $pdf->Cell(30, 10, $row['tanggal_masuk'], 1);
    $pdf->Cell(25, 10, $row['jumlah'], 1);
    $pdf->Cell(40, 10, $row['nama_suplier'], 1);
    $pdf->Ln();
}

$pdf->Output();
?>
