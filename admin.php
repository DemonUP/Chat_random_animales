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
    <link rel="stylesheet" href="assets/css/admin.css?=1.4">
</head>
<body>

    <div class="admin-dashboard">
        <div class="admin-header">
            <h1>Panel de Moderación</h1>
        </div>

        <div class="dashboard-content">
            <!-- 📌 Sección de Usuarios Conectados -->
            <div class="user-section">
                <h2>Usuarios Conectados</h2>
                <table class="user-table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody id="users">
                        <!-- Usuarios se insertarán aquí con JavaScript -->
                    </tbody>
                </table>
            </div>

            <!-- 📌 Sección de Chat en Vivo -->
            <div class="chat-section">
                <h2>Chat en Vivo</h2>
                <div id="chat-box"></div>
            </div>
        </div>

        <button class="btn-exit" onclick="window.location.href='index.php'">Abandonar</button>
    </div>

    <!-- Incluir el archivo de JavaScript externo -->
    <script src="assets/js/admin.js?=2.4"></script>

</body>
</html>
