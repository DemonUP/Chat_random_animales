<?php
// config.php

// Configuración de la conexión a la base de datos
$host = 'localhost'; // Dirección del servidor de base de datos
$db = 'chat_db'; // Nombre de la base de datos
$user = 'root'; // Usuario de la base de datos (cambia esto si tienes un usuario diferente)
$pass = ''; // Contraseña del usuario (cambia esto si tienes una contraseña establecida)

// Crear la conexión a la base de datos
$conn = new mysqli($host, $user, $pass, $db);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// El archivo config.php ahora se puede incluir en otros scripts PHP para reutilizar la conexión
?>
