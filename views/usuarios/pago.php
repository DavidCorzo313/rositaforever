<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../../index.php');
    exit;
}

require_once '../../config/database.php';
$conexion = database::getConexion(); // ✅ Conexión correcta

$total = 0;
$carrito = $_SESSION['carrito'] ?? [];

if (empty($carrito)) {
    header('Location: carrito.php');
    exit;
}

// Calcular total
foreach ($carrito as $item) {
    $total += $item['precio'] * $item['cantidad'];
}

// Obtener NIT guardado del usuario
$documentoGuardado = '';
$usuarioID = $_SESSION['usuario']['usu_ID_usuario'] ?? null;

if ($usuarioID) {
    $stmt = $conexion->prepare("SELECT usu_NIT FROM usuarios WHERE usu_ID_usuario = :id");
    $stmt->execute([':id' => $usuarioID]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        $documentoGuardado = trim($usuario['usu_NIT'] ?? '');
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pago</title>
    <link rel="stylesheet" href="../../public/css/checkout.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body>

<?php include '../partials/navbar_clientes.php'; ?>

<div class="container py-5">
    <h2 class="text-center mb-4" style="color: #00d9ff;">Pago con PSE</h2>


    <div class="card mx-auto shadow" style="max-width: 500px;">
        <div class="card-body">
            <h4 class="card-title mb-4">Resumen del pago</h4>
            <p>Total a pagar: <strong>$<?= number_format($total, 2) ?></strong></p>

            <form action="../../controllers/PagarController.php" method="POST">
                <div class="mb-3">
                    <label class="form-label">Banco PSE</label>
                    <select class="form-select" name="banco" required>
                        <option value="">Selecciona tu banco</option>
                        <option value="Bancolombia">Bancolombia</option>
                        <option value="Davivienda">Davivienda</option>
                        <option value="BBVA">BBVA</option>
                        <option value="Banco de Bogotá">Banco de Bogotá</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Número de documento</label>
                    <input type="text" name="documento" class="form-control"
                        value="<?= htmlspecialchars($documentoGuardado) ?>" required>
                </div>
                <input type="hidden" name="documento_guardado" value="<?= htmlspecialchars($documentoGuardado) ?>">

                <button type="submit" class="btn btn-success w-100">Pagar con PSE</button>
            </form>
        </div>
    </div>
</div>

<?php include '../partials/footer_clientes.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
