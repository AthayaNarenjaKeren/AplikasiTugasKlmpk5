<?php
session_start(); // Pastikan session dimulai

// Hapus semua variabel session
$_SESSION = array();

// Hancurkan session
session_destroy();

// Redirect ke halaman login dengan pesan sukses
header("Location: login.php?logged_out=1");
exit();
?>