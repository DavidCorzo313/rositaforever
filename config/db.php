<?php
$host = 'localhost';
$db = 'rositaforever'; // <-- este es el nombre correcto de tu base de datos
$user = 'root';
$pass = '';

$conexion = new mysqli($host, $user, $pass, $db);

if ($conexion->connect_error) {
    die('Error de conexión: ' . $conexion->connect_error);
}
?>
