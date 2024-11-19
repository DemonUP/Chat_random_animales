<?php
// Iniciar sesión
session_start();

// Incluir el archivo de configuración para la conexión a la base de datos
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

// Si el usuario ya tiene asignado un animal, redirigir al chat directamente
if (isset($_SESSION['username']) && $_SESSION['username']) {
    header("Location: chat.php");
    exit();
}

// Verificar si se solicitó un animal
$selected_animal = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['get_animal'])) {
    // Obtener animales disponibles (no usados)
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
        $update_sql = "UPDATE users SET is_taken = 1 WHERE username = '$random_animal'";
        $conn->query($update_sql);

        // Asignar el animal al usuario y almacenar en sesión
        $_SESSION['username'] = $random_animal;
        $selected_animal = $random_animal;

        // No hacer ninguna redirección hasta que pasen los 7 segundos
    } else {
        $error = "Lo sentimos, no hay animales disponibles en este momento.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Chat Random</title>
    <link rel="stylesheet" href="assets/css/index.css">
    <script>
        function redirectToChat() {
            let countdown = 7; // Inicializa la cuenta regresiva en 7 segundos
            const countdownElement = document.getElementById('countdown'); // Selecciona el elemento de texto para actualizarlo

            // Actualiza el texto cada segundo
            const interval = setInterval(function() {
                countdown -= 1;
                countdownElement.textContent = countdown; // Actualiza el texto en el elemento
                if (countdown <= 0) {
                    clearInterval(interval); // Detiene el intervalo
                    window.location.href = 'chat.php'; // Redirige al usuario
                }
            }, 1000);
        }
    </script>
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
                <script>redirectToChat();</script>
            </div>
        <?php else: ?>
            <form method="post" action="">
                <button type="submit" name="get_animal">Tirar el Dado</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
