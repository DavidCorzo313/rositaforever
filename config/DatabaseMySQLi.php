<?php
class DatabaseMySQLi {
    public static function getConexion() {
        $host = 'localhost';
        $user = 'root';
        $pass = '';
        $dbname = 'rositaforever';

        $conn = new mysqli($host, $user, $pass, $dbname);

        if ($conn->connect_error) {
            die("❌ Conexión MySQLi fallida: " . $conn->connect_error);
        }

        $conn->set_charset("utf8mb4");
        return $conn;
    }
}
