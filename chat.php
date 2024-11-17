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
    header("Location: login.php");
    exit();
}

// Funcionalidad para borrar mensajes
if (isset($_POST['delete_message']) && isset($_SESSION['is_admin']) && $_SESSION['is_admin']) {
    $message_id = $_POST['message_id'];
    $delete_sql = "DELETE FROM chat_messages WHERE id = $message_id";
    $conn->query($delete_sql);
}

// Funcionalidad para desloguear/borrar usuarios (solo para Admin)
if (isset($_POST['action']) && isset($_SESSION['is_admin']) && $_SESSION['is_admin']) {
    $user_id = $_POST['user_id'];
    if ($_POST['action'] === 'logout') {
        $update_sql = "UPDATE users SET logged_in = 0 WHERE id = $user_id";
        $conn->query($update_sql);
    } elseif ($_POST['action'] === 'delete') {
        $delete_sql = "DELETE FROM users WHERE id = $user_id";
        $conn->query($delete_sql);
    }
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
                    if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) {
                        echo "<form method='post' style='display:inline;'>
                                <input type='hidden' name='message_id' value='" . $row['id'] . "'>
                                <button type='submit' name='delete_message'>Eliminar</button>
                              </form>";
                    }
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
            <a href="index.php">Cerrar sesión</a>
        </div>
        <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) { ?>
            <div class="admin-panel">
                <h3>Panel de Admin - Gestión de Usuarios</h3>
                <?php
                $user_sql = "SELECT * FROM users";
                $user_result = $conn->query($user_sql);
                if ($user_result->num_rows > 0) {
                    while ($user_row = $user_result->fetch_assoc()) {
                        echo "<p>Usuario: " . htmlspecialchars($user_row['username']) . " - Estado: " . ($user_row['logged_in'] ? "Logueado" : "Deslogueado") . "</p>";
                        echo "<form method='post' style='display:inline;'>
                                <input type='hidden' name='user_id' value='" . $user_row['id'] . "'>
                                <button type='submit' name='action' value='logout'>Desloguear</button>
                                <button type='submit' name='action' value='delete'>Eliminar</button>
                              </form>";
                    }
                } else {
                    echo "<p>No hay usuarios registrados.</p>";
                }
                ?>
            </div>
        <?php } ?>
    </div>
    <script src="assets/js/chat.js"></script>
</body>
</html>