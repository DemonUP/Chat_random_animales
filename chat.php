<?php
// Iniciar sesión
session_start();

// Incluir el archivo de configuración para la conexión a la base de datos
include 'config.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['logout'])) {
    // Desloguear al usuario
    $username = $_SESSION['username'];
    $update_sql = "UPDATE users SET logged_in = 0 WHERE username = '$username'";
    $conn->query($update_sql);
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
    <link rel="stylesheet" href="assets/css/chat.css">
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

    <script src="assets/js/chat.js"></script>
</body>
</html>
