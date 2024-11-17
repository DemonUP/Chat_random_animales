// assets/js/chat.js

const chatForm = document.getElementById('chat-form');
const chatBox = document.getElementById('chat-box');

// Función para cargar mensajes cada 2 segundos
function loadMessages() {
    fetch('acciones/get_messages.php')
        .then(response => response.text())
        .then(data => {
            chatBox.innerHTML = data;
            chatBox.scrollTop = chatBox.scrollHeight; // Scroll al final
        });
}

// Cargar mensajes al cargar la página
loadMessages();
setInterval(loadMessages, 2000);

// Enviar mensaje
chatForm.addEventListener('submit', function (e) {
    e.preventDefault();
    const username = document.getElementById('username').value;
    const message = document.getElementById('message').value;

    fetch('acciones/send_message.php', {
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
