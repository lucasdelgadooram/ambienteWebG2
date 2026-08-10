DROP DATABASE IF EXISTS tiendaPaluseAmbiente;
DROP USER IF EXISTS 'usuario_prueba'@'%';
DROP USER IF EXISTS 'usuario_reportes'@'%';

CREATE DATABASE tiendaPaluseAmbiente;
USE tiendaPaluseAmbiente;

CREATE USER 'usuario_prueba'@'%' IDENTIFIED BY 'Usuar1o_Clave.';
CREATE USER 'usuario_reportes'@'%' IDENTIFIED BY 'Usuar1o_Reportes.';

GRANT SELECT, INSERT, UPDATE, DELETE ON tiendaPaluseAmbiente.* TO 'usuario_prueba'@'%';
GRANT SELECT ON tiendaPaluseAmbiente.* TO 'usuario_reportes'@'%';
FLUSH PRIVILEGES;

CREATE TABLE usuario (
  id_usuario INT NOT NULL AUTO_INCREMENT,
  username VARCHAR(30) NOT NULL UNIQUE,
  password VARCHAR(512) NOT NULL,
  nombre VARCHAR(20) NOT NULL,
  apellidos VARCHAR(30) NOT NULL,
  correo VARCHAR(75) NULL UNIQUE,
  telefono VARCHAR(25) NULL,
  ruta_imagen VARCHAR(1024),
  activo BOOLEAN,
  politicas_aceptadas BOOLEAN DEFAULT FALSE,
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id_usuario),
  CHECK (correo REGEXP '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$'),
  INDEX ndx_username (username)
) ENGINE = InnoDB;

CREATE TABLE rol (
  id_rol INT NOT NULL AUTO_INCREMENT,
  rol VARCHAR(20) UNIQUE,
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id_rol)
) ENGINE = InnoDB;

CREATE TABLE usuario_rol (
  id_usuario INT NOT NULL,
  id_rol INT NOT NULL,
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id_usuario, id_rol),
  FOREIGN KEY fk_usuarioRol_usuario (id_usuario) REFERENCES usuario(id_usuario),
  FOREIGN KEY fk_usuarioRol_rol (id_rol) REFERENCES rol(id_rol)
) ENGINE = InnoDB;

CREATE TABLE categoria (
  id_categoria INT NOT NULL AUTO_INCREMENT,
  descripcion VARCHAR(50) NOT NULL,
  ruta_imagen VARCHAR(1024),
  activo BOOLEAN,
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id_categoria),
  UNIQUE (descripcion),
  INDEX ndx_descripcion (descripcion)
) ENGINE = InnoDB;

CREATE TABLE producto (
  id_producto INT NOT NULL AUTO_INCREMENT,
  id_categoria INT NOT NULL,
  descripcion VARCHAR(50) NOT NULL,
  detalle TEXT,
  precio DECIMAL(12,2) CHECK (precio >= 0),
  existencias INT UNSIGNED CHECK (existencias >= 0),
  ruta_imagen VARCHAR(1024),
  activo BOOLEAN,
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id_producto),
  UNIQUE (descripcion),
  INDEX ndx_descripcion (descripcion),
  FOREIGN KEY fk_producto_categoria (id_categoria) REFERENCES categoria(id_categoria)
) ENGINE = InnoDB;

CREATE TABLE factura (
    id_factura INT NOT NULL AUTO_INCREMENT,
    id_usuario INT NOT NULL,
    fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    estado ENUM(
        'Activa',
        'En proceso',
        'Enviado',
        'Completado',
        'Anulada'
    ) NOT NULL DEFAULT 'Activa',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id_factura),
    INDEX ndx_factura_usuario (id_usuario),
    CONSTRAINT fk_factura_usuario
        FOREIGN KEY (id_usuario)
        REFERENCES usuario(id_usuario)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    CONSTRAINT chk_factura_total
        CHECK (total >= 0)
) ENGINE = InnoDB;

