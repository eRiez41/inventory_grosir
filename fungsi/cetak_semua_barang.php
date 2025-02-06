<?php
require('../library/fpdf.php');
include '../koneksi/koneksi.php';

class PDF extends FPDF
{
    function Header()
    {
        // Set font untuk judul
        $this->SetFont('Times', 'B', 16);
        $this->Cell(0, 10, 'Stok Barang', 0, 1, 'C');
        $this->Cell(0, 10, 'El Sandal Grosir', 0, 1, 'C');
        $this->Ln(5);

        // Garis pemisah
        $this->Line(10, 30, 287, 30);
        $this->Ln(10);

        // Hitung lebar total kolom
        $total_width = 10 + 40 + 40 + 25 + 25 + 20 + 45;

        // Tentukan posisi X untuk menengahkan tabel
        $this->SetX((297 - $total_width) / 2);

        // Set warna latar belakang untuk header
        $this->SetFillColor(255, 255, 0); // Kuning

        // Set font untuk header tabel
        $this->SetFont('Times', 'B', 12);
        $this->Cell(10, 10, 'No', 1, 0, 'C', true); // Tengahkan judul kolom dengan warna latar
        $this->Cell(40, 10, 'Nama Barang', 1, 0, 'C', true); // Tengahkan judul kolom dengan warna latar
        $this->Cell(40, 10, 'Kategori', 1, 0, 'C', true); // Tengahkan judul kolom dengan warna latar
        $this->Cell(25, 10, 'Ukuran', 1, 0, 'C', true); // Tengahkan judul kolom dengan warna latar
        $this->Cell(25, 10, 'Warna', 1, 0, 'C', true); // Tengahkan judul kolom dengan warna latar
        $this->Cell(20, 10, 'Stok', 1, 0, 'C', true); // Tengahkan judul kolom dengan warna latar
        $this->Cell(45, 10, 'Deskripsi', 1, 0, 'C', true); // Tengahkan judul kolom dengan warna latar
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
$total_width = 10 + 40 + 40 + 25 + 25 + 20 + 45;

// Tentukan margin kiri untuk menengahkan tabel
$pdf->SetLeftMargin((297 - $total_width) / 2);

$pdf->SetFont('Times', '', 10);

// Ambil data barang dengan menggabungkan tabel kategori, ukuran, dan warna
$queryBarang = "
    SELECT
        b.id,
        b.nama,
        k.nama_kategori,
        u.nama_ukuran,
        w.nama_warna,
        b.stok,
        b.deskripsi
    FROM
        barang b
    JOIN
        kategori k ON b.kategori_id = k.id
    JOIN
        ukuran u ON b.ukuran_id = u.id
    JOIN
        warna w ON b.warna_id = w.id
";
$resultBarang = $koneksi->query($queryBarang);

$no = 1;
while ($row = $resultBarang->fetch_assoc()) {
    $pdf->Cell(10, 10, $no++, 1);
    $pdf->Cell(40, 10, $row['nama'], 1);
    $pdf->Cell(40, 10, $row['nama_kategori'], 1); // Perpanjang kolom kategori
    $pdf->Cell(25, 10, $row['nama_ukuran'], 1);
    $pdf->Cell(25, 10, $row['nama_warna'], 1);
    $pdf->Cell(20, 10, $row['stok'], 1);
    $pdf->Cell(45, 10, $row['deskripsi'], 1);
    $pdf->Ln();
}

$pdf->Output();
?>
