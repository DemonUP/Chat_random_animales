document.getElementById('chat-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const message = document.getElementById('message').value;
    const username = document.getElementById('username').value;

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

function loadMessages() {
    fetch('acciones/get_messages.php')
        .then(response => response.text())
        .then(data => {
            document.getElementById('chat-box').innerHTML = data;
            document.getElementById('chat-box').scrollTop = document.getElementById('chat-box').scrollHeight;
        });
}

// ⚠ Evita alertas repetidas
let userExpelled = false;

function checkUserStatus() {
    if (userExpelled) return; // ❌ Si ya se expulsó, no seguir ejecutando

    fetch('acciones/check_status.php')
    .then(response => response.text())
    .then(status => {
        if (status.trim() === "logout") {
            userExpelled = true; // ✅ Marcar usuario como expulsado
            alert("Has sido desconectado por un administrador.");
            window.location.href = "index.php"; // 🔄 Redirigir
        }
    });
}

// 🚀 Evitar múltiples ejecuciones
const statusInterval = setInterval(() => {
    if (!userExpelled) {
        checkUserStatus();
    } else {
        clearInterval(statusInterval); // ✅ Detener la ejecución si el usuario ya fue expulsado
    }
}, 3500);

setInterval(loadMessages, 1000);
