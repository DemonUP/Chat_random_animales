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

    // Verificar si el usuario está en la base de datos antes de modificarlo
    $check_user = $conn->prepare("SELECT is_taken FROM users WHERE username = ?");
    $check_user->bind_param("s", $username);
    $check_user->execute();
    $result = $check_user->get_result();
    $check_user->close();

    if ($result->num_rows > 0) {
        // 🔥 1️⃣ Eliminar todos los mensajes del usuario expulsado
        $delete_messages = $conn->prepare("DELETE FROM chat_messages WHERE user = ?");
        $delete_messages->bind_param("s", $username);
        $delete_messages->execute();
        $delete_messages->close();

        // 🚫 2️⃣ Marcar el usuario como disponible nuevamente
        $update_user = $conn->prepare("UPDATE users SET is_taken = 0 WHERE username = ?");
        $update_user->bind_param("s", $username);
        $update_user->execute();
        $affected_rows = $update_user->affected_rows;
        $update_user->close();

        // ✅ 3️⃣ Enviar éxito solo si realmente se actualizó el usuario
        if ($affected_rows > 0) {
            echo "success";
        } else {
            echo "error";
        }
    } else {
        echo "error"; // Usuario no encontrado
    }
}
?>

