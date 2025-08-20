<?php
session_start();
require_once '../config/database.php';

$conexion = database::getConexion();

if (!isset($_SESSION['usuario'], $_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    header("Location: ../views/usuarios/carrito.php");
    exit;
}

$carrito = $_SESSION['carrito'];
$usuarioID = $_SESSION['usuario']['usu_ID_usuario'];

try {
    $conexion->beginTransaction();

    foreach ($carrito as $item) {
        $productoID = $item['id'];
        $cantidadVendida = $item['cantidad'];
        $monto = $item['precio'] * $cantidadVendida;

        // ✅ 1. Verificar stock disponible
        $stmtStock = $conexion->prepare("
            SELECT inv_cantidad_disponible 
            FROM inventario 
            WHERE inv_ID_producto = ?
        ");
        $stmtStock->execute([$productoID]);
        $stockActual = $stmtStock->fetchColumn();

        if ($stockActual === false || $stockActual < $cantidadVendida) {
            throw new Exception("Stock insuficiente para el producto ID $productoID");
        }

        // ✅ 2. Registrar salida de inventario
        $stmtSalida = $conexion->prepare("
            INSERT INTO salida_inventario 
            (sal_ID_producto, sal_cantidad, sal_motivo, sal_fecha, sal_monto)
            VALUES (:producto, :cantidad, :motivo, NOW(), :monto)
        ");
        $stmtSalida->execute([
            ':producto' => $productoID,
            ':cantidad' => $cantidadVendida,
            ':motivo'   => 'vendido',
            ':monto'    => $monto
        ]);

        // ✅ 3. Descontar stock
        $stmtUpdate = $conexion->prepare("
            UPDATE inventario
            SET inv_stock = inv_stock - :cantidad,
                inv_cantidad_disponible = inv_cantidad_disponible - :cantidad
            WHERE inv_ID_producto = :producto
        ");
        $stmtUpdate->execute([
            ':producto' => $productoID,
            ':cantidad' => $cantidadVendida
        ]);
    }

    $conexion->commit();

    // ✅ 4. Vaciar carrito y redirigir
    unset($_SESSION['carrito']);
    header("Location: ../views/usuarios/checkout_exito.php");
    exit;

} catch (Exception $e) {
    $conexion->rollBack();
    die("❌ Error al procesar el pago: " . $e->getMessage());
}
