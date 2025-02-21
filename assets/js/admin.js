// 🔄 Actualizar lista de usuarios conectados
function updateUsers() {
    fetch('acciones/get_users.php')
    .then(response => response.text())
    .then(data => {
        document.getElementById('users').innerHTML = data;
    });
}

// 🔄 Actualizar chat en vivo
function updateChat() {
    fetch('acciones/get_messages.php')
    .then(response => response.text())
    .then(data => {
        document.getElementById('chat-box').innerHTML = data;
    });
}

// 🚀 Llamar a las funciones de actualización cada 2 segundos
setInterval(updateUsers, 2000);
setInterval(updateChat, 2000);

// ❌ Expulsar usuario
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
            alert('Usuario expulsado.');
        }
    });
}

// 🔄 Cargar los datos al abrir la página
document.addEventListener("DOMContentLoaded", function () {
    updateUsers();
    updateChat();
});
