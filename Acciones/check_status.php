<?php
session_start();
include '../config.php';

if (!isset($_SESSION['username'])) {
    echo "logout";
    exit();
}

$username = $_SESSION['username'];
$result = $conn->query("SELECT is_taken FROM users WHERE username = '$username'");

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row['is_taken'] == 0) {
        echo "logout";
        exit();
    } else {
        echo "active";
        exit();
    }
} else {
    echo "logout";
    exit();
}
?>
