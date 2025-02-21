<?php
// Iniciar sesión
session_start();
include 'config.php';

// Lista de animales disponibles y sus imágenes
$animal_images = [
    'León' => 'assets/images/leon.jpg',
    'Tigre' => 'assets/images/tigre.jpg',
    'Elefante' => 'assets/images/elefante.jpg',
    'Jirafa' => 'assets/images/jirafa.jpg',
    'Panda' => 'assets/images/panda.jpg',
    'Cebra' => 'assets/images/cebra.jpg',
    'Mono' => 'assets/images/mono.jpg',
    'Lobo' => 'assets/images/lobo.jpg',
    'Oso' => 'assets/images/oso.jpg',
    'Rinoceronte' => 'assets/images/rinoceronte.jpg',
];

// Si el usuario ya tiene asignado un animal, redirigir al chat
if (isset($_SESSION['username']) && $_SESSION['username']) {
    header("Location: chat.php");
    exit();
}

// Verificar si se solicitó un animal
$selected_animal = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['get_animal'])) {
    // Obtener animales disponibles
    $sql = "SELECT username FROM users WHERE is_taken = 0";
    $result = $conn->query($sql);
    $available_animals = [];
    while ($row = $result->fetch_assoc()) {
        $available_animals[] = $row['username'];
    }

    if (count($available_animals) > 0) {
        // Seleccionar un animal al azar
        $random_animal = $available_animals[array_rand($available_animals)];

        // Marcar el animal como tomado en la base de datos
        $conn->query("UPDATE users SET is_taken = 1 WHERE username = '$random_animal'");

        // Asignar el animal al usuario y almacenar en sesión
        $_SESSION['username'] = $random_animal;
        $selected_animal = $random_animal;
    } else {
        $error = "Lo sentimos, no hay animales disponibles en este momento.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Chat Random</title>
    <link rel="stylesheet" href="assets/css/index.css?=1.1">
</head>
<body>
    <div class="login-container">
        <h2>Chat Random - Elige tu Animal</h2>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>

        <?php if ($selected_animal): ?>
    <div class="animal-display">
        <h3>¡Te tocó: <?php echo htmlspecialchars($selected_animal); ?>!</h3>
        <img src="<?php echo $animal_images[$selected_animal]; ?>" alt="<?php echo htmlspecialchars($selected_animal); ?>">
        <p>Entrando al chat en <span id="countdown">7</span> segundos...</p>
    </div>
        <?php else: ?>
            <form class="form_boton" method="post" action="">
                <button type="submit" name="get_animal">Tirar el Dado</button>
            </form>
        <?php endif; ?>
    </div>

    <!-- 🔧 Botón de Admin (Tuerquita) fijo en la esquina -->
    <button id="admin-btn" class="admin-button">⚙️</button>

    <!-- 🛠 Menú flotante de autenticación Admin -->
    <div id="admin-login" class="admin-login">
        <input type="text" id="admin-username" placeholder="Usuario" required>
        <input type="password" id="admin-pass" placeholder="Contraseña" required>
        <button onclick="loginAdmin()">Entrar</button>
    </div>

    <!-- Incluir archivo de JavaScript externo -->
    <script src="assets/js/index.js?=1.6"></script>
</body>
</html>
