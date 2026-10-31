<?php
session_start();
require 'koneksi.php';

// proteksi halaman
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'id';
$text = [
    'id' => ['title' => 'Tambah Mahasiswa', 'btn' => 'Simpan', 'back' => 'Kembali'],
    'en' => ['title' => 'Add Student', 'btn' => 'Save', 'back' => 'Back']
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // sanitasi input
    $nim = htmlspecialchars(trim($_POST['nim']));
    $nama = htmlspecialchars(trim($_POST['nama']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $jurusan = htmlspecialchars(trim($_POST['jurusan']));
    $angkatan = htmlspecialchars(trim($_POST['angkatan']));

    // proses create data baru ke tabel
    $stmt = $pdo->prepare("INSERT INTO mahasiswa (nim, nama, email, jurusan, angkatan) VALUES (:nim, :nama, :email, :jurusan, :angkatan)");[cite: 77]
    $stmt->execute([
        ':nim' => $nim, 
        ':nama' => $nama, 
        ':email' => $email, 
        ':jurusan' => $jurusan, 
        ':angkatan' => $angkatan
    ]);
    
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title><?= $text[$lang]['title'] ?></title>
</head>
<body>
    <h2><?= $text[$lang]['title'] ?></h2>
    <form method="POST" action="">
        <label>NIM:</label><br>
        <input type="text" name="nim" required><br>
        
        <label>Nama:</label><br>
        <input type="text" name="nama" required><br>
        
        <label>Email:</label><br>
        <input type="email" name="email" required><br>
        
        <label>Jurusan:</label><br>
        <input type="text" name="jurusan" required><br>
        
        <label>Angkatan:</label><br>
        <input type="number" name="angkatan" required><br><br>
        
        <button type="submit"><?= $text[$lang]['btn'] ?></button>
        <a href="index.php"><?= $text[$lang]['back'] ?></a>
    </form>
</body>
</html>
