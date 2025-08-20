<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Nosotros - Rosita Forever</title>

<!-- Estilos -->
<link rel="stylesheet" href="../../public/css/nosotros.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.cdnfonts.com/css/self-deception" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="../../public/css/chat.css">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
<script src="../../public/js/chat.js" defer></script>
<script src="js/script.js" defer></script>

<?php include_once '../partials/chat.php'; ?>

<style>
body {
    background-color: #111;
    color: #fff;
    font-family: "Poppins", sans-serif;
    min-height: 100vh;
}

.nosotros-section {
    position: relative;
    z-index: 1;
}

.nosotros-section::before {
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

/* Títulos y glow */
.text-neon {
    color: #00cfff;
    text-shadow: 0 0 8px rgba(0,255,255,0.5), 0 0 15px rgba(0,195,255,0.4);
}

.text-subtitle {
    color: #fff;
}

.descripcion-blanca {
    color: #fff;
}

/* Iconos */
.icon-neon {
    color: #00cfff;
    text-shadow: 0 0 5px rgba(0,255,255,0.5);
}

/* Bordes y glow en imágenes */
.neon-border {
    border: 2px solid #00cfff;
    box-shadow: 0 0 15px rgba(0,255,255,0.3);
}

/* Tarjetas reseñas */
.reseña-card {
    background: linear-gradient(145deg, #1c1c1c 0%, #2a2a2a 100%);
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 5px 20px rgba(0,255,255,0.2), 0 10px 40px rgba(0,195,255,0.15);
    transition: transform 0.4s ease, box-shadow 0.4s ease, background 0.4s ease;
}
.reseña-card:hover {
    transform: scale(1.03);
    box-shadow: 0 10px 30px rgba(0,255,255,0.4), 0 20px 60px rgba(0,195,255,0.3);
}

/* Texto de reseñas en blanco */
.reseña-card h6,
.reseña-card p,
.reseña-card blockquote,
.reseña-card .text-muted,
.reseña-card small {
    color: #fff !important;
}
.reseña-card blockquote p {
    font-style: italic;
}
</style>

</head>
<body>

<?php include_once '../partials/navbar_clientes.php'; ?>

<!-- SECCIÓN QUIÉNES SOMOS -->
<section class="nosotros-section">
  <div class="container">
    <div class="row g-5 align-items-center">
      <!-- Texto -->
      <div class="col-lg-6 order-lg-2">
        <h2 class="display-5 fw-bold mb-3 text-neon">Rosita Forever</h2>
        <h5 class="mb-3 text-white">Licorera E-Commerce con Pasión y Estilo</h5>
        <p class="mb-4 fs-6 text-white">
          Somos una tienda digital especializada en licores nacionales e importados, comprometida con ofrecer productos auténticos, atención cercana y una experiencia de compra rápida y confiable. En <strong>Rosita Forever</strong>, mezclamos tecnología, diseño y sabor para acompañarte en cada brindis de la vida.
        </p>

        <ul class="list-unstyled">
          <li class="d-flex align-items-start mb-3">
            <span class="me-3">
              <i class="bi bi-lightning-charge-fill icon-neon"></i>
            </span>
            <div>
              <h6 class="fw-semibold mb-1 text-white">Compra Ágil</h6>
              <p class="mb-0 text-white">Un proceso intuitivo, rápido y 100% online desde cualquier lugar del país.</p>
            </div>
          </li>
          <li class="d-flex align-items-start mb-3">
            <span class="me-3">
              <i class="bi bi-box-seam-fill icon-neon"></i>
            </span>
            <div>
              <h6 class="fw-semibold mb-1 text-white">Envíos Seguros</h6>
              <p class="mb-0 text-white">Cobertura nacional con entrega puntual y seguimiento en tiempo real.</p>
            </div>
          </li>
          <li class="d-flex align-items-start">
            <span class="me-3">
              <i class="bi bi-award-fill icon-neon"></i>
            </span>
            <div>
              <h6 class="fw-semibold mb-1 text-white">Calidad Garantizada</h6>
              <p class="mb-0 text-white">Productos certificados y cuidadosamente seleccionados para ti.</p>
            </div>
          </li>
        </ul>
      </div>

      <!-- Imagen -->
      <div class="col-lg-6 order-lg-1">
        <div class="position-relative">
          <img src="../../views/img/categorias/logo.png" alt="Rosita Forever Logo" class="img-fluid rounded-4 shadow neon-border">
          <div class="tag-desde text-white">
            Desde 2019
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php
require_once '../../config/database.php';
$conexion = new PDO("mysql:host=localhost;dbname=rositaforever", "root", "");
$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$resenas = $conexion->query("SELECT r.res_Calificacion, r.res_Comentario, r.res_Fecha, u.usu_Nombre, u.usu_Foto 
FROM resena r 
JOIN usuarios u ON r.res_ID_usuario = u.usu_ID_usuario 
WHERE r.res_Comentario IS NOT NULL AND r.res_Calificacion = 5 
ORDER BY r.res_Fecha DESC 
LIMIT 6")->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- SECCIÓN DE RESEÑAS -->
<section class="reseñas-section">
  <div class="container">
    <div class="text-center mb-5">
      <h3 class="fw-bold text-neon">⭐ Opiniones de nuestros clientes</h3>
      <p class="text-white">Historias reales de quienes confiaron en nosotros y se endulzaron con Rosita Forever.</p>
    </div>
    <div class="row g-4">
      <?php foreach ($resenas as $res): ?>
        <div class="col-md-4">
          <div class="card reseña-card h-100">
            <div class="card-body text-center">
              <img src="../../views/<?= !empty($res['usu_Foto']) ? htmlspecialchars($res['usu_Foto']) : 'img/perfiles/user.png' ?>" class="rounded-circle mb-3" width="90" height="90" style="object-fit: cover;">
              <h6 class="fw-bold"><?= htmlspecialchars($res['usu_Nombre']) ?></h6>
              <p class="mb-1 small">Cliente</p>
              <div class="mb-3 text-warning">
                <?php for ($i = 0; $i < 5; $i++): ?>
                  <i class="<?= $i < $res['res_Calificacion'] ? 'fas fa-star' : 'far fa-star' ?>"></i>
                <?php endfor; ?>
              </div>
              <blockquote class="blockquote small">
                <p><i class="fas fa-quote-left me-2"></i><?= htmlspecialchars($res['res_Comentario']) ?></p>
              </blockquote>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php include '../partials/footer_clientes.php'; ?>
</body>
</html>