<?php
// Incluir el archivo de configuración para la conexión a la base de datos
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'];
    $message = $_POST['message'];

    // Evitar la inyección SQL mediante escape de cadenas
    $user = $conn->real_escape_string($user);
    $message = $conn->real_escape_string($message);

    // Insertar el mensaje en la base de datos
    $sql = "INSERT INTO chat_messages (user, message) VALUES ('$user', '$message')";

    if ($conn->query($sql) === TRUE) {
        echo 'success';
    } else {
        echo 'error';
    }
}

$conn->close();
?>
