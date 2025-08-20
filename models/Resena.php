<?php
require_once __DIR__ . '/../config/database.php';

class Resena {
    private $db;

    public function __construct() {
        $this->db = database::getConexion();
    }

    public function guardar($data) {
        $sql = "INSERT INTO resena (res_ID_producto, res_ID_usuario, res_Calificacion, res_Comentario)
                VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['res_ID_producto'],
            $data['res_ID_usuario'],
            $data['res_Calificacion'],
            $data['res_Comentario']
        ]);
    }

    public function existeResena($productoId, $usuarioId) {
        $sql = "SELECT COUNT(*) FROM resena WHERE res_ID_producto = ? AND res_ID_usuario = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$productoId, $usuarioId]);
        return $stmt->fetchColumn() > 0;
    }
}