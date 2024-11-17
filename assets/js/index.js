// assets/js/login.js

document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('.login-container form');
    form.addEventListener('submit', function (e) {
        const usernameInput = form.querySelector('input[name="username"]');
        if (usernameInput.value.trim() === '') {
            e.preventDefault(); // Evita enviar el formulario si el campo está vacío
            alert('Por favor, ingrese un nombre de usuario.');
        }
    });
});

document.getElementById('username').addEventListener('input', function() {
    const adminPasswordField = document.getElementById('admin-password-field');
    if (this.value.toLowerCase() === 'admin') {
        adminPasswordField.style.display = 'block';
    } else {
        adminPasswordField.style.display = 'none';
    }
});
