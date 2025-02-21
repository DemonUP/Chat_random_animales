# Chat Anónimo Random  

Sistema de chat anónimo donde los usuarios reciben un nombre aleatorio basado en animales.  
Incluye un **panel de administración** para moderación en tiempo real.  

## 📌 Características  
- Asignación automática de nombre al ingresar.  
- Chat en tiempo real con actualización automática.  
- Panel de administración para monitorear usuarios y mensajes.  
- Expulsión de usuarios con eliminación de mensajes.  

## 🚀 Instalación  

### 1️⃣ Clonar el repositorio  
```bash
git clone https://github.com/DemonUP/Chat_random_animales.git
cd chat_random

 2️⃣ Configurar la Base de Datos
Ejecuta el siguiente SQL en MySQL:

sql
Copiar
Editar
CREATE DATABASE chat_db;
USE chat_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    is_taken TINYINT(1) DEFAULT 0
);

CREATE TABLE chat_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user VARCHAR(50) NOT NULL,
    message TEXT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

INSERT INTO admins (username, password) VALUES ('DIOS', 'hola1234');