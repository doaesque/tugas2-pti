<?php
session_start();
require 'koneksi.php';

// mengatur pilihan bahasa antarmuka
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = htmlspecialchars($_GET['lang']);
}
$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'id';

// kamus bahasa sederhana
$text = [
    'id' => ['title' => 'Masuk', 'user' => 'Nama Pengguna', 'pass' => 'Kata Sandi', 'btn' => 'Masuk', 'err' => 'Username atau password salah!', 'empty' => 'Data tidak boleh kosong!'],
    'en' => ['title' => 'Login', 'user' => 'Username', 'pass' => 'Password', 'btn' => 'Login', 'err' => 'Invalid username or password!', 'empty' => 'Data cannot be empty!']
];

$error_msg = '';

// modul pengolahan form data & validasi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // penerapan sanitasi data input
    $username = htmlspecialchars(trim($_POST['username']));
    $password = trim($_POST['password']);

    // penerapan struktur kontrol percabangan
    if (!empty($username) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // verifikasi password hash
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: index.php");
            exit;
        } else {
            $error_msg = $text[$lang]['err'];
        }
    } else {
        $error_msg = $text[$lang]['empty'];
    }
}
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title><?= $text[$lang]['title'] ?></title>
</head>
<body>
    <div style="text-align: right;">
        <a href="?lang=id">ID</a> | <a href="?lang=en">EN</a>
    </div>
    <h2><?= $text[$lang]['title'] ?></h2>
    
    <?php if ($error_msg): ?>
        <p style="color:red;"><?= $error_msg ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label><?= $text[$lang]['user'] ?>:</label><br>
        <input type="text" name="username" required><br><br>
        
        <label><?= $text[$lang]['pass'] ?>:</label><br>
        <input type="password" name="password" required><br><br>
        
        <button type="submit"><?= $text[$lang]['btn'] ?></button>
    </form>
</body>
</html>
