<?php
$host = 'localhost';
$user = 'root';
$pass = '1sampai8';
$db   = 'projectMagang1';

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
