<?php
// Incluir el archivo de configuración para la conexión a la base de datos
include '../config.php';

// Mostrar los mensajes del chat desde la base de datos
$sql = "SELECT * FROM chat_messages ORDER BY timestamp ASC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<p><strong>" . htmlspecialchars($row['user']) . ":</strong> " . htmlspecialchars($row['message']) . "</p>";
        if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) {
            echo "<button onclick='deleteMessage(" . $row['id'] . ")'>Eliminar</button>";
        }
    }
} else {
    echo "<p>No hay mensajes aún.</p>";
}
?>
