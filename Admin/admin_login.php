<?php
session_start();
include '../config.php'; // Conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']); // Elimina espacios
    $password = trim($_POST['password']);

    // 🔍 Buscar el usuario y verificar la contraseña
    $stmt = $conn->prepare("SELECT username FROM admins WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    // Si existe el usuario, iniciar sesión como admin
    if ($result->num_rows > 0) {
        $_SESSION['is_admin'] = true;
        $_SESSION['admin_username'] = $username;
        echo "success";
    } else {
        echo "error"; // Usuario o contraseña incorrectos
    }

    $stmt->close();
    $conn->close();
}
?>
