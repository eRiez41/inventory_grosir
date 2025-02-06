<!-- fungsi/logout.php -->
<?php
session_start();
session_destroy();
header("Location: ../halaman/login.php");
exit();
?>
