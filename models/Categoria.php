<?php
require_once __DIR__ . '/../config/database.php';

class Categoria {
    private $conn;

    public function __construct() {
        $this->conn = database::getConexion(); // ✅ Conectado a rositaforever
    }

    // Obtener una categoría por su ID
    public function obtenerCategoriaPorId($id) {
        $sql = "SELECT * FROM categorias_productos WHERE cat_ID_categoria = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Obtener todas las categorías
    public function obtenerCategoriasTodas() {
        $sql = "SELECT * FROM categorias_productos";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener solo las categorías disponibles y activas
    public function obtenerCategoriasDisponibles() {
        $sql = "SELECT * FROM categorias_productos WHERE cat_Disponibilidad = 'Disponible' AND cat_estado = 'Activo'";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Insertar una nueva categoría (sin autoincremento)
    public function insertarCategoria($data) {
        $sqlMax = "SELECT MAX(cat_ID_categoria) AS max_id FROM categorias_productos";
        $stmtMax = $this->conn->query($sqlMax);
        $max = $stmtMax->fetch(PDO::FETCH_ASSOC)['max_id'];
        $nextId = $max ? $max + 1 : 1;

        $sql = "INSERT INTO categorias_productos (
                    cat_ID_categoria, cat_Nombre, cat_descripcion, 
                    cat_Disponibilidad, cat_Imagen, cat_estado
                ) VALUES (
                    :id, :nombre, :descripcion, :disponibilidad, :imagen, 'Activo')";
        $stmt = $this->conn->prepare($sql);

        $data[':id'] = $nextId;
        return $stmt->execute($data);
    }

    // Cambiar el estado de una categoría
    public function cambiarEstadoCategoria($id, $estado) {
        $sql = "UPDATE categorias_productos SET cat_estado = :estado WHERE cat_ID_categoria = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':estado' => $estado, ':id' => $id]);
    }

    // Eliminar una categoría (solo si no tiene productos relacionados)
    public function eliminarCategoria($id) {
        $sqlCheck = "SELECT COUNT(*) FROM producto WHERE pro_ID_categoria = :id";
        $stmtCheck = $this->conn->prepare($sqlCheck);
        $stmtCheck->execute([':id' => $id]);
        $total = $stmtCheck->fetchColumn();

        if ($total > 0) {
            return "NO_ELIMINAR"; // No eliminar si hay productos asociados
        }

        $sql = "DELETE FROM categorias_productos WHERE cat_ID_categoria = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
