<?php
include '../config.php';

// Obtener usuarios conectados
$sql = "SELECT username FROM users WHERE is_taken = 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td class='user-name'>" . htmlspecialchars($row['username']) . "</td>
                <td><button class='btn-logout' onclick='logoutUser(\"" . $row['username'] . "\")'>Expulsar</button></td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='2' class='no-users'>No hay usuarios conectados...</td></tr>";
}

$conn->close();
?>
