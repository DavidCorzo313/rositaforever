<?php
function conectar() {
    $host = "localhost";
    $usuario = "root";
    $contrasena = "";
    $base_datos = "rositaforever"; // Verifica que el nombre esté bien escrito

    $conexion = new mysqli($host, $usuario, $contrasena, $base_datos);

    if ($conexion->connect_error) {
        die("❌ Error de conexión: " . $conexion->connect_error);
    }

    // Establece zona horaria Colombia (-05:00)
    $conexion->query("SET time_zone = '-05:00'");

    return $conexion;
}
?>
