<?php
// Incluir el archivo de configuración para la conexión a la base de datos
include '../config.php';

// Obtener la lista de usuarios
$user_sql = "SELECT * FROM users";
$user_result = $conn->query($user_sql);
if ($user_result->num_rows > 0) {
    while ($user_row = $user_result->fetch_assoc()) {
        echo "<p>Usuario: " . htmlspecialchars($user_row['username']) . " - Estado: " . ($user_row['logged_in'] ? "Logueado" : "Deslogueado") . "</p>";
        echo "<button class='admin-action' data-user-id='" . $user_row['id'] . "' data-action='logout'>Desloguear</button>";
        echo "<button class='admin-action' data-user-id='" . $user_row['id'] . "' data-action='delete'>Eliminar</button>";
    }
} else {
    echo "<p>No hay usuarios registrados.</p>";
}
?>
