<?php
require_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $producto = $_POST['producto'];
    $cantidad = $_POST['cantidad'];
    $fecha = $_POST['fecha'];

    try {
        $conexion = database::getConexion();
        $stmt = $conexion->prepare("INSERT INTO salida_inventario (producto, cantidad, fecha_salida) VALUES (?, ?, ?)");
        $stmt->execute([$producto, $cantidad, $fecha]);

        // Redirigir o mostrar mensaje
        header("Location: ../../views/salidas/formulario.php?success=1");
        exit();
    } catch (PDOException $e) {
        echo "❌ Error al insertar: " . $e->getMessage();
    }
}
