<?php
session_start();
require 'koneksi.php';

// proteksi halaman, cek user udah login apa belum
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// ngatur preferensi bahasa dari sesi
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = htmlspecialchars($_GET['lang']);
}
$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'id';

// kamus bahasa
$text = [
    'id' => ['title' => 'Data Mahasiswa', 'add' => 'Tambah Data', 'logout' => 'Keluar', 'nim' => 'NIM', 'nama' => 'Nama', 'email' => 'Email', 'jurusan' => 'Jurusan', 'angkatan' => 'Angkatan', 'aksi' => 'Aksi', 'edit' => 'Ubah', 'delete' => 'Hapus', 'confirm' => 'Yakin ingin menghapus?'],
    'en' => ['title' => 'Student Data', 'add' => 'Add Data', 'logout' => 'Logout', 'nim' => 'Student ID', 'nama' => 'Name', 'email' => 'Email', 'jurusan' => 'Major', 'angkatan' => 'Batch', 'aksi' => 'Action', 'edit' => 'Edit', 'delete' => 'Delete', 'confirm' => 'Are you sure?']
];

// modul delete: ngehapus data dari database
if (isset($_GET['hapus'])) {
    $id = htmlspecialchars($_GET['hapus']);
    // pake prepared statement buat cegah sql injection
    $stmt = $pdo->prepare("DELETE FROM mahasiswa WHERE id = :id");[cite: 80, 157]
    $stmt->execute([':id' => $id]);
    header("Location: index.php");
    exit;
}

// modul read: ngambil semua data mahasiswa
$stmt = $pdo->prepare("SELECT * FROM mahasiswa ORDER BY nama ASC");[cite: 78]
$stmt->execute();
$mahasiswa = $stmt->fetchAll(PDO::FETCH_ASSOC);[cite: 72]
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title><?= $text[$lang]['title'] ?></title>
</head>
<body>
    <div style="text-align: right;">
        <a href="?lang=id">ID</a> | <a href="?lang=en">EN</a> | 
        <a href="logout.php"><?= $text[$lang]['logout'] ?> (<?= htmlspecialchars($_SESSION['username']) ?>)</a>
    </div>
    
    <h2><?= $text[$lang]['title'] ?></h2>
    <a href="tambah.php"><?= $text[$lang]['add'] ?></a><br><br>

    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th><?= $text[$lang]['nim'] ?></th>
            <th><?= $text[$lang]['nama'] ?></th>
            <th><?= $text[$lang]['email'] ?></th>
            <th><?= $text[$lang]['jurusan'] ?></th>
            <th><?= $text[$lang]['angkatan'] ?></th>
            <th><?= $text[$lang]['aksi'] ?></th>
        </tr>
        <?php foreach ($mahasiswa as $mhs): ?>
        <tr>
            <td><?= htmlspecialchars($mhs['nim']) ?></td>
            <td><?= htmlspecialchars($mhs['nama']) ?></td>
            <td><?= htmlspecialchars($mhs['email']) ?></td>
            <td><?= htmlspecialchars($mhs['jurusan']) ?></td>
            <td><?= htmlspecialchars($mhs['angkatan']) ?></td>
            <td>
                <a href="edit.php?id=<?= $mhs['id'] ?>"><?= $text[$lang]['edit'] ?></a> | 
                <a href="index.php?hapus=<?= $mhs['id'] ?>" onclick="return confirm('<?= $text[$lang]['confirm'] ?>')"><?= $text[$lang]['delete'] ?></a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
