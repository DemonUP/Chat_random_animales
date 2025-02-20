function redirectToChat() {
    let countdown = 7;
    const countdownElement = document.getElementById('countdown');
    const blackScreen = document.createElement('div');
    blackScreen.classList.add('black-screen');
    document.body.appendChild(blackScreen);

    const interval = setInterval(() => {
        countdown -= 1;
        countdownElement.textContent = countdown;

        if (countdown <= 0) {
            clearInterval(interval);
            blackScreen.classList.add('show');
            setTimeout(() => { window.location.href = 'chat.php'; }, 2500);
        }
    }, 1000);
}

// Función para autenticar admin
function loginAdmin() {
    let pass = document.getElementById('admin-pass').value;
    fetch('Admin/admin_login.php', { // Ruta corregida
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'password=' + encodeURIComponent(pass)
    })
    .then(response => response.text())
    .then(data => {
        if (data === 'success') {
            window.location.href = 'admin.php';
        } else {
            alert('Contraseña incorrecta');
        }
    });
}

