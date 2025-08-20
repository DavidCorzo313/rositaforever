<?php
session_start();
$carrito = $_SESSION['carrito'] ?? [];
$total = 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="../../public/css/carrito.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <?php include_once '../partials/chat.php'; ?>
<script src="../../public/js/chat.js"></script>
 <link rel="stylesheet" href="../../public/css/chat.css">
 
</head>
<body>

<?php include '../partials/navbar_clientes.php'; ?>

<div class="container py-5">
    <h2 class="text-center mb-4" style="color: #00d9ff; text-shadow: 0 0 8px rgba(0, 255, 255, 0.5);">Mi Carrito</h2>


    <?php if (!empty($carrito)): ?>
    <form action="../../controllers/CarritoController.php" method="POST">
        <input type="hidden" name="action" value="actualizar">
        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Imagen</th>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($carrito as $item): 
                    $subtotal = $item['precio'] * $item['cantidad'];
                    $total += $subtotal;
                ?>
                    <tr>
                        <td><img src="../img/productos/<?= htmlspecialchars($item['imagen']) ?>" width="60" alt=""></td>
                        <td><?= htmlspecialchars($item['nombre']) ?></td>
                        <td>$<?= number_format($item['precio'], 2) ?></td>
                        <td>
                            <input type="number" name="cantidades[<?= $item['id'] ?>]" min="1" value="<?= $item['cantidad'] ?>" class="form-control text-center">
                        </td>
                        <td>$<?= number_format($subtotal, 2) ?></td>
                        <td>
                            <a href="../../controllers/CarritoController.php?action=eliminar&id=<?= $item['id'] ?>" class="btn btn-danger btn-sm">X</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="text-end mb-4">
            <button type="submit" class="btn btn-primary">Refrescar Carrito</button>
        </div>
        <div class="text-end fs-4 fw-bold mb-4" style="color: #00d9ff; text-shadow: 0 0 8px rgba(0, 255, 255, 0.5);">
    Total: $<?= number_format($total, 2) ?>
</div>

        <div class="d-flex justify-content-between">
            <a href="Pagina_Inicial.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Seguir comprando
            </a>
            <a href="checkout.php" class="btn btn-success">
                Continuar con la compra <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </form>

    <?php else: ?>
    <div class="alert alert-info text-center">Tu carrito está vacío.</div>
    <div class="text-center mt-4">
        <a href="Pagina_Inicial.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Seguir comprando
        </a>
    </div>
    <?php endif; ?>
</div>

<?php include '../partials/footer_clientes.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>