<?php
// jalanin session buat ngakses data yang mau dihapus
session_start();
// hapus semua data sesi
session_destroy();
// arahin balik ke halaman login
header("Location: login.php");
exit;
?>
