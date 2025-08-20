<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../../models/Categoria.php';
$categoriaModel = new Categoria();
$categorias = $categoriaModel->obtenerCategoriasDisponibles();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Rosita Forever</title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Iconos -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<!-- Estilos personalizados -->
<link rel="stylesheet" href="/rositaforever/public/css/categorias.css">

<?php include_once '../partials/chat.php'; ?>
<script src="../../public/js/chat.js"></script>
<link rel="stylesheet" href="../../public/css/chat.css">

<style>
/* Fondo negro con partículas y glow */
body {
    background-color: #111;
    color: #fff;
    font-family: "Poppins", sans-serif;
    min-height: 100vh;
    overflow-x: hidden;
}

.container {
    position: relative;
    z-index: 1;
}

.container::before {
    content: '';
    position: absolute;
    width: 100%;
    height: 100%;
    background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="50" height="50"><circle cx="25" cy="25" r="2" fill="rgba(0,255,255,0.05)"/></svg>') repeat;
    top: 0;
    left: 0;
    pointer-events: none;
    animation: moveParticles 60s linear infinite;
    z-index: 0;
}
@keyframes moveParticles {
    0% {background-position: 0 0;}
    100% {background-position: 1000px 1000px;}
}

/* Título de categorías */
.titulo-categorias {
    font-size: 2.8rem;
    font-weight: 700;
    color: #00cfff;
    text-shadow: 0 0 10px rgba(0, 255, 255, 0.5), 0 0 25px rgba(0, 195, 255, 0.4);
}

/* Tarjetas de categorías */
.categoria-card {
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    border-radius: 15px;
    border: 2px solid #00cfff;
    box-shadow: 0 0 15px rgba(0, 255, 255, 0.2);
    background: linear-gradient(145deg, #1c1c1c 0%, #2a2a2a 100%);
    transition: transform 0.3s ease, box-shadow 0.3s ease, background 0.3s ease;
    overflow: hidden;
    position: relative;
}
.categoria-card:hover {
    transform: scale(1.07);
    box-shadow: 0 0 25px rgba(0, 255, 255, 0.6), 0 0 50px rgba(0, 195, 255, 0.4);
    background: linear-gradient(145deg, #222 0%, #333 100%);
}

/* Imagen de categoría */
.card-img-container {
    background: linear-gradient(135deg, rgba(0, 255, 255, 0.05), rgba(0, 195, 255, 0.05));
    padding: 15px;
    border-radius: 10px 10px 0 0;
    transition: background 0.3s ease;
}
.categoria-card:hover .card-img-container {
    background: linear-gradient(135deg, rgba(0, 255, 255, 0.2), rgba(0, 195, 255, 0.2));
}
.card-img-top {
    width: 100%;
    height: 160px;
    object-fit: contain;
    transition: transform 0.3s ease;
}
.categoria-card:hover .card-img-top {
    transform: scale(1.1) rotate(-2deg);
}

/* Contenido */
.card-body {
    text-align: center;
    padding: 15px;
    margin-top: 10px;
}
.card-title {
    font-size: 1.3rem;
    font-weight: 700;
    color: #fff; /* Blanco */
    text-shadow: 0 0 5px #00cfff, 0 0 10px #00cfff; /* Glow azul */
}


/* Botón */
.btn-categoria {
    background-color: #00cfff !important;
    color: white !important;
    font-weight: 600;
    border-radius: 8px;
    padding: 8px 16px;
    transition: all 0.3s ease, box-shadow 0.3s ease;
    border: none;
    box-shadow: 0 4px 15px rgba(0, 255, 255, 0.35);
}
.btn-categoria:hover {
    background-color: #009dff !important;
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(0, 255, 255, 0.6);
}

/* Overlay brillante */
.categoria-card::before {
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
.categoria-card:hover::before {
    background: rgba(255, 255, 255, 0.1);
}
</style>

</head>
<body>
<?php include '../partials/navbar_clientes.php'; ?>

<div class="container py-5">
    <h2 class="titulo-categorias text-center mb-5">Categorías de Licores</h2>
    <div class="row g-4 justify-content-center">
        <?php if (!empty($categorias)): ?>
            <?php foreach ($categorias as $cat): ?>
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card categoria-card h-100">
                    <div class="card-img-container">
                        <img src="../img/categorias/<?= htmlspecialchars($cat['cat_Imagen']) ?>" class="card-img-top" alt="<?= htmlspecialchars($cat['cat_Nombre']) ?>">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($cat['cat_Nombre']) ?></h5>
                        <a href="productos_categoria.php?categoria=<?= $cat['cat_ID_categoria'] ?>" class="btn btn-categoria">Ver más</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-warning text-center">No hay categorías disponibles.</div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../partials/footer_clientes.php'; ?>
</body>
</html>
