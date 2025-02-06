Aplikasi Inventory Grosir Sendal

Aplikasi ini adalah sistem manajemen inventory barang berbasis PHP dan MySQL, dirancang untuk mengelola stok barang, transaksi masuk & keluar, serta pencatatan data barang dalam bisnis grosir sendal.

 📌 Fitur Utama
✅ Manajemen Barang – Tambah, edit, hapus data barang.  
✅ Manajemen Stok – Catat transaksi barang masuk dan keluar.  
✅ Pencarian Barang – Fitur pencarian berdasarkan nama/kategori.  
✅ Laporan Stok – Generate laporan stok dan transaksi.  
✅ Autentikasi Pengguna – Sistem login dengan hak akses admin.  

 🛠 Instalasi
1. Clone Repository  
   git clone https://github.com/eRiez41/inventory_grosir.git
2. Buat Database di MySQL dengan nama:  
   grosir_sendal
3. Import Database  
   Gunakan file grosir_sendal.sql yang ada dalam folder proyek untuk mengimpor struktur dan data awal ke database.  
4. Konfigurasi Koneksi Database  
   Edit file config.php dan sesuaikan dengan kredensial MySQL Anda:
   php
   $host = "localhost";
   $user = "root"; // Sesuaikan jika ada password
   $pass = "";
   $db   = "grosir_sendal";
   
5. Jalankan Aplikasi  
   Buka browser dan akses:  
   
   http://localhost/inventory_grosir/
   
 🔑 Akun Login Default
- Username: admin  
- Password: admin123  

 📌 Teknologi yang Digunakan
- Backend: PHP (Native)
- Database: MySQL
- Frontend: HTML, CSS, JavaScript (Bootstrap)
- Server: Apache (XAMPP/LAMP)

 🤝 Kontribusi
Silakan fork repo ini dan lakukan pull request jika ingin berkontribusi dalam pengembangannya.

 📜 Lisensi
Aplikasi ini dapat digunakan dan dimodifikasi sesuai kebutuhan.


