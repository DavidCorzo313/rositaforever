<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once '../../config/database.php';

$current_page = basename($_SERVER['PHP_SELF']);

$usuarioData = null;
if (isset($_SESSION['usuario']['usu_ID_usuario'])) {
    $usuarioID = $_SESSION['usuario']['usu_ID_usuario'];
    $conexion = database::getConexion();
    $stmt = $conexion->prepare("SELECT usu_Nombre, usu_Apellido, usu_Email, usu_Foto FROM usuarios WHERE usu_ID_usuario = :id");
    $stmt->execute([':id' => $usuarioID]);
    $usuarioData = $stmt->fetch(PDO::FETCH_ASSOC);
    $usuarioData['usu_Foto'] = (!empty($usuarioData['usu_Foto'])) 
        ? '../' . ltrim($usuarioData['usu_Foto'], '/') 
        : '../img/perfiles/user.png';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Rosita Forever</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    body { background-color: #0a0a0a; color: white; }
    .navbar {
        background-color: #0a0a0a;
        border-bottom: 2px solid #00f7ff;
        box-shadow: 0 0 10px #00f7ff;
    }
    .navbar-anim { animation: navbarFadeIn 1s ease-out; }
    @keyframes navbarFadeIn { from { opacity:0; transform:translateY(-20px);} to {opacity:1; transform:translateY(0);} }
    .navbar-brand {
        font-size: 40px;
        font-family: 'Brush Script MT', cursive;
        color: #00f7ff !important;
        text-shadow: 0 0 5px #00f7ff, 0 0 10px #00f7ff;
        transition: all 0.3s ease;
    }
    .navbar-brand:hover {
        text-shadow: 0 0 10px #00f7ff, 0 0 20px #00f7ff, 0 0 30px #00f7ff;
    }
    .ofertas-page .navbar-brand { font-size: 20px; }
    .nav-link { font-weight: bold; color: #e0faff !important; text-shadow: 0 0 5px #00f7ff; transition: all 0.3s ease; }
    .nav-link:hover, .nav-link.active { color: #00f7ff !important; text-shadow: 0 0 10px #00f7ff, 0 0 20px #00f7ff; }
    .search-box {
        background-color: transparent;
        border: 2px solid #00f7ff;
        border-radius: 30px;
        padding: 2px 10px;
        box-shadow: 0 0 5px #00f7ff;
    }
    .search-box input { background: transparent; border: none; outline: none; color: white; }
    .dropdown-menu { background-color: #0a0a0a; border: 1px solid #00f7ff; }
    .dropdown-item.mi-perfil { color: #6c757d !important; } /* gris oscuro */
    .dropdown-item.cerrar-sesion { color: #d9534f !important; } /* rojo suave */
    .dropdown-item.mi-perfil:hover,
    .dropdown-item.cerrar-sesion:hover { background-color: rgba(0,247,255,0.1); color: #00f7ff !important; }
    .profile-img { width: 55px; height: 55px; border-radius: 50%; object-fit: cover; box-shadow: 0 0 8px #00f7ff; }
    .cart-icon { width: 50px; height: 50px; object-fit: contain; filter: drop-shadow(0 0 5px #00f7ff); transition: all 0.3s ease; }
    .cart-icon:hover { transform: scale(1.1); filter: drop-shadow(0 0 15px #00f7ff) drop-shadow(0 0 20px #00f7ff); }
    .logo-cart-container { display: flex; align-items: center; gap: 15px; }
</style>
</head>
<body class="<?php echo ($current_page == 'ofertas.php') ? 'ofertas-page' : ''; ?>">

<nav class="navbar navbar-expand-lg navbar-anim">
    <div class="container-fluid">
        <div class="logo-cart-container">
            <a href="../usuarios/carrito.php">
                <img src="../img/logos/logoycarrito.png" alt="Carrito" class="cart-icon">
            </a>
            <a class="navbar-brand mb-0" href="../usuarios/Pagina_Inicial.php">Rosita Forever</a>
        </div>

        <button class="navbar-toggler text-light" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <i class="fas fa-bars"></i>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link <?php echo ($current_page == 'Pagina_Inicial.php') ? 'active' : ''; ?>" href="../usuarios/Pagina_Inicial.php"><i class="fas fa-home"></i> Inicio</a></li>
                <li class="nav-item"><a class="nav-link <?php echo ($current_page == 'categorias.php') ? 'active' : ''; ?>" href="../usuarios/categorias.php"><i class="fas fa-list"></i> Categoría</a></li>
                <li class="nav-item"><a class="nav-link <?php echo ($current_page == 'ofertas.php' || strpos($_SERVER['REQUEST_URI'], 'ofertas') !== false) ? 'active' : ''; ?>" href="../usuarios/ofertas.php"><i class="fas fa-tags"></i> Ofertas</a></li>
                <li class="nav-item"><a class="nav-link <?php echo ($current_page == 'nosotros.php') ? 'active' : ''; ?>" href="../usuarios/nosotros.php"><i class="fas fa-users"></i> Nosotros</a></li>
            </ul>

            <form class="d-flex me-3 search-box" method="GET" action="../usuarios/busqueda.php">
                <input class="form-control me-2 bg-transparent text-light border-0" type="text" name="q" placeholder="Buscar..." required>
                <button class="btn text-info" type="submit"><i class="fas fa-search"></i></button>
            </form>

            <?php if ($usuarioData): ?>
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-light text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                        <img src="<?= $usuarioData['usu_Foto'] ?>" alt="Perfil" class="profile-img me-2">
                        <span><?= htmlspecialchars($usuarioData['usu_Nombre']) ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item mi-perfil" href="../usuarios/perfil.php">Mi perfil</a></li>
                        <li><a class="dropdown-item cerrar-sesion" href="/rositaforever/logout.php">Cerrar sesión</a></li>
                    </ul>
                </div>
            <?php else: ?>
                <a href="/rositaforever/views/usuarios/login.php" class="btn btn-info ms-2">Iniciar sesión</a>
            <?php endif; ?>
        </div>
    </div>  
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
