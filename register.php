<?php
session_start();
require 'koneksi.php';

// mengatur pilihan bahasa antarmuka
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = htmlspecialchars($_GET['lang']);
}
$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'id';

$text = [
    'id' => ['title' => 'Daftar', 'user' => 'Nama Pengguna', 'pass' => 'Kata Sandi', 'btn' => 'Daftar', 'err' => 'Username sudah terdaftar!', 'empty' => 'Data tidak boleh kosong!', 'sukses' => 'Registrasi berhasil, silakan login.'],
    'en' => ['title' => 'Register', 'user' => 'Username', 'pass' => 'Password', 'btn' => 'Register', 'err' => 'Username already exists!', 'empty' => 'Data cannot be empty!', 'sukses' => 'Registration successful, please login.']
];

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // penerapan sanitasi data input
    $username = htmlspecialchars(trim($_POST['username']));
    $password = trim($_POST['password']);
    
    // penerapan struktur kontrol percabangan
    if (!empty($username) && !empty($password)) {
        // ngecek apakah username udah ada
        $stmt_check = $pdo->prepare("SELECT id FROM users WHERE username = :username");
        $stmt_check->execute([':username' => $username]);
        
        if ($stmt_check->fetch()) {
            $message = "<p style='color:red;'>{$text[$lang]['err']}</p>";
        } else {
            // hash password buat keamanan
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password, lang_preference) VALUES (:username, :password, :lang)");[cite: 157]
            $stmt->execute([':username' => $username, ':password' => $hashed_password, ':lang' => $lang]);
            
            $message = "<p style='color:green;'>{$text[$lang]['sukses']} <a href='login.php'>Login</a></p>";
        }
    } else {
        $message = "<p style='color:red;'>{$text[$lang]['empty']}</p>";
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
    
    <?= $message ?>

    <form method="POST" action="">
        <label><?= $text[$lang]['user'] ?>:</label><br>
        <input type="text" name="username" required><br><br>
        
        <label><?= $text[$lang]['pass'] ?>:</label><br>
        <input type="password" name="password" required><br><br>
        
        <button type="submit"><?= $text[$lang]['btn'] ?></button>
    </form>
    <p><a href="login.php">Ke halaman login</a></p>
</body>
</html>
