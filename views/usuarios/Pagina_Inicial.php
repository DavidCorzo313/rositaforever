<?php
$conn = new mysqli("localhost", "root", "", "rositaforever");

// Obtener los 8 productos más visualizados
$destacados = $conn->query("SELECT * FROM producto ORDER BY pro_Visualizaciones DESC LIMIT 8");

// Obtener todas las categorías disponibles y activas
$categorias = $conn->query("SELECT * FROM categorias_productos WHERE cat_Disponibilidad = 'Disponible' AND cat_estado = 'Activo'");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Rosita Forever 🍷</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap core -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Iconos y fuentes -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;900&display=swap">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

  <!-- CSS personalizado -->
  <link rel="stylesheet" href="../../public/css/pagina_inicial.css">
<?php include_once '../partials/chat.php'; ?>
<script src="../../public/js/chat.js"></script>
 <link rel="stylesheet" href="../../public/css/chat.css">

</head>

<body>

<!-- NAVBAR -->
<?php include_once '../partials/navbar_clientes.php'; ?>

<!-- CARRUSEL DINÁMICO -->
<div id="carouselExample" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="4000" data-bs-pause="false">
  <div class="carousel-inner">
    <?php $i = 0; while ($row = $destacados->fetch_assoc()): ?>
      <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
        <img src="../img/productos/<?= htmlspecialchars($row['pro_Imagen']) ?>" class="d-block w-100" alt="<?= htmlspecialchars($row['pro_Nombre']) ?>">
        <div class="carousel-caption d-none d-md-block">
          <h5 style="color: black;"><?= htmlspecialchars($row['pro_Nombre']) ?></h5>
          <p style="color: black;"><?= htmlspecialchars($row['pro_Descripcion']) ?></p>
        </div>
      </div>
    <?php $i++; endwhile; ?>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
    <span class="carousel-control-next-icon"></span>
  </button>
</div>

<!-- CATEGORÍAS DINÁMICAS -->
<div class="container py-5">
  <div><h2 class="text-center mb-4">Categorías 🍷🍸 </h2></div>
  <div class="row g-4">
    <?php if ($categorias->num_rows > 0): ?>
      <?php while ($cat = $categorias->fetch_assoc()): ?>
        <div class="col-6 col-md-4 col-lg-3">
          <div class="card text-center shadow-sm">
            <div class="d-flex align-items-center justify-content-center bg-white" style="height: 200px;">
              <img src="../img/categorias/<?= htmlspecialchars($cat['cat_Imagen']) ?>" 
                   alt="<?= htmlspecialchars($cat['cat_Nombre']) ?>" 
                   style="max-height: 100%; max-width: 100%; object-fit: contain;">
            </div>
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($cat['cat_Nombre']) ?></h5>
              <a href="productos_categoria.php?categoria=<?= $cat['cat_ID_categoria'] ?>" class="btn btn-categoria">Ver más</a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="col-12">
        <div class="alert alert-warning text-center">No hay categorías disponibles.</div>
      </div>
    <?php endif; ?>
  </div>
</div>

<!--footer-->
<?php include_once '../partials/footer_clientes.php'; ?>
<script src="js/Home_page.js"></script>
</body>
</html>