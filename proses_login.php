<?php
session_start();

$username = "admin";
$password = "1234";

if ($_POST['username'] === $username && $_POST['password'] === $password) {
    $_SESSION['login'] = true;
    header("Location: index.php");
    exit;
} else {
    header("Location: login.php?error=1");
    exit;
}
