
-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS tienda_online;
USE tienda_online;

-- Tabla Rol
CREATE TABLE Rol (
  rol_ID_rol INT PRIMARY KEY AUTO_INCREMENT,
  rol_Nombre VARCHAR(100),
  rol_Administrador VARCHAR(100),
  rol_Cliente VARCHAR(100)
);

-- Tabla Usuarios
CREATE TABLE usuarios (
  usu_ID_usuario INT PRIMARY KEY AUTO_INCREMENT,
  usu_ID_rol INT,
  usu_Nombre VARCHAR(100),
  usu_Apellido VARCHAR(100),
  usu_Email VARCHAR(100),
  usu_Contraseña VARCHAR(100),
  usu_Telefono VARCHAR(20),
  usu_NIT VARCHAR(20),
  usu_Direccion VARCHAR(200),
  usu_Localidad VARCHAR(100),
  FOREIGN KEY (usu_ID_rol) REFERENCES Rol(rol_ID_rol)
);

-- Tabla Orden
CREATE TABLE orden (
  ord_ID_orden INT PRIMARY KEY AUTO_INCREMENT,
  ord_ID_usuario INT,
  ord_Fecha DATE,
  ord_Total DECIMAL(10,2),
  ord_Estado VARCHAR(50),
  FOREIGN KEY (ord_ID_usuario) REFERENCES usuarios(usu_ID_usuario)
);

-- Tabla Pago
CREATE TABLE pago (
  pag_ID_pago INT PRIMARY KEY AUTO_INCREMENT,
  pag_ID_orden INT,
  pag_Monto DECIMAL(10,2),
  pag_Fecha DATE,
  pag_Tipo VARCHAR(50),
  FOREIGN KEY (pag_ID_orden) REFERENCES orden(ord_ID_orden)
);

-- Tabla Producto
CREATE TABLE producto (
  pro_ID_producto INT PRIMARY KEY AUTO_INCREMENT,
  pro_Nombre VARCHAR(100),
  pro_Precio DECIMAL(10,2),
  pro_Descripcion TEXT,
  pro_Reseña VARCHAR(255)
);

-- Tabla Proveedor
CREATE TABLE proveedor (
  prov_ID_proveedor INT PRIMARY KEY AUTO_INCREMENT,
  prov_Nombre VARCHAR(100),
  prov_Email VARCHAR(100),
  prov_Direccion VARCHAR(200),
  prov_Telefono VARCHAR(20)
);

-- Tabla Inventario
CREATE TABLE Inventario (
  inv_ID_inventario INT PRIMARY KEY AUTO_INCREMENT,
  inv_ID_producto INT,
  inv_stock INT,
  inv_cantidad_disponible INT,
  inv_estado VARCHAR(50),
  inv_fecha_ultimo_ingreso DATE,
  FOREIGN KEY (inv_ID_producto) REFERENCES producto(pro_ID_producto)
);

-- Tabla Carrito de Compra
CREATE TABLE carrito_compra (
  car_ID_carrito INT PRIMARY KEY AUTO_INCREMENT,
  car_ID_usuario INT,
  car_ID_producto INT,
  car_cantidad INT,
  FOREIGN KEY (car_ID_usuario) REFERENCES usuarios(usu_ID_usuario),
  FOREIGN KEY (car_ID_producto) REFERENCES producto(pro_ID_producto)
);

-- Tabla Ventas Totales
CREATE TABLE ventas_totales (
  ven_ID_venta INT PRIMARY KEY AUTO_INCREMENT,
  ven_ID_producto INT,
  ven_cantidad INT,
  ven_total DECIMAL(10,2),
  ven_fecha DATE,
  FOREIGN KEY (ven_ID_producto) REFERENCES producto(pro_ID_producto)
);

-- Tabla Descuento
CREATE TABLE Descuento (
  desc_ID_descuento INT PRIMARY KEY AUTO_INCREMENT,
  desc_codigo VARCHAR(50),
  desc_descripcion TEXT,
  desc_porcentaje FLOAT,
  desc_estado VARCHAR(50),
  desc_fecha_inicio DATE,
  desc_fecha_fin DATE
);

-- Tabla Categorías de Productos
CREATE TABLE categorias_productos (
  cat_ID_categoria INT PRIMARY KEY AUTO_INCREMENT,
  cat_Nombre VARCHAR(100),
  cat_descripcion TEXT,
  cat_Disponibilidad VARCHAR(50)
);

-- Relaciones adicionales
-- producto con proveedor
ALTER TABLE producto
  ADD COLUMN pro_ID_proveedor INT,
  ADD FOREIGN KEY (pro_ID_proveedor) REFERENCES proveedor(prov_ID_proveedor);

-- producto con usuarios
ALTER TABLE producto
  ADD COLUMN pro_ID_usuario INT,
  ADD FOREIGN KEY (pro_ID_usuario) REFERENCES usuarios(usu_ID_usuario);

-- producto con Descuento
ALTER TABLE producto
  ADD COLUMN pro_ID_descuento INT,
  ADD FOREIGN KEY (pro_ID_descuento) REFERENCES Descuento(desc_ID_descuento);

-- producto con categorias_productos
ALTER TABLE producto
  ADD COLUMN pro_ID_categoria INT,
  ADD FOREIGN KEY (pro_ID_categoria) REFERENCES categorias_productos(cat_ID_categoria);