CREATE TABLE venta (
    id_venta INT NOT NULL AUTO_INCREMENT,
    id_factura INT NOT NULL,
    id_producto INT NOT NULL,
    precio_historico DECIMAL(12,2) NOT NULL,
    cantidad INT UNSIGNED NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id_venta),
    INDEX ndx_venta_factura (id_factura),
    INDEX ndx_venta_producto (id_producto),
    UNIQUE KEY uk_factura_producto (
        id_factura,
        id_producto
    ),
    CONSTRAINT fk_venta_factura
        FOREIGN KEY (id_factura)
        REFERENCES factura(id_factura)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT fk_venta_producto
        FOREIGN KEY (id_producto)
        REFERENCES producto(id_producto)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    CONSTRAINT chk_venta_precio
        CHECK (precio_historico >= 0),
    CONSTRAINT chk_venta_cantidad
        CHECK (cantidad > 0)
) ENGINE = InnoDB;

CREATE TABLE favoritos (
  id_favorito INT AUTO_INCREMENT PRIMARY KEY,
  id_usuario INT NOT NULL,
  id_producto INT NOT NULL,
  UNIQUE (id_usuario, id_producto),
  FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario),
  FOREIGN KEY (id_producto) REFERENCES producto(id_producto)
) ENGINE = InnoDB;

CREATE TABLE ruta (
  id_ruta INT AUTO_INCREMENT NOT NULL,
  ruta VARCHAR(255) NOT NULL,
  id_rol INT NULL,
  requiere_rol BOOLEAN NOT NULL DEFAULT TRUE,
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CHECK (id_rol IS NOT NULL OR requiere_rol = FALSE),
  PRIMARY KEY (id_ruta),
  FOREIGN KEY (id_rol) REFERENCES rol(id_rol)
) ENGINE = InnoDB;

CREATE TABLE constante (
  id_constante INT AUTO_INCREMENT NOT NULL,
  atributo VARCHAR(25) NOT NULL,
  valor VARCHAR(150) NOT NULL,
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id_constante),
  UNIQUE (atributo)
) ENGINE = InnoDB;

CREATE TABLE oferta (
  id_oferta INT NOT NULL AUTO_INCREMENT,
  id_producto INT NOT NULL,
  porcentaje_descuento DECIMAL(5,2) NOT NULL CHECK (porcentaje_descuento BETWEEN 0 AND 100),
  fecha_inicio DATE NOT NULL,
  fecha_fin DATE NOT NULL,
  activo BOOLEAN DEFAULT TRUE,
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id_oferta),
  FOREIGN KEY fk_oferta_producto (id_producto) REFERENCES producto(id_producto)
) ENGINE = InnoDB;

CREATE TABLE mensaje_contacto (
  id_mensaje INT NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(100) NOT NULL,
  correo VARCHAR(75) NOT NULL,
  telefono VARCHAR(25) NULL,
  consulta TEXT NOT NULL,
  leido BOOLEAN DEFAULT FALSE,
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id_mensaje),
  CHECK (correo REGEXP '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$')
) ENGINE = InnoDB;

CREATE TABLE ticket_soporte (
  id_ticket INT NOT NULL AUTO_INCREMENT,
  id_usuario INT NOT NULL,
  asunto VARCHAR(100) NOT NULL,
  mensaje TEXT NOT NULL,
  respuesta TEXT NULL,
  estado ENUM('Abierto', 'En revision', 'Respondido', 'Cerrado') DEFAULT 'Abierto',
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id_ticket),
  FOREIGN KEY fk_ticket_usuario (id_usuario) REFERENCES usuario(id_usuario)
) ENGINE = InnoDB;

CREATE TABLE resena (
  id_resena INT NOT NULL AUTO_INCREMENT,
  id_usuario INT NOT NULL,
  id_producto INT NULL,
  calificacion TINYINT NOT NULL CHECK (calificacion BETWEEN 1 AND 5),
  comentario TEXT NOT NULL,
  activo BOOLEAN DEFAULT TRUE,
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id_resena),
  FOREIGN KEY fk_resena_usuario (id_usuario) REFERENCES usuario(id_usuario),
  FOREIGN KEY fk_resena_producto (id_producto) REFERENCES producto(id_producto)
) ENGINE = InnoDB;

