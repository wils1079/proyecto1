-- parcial_1 SQL - ejecuta en la DB mesa_ayuda

-- Asegúrate de que la tabla usuarios tenga estas columnas:
CREATE TABLE IF NOT EXISTS usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(150) NOT NULL,
  correo VARCHAR(200) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  perfil ENUM('Cliente','Administrador','Técnico') NOT NULL DEFAULT 'Cliente',
  creado_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla tickets (agregada/actualizada)
CREATE TABLE IF NOT EXISTS tickets (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL,
  titulo VARCHAR(150) NOT NULL,
  descripcion TEXT,
  estado ENUM('abierto','en_progreso','finalizado','cerrado') NOT NULL DEFAULT 'abierto',
  asignado_a INT DEFAULT NULL,
  creado_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
  FOREIGN KEY (asignado_a) REFERENCES usuarios(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Notificaciones (mensajes para usuarios)
CREATE TABLE IF NOT EXISTS notificaciones (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL,
  ticket_id INT DEFAULT NULL,
  mensaje VARCHAR(500) NOT NULL,
  leido TINYINT(1) NOT NULL DEFAULT 0,
  creado_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
  FOREIGN KEY (ticket_id) REFERENCES tickets(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
