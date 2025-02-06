<!-- komponen/sidebar.php -->
<div class="sidebar">
    <ul class="list-group">
        <li class="list-group-item">
            <a href="../halaman/dashboard.php" class="text-decoration-none text-light">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </li>
        <li class="list-group-item menu-item">
            <a href="#" class="text-decoration-none text-light" onclick="toggleSubMenu(event, 'barangSubMenu', 'toggleSymbolBarang')">
                <i class="fas fa-box-open"></i> Barang <span id="toggleSymbolBarang" class="symbol">ˇ</span>
            </a>
            <ul class="submenu list-group" id="barangSubMenu">
                <li class="list-group-item">
                    <a href="../halaman/barang.php" class="text-decoration-none text-light">
                        <i class="fas fa-boxes"></i> Semua Barang
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="../halaman/barang_masuk.php" class="text-decoration-none text-light">
                        <i class="fas fa-download"></i> Barang Masuk
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="../halaman/barang_keluar.php" class="text-decoration-none text-light">
                        <i class="fas fa-upload"></i> Barang Keluar
                    </a>
                </li>
            </ul>
        </li>
        <li class="list-group-item menu-item">
            <a href="#" class="text-decoration-none text-light" onclick="toggleSubMenu(event, 'kelolaBarangSubMenu', 'toggleSymbolKelolaBarang')">
                <i class="fas fa-cog"></i> Kelola Barang <span id="toggleSymbolKelolaBarang" class="symbol">ˇ</span>
            </a>
            <ul class="submenu list-group" id="kelolaBarangSubMenu">
                <li class="list-group-item">
                    <a href="../halaman/warna.php" class="text-decoration-none text-light">
                        <i class="fas fa-palette"></i> Warna Barang
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="../halaman/ukuran.php" class="text-decoration-none text-light">
                        <i class="fas fa-ruler-combined"></i> Ukuran Barang
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="../halaman/kategori.php" class="text-decoration-none text-light">
                        <i class="fas fa-tags"></i> Kategori Barang
                    </a>
                </li>
            </ul>
        </li>
        <li class="list-group-item">
            <a href="../halaman/suplier.php" class="text-decoration-none text-light">
                <i class="fas fa-truck"></i> Suplier
            </a>
        </li>
    </ul>
</div>

<link rel="stylesheet" href="../aset/css/sidebar.css">

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Muat status submenu dari localStorage saat halaman dimuat
        const barangSubMenu = document.getElementById('barangSubMenu');
        const toggleSymbolBarang = document.getElementById('toggleSymbolBarang');
        const kelolaBarangSubMenu = document.getElementById('kelolaBarangSubMenu');
        const toggleSymbolKelolaBarang = document.getElementById('toggleSymbolKelolaBarang');

        if (localStorage.getItem('barangSubMenu') === 'show') {
            barangSubMenu.classList.add('show');
            toggleSymbolBarang.textContent = 'ˆ';
        }

        if (localStorage.getItem('kelolaBarangSubMenu') === 'show') {
            kelolaBarangSubMenu.classList.add('show');
            toggleSymbolKelolaBarang.textContent = 'ˆ';
        }
    });

    function toggleSubMenu(event, subMenuId, toggleSymbolId) {
        event.preventDefault();
        const subMenu = document.getElementById(subMenuId);
        const toggleSymbol = document.getElementById(toggleSymbolId);

        subMenu.classList.toggle('show');

        // Ubah simbol berdasarkan status submenu
        if (subMenu.classList.contains('show')) {
            toggleSymbol.textContent = 'ˆ';
            localStorage.setItem(subMenuId, 'show');
        } else {
            toggleSymbol.textContent = 'ˇ';
            localStorage.setItem(subMenuId, 'hide');
        }
    }
</script>

<!-- Tambahkan Font Awesome ke dalam <head> di file HTML utama Anda -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
