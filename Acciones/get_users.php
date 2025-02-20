<?php
include '../config.php';

$users_sql = "SELECT * FROM users WHERE is_taken = 1";
$users_result = $conn->query($users_sql);

if ($users_result->num_rows > 0) {
    while ($user = $users_result->fetch_assoc()) {
        echo "<div class='user-card'>
                <p><strong>" . htmlspecialchars($user['username']) . "</strong></p>
                <button class='btn-logout' onclick='logoutUser(\"{$user['username']}\")'>Expulsar</button>
              </div>";
    }
} else {
    echo "<p class='no-users'>No hay usuarios conectado...</p>";
}
?>

