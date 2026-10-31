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
    'id' => ['title' => 'Ubah Mahasiswa', 'btn' => 'Perbarui', 'back' => 'Kembali'],
    'en' => ['title' => 'Edit Student', 'btn' => 'Update', 'back' => 'Back']
];

$id = $_GET['id'];

// ambil data spesifik buat ditampilin di form
$stmt = $pdo->prepare("SELECT * FROM mahasiswa WHERE id = :id");
$stmt->execute([':id' => $id]);
$mhs = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // sanitasi input
    $nim = htmlspecialchars(trim($_POST['nim']));
    $nama = htmlspecialchars(trim($_POST['nama']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $jurusan = htmlspecialchars(trim($_POST['jurusan']));
    $angkatan = htmlspecialchars(trim($_POST['angkatan']));

    // proses update data ke database
    $stmt = $pdo->prepare("UPDATE mahasiswa SET nim = :nim, nama = :nama, email = :email, jurusan = :jurusan, angkatan = :angkatan WHERE id = :id");[cite: 79]
    $stmt->execute([
        ':nim' => $nim, 
        ':nama' => $nama, 
        ':email' => $email, 
        ':jurusan' => $jurusan, 
        ':angkatan' => $angkatan, 
        ':id' => $id
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
        <input type="text" name="nim" value="<?= htmlspecialchars($mhs['nim']) ?>" required><br>
        
        <label>Nama:</label><br>
        <input type="text" name="nama" value="<?= htmlspecialchars($mhs['nama']) ?>" required><br>
        
        <label>Email:</label><br>
        <input type="email" name="email" value="<?= htmlspecialchars($mhs['email']) ?>" required><br>
        
        <label>Jurusan:</label><br>
        <input type="text" name="jurusan" value="<?= htmlspecialchars($mhs['jurusan']) ?>" required><br>
        
        <label>Angkatan:</label><br>
        <input type="number" name="angkatan" value="<?= htmlspecialchars($mhs['angkatan']) ?>" required><br><br>
        
        <button type="submit"><?= $text[$lang]['btn'] ?></button>
        <a href="index.php"><?= $text[$lang]['back'] ?></a>
    </form>
</body>
</html>
