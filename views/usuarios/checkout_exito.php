<?php
session_start();
require_once '../../config/database.php';

$conexion = database::getConexion();

if (!isset($_SESSION['usuario'], $_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    header("Location: carrito.php");
    exit;
}

$carrito = $_SESSION['carrito'];
$usuarioID = $_SESSION['usuario']['usu_ID_usuario'];

try {
    $conexion->beginTransaction();

    foreach ($carrito as $item) {
        $productoID = $item['id'];
        $cantidadVendida = $item['cantidad'];

        // ✅ 1. Registrar en salida_inventario
        $stmtSalida = $conexion->prepare("
            INSERT INTO salida_inventario (sal_ID_producto, sal_cantidad, sal_motivo, sal_fecha)
            VALUES (:producto, :cantidad, :motivo, NOW())
        ");
        $stmtSalida->execute([
            ':producto' => $productoID,
            ':cantidad' => $cantidadVendida,
            ':motivo'   => 'vendido'
        ]);

        // ✅ 2. Actualizar inventario
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

    // ✅ Limpiar carrito
    unset($_SESSION['carrito']);

} catch (Exception $e) {
    $conexion->rollBack();
    die("❌ Error al procesar el pago: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pago Exitoso</title>
    <link rel="stylesheet" href="../../public/css/checkout.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body>

<?php include '../partials/navbar_clientes.php'; ?>


<div class="container py-5 text-center">
    <h2 class="text-success mb-3">✅ ¡Pago exitoso!</h2>
    <p class="lead">Gracias por tu compra. El inventario fue cargado.</p>

    <?php if ($pagoInfo): ?>
        <p><strong>Banco:</strong> <?= htmlspecialchars($pagoInfo['banco']) ?></p>
        <p><strong>Documento:</strong> <?= htmlspecialchars($pagoInfo['documento']) ?></p>
    <?php endif; ?>

    <a href="Pagina_Inicial.php" class="btn btn-primary mt-3">Volver al inicio</a>
</div>


<?php include '../partials/footer_clientes.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
