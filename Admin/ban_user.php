<?php
session_start();
include '../config.php';

if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    exit("Acceso denegado");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $conn->query("UPDATE users SET banned = 1 WHERE username = '$username'");
    echo "success";
}
?>
