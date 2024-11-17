// assets/js/chat.js

const chatForm = document.getElementById('chat-form');
const chatBox = document.getElementById('chat-box');
const username = document.getElementById('username').value;
const userManagement = document.getElementById('user-management');

// Función para cargar mensajes cada 2 segundos
function loadMessages() {
    fetch('Acciones/get_messages.php')
        .then(response => response.text())
        .then(data => {
            chatBox.innerHTML = data;
            chatBox.scrollTop = chatBox.scrollHeight; // Scroll al final
        });
}

// Función para cargar la gestión de usuarios para el Admin
function loadUserManagement() {
    if (userManagement) {
        fetch('Acciones/get_users.php')
            .then(response => response.text())
            .then(data => {
                userManagement.innerHTML = data;
                attachUserActions();
            });
    }
}

// Cargar mensajes al cargar la página y cada 2 segundos
loadMessages();
setInterval(loadMessages, 2000);

// Cargar la lista de usuarios al cargar la página y cada 5 segundos si es Admin
if (userManagement) {
    loadUserManagement();
    setInterval(loadUserManagement, 5000);
}

// Enviar mensaje
chatForm.addEventListener('submit', function (e) {
    e.preventDefault();
    const message = document.getElementById('message').value;

    fetch('Acciones/send_message.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `username=${encodeURIComponent(username)}&message=${encodeURIComponent(message)}`
    })
    .then(response => response.text())
    .then(data => {
        if (data === 'success') {
            document.getElementById('message').value = '';
            loadMessages();
        }
    });
});

// Eliminar mensaje (para Admin)
function deleteMessage(messageId) {
    fetch('chat.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `action=delete_message&message_id=${messageId}`
    })
    .then(response => response.text())
    .then(() => {
        loadMessages();
    });
}

// Adjuntar eventos para desloguear/eliminar usuarios (solo Admin)
function attachUserActions() {
    document.querySelectorAll('.admin-action').forEach(button => {
        button.addEventListener('click', function () {
            const userId = this.dataset.userId;
            const action = this.dataset.action;

            fetch('chat.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `action=${encodeURIComponent(action)}&user_id=${encodeURIComponent(userId)}`
            })
            .then(() => {
                loadUserManagement();
            });
        });
    });
}
