<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    // Jika belum login, arahkan ke halaman login
    header('Location: ../auth/login.php');
    exit();
}

// Jika sudah login, arahkan ke halaman utama (beranda)
header('Location: ../php/beranda.php');
exit();
?>
