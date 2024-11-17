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
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }
        .chat-container {
            width: 400px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .chat-box {
            max-height: 300px;
            overflow-y: auto;
            padding: 10px;
            border: 1px solid #ccc;
            margin-bottom: 10px;
        }
        .chat-box p {
            margin: 5px 0;
        }
        .chat-input {
            display: flex;
        }
        .chat-input input[type="text"] {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .chat-input button {
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }
        .logout {
            text-align: center;
            margin-top: 10px;
        }
        .logout a {
            color: #007bff;
            text-decoration: none;
        }
    </style>
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

    <script>
        const chatForm = document.getElementById('chat-form');
        const chatBox = document.getElementById('chat-box');

        // Función para cargar mensajes cada 2 segundos
        function loadMessages() {
            fetch('Acciones/get_messages.php')
                .then(response => response.text())
                .then(data => {
                    chatBox.innerHTML = data;
                    chatBox.scrollTop = chatBox.scrollHeight; // Scroll al final
                });
        }

        // Cargar mensajes al cargar la página
        loadMessages();
        setInterval(loadMessages, 2000);

        // Enviar mensaje
        chatForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const username = document.getElementById('username').value;
            const message = document.getElementById('message').value;

            fetch('Acciones/send_message.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `username=${encodeURIComponent(username)}&message=${encodeURIComponent(message)}`
            })
            .then(response => response.text())
            .then(data => {
                if (data === 'success') {
                    document.getElementById('message').value = '';
                    loadMessages();
                }
            });
        });
    </script>
</body>
</html>
