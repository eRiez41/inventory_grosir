<!-- index.php -->
<?php
session_start();
if (isset($_SESSION['username'])) {
    header("Location: halaman/dashboard.php");
} else {
    header("Location: halaman/login.php");
}
exit();
?>