INSERT INTO rol (rol) VALUES ('ADMIN'), ('VENDEDOR'), ('USER');

INSERT INTO categoria (
  descripcion,
  ruta_imagen,
  activo
) VALUES
  ('Ropa', NULL, TRUE),
  ('Accesorios', NULL, TRUE),
  ('Envoltorios', NULL, TRUE),
  ('Papeleria', NULL, TRUE),
  ('Personalizados', NULL, TRUE),
  ('Otros', NULL, TRUE);

INSERT INTO usuario (
  username,
  password,
  nombre,
  apellidos,
  correo,
  telefono,
  ruta_imagen,
  activo,
  politicas_aceptadas
) VALUES (
  'admin',
  '$2y$10$VHQ05L9g7REVO1LfmsSl.esV.UdLR9sgpOD5iw7oJtd.FavkHFlCq', #1234
  'Admin',
  'Paluse',
  'admin@paluse.com',
  '00000000',
  NULL,
  TRUE,
  TRUE
);

INSERT INTO usuario_rol (id_usuario, id_rol)
SELECT usuario.id_usuario, rol.id_rol
FROM usuario, rol
WHERE usuario.username = 'admin'
  AND rol.rol = 'ADMIN';

INSERT INTO categoria (descripcion, ruta_imagen, activo) VALUES
('Ropa', NULL, 1),
('Accesorios', NULL, 1),
('Envoltorios', NULL, 1),
('Papeleria', NULL, 1),
('Personalizados', NULL, 1),
('Otros', NULL, 1);

INSERT INTO producto (id_categoria, descripcion, detalle, precio, existencias, ruta_imagen, activo) VALUES
(1, 'Sueta personalizada', 'Sueta con diseño único Paluse. Ideal para regalar.', 12000.00, 25, 'suetaPaluse.jpg', 1),
(1, 'Peluche personalizado', 'Peluche decorativo con detalle especial.', 8500.00, 15, 'peluchePaluse.jpg', 1),
(2, 'Taza personalizada', 'Color blanco con diseño personalizado. Perfecta para regalos.', 8500.00, 30, 'taza.jpeg', 1),
(2, 'Cuadro personalizado', 'Cuadro con borde de madera y diseño a elección.', 7000.00, 20, 'cartelJackson.jpg', 1),
(3, 'Caja de cartón personalizada', 'Caja con imagen a escoger. Ideal para empaques especiales.', 6000.00, 40, 'cajaDesplegable.jpg', 1),
(3, 'Combo personalizado', 'Caja tipo cofre, accesorio y relleno. El regalo perfecto.', 11300.00, 10, 'comboRebeca.jpg', 1);
 
-- ============================================
-- PRODUCTOS PARA CATEGORÍA: PAPELERIA (id_categoria = 4)
-- ============================================
INSERT INTO producto (id_categoria, descripcion, detalle, precio, existencias, ruta_imagen, activo) VALUES
((SELECT id_categoria FROM categoria WHERE descripcion = 'Papeleria'), 'Cuaderno personalizado', 'Cuaderno con diseño personalizado, ideal para apuntes o regalos.', 4500.00, 30, NULL, 1),
((SELECT id_categoria FROM categoria WHERE descripcion = 'Papeleria'), 'Set de tarjetas personalizadas', 'Set de 10 tarjetas con diseño único para ocasiones especiales.', 6500.00, 25, NULL, 1),
((SELECT id_categoria FROM categoria WHERE descripcion = 'Papeleria'), 'Calendario personalizado', 'Calendario de pared con fotos y fechas especiales.', 7500.00, 15, NULL, 1),
((SELECT id_categoria FROM categoria WHERE descripcion = 'Papeleria'), 'Libreta de notas personalizada', 'Libreta con cubierta personalizada y hojas de calidad.', 3800.00, 40, NULL, 1);

