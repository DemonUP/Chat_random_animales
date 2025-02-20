<?php
session_start();
include 'config.php';

if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Moderación</title>
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>

    <div class="admin-container">
        <h1> Panel de Moderación </h1>

        <!-- Sección de Usuarios en Tiempo Real -->
        <h2>Usuarios Conectados</h2>
        <div id="users"></div>

        <!-- Sección de Chat en Tiempo Real -->
        <h2>Chat en Vivo</h2>
        <div id="chat-box"></div>

        <button class="btn-exit" onclick="window.location.href='index.php'">Abandonar</button>
    </div>

    <script>
    // 🔄 Función para actualizar la lista de usuarios conectados
    function updateUsers() {
        fetch('acciones/get_users.php')
        .then(response => response.text())
        .then(data => {
            document.getElementById('users').innerHTML = data;
        });
    }

    // 🔄 Función para actualizar los mensajes del chat en tiempo real
    function updateChat() {
        fetch('acciones/get_messages.php')
        .then(response => response.text())
        .then(data => {
            document.getElementById('chat-box').innerHTML = data;
        });
    }

    // 🚀 Llamar a las funciones cada 3 segundos
    setInterval(updateUsers, 1000);
    setInterval(updateChat, 1000);

    // 🔥 Expulsar usuarios en tiempo real
    function logoutUser(username) {
        fetch('acciones/logout_user.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'username=' + encodeURIComponent(username)
        })
        .then(response => response.text())
        .then(data => {
            if (data === 'success') {
                alert('Usuario expulsado.');
                updateUsers();
            } else {
                alert('Error al expulsar al usuario.');
            }
        });
    }

    // 🔄 Cargar los datos al abrir la página
    updateUsers();
    updateChat();
    </script>

</body>
</html>
