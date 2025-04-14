<?php
require_once(__DIR__ . '/../env.php');


// Load env dari file
loadEnv(__DIR__ . '/../.env');

// Ambil nilai dari env
$host = getenv('DB_HOST');
$port = getenv('DB_PORT');
$db   = getenv('DB_DATABASE');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');

// Buat koneksi ke MySQL
$conn = new mysqli($host, $user, $pass, $db, $port);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