-- ============================================
-- PRODUCTOS PARA CATEGORÍA: PERSONALIZADOS (id_categoria = 5)
-- ============================================
INSERT INTO producto (id_categoria, descripcion, detalle, precio, existencias, ruta_imagen, activo) VALUES
((SELECT id_categoria FROM categoria WHERE descripcion = 'Personalizados'), 'Llavero personalizado', 'Llavero metálico con grabado personalizado.', 3500.00, 50, NULL, 1),
((SELECT id_categoria FROM categoria WHERE descripcion = 'Personalizados'), 'Imán personalizado', 'Imán para nevera con diseño personalizado.', 2500.00, 60, NULL, 1),
((SELECT id_categoria FROM categoria WHERE descripcion = 'Personalizados'), 'Gorros personalizados', 'Gorro con diseño único y personalizado.', 5500.00, 20, NULL, 1),
((SELECT id_categoria FROM categoria WHERE descripcion = 'Personalizados'), 'Bolsa ecológica personalizada', 'Bolsa de tela reutilizable con diseño personalizado.', 4800.00, 35, NULL, 1);

-- ============================================
-- PRODUCTOS PARA CATEGORÍA: OTROS (id_categoria = 6)
-- ============================================
INSERT INTO producto (id_categoria, descripcion, detalle, precio, existencias, ruta_imagen, activo) VALUES
((SELECT id_categoria FROM categoria WHERE descripcion = 'Otros'), 'Portarretrato personalizado', 'Portarretrato de madera con diseño personalizado.', 6500.00, 18, NULL, 1),
((SELECT id_categoria FROM categoria WHERE descripcion = 'Otros'), 'Cojín personalizado', 'Cojín decorativo con diseño personalizado.', 8500.00, 12, NULL, 1),
((SELECT id_categoria FROM categoria WHERE descripcion = 'Otros'), 'Taza térmica personalizada', 'Taza térmica con diseño personalizado.', 9500.00, 22, NULL, 1),
((SELECT id_categoria FROM categoria WHERE descripcion = 'Otros'), 'Juego de posavasos personalizado', 'Set de 4 posavasos con diseño personalizado.', 4200.00, 28, NULL, 1);

-- ============================================
-- PRODUCTOS ADICIONALES PARA CATEGORÍAS EXISTENTES
-- ============================================
-- Más productos para Ropa
INSERT INTO producto (id_categoria, descripcion, detalle, precio, existencias, ruta_imagen, activo) VALUES
((SELECT id_categoria FROM categoria WHERE descripcion = 'Ropa'), 'Camiseta personalizada', 'Camiseta 100% algodón con diseño personalizado.', 8500.00, 30, NULL, 1),
((SELECT id_categoria FROM categoria WHERE descripcion = 'Ropa'), 'Gorra personalizada', 'Gorra con diseño personalizado.', 4500.00, 25, NULL, 1);

-- Más productos para Accesorios
INSERT INTO producto (id_categoria, descripcion, detalle, precio, existencias, ruta_imagen, activo) VALUES
((SELECT id_categoria FROM categoria WHERE descripcion = 'Accesorios'), 'Pulsera personalizada', 'Pulsera con nombre o mensaje personalizado.', 3200.00, 40, NULL, 1),
((SELECT id_categoria FROM categoria WHERE descripcion = 'Accesorios'), 'Llavero acrílico personalizado', 'Llavero acrílico con diseño personalizado.', 2800.00, 45, NULL, 1);

-- Más productos para Envoltorios
INSERT INTO producto (id_categoria, descripcion, detalle, precio, existencias, ruta_imagen, activo) VALUES
((SELECT id_categoria FROM categoria WHERE descripcion = 'Envoltorios'), 'Bolsa de regalo personalizada', 'Bolsa de regalo con diseño personalizado.', 3200.00, 50, NULL, 1),
((SELECT id_categoria FROM categoria WHERE descripcion = 'Envoltorios'), 'Etiqueta personalizada para regalo', 'Set de 20 etiquetas con diseño personalizado.', 2800.00, 60, NULL, 1);