<?php
require_once '../../models/Producto.php';
require_once '../../models/Categoria.php';
session_start();

$categoriaId = isset($_GET['categoria']) ? intval($_GET['categoria']) : 0;
if ($categoriaId <= 0) {
    die("❌ Categoría no especificada o ID inválido");
}

$categoriaModel = new Categoria();
$categoria = $categoriaModel->obtenerCategoriaPorId($categoriaId);
if (!$categoria || !isset($categoria['cat_ID_categoria'])) {
    die("❌ La categoría con ID $categoriaId no existe.");
}

$productoModel = new Producto();
$productos = $productoModel->obtenerProductosPorCategoria($categoriaId);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($categoria['cat_Nombre']) ?> - rositaforever</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <?php include_once '../partials/chat.php'; ?>
    <script src="../../public/js/chat.js"></script>
    <link rel="stylesheet" href="../../public/css/chat.css">

    <!-- CSS futurista con partículas -->
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap");
        @import url("https://fonts.cdnfonts.com/css/self-deception");

        /* -----------------------------
           Fondo animado de gradiente
        ---------------------------------*/
        body {
            font-family: "Poppins", sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(-45deg, #1e3c72, #2a5298, #00d9ff, #00ffc3);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
        }

        @keyframes gradientBG {
            0% {background-position: 0% 50%;}
            50% {background-position: 100% 50%;}
            100% {background-position: 0% 50%;}
        }

        /* Contenedor de tarjetas */
        .card {
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            border-radius: 15px;
            border: 2px solid rgba(0,255,255,0.5);
            background: linear-gradient(145deg, #ffffff 0%, #f0f8ff 100%);
            box-shadow: 0 0 20px rgba(0, 255, 255, 0.4);
            transition: transform 0.5s ease, box-shadow 0.5s ease, background 1.5s ease;
            overflow: hidden;
            position: relative;
            margin-bottom: 30px;
        }

        /* Hover con glow y zoom */
        .card:hover {
            transform: scale(1.08);
            box-shadow: 0 0 45px rgba(0, 255, 255, 0.8), 0 0 90px rgba(0, 195, 255, 0.5);
            background: linear-gradient(145deg, #e0f7ff 0%, #d0f0ff 50%, #f0f8ff 100%);
        }

        /* Contenedor de imagen con glow */
        .card-img-container {
            background: linear-gradient(135deg, rgba(0, 255, 255, 0.1), rgba(0, 195, 255, 0.1));
            padding: 15px;
            border-radius: 10px 10px 0 0;
            transition: background 0.5s ease;
            position: relative;
            overflow: hidden;
        }

        .card:hover .card-img-container {
            background: linear-gradient(135deg, rgba(0, 255, 255, 0.2), rgba(0, 195, 255, 0.3));
        }

        .card img {
            width: 100%;
            height: 160px;
            object-fit: contain;
            transition: transform 0.5s ease;
        }

        .card:hover img {
            transform: scale(1.15) rotate(-3deg);
        }

        .card-body {
            text-align: center;
            padding: 15px;
            margin-top: 10px;
        }

        .card-title {
            font-size: 19px;
            font-weight: 700;
            margin-bottom: 12px;
            color: #007bff;
            text-shadow: 0 0 8px rgba(0, 195, 255, 0.6);
            transition: text-shadow 0.5s ease;
        }

        .card:hover .card-title {
            text-shadow: 0 0 20px rgba(0, 255, 255, 0.9);
        }

        .price {
            font-size: 1.5rem;
            color: #28a745;
            font-weight: bold;
        }

        .stock {
            font-size: 1rem;
            color: #6c757d;
        }

        .reviews {
            font-size: 0.9rem;
            color: #ff9800;
        }

        .btn-primary, .btn-categoria {
            background-color: #00cfff !important;
            color: white !important;
            font-weight: 600;
            border-radius: 8px;
            padding: 8px 16px;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 255, 255, 0.35);
            transition: all 0.5s ease, box-shadow 0.5s ease;
        }

        .btn-primary:hover, .btn-categoria:hover {
            background-color: #009dff !important;
            transform: scale(1.12);
            box-shadow: 0 6px 25px rgba(0, 255, 255, 0.8), 0 0 50px rgba(0, 195, 255, 0.5);
        }

        .boton {
            text-decoration: none;
            color: white;
        }

        /* Overlay brillante */
        .card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: rgba(255, 255, 255, 0.05);
            transform: rotate(45deg);
            pointer-events: none;
            transition: all 0.5s ease;
        }

        .card:hover::before {
            top: 0;
            left: 0;
            background: rgba(255, 255, 255, 0.12);
        }

        /* Partículas flotantes detrás de la tarjeta */
        .card-img-container::after {
            content: '';
            position: absolute;
            width: 150%;
            height: 150%;
            top: -25%;
            left: -25%;
            background: radial-gradient(circle, rgba(0,255,255,0.2) 2%, transparent 2%) repeat;
            background-size: 20px 20px;
            animation: floatParticles 6s linear infinite;
            pointer-events: none;
        }

        @keyframes floatParticles {
            0% {background-position: 0 0;}
            50% {background-position: 10px 10px;}
            100% {background-position: 0 0;}
        }

        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, #4a69bd, #344d99);
            border-radius: 3px;
        }
    </style>
</head>
<body>
<?php include '../partials/navbar_clientes.php'; ?>

<div class="container mt-5">
    <?php if (isset($_GET['resena']) && $_GET['resena'] === 'existe'): ?>
        <div class="alert alert-warning text-center">❗ Ya has calificado este producto anteriormente.</div>
    <?php endif; ?>
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
            <div class="col-md-4">
                <div class="card">
                    <div class="card-img-container">
                        <img src="../img/productos/<?= htmlspecialchars($prod['pro_Imagen']) ?>" class="card-img-top" alt="<?= htmlspecialchars($prod['pro_Nombre']) ?>">
                    </div>
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
                        <h6>Reseñas:</h6>
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
                                <button type="submit" class="btn btn-outline-secondary">Enviar reseña</button>
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
