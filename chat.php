<?php
// Iniciar sesión
session_start();

// Incluir el archivo de configuración para la conexión a la base de datos
include 'config.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}


$username = $_SESSION['username'];

$check_status = $conn->query("SELECT is_taken FROM users WHERE username = '$username'");
if ($check_status->num_rows > 0) {
    $row = $check_status->fetch_assoc();
    if ($row['is_taken'] == 0) {
        session_destroy();
        header("Location: index.php?error=logout");
        exit();
    }
}

// Deslogueo de usuario
if (isset($_GET['logout'])) {
    // Eliminar los mensajes del usuario desconectado
    $delete_sql = "DELETE FROM chat_messages WHERE user = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("s", $username); // $username viene de $_SESSION['username']
    $stmt->execute();
    $stmt->close();

    // Marcar el usuario como disponible nuevamente
    $update_sql = "UPDATE users SET is_taken = 0 WHERE username = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->close();

    // Cerrar la sesión
    session_destroy();
    header("Location: index.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Interface</title>
    <link rel="stylesheet" href="assets/css/chat.css?v=2.1">
</head>
<body>
    <div class="chat-container">
        <div class="chat-box" id="chat-box">
            <?php
            // Mostrar los mensajes del chat desde la base de datos
            $sql = "SELECT * FROM chat_messages ORDER BY timestamp ASC";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<p><strong>" . htmlspecialchars($row['user']) . ":</strong> " . htmlspecialchars($row['message']) . "</p>";
                }
            } else {
                echo "<p>No hay mensajes aún.</p>";
            }
            ?>
        </div>
        <form id="chat-form" class="chat-input">
            <input type="hidden" id="username" value="<?php echo $_SESSION['username']; ?>">
            <input type="text" id="message" placeholder="Escribe un mensaje..." required>
            <button type="submit">Enviar</button>
        </form>
        <div class="logout">
            <a href="?logout=true">Cerrar sesión</a>
        </div>
    </div>
    <script src="assets/js/chat.js?=1.0"></script>
</body>
</html>
