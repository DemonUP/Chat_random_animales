<?php
// Iniciar sesión
session_start();

// Incluir el archivo de configuración para la conexión a la base de datos
include 'config.php';

// Comprobar si el usuario ya está logueado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username'])) {
    $username = htmlspecialchars($_POST['username']);

    // Verificar si el usuario ya existe en la tabla y si está logueado
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['logged_in']) {
            $error = "El usuario ya está logueado. Debes desloguearte antes de poder loguearte nuevamente.";
        } else {
            // Si el usuario existe pero no está logueado, actualizar el estado de logueo
            $update_sql = "UPDATE users SET logged_in = 1 WHERE username = '$username'";
            if ($conn->query($update_sql) === TRUE) {
                $_SESSION['username'] = $username;
                header("Location: chat.php");
                exit();
            } else {
                $error = "Error al actualizar el estado de logueo.";
            }
        }
    } else {
        // Registrar al usuario si no existe en la tabla
        $insert_sql = "INSERT INTO users (username, logged_in) VALUES ('$username', 1)";
        if ($conn->query($insert_sql) === TRUE) {
            $_SESSION['username'] = $username;
            header("Location: chat.php");
            exit();
        } else {
            $error = "Error al registrar al usuario.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Chat</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }
        .login-container {
            width: 300px;
            margin: 100px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .login-container input[type="text"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .login-container button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <form method="post" action="">
            <input type="text" name="username" placeholder="Ingrese su nombre" required>
            <button type="submit">Entrar al chat</button>
        </form>
    </div>
</body>
</html>
