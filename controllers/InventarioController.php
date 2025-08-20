<?php
require_once __DIR__ . '/../models/Inventario.php';

$inventario = new Inventario();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $producto = $_POST['producto'] ?? null;
    $cantidad = $_POST['cantidad'] ?? null;
    $tipo     = $_POST['tipo'] ?? '';
    $motivo   = $_POST['motivo'] ?? null;

    try {
        // Validar que exista el producto antes de registrar movimiento
        if (!$inventario->existeProducto($producto)) {
            throw new Exception("❌ El producto no existe en la base de datos.");
        }

        if ($tipo === 'ingreso') {
            $inventario->registrarIngreso($producto, $cantidad);
            $inventario->registrarMovimiento($producto, 'ingreso', $cantidad, null);
            header("Location: ../views/admins/admin_inventario.php?mensaje=ingreso_ok");
            exit;
        }

        if ($tipo === 'salida') {
            $inventario->eliminarProducto($producto, $cantidad, $motivo);
            $inventario->registrarMovimiento($producto, 'salida', $cantidad, $motivo);
            header("Location: ../views/admins/admin_inventario.php?mensaje=salida_ok");
            exit;
        }

        // Si el tipo no es válido
        throw new Exception("❌ Tipo de operación no válido.");

    } catch (Exception $e) {
        // Redirecciona con el mensaje de error
        $mensaje = urlencode($e->getMessage());
        header("Location: ../views/admins/admin_inventario.php?error=$mensaje");
        exit;
    }
}
