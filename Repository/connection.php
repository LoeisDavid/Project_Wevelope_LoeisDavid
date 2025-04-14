<?php
require_once(__DIR__ . '/../env.php');

// Ambil nilai dari env
$host = $DB_HOST;
$port = $DB_PORT;
$db   = $DB_DATABASE;
$user =$DB_USER;
$pass = $DB_PASS;

// Buat koneksi ke MySQL
$conn = new mysqli($host, $user, $pass, $db, $port);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
