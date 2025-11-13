<?php
session_start(); // Oturumu başlat
session_unset(); // Oturum verilerini temizle
session_destroy(); // Oturumu yok et
header("Location: login.php"); // Giriş sayfasına yönlendir
exit();
?>
