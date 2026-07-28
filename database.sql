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
    
 
