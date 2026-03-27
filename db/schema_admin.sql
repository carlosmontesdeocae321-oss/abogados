-- db/schema_admin.sql
-- Run this script to create necessary tables for the admin system

CREATE TABLE IF NOT EXISTS usuarios_admin (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) DEFAULT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS consultas_clientes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(200) NOT NULL,
  email VARCHAR(255) NOT NULL,
  telefono VARCHAR(80) DEFAULT NULL,
  tipo_servicio VARCHAR(120) DEFAULT NULL,
  mensaje TEXT NOT NULL,
  fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
  ciudad VARCHAR(120) DEFAULT NULL,
  INDEX (email), INDEX (fecha)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS publicaciones (
  id INT AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(255) NOT NULL,
  descripcion TEXT,
  imagen VARCHAR(512) DEFAULT NULL,
  categoria VARCHAR(120) DEFAULT NULL,
  fecha DATE DEFAULT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create a default admin user (change password)
-- Replace 'yourpassword' with a secure password before running. Generate hash with PHP: password_hash('yourpassword', PASSWORD_BCRYPT)
-- INSERT INTO usuarios_admin (name, email, password_hash) VALUES ('Administrador','admin@example.com','$2y$10$REPLACE_WITH_BCRYPT_HASH');
