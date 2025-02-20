<?php
session_start();
include '../config.php';

// Verificar si el usuario es administrador
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    exit("Acceso denegado");
}

// Verificar si se recibe un nombre de usuario válido
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
    $username = $conn->real_escape_string($_POST['username']);

    // 🔥 1️⃣ Eliminar todos los mensajes del usuario expulsado
    $delete_messages = $conn->prepare("DELETE FROM chat_messages WHERE user = ?");
    $delete_messages->bind_param("s", $username);
    $delete_messages->execute();
    $delete_messages->close();

    // 🚫 2️⃣ Marcar el usuario como disponible nuevamente
    $update_user = $conn->prepare("UPDATE users SET is_taken = 0 WHERE username = ?");
    $update_user->bind_param("s", $username);
    $update_user->execute();
    $update_user->close();

    echo "success";
}
?>

