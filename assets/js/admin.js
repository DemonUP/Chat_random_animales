// ❌ Evitar alertas repetidas con un Set
let expulsados = new Set();

// 🔄 Actualizar lista de usuarios conectados
function updateUsers() {
    fetch('acciones/get_users.php')
    .then(response => response.text())
    .then(data => {
        document.getElementById('users').innerHTML = data;

        // 🔍 Si un usuario ya está en "expulsados" y no está en la lista, eliminarlo del Set
        expulsados.forEach(user => {
            if (!data.includes(user)) {
                expulsados.delete(user);
            }
        });
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

// ❌ Expulsar usuario sin alertas repetidas
function logoutUser(username) {
    if (expulsados.has(username)) return; // 📌 Evitar ejecución duplicada

    expulsados.add(username); // 📌 Marcar usuario como expulsado antes de la petición

    fetch('Admin/logout_user.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'username=' + encodeURIComponent(username)
    })
    .then(response => response.text())
    .then(data => {
        if (data.trim() === 'success') {
            // 🔄 Primero eliminamos el usuario de la interfaz para evitar alertas repetidas
            document.querySelector(`tr[data-user="${username}"]`)?.remove();

            // ✅ Esperamos 300ms antes de mostrar la alerta
            setTimeout(() => {
                alert(`Usuario ${username} expulsado.`);
            }, 300);
        } else {
            expulsados.delete(username); // ❌ Si falla, permitir reintento
        }
    });
}

// 🔄 Cargar los datos al abrir la página
document.addEventListener("DOMContentLoaded", function () {
    updateUsers();
    updateChat();
});
