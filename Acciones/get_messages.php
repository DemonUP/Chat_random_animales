<?php
// Incluir el archivo de configuración para la conexión a la base de datos
include '../config.php';

// Obtener los mensajes del chat
$sql = "SELECT * FROM chat_messages ORDER BY timestamp ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<p><strong>" . htmlspecialchars($row['user']) . ":</strong> " . htmlspecialchars($row['message']) . "</p>";
    }
} else {
    echo "<p>No hay mensajes aún.</p>";
}

$conn->close();
?>
