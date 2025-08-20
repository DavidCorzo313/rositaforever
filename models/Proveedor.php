<?php
class Proveedor {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli("localhost", "root", "", "rositaforever"); // ✅ cambiado
        if ($this->conn->connect_error) {
            die("Error: " . $this->conn->connect_error);
        }
    }

    public function obtenerProveedores() {
        $result = $this->conn->query("SELECT * FROM proveedor");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
