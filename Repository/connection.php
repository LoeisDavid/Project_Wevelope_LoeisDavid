<?php
require_once(__DIR__ . '/../env.php');
require_once __DIR__ . '/Medoo.php';
use Medoo\Medoo;

// Ambil nilai dari env
$host = $DB_HOST;
$port = $DB_PORT;
$db   = $DB_DATABASE;
$user =$DB_USER;
$pass = $DB_PASS;

// Buat koneksi ke MySQL
$database = new Medoo([
    // Jenis database: mysql, pgsql, sqlite, sqlsrv, oracle, dll.
    'database_type' => 'mysql',

    // Nama database yang akan dipakai
    'database_name' => $db,

    // Host atau server database (bisa IP atau domain)
    'server'        => $DB_HOST,

    // Username dan password untuk koneksi
    'username'      => $user,
    'password'      => $pass,

    // Karakter set yang dipakai (opsional, tapi direkomendasikan utf8mb4)
    'charset'       => 'utf8mb4',

    // (Opsional) Port kalau tidak pakai port default
    // 'port'        => 3306,

    // (Opsional) Prefix untuk nama tabel
    // misal 'prefix_' maka Medoo->select('users', ...) akan jadi SELECT * FROM prefix_users
    // 'prefix'      => 'prefix_',

    // (Opsional) Array opsi PDO langsung
    // Contoh untuk case-sensitivity, error mode, atau persistent connection
    // 'option'      => [
    //     PDO::ATTR_CASE               => PDO::CASE_NATURAL,
    //     PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    //     PDO::ATTR_PERSISTENT         => true
    // ],
]);
