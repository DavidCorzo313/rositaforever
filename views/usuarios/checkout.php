<?php
session_start();
require_once '../../config/database.php';
$conexion = database::getConexion(); // ✅ usa la clase correctamente

$carrito = $_SESSION['carrito'] ?? [];
$total = 0;

if (empty($carrito)) {
    header('Location: carrito.php');
    exit;
}

$direccionGuardada = '';
$localidadGuardada = '';

if (isset($_SESSION['usuario']['usu_ID_usuario'])) {
    $usuarioID = $_SESSION['usuario']['usu_ID_usuario'];
    $stmt = $conexion->prepare("SELECT usu_Nombre, usu_Apellido, usu_Email, usu_Direccion, usu_Localidad FROM usuarios WHERE usu_ID_usuario = :id");
    $stmt->execute([':id' => $usuarioID]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        $nombreCompleto = trim(($usuario['usu_Nombre'] ?? '') . ' ' . ($usuario['usu_Apellido'] ?? ''));
        $emailGuardado = trim($usuario['usu_Email'] ?? '');
        $direccionGuardada = trim($usuario['usu_Direccion'] ?? '');
        $localidadGuardada = trim($usuario['usu_Localidad'] ?? '');
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Finalizar Compra</title>
    <link rel="stylesheet" href="../../public/css/checkout.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body>

<?php include '../partials/navbar_clientes.php'; ?>

<div class="container py-5">
    <h2 class="text-center mb-4" style="color: #00d9ff; text-shadow: 0 0 8px rgba(0, 255, 255, 0.5);">
    Finalizar Compra
</h2>


    <div class="row">
    <div class="col-md-6">
        <h4 style="color: #00d9ff; text-shadow: 0 0 8px rgba(0, 255, 255, 0.5);">Datos de envío</h4>

            <form action="../../controllers/CheckoutController.php" method="POST">
                <div class="mb-3">
                <label class="form-label" style="color: #00d9ff; text-shadow: 0 0 8px rgba(0, 255, 255, 0.5);">
                 Nombre completo
                 </label>

                    <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($nombreCompleto ?? '') ?>" required>
                </div>
                
              <div class="mb-3">
    <label class="form-label" style="color: #00d9ff;">Correo electrónico</label>
    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($emailGuardado ?? '') ?>" required>
</div>


              <div class="mb-3">
    <label class="form-label" style="color: #00d9ff;">Dirección de envío</label>
    <textarea name="direccion" class="form-control" rows="3" <?= empty($direccionGuardada) ? 'required' : '' ?>><?= htmlspecialchars($direccionGuardada) ?></textarea>
    <?php if (!empty($direccionGuardada)): ?>
    <small class="form-text" style="color: #00d9ff;">Ya tienes una dirección guardada. Puedes cambiarla si lo deseas en tu perfil.</small>
<?php endif; ?>

</div>


          <div class="mb-3">
    <label class="form-label" style="color: #00d9ff;">Localidad</label>
    <input type="text" name="localidad" class="form-control" value="<?= htmlspecialchars($localidadGuardada) ?>" required style="border-color: #00d9ff;">
    <?php if (!empty($localidadGuardada)): ?>
        <small class="form-text" style="color: #00d9ff;">
            Localidad actual: <?= htmlspecialchars($localidadGuardada) ?>. Puedes cambiarla si lo deseas en tu perfil.
        </small>
    <?php endif; ?>
</div>



                <button type="submit" class="btn btn-success">Confirmar pedido</button>
            </form>
        </div>
        <div class="col-md-6">
            <h4 style="color: #00d9ff;">Resumen del pedido</h4>

            <ul class="list-group mb-3">
                <?php foreach ($carrito as $item): 
                    $subtotal = $item['precio'] * $item['cantidad'];
                    $total += $subtotal;
                ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?= htmlspecialchars($item['nombre']) ?> x<?= $item['cantidad'] ?>
                        <span>$<?= number_format($subtotal, 2) ?></span>
                    </li>
                <?php endforeach; ?>
                <li class="list-group-item d-flex justify-content-between fw-bold">
                    Total
                    <span>$<?= number_format($total, 2) ?></span>
                </li>
            </ul>
        </div>
    </div>
</div>

<?php include '../partials/footer_clientes.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>