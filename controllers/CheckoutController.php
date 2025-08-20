<?php
session_start();
require_once '../config/database.php';
require_once '../models/Producto.php';

$conexion = database::getConexion();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre    = trim($_POST['nombre']);
    $email     = trim($_POST['email']);
    $direccion = trim($_POST['direccion']);
    $localidad = trim($_POST['localidad']);
    $carrito   = $_SESSION['carrito'] ?? [];

    if (empty($carrito)) {
        header('Location: ../views/usuarios/carrito.php?error=Carrito vacío');
        exit;
    }

    if (isset($_SESSION['usuario']['usu_ID_usuario'])) {
        $usuarioID = $_SESSION['usuario']['usu_ID_usuario'];

        // ✅ Actualiza dirección del usuario
        $stmt = $conexion->prepare("UPDATE usuarios SET usu_Direccion = ?, usu_Localidad = ? WHERE usu_ID_usuario = ?");
        $stmt->execute([$direccion, $localidad, $usuarioID]);

        // ✅ Procesa inventario y registra salida
        foreach ($carrito as $item) {
            $productoID = $item['id'];
            $cantidad = $item['cantidad'];
            $monto = $item['precio'] * $cantidad;

            // Descuenta stock
            $conexion->prepare("
                UPDATE inventario 
                SET inv_stock = inv_stock - ?, 
                    inv_cantidad_disponible = inv_cantidad_disponible - ? 
                WHERE inv_ID_producto = ?
            ")->execute([$cantidad, $cantidad, $productoID]);

            // Registra salida
            $conexion->prepare("
                INSERT INTO salida_inventario (sal_ID_producto, sal_cantidad, sal_motivo, sal_fecha, sal_monto)
                VALUES (?, ?, 'Vendido', NOW(), ?)
            ")->execute([$productoID, $cantidad, $monto]);
        }
    }

    // 🔄 Redirige a la vista de pago
    header('Location: ../views/usuarios/pago.php');
    exit;
}
