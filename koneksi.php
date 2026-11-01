<?php
$host = "localhost";
$nama_db = "sim_data";
$username = "root";
// kosongin aja kalo di lokal ngga pake password
$password = ""; 

// nyiapin dsn buat koneksi ke mysql
$dsn = "mysql:host=" . $host . ";dbname=" . $nama_db . ";charset=utf8";

try {
    $pdo = new PDO($dsn, $username, $password);
    // aktifin mode exception biar gampang nangkep error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // matiin proses kalo koneksi gagal
    die("Koneksi gagal: " . $e->getMessage());
}
?>
