<?php
require_once __DIR__ . '/../config/database.php';

class Producto {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConexion(); // ✅ Conexión con base de datos rositaforever
    }

    public function obtenerTodos() {
        $sql = "SELECT p.*, c.cat_Nombre, pr.prov_Nombre
                FROM producto p
                LEFT JOIN categorias_productos c ON p.pro_ID_categoria = c.cat_ID_categoria
                LEFT JOIN proveedor pr ON p.pro_ID_proveedor = pr.prov_ID_proveedor";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id) {
        $stmt = $this->conn->prepare("SELECT * FROM producto WHERE pro_ID_producto = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertarProveedor($nombre) {
        $stmt = $this->conn->prepare("INSERT INTO proveedor (prov_Nombre) VALUES (?)");
        $stmt->execute([$nombre]);
        return $this->conn->lastInsertId();
    }

    public function insertarConInventario($data) {
        $this->conn->beginTransaction();
        try {
            $sql = "INSERT INTO producto (
                        pro_Nombre, pro_Descripcion, pro_Precio, pro_Descuento,
                        pro_ID_categoria, pro_Imagen, pro_Destacado,
                        pro_ID_usuario, pro_ID_proveedor
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                $data['nombre'],
                $data['descripcion'],
                $data['precio'],
                $data['descuento'],
                $data['categoria'],
                $data['imagen'],
                $data['destacado'],
                $data['usuario'],
                $data['proveedor']
            ]);

            $productoID = $this->conn->lastInsertId();

            $stmt2 = $this->conn->prepare("INSERT INTO inventario (
                inv_ID_producto, inv_stock, inv_cantidad_disponible, inv_estado, inv_fecha_ultimo_ingreso
            ) VALUES (?, ?, ?, ?, CURDATE())");
            $stmt2->execute([$productoID, $data['stock'], $data['stock'], 'Disponible']);

            $stmt3 = $this->conn->prepare("INSERT INTO ingreso_inventario (ing_ID_producto, ing_cantidad) VALUES (?, ?)");
            $stmt3->execute([$productoID, $data['stock']]);

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function actualizar($id, $data) {
        $sql = "UPDATE producto SET 
            pro_Nombre = ?, 
            pro_Descripcion = ?, 
            pro_Precio = ?, 
            pro_Descuento = ?, 
            pro_ID_categoria = ?, 
            pro_Imagen = ?, 
            pro_Destacado = ?, 
            pro_ID_proveedor = ?
            WHERE pro_ID_producto = ?";
        return $this->conn->prepare($sql)->execute([
            $data['nombre'],
            $data['descripcion'],
            $data['precio'],
            $data['descuento'],
            $data['categoria'],
            $data['imagen'],
            $data['destacado'],
            $data['proveedor'],
            $id
        ]);
    }

    public function eliminar($id) {
        $this->conn->beginTransaction();
        try {
            $this->conn->prepare("DELETE FROM resena WHERE res_ID_producto = ?")->execute([$id]);
            $this->conn->prepare("DELETE FROM ingreso_inventario WHERE ing_ID_producto = ?")->execute([$id]);
            $this->conn->prepare("DELETE FROM salida_inventario WHERE sal_ID_producto = ?")->execute([$id]);
            $this->conn->prepare("DELETE FROM inventario WHERE inv_ID_producto = ?")->execute([$id]);

            $stmt = $this->conn->prepare("DELETE FROM producto WHERE pro_ID_producto = ?");
            $stmt->execute([$id]);

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function obtenerProveedores() {
        return $this->conn->query("SELECT * FROM proveedor")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerProductosPorCategoria($categoriaId) {
        $sql = "SELECT p.*, i.inv_cantidad_disponible 
                FROM producto p
                JOIN inventario i ON p.pro_ID_producto = i.inv_ID_producto
                WHERE p.pro_ID_categoria = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$categoriaId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerResenasPorProducto($productoID) {
        $stmt = $this->conn->prepare("
            SELECT r.*, u.usu_Nombre, u.usu_Apellido
            FROM resena r
            JOIN usuarios u ON r.res_ID_usuario = u.usu_ID_usuario
            WHERE r.res_ID_producto = ?
            ORDER BY r.res_Calificacion DESC
        ");
        $stmt->execute([$productoID]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contarProductos($buscar = '') {
        if ($buscar !== '') {
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM producto WHERE pro_Nombre LIKE ?");
            $stmt->execute(["%$buscar%"]);
            return $stmt->fetchColumn();
        }
        return $this->conn->query("SELECT COUNT(*) FROM producto")->fetchColumn();
    }

    public function obtenerProductosPaginados($inicio, $limite, $buscar = '') {
        if ($buscar !== '') {
            $stmt = $this->conn->prepare("
                SELECT p.*, c.cat_Nombre, pr.prov_Nombre
                FROM producto p
                LEFT JOIN categorias_productos c ON p.pro_ID_categoria = c.cat_ID_categoria
                LEFT JOIN proveedor pr ON p.pro_ID_proveedor = pr.prov_ID_proveedor
                WHERE p.pro_Nombre LIKE ?
                ORDER BY p.pro_ID_producto DESC
                LIMIT ?, ?
            ");
            $stmt->bindValue(1, "%$buscar%", PDO::PARAM_STR);
            $stmt->bindValue(2, $inicio, PDO::PARAM_INT);
            $stmt->bindValue(3, $limite, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $stmt = $this->conn->prepare("
                SELECT p.*, c.cat_Nombre, pr.prov_Nombre
                FROM producto p
                LEFT JOIN categorias_productos c ON p.pro_ID_categoria = c.cat_ID_categoria
                LEFT JOIN proveedor pr ON p.pro_ID_proveedor = pr.prov_ID_proveedor
                ORDER BY p.pro_ID_producto DESC
                LIMIT ?, ?
            ");
            $stmt->bindValue(1, $inicio, PDO::PARAM_INT);
            $stmt->bindValue(2, $limite, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
}
