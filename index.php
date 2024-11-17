<?php
// Iniciar sesión
session_start();

// Incluir el archivo de configuración para la conexión a la base de datos
include 'config.php';

// Comprobar si el usuario ya está logueado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username'])) {
    $username = htmlspecialchars($_POST['username']);
    $password = isset($_POST['password']) ? htmlspecialchars($_POST['password']) : '';

    if ($username === "Admin") {
        if ($password === "1234") {
            $_SESSION['username'] = $username;
            $_SESSION['is_admin'] = true;
            header("Location: chat.php");
            exit();
        } else {
            $error = "Contraseña incorrecta para Admin.";
        }
    } else {
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
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Chat</title>
    <link rel="stylesheet" href="assets/css/index.css">
</head>
<body>
    <div class="login-container">
        <h2>Login al Chat</h2>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <form method="post" action="">
            <input type="text" id="username" name="username" placeholder="Ingrese su nombre" required>
            <div id="admin-password-field" style="display: none;">
                <input type="text" type="password" name="password" placeholder="Contraseña" id="password">
            </div>
            <button type="submit">Entrar al chat</button>
        </form>
    </div>
    <script src="assets/js/index.js"></script>
</body>
</html>