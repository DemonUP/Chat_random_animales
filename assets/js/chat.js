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

function checkUserStatus() {
    fetch('acciones/check_status.php')
    .then(response => response.text())
    .then(status => {
        if (status === "logout") {
            alert("Has sido desconectado por un administrador.");
            window.location.href = "index.php";
        }
    });
}

// Revisar el estado del usuario cada 5 segundos
setInterval(checkUserStatus, 3500);
setInterval(loadMessages, 1000);
