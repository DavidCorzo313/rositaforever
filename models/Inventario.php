<?php
require_once __DIR__ . '/../config/database.php';

class Inventario {
    private $db;

    public function __construct() {
        $this->db = database::getConexion(); // ✅ Conexión correcta a rositaforever
    }

    // Registrar ingreso de inventario
    public function registrarIngreso($idProducto, $cantidad) {
        // 1. Registrar ingreso en la tabla ingreso_inventario
        $sql = "INSERT INTO ingreso_inventario (ing_ID_producto, ing_cantidad) VALUES (:producto, :cantidad)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':producto' => $idProducto,
            ':cantidad' => $cantidad
        ]);

        // 2. Verificar si el producto ya existe en inventario
        $sql = "SELECT inv_cantidad_disponible FROM inventario WHERE inv_ID_producto = :producto";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':producto' => $idProducto]);
        $existente = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existente) {
            // 3. Si existe, actualizar la cantidad sumando
            $nuevaCantidad = $existente['inv_cantidad_disponible'] + $cantidad;
            $sql = "UPDATE inventario SET inv_cantidad_disponible = :cantidad WHERE inv_ID_producto = :producto";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':cantidad' => $nuevaCantidad,
                ':producto' => $idProducto
            ]);
        } else {
            // 4. Si no existe, crear nuevo registro
            $sql = "INSERT INTO inventario (inv_ID_producto, inv_cantidad_disponible) VALUES (:producto, :cantidad)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':producto' => $idProducto,
                ':cantidad' => $cantidad
            ]);
        }

        // 5. Registrar movimiento
        $this->registrarMovimiento($idProducto, 'ingreso', $cantidad);

        return true;
    }

    // Registrar salida de inventario
    public function eliminarProducto($idProducto, $cantidad, $motivo) {
        // 1. Verificar stock actual
        $sql = "SELECT inv_cantidad_disponible FROM inventario WHERE inv_ID_producto = :producto";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':producto' => $idProducto]);
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($producto && $producto['inv_cantidad_disponible'] >= $cantidad) {
            // 2. Actualizar stock
            $nuevoStock = $producto['inv_cantidad_disponible'] - $cantidad;
            $sql = "UPDATE inventario SET inv_cantidad_disponible = :cantidad WHERE inv_ID_producto = :producto";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':cantidad' => $nuevoStock,
                ':producto' => $idProducto
            ]);

            // 3. Registrar movimiento de salida
            $this->registrarMovimiento($idProducto, 'salida', $cantidad, $motivo);

            return true;
        } else {
            throw new Exception("❌ No hay suficiente stock disponible para realizar la salida.");
        }
    }

    // Obtener todo el inventario con nombre del producto
    public function obtenerInventario() {
        $sql = "SELECT p.pro_ID_producto, p.pro_Nombre, i.* 
                FROM inventario i 
                JOIN producto p ON i.inv_ID_producto = p.pro_ID_producto";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener solo productos activos
    public function obtenerProductosActivos() {
        $sql = "SELECT pro_ID_producto, pro_Nombre 
                FROM producto 
                WHERE pro_estado = 'Activo'";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Contar elementos en inventario (con búsqueda opcional)
    public function contarInventario($buscar = '') {
        if ($buscar !== '') {
            $stmt = $this->db->prepare("
                SELECT COUNT(*) 
                FROM inventario i 
                JOIN producto p ON i.inv_ID_producto = p.pro_ID_producto
                WHERE p.pro_Nombre LIKE ?
            ");
            $stmt->execute(["%$buscar%"]);
            return $stmt->fetchColumn();
        }

        return $this->db->query("SELECT COUNT(*) FROM inventario")->fetchColumn();
    }

    // Obtener inventario paginado (con búsqueda opcional)
    public function obtenerInventarioPaginado($inicio, $limite, $buscar = '') {
        if ($buscar !== '') {
            $stmt = $this->db->prepare("
                SELECT i.*, p.pro_Nombre, p.pro_ID_producto
                FROM inventario i
                JOIN producto p ON i.inv_ID_producto = p.pro_ID_producto
                WHERE p.pro_Nombre LIKE ?
                ORDER BY p.pro_ID_producto
                LIMIT ?, ?
            ");
            $stmt->bindValue(1, "%$buscar%", PDO::PARAM_STR);
            $stmt->bindValue(2, $inicio, PDO::PARAM_INT);
            $stmt->bindValue(3, $limite, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $stmt = $this->db->prepare("
                SELECT i.*, p.pro_Nombre, p.pro_ID_producto
                FROM inventario i
                JOIN producto p ON i.inv_ID_producto = p.pro_ID_producto
                ORDER BY p.pro_ID_producto
                LIMIT ?, ?
            ");
            $stmt->bindValue(1, $inicio, PDO::PARAM_INT);
            $stmt->bindValue(2, $limite, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    // ✅ Método para registrar movimientos (ingreso o salida)
    public function registrarMovimiento($idProducto, $tipo, $cantidad, $motivo = null) {
        $sql = "INSERT INTO movimientos_inventario (pro_ID_producto, tipo_movimiento, cantidad, sal_motivo, fecha_movimiento)
                VALUES (:producto, :tipo, :cantidad, :motivo, NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':producto' => $idProducto,
            ':tipo'     => $tipo,
            ':cantidad' => $cantidad,
            ':motivo'   => $motivo
        ]);
    }

    // ✅ Método para validar existencia de producto antes de registrar movimiento
    public function existeProducto($idProducto) {
        $stmt = $this->db->prepare("SELECT 1 FROM producto WHERE pro_ID_producto = ?");
        $stmt->execute([$idProducto]);
        return $stmt->fetchColumn() !== false;
    }
}
