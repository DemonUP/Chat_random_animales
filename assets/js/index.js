// ⏳ Redirección con cuenta regresiva al chat
function redirectToChat() {
    let countdown = 7;
    const countdownElement = document.getElementById('countdown');

    if (!countdownElement) {
        console.error("Elemento #countdown no encontrado.");
        return;
    }

    // Crear la pantalla negra para la transición
    const blackScreen = document.createElement('div');
    blackScreen.classList.add('black-screen');
    document.body.appendChild(blackScreen);

    // Actualizar el contador cada segundo
    const interval = setInterval(() => {
        countdown -= 1;
        countdownElement.textContent = countdown;

        if (countdown <= 0) {
            clearInterval(interval);
            blackScreen.classList.add('show');
            setTimeout(() => { 
                window.location.href = 'chat.php'; 
            }, 2500);
        }
    }, 1000);
}

// 🔐 Función para autenticar admin con la base de datos
function loginAdmin() {
    let username = document.getElementById('admin-username').value.trim();
    let pass = document.getElementById('admin-pass').value.trim();

    fetch('Admin/admin_login.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'username=' + encodeURIComponent(username) + '&password=' + encodeURIComponent(pass)
    })
    .then(response => response.text())
    .then(data => {
        console.log("Respuesta del servidor:", data); // 👀 DEBUG
        if (data.trim() === 'success') {
            window.location.href = 'admin.php';
        } else {
            alert('Usuario o contraseña incorrectos.');
        }
    });
}

// 📌 Mostrar/Ocultar login de admin
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById('admin-btn').addEventListener('click', () => {
        let adminLogin = document.getElementById('admin-login');
        adminLogin.style.display = (adminLogin.style.display === 'block') ? 'none' : 'block';
    });

    // Iniciar el contador solo si existe el elemento
    if (document.getElementById('countdown')) {
        redirectToChat();
    }
});
