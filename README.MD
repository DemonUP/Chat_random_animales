# 📢 Chat Anónimo Random

Un sistema de chat anónimo donde los usuarios reciben un nombre aleatorio basado en animales. Incluye un panel de administración para moderación en tiempo real.

## 📌 Características
- ✅ Asignación automática de nombre al ingresar.
- ✅ Chat en tiempo real con actualización automática.
- ✅ Panel de administración para monitorear usuarios y mensajes.
- ✅ Expulsión de usuarios con eliminación de mensajes.

---

## 📂 Estructura de la Base de Datos

Este archivo describe la estructura de la base de datos del **Chat Random**, incluyendo las tablas necesarias y sus relaciones.

### 🛠️ 1. Crear la Base de Datos

```sql
CREATE DATABASE chat_db;
USE chat_db;
🏷️ 2. Tabla de Usuarios (users)
Guarda a los usuarios anónimos y su estado en el chat.

sql
Copiar
Editar
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    is_taken TINYINT(1) DEFAULT 0, -- 1 = En línea, 0 = Disponible
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
💬 3. Tabla de Mensajes (chat_messages)
Almacena los mensajes enviados en el chat.

sql
Copiar
Editar
CREATE TABLE chat_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user VARCHAR(50) NOT NULL,
    message TEXT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user) REFERENCES users(username) ON DELETE CASCADE
);
🔑 4. Tabla de Administradores (admins)
Almacena los administradores con acceso especial.

sql
Copiar
Editar
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL, -- Guardar con password_hash()
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
🏗️ 5. Insertar un Administrador Inicial
sql
Copiar
Editar
INSERT INTO admins (username, password) VALUES ('Dios', 'hola1234');
⚠️ Nota: Cambia hola1234 por una versión cifrada usando password_hash() en PHP para mayor seguridad.

🚀 Instalación y Uso
Configurar la Base de Datos

Copia y pega las consultas SQL en tu gestor de base de datos (MySQL, MariaDB, etc.).
Implementar el Servidor

Asegúrate de tener un entorno con PHP y MySQL configurado.
Desarrolla una interfaz de chat en tiempo real (puedes usar WebSockets o AJAX para las actualizaciones).