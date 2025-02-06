<?php
session_start();
include '../koneksi/koneksi.php';

$username = $_POST['username'];
$password = $_POST['password'];

// Query sederhana untuk contoh, gunakan prepared statements untuk keamanan
$query = "SELECT * FROM user WHERE username='$username' AND password='$password'";
$result = $koneksi->query($query);

if ($result->num_rows > 0) {
    $_SESSION['username'] = $username;
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Username atau password salah']);
}
?>
