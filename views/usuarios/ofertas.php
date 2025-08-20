<?php
// ¡Muy importante! session_start al inicio, antes de cualquier HTML
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Ofertas Rosita Forever</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
<?php include_once '../partials/chat.php'; ?>
<script src="../../public/js/chat.js"></script>
<link rel="stylesheet" href="../../public/css/chat.css">

<style>
/* =============================
   Estilos Base
============================= */
body {
    font-family: "Poppins", sans-serif;
    background-color: #111; /* Fondo negro elegante */
    color: #fff;
    min-height: 100vh;
    overflow-x: hidden;
}

/* -----------------------------
   Título de Ofertas
------------------------------*/
.titulo-confiteria {
    font-family: 'Pacifico', cursive;
    font-size: 3rem;
    color: #00cfff;
    text-shadow: 0 0 10px rgba(0, 255, 255, 0.7), 0 0 25px rgba(0, 195, 255, 0.5);
    margin-bottom: 50px;
}

/* -----------------------------
   Contenedor de productos con partículas
------------------------------*/
.products-container {
    position: relative;
}
.products-container::before {
    content: '';
    position: absolute;
    width: 100%;
    height: 100%;
    background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="50" height="50"><circle cx="25" cy="25" r="2" fill="rgba(0,255,255,0.1)"/></svg>') repeat;
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

/* -----------------------------
   Tarjetas de productos
------------------------------*/
.product-card {
    position: relative;
    background: linear-gradient(145deg, #1c1c1c 0%, #2a2a2a 100%);
    border-radius: 20px;
    padding: 20px;
    margin-bottom: 30px;
    overflow: hidden;
    z-index: 1;
    box-shadow: 0 5px 20px rgba(0,255,255,0.2), 0 10px 40px rgba(0,195,255,0.15);
    transition: transform 0.4s ease, box-shadow 0.4s ease, background 0.4s ease;
}
.product-card:hover {
    transform: scale(1.05);
    box-shadow: 0 10px 30px rgba(0,255,255,0.4), 0 20px 60px rgba(0,195,255,0.3);
    background: linear-gradient(145deg, #222 0%, #333 50%, #1c1c1c 100%);
}

/* Imagen */
.product-card img {
    max-height: 200px;
    object-fit: contain;
    border-radius: 15px;
    margin-bottom: 15px;
    transition: transform 0.4s ease;
}
.product-card:hover img {
    transform: scale(1.1) rotate(-2deg);
}

/* Nombre */
.product-card h5 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #00cfff;
    text-shadow: 0 0 5px rgba(0,255,255,0.5);
    margin-bottom: 15px;
}

/* Precio */
.product-card p del {
    color: #aaa;
    font-size: 1rem;
    margin-right: 10px;
}
.product-card p strong {
    color: #28a745;
    font-size: 1.3rem;
    font-weight: 700;
}

/* Ribbon de descuento */
.ribbon {
    width: 120px;
    height: 30px;
    background: #ff4d4d;
    position: absolute;
    top: 15px;
    left: -35px;
    text-align: center;
    line-height: 30px;
    color: #fff;
    font-weight: 700;
    font-size: 0.9rem;
    transform: rotate(-45deg);
    box-shadow: 0 2px 10px rgba(255,0,0,0.3);
}
.ribbon::after, .ribbon::before {
    content: "";
    position: absolute;
    border: 15px solid transparent;
}
.ribbon::before {
    left: 0; top: 30px;
    border-top-color: #ff4d4d;
}
.ribbon::after {
    right: 0; top: 30px;
    border-top-color: #ff4d4d;
}

/* Overlay brillante */
.product-card::after {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: rgba(255,255,255,0.05);
    transform: rotate(45deg);
    pointer-events: none;
    transition: all 0.5s ease;
}
.product-card:hover::after {
    background: rgba(255,255,255,0.12);
}

/* Footer */
.footer-confiteria {
    background: #111;
    padding-top: 20px;
    padding-bottom: 20px;
}
.footer-confiteria .brand-name {
    font-weight: 700;
    font-size: 1.4rem;
    color: #00cfff;
    text-shadow: 0 0 5px rgba(0,255,255,0.5);
}
.footer-confiteria .footer-desc {
    font-size: 0.9rem;
    color: #aaa;
}
.footer-confiteria .social-icon {
    color: #00cfff;
    font-size: 1.5rem;
    margin-right: 10px;
    transition: transform 0.3s ease, color 0.3s ease;
}
.footer-confiteria .social-icon:hover {
    color: #007bff;
    transform: scale(1.2);
}
</style>
</head>
<body>

<?php include_once '../partials/navbar_clientes.php'; ?>

<div class="container mt-5 products-container">
  <div class="text-center">
    <h5 class="titulo-confiteria">¡Promociones!</h5>
  </div>
  <div class="row">
    <?php
    $conexion = new mysqli("localhost", "root", "", "rositaforever");

    $errorConexion = '';
    if ($conexion->connect_error) {
        $errorConexion = "❌ Conexión fallida: " . $conexion->connect_error;
    }

    $sql = "SELECT pro_Nombre, pro_Precio, pro_Descuento, pro_Imagen
            FROM producto
            WHERE pro_Descuento > 0 AND pro_estado = 'Activo'";
    $resultado = $conexion->query($sql);

    if($errorConexion) {
        echo "<div class='alert alert-danger text-center'>$errorConexion</div>";
    } elseif ($resultado->num_rows > 0) {
        while($producto = $resultado->fetch_assoc()) {
            $nombre = htmlspecialchars($producto["pro_Nombre"]);
            $precioOriginal = number_format($producto["pro_Precio"], 0, ',', '.');
            $descuento = $producto["pro_Descuento"];
            $precioDescuento = number_format($producto["pro_Precio"] * (1 - $descuento/100), 0, ',', '.');
            $imagen = htmlspecialchars($producto["pro_Imagen"] ?? 'default.jpg');

            echo "
            <div class='col-md-4 mb-4'>
              <div class='product-card text-center'>
                <div class='ribbon'>-{$descuento}% OFF</div>
                <h5>{$nombre}</h5>
                <img src='../../views/img/productos/{$imagen}' class='img-fluid' alt='{$nombre}'>
                <p><del>\${$precioOriginal}</del> <strong>\${$precioDescuento}</strong></p>
              </div>
            </div>";
        }
    } else {
        echo "<div class='alert alert-warning text-center pt-3'><p>No hay productos con descuento en este momento.</p></div>";
    }

    $conexion->close();
    ?>
  </div>
</div>

<footer class="footer-confiteria text-white">
  <div class="container py-4">
    <div class="row align-items-center text-center text-md-start">
      <div class="col-md-4 mb-3 mb-md-0">
        <h5 class="mb-0 brand-name">rositaforever</h5>
        <p class="footer-desc">Tu licorera de confianza</p>
      </div>
      <div class="col-md-4 mb-3 mb-md-0">
        <p class="mb-1">Síguenos:</p>
        <div>
          <a href="#" class="social-icon"><i class="bi bi-facebook"></i></a>
          <a href="#" class="social-icon"><i class="bi bi-instagram"></i></a>
          <a href="#" class="social-icon"><i class="bi bi-tiktok"></i></a>
        </div>
      </div>
      <div class="col-md-4">
        <p class="mb-1"><i class="bi bi-envelope-fill me-2"></i> rositaforever6@gmail.com</p>
        <p class="mb-0"><i class="bi bi-phone-fill me-2"></i> +57 3186220874</p>
      </div>
    </div>
    <hr class="my-3">
    <div class="row">
      <div class="col text-center small">
        © 2025 Rosita Forever, Todos los derechos reservados.
      </div>
    </div>
  </div>
</footer>

<script src="actualizado.js"></script>
</body>
</html>
<style>