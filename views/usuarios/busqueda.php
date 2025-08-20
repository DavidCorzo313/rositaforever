<?php 
require_once '../../config/database.php';
require_once '../../models/Producto.php';
$conexion = database::getConexion();
$productoModel = new Producto();

$termino = trim($_GET['q'] ?? '');
$productos = [];
$categoria = ['cat_Nombre' => 'Resultados de búsqueda', 'cat_ID_categoria' => 0];

if ($termino !== '') {
    $stmt = $conexion->prepare("
        SELECT p.*, i.inv_cantidad_disponible
        FROM producto p
        JOIN inventario i ON p.pro_ID_producto = i.inv_ID_producto
        WHERE p.pro_Estado = 'Activo' 
        AND p.pro_Nombre LIKE :nombre
    ");
    $stmt->execute([
        ':nombre' => "%$termino%"
    ]);
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($categoria['cat_Nombre']) ?> Rosita Forever 🍸</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../public/css/productos.css">
</head>
<body>
<?php include '../partials/navbar_clientes.php'; ?>

<div class="container mt-5">
    <h2 class="text-center mb-4" style="color: #00d9ff; text-shadow: 0 0 8px rgba(0, 255, 255, 0.5);"><?= htmlspecialchars($categoria['cat_Nombre']) ?></h2>

    <div class="row">
        <?php foreach ($productos as $prod): ?>
            <?php 
                $resenas = $productoModel->obtenerResenasPorProducto($prod['pro_ID_producto']);
                $promedio = 0;
                $mejorResena = "";
                if (count($resenas) > 0) {
                    $total = array_sum(array_column($resenas, 'res_Calificacion'));
                    $promedio = round($total / count($resenas));
                    usort($resenas, fn($a, $b) => $b['res_Calificacion'] <=> $a['res_Calificacion']);
                    $mejorResena = $resenas[0]['res_Comentario'];
                }
            ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="../img/productos/<?= htmlspecialchars($prod['pro_Imagen']) ?>" class="card-img-top" alt="<?= htmlspecialchars($prod['pro_Nombre']) ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($prod['pro_Nombre']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($prod['pro_Descripcion']) ?></p>
                        <p><strong>Precio:</strong> $<?= number_format($prod['pro_Precio']) ?></p>
                        <p><strong>Stock:</strong> <?= $prod['inv_cantidad_disponible'] ?> unidades</p>

                        <form action="../../controllers/CarritoController.php" method="POST">
                            <input type="hidden" name="action" value="agregar">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($prod['pro_ID_producto']) ?>">
                            <input type="hidden" name="cantidad" value="1">
                            <button class="btn btn-primary">
                                <i class="fas fa-shopping-cart"></i> Agregar al carrito
                            </button>
                        </form>

                        <hr>
                        <h6>Calificanos:</h6>
                        <p><?= str_repeat("⭐", $promedio) ?> (<?= $promedio ?>/5)</p>
                        <p>"<?= htmlspecialchars($mejorResena ?: "Sé el primero en comentar") ?>"</p>

                        <?php if (isset($_SESSION['usuario'])): ?>
                            <form method="POST" action="/rositaforever/controllers/ResenaController.php" class="mt-3">
                                <input type="hidden" name="producto_id" value="<?= $prod['pro_ID_producto'] ?>">
                                <input type="hidden" name="categoria_id" value="<?= $categoria['cat_ID_categoria'] ?>">
                                <input type="hidden" name="usuario_id" value="<?= $_SESSION['usuario']['usu_ID_usuario'] ?>">
                                <div class="mb-2">
                                    <label>Calificación:</label>
                                    <select name="calificacion" class="form-select" required>
                                        <option value="5">⭐⭐⭐⭐⭐</option>
                                        <option value="4">⭐⭐⭐⭐</option>
                                        <option value="3">⭐⭐⭐</option>
                                        <option value="2">⭐⭐</option>
                                        <option value="1">⭐</option>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <textarea name="comentario" class="form-control" placeholder="Tu comentario..." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-outline-secondary">Enviar Calificacion</button>
                            </form>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include '../partials/footer_clientes.php'; ?>
</body>
</html>