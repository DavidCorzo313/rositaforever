<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rosita Forever Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="/rositaforever/public/css/admin_styles.css" rel="stylesheet">
  <style>
    /* Sidebar */
    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      height: 100%;
      width: 240px;
      background-color: #0c0c0c;
      border-right: 2px solid #00ffff;
      padding-top: 20px;
      z-index: 1000;
      transition: width 0.3s ease;
      overflow: hidden;
    }
    .sidebar.collapsed {
      width: 70px;
    }

    /* Imagen de perfil */
    .profile-img {
      display: flex;
      justify-content: center;
      margin-bottom: 15px;
      transition: all 0.3s ease;
    }
    .profile-img img {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 50%;
      border: 2px solid #00ffff;
      transition: all 0.3s ease;
    }
    .sidebar.collapsed .profile-img img {
      width: 40px;
      height: 40px;
    }

    /* Título */
    .sidebar .navbar-brand {
      color: #00ffff;
      font-weight: bold;
      text-align: center;
      display: block;
      padding: 10px;
      font-size: 1.2rem;
      white-space: nowrap;
    }
    .sidebar.collapsed .navbar-brand span {
      display: none;
    }

    /* Menú */
    .sidebar .nav-link {
      color: #00d4ff; /* Color más profesional y llamativo */
      font-weight: 500;
      padding: 10px 20px;
      transition: background 0.3s, color 0.3s, text-shadow 0.3s;
      display: flex;
      align-items: center;
      gap: 10px;
      white-space: nowrap;
    }
    .sidebar .nav-link:hover {
      background-color: #00ffff20;
      color: #00ffff;
      text-shadow: 0 0 5px #00ffff; /* Brillo sutil al pasar el mouse */
    }
    .sidebar.collapsed .nav-link span {
      display: none;
    }

    /* Texto usuario */
    .sidebar .navbar-text {
      color: #00ffff;
      padding: 15px 20px;
      font-weight: 600;
      display: block;
      border-top: 1px solid #00ffff50;
      margin-top: 15px;
      white-space: nowrap;
    }
    .sidebar.collapsed .navbar-text {
      text-align: center;
    }
    .sidebar.collapsed .navbar-text span {
      display: none;
    }

    /* Botón logout */
    .sidebar .btn-logout {
      margin: 15px 20px;
      border-color: #00e5ff;
      color: #00e5ff;
      width: calc(100% - 40px);
      transition: all 0.3s ease;
    }
    .sidebar .btn-logout:hover {
      background-color: #00e5ff;
      color: #0c0c0c;
    }
    .sidebar.collapsed .btn-logout {
      width: 40px;
      padding: 5px;
    }

    /* Contenido principal */
    .content {
      margin-left: 240px;
      padding: 20px;
      transition: margin-left 0.3s ease;
    }
    .sidebar.collapsed ~ .content {
      margin-left: 70px;
    }

    /* Botón colapsar */
    .toggle-btn {
      position: absolute;
      top: 10px;
      right: -15px;
      background-color: #00e5ff;
      border-radius: 50%;
      border: none;
      width: 30px;
      height: 30px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      z-index: 1100;
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
  <!-- Botón para colapsar -->
  <button class="toggle-btn" id="toggle-btn">
    <i class="fa-solid fa-angle-left"></i>
  </button>

  <!-- Imagen de perfil -->
  <div class="text-center mb-4">
    <img src="../img/logos/logo.jpg" alt="Foto de Administrador" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #00ffff;">
    <h4 class="mt-2 text-info">Administrador</h4>
  </div>

  <!-- Logo + título -->
  <a class="navbar-brand" href="/rositaforever/views/admins/admin_inventario.php">
    <span>Rosita Forever Admin</span>
  </a>

  <!-- Menú -->
  <nav class="nav flex-column mt-3">
    <a class="nav-link" href="/rositaforever/views/admins/admin_inventario.php"><i class="fa-solid fa-box"></i> <span>Inventario</span></a>
    <a class="nav-link" href="/rositaforever/views/admins/admin_productos.php"><i class="fa-solid fa-cake-candles"></i> <span>Productos</span></a>
    <a class="nav-link" href="/rositaforever/views/admins/admin_categorias.php"><i class="fa-solid fa-folder"></i> <span>Categorías</span></a>
    <a class="nav-link" href="/rositaforever/views/admins/admin_usuarios.php"><i class="fa-solid fa-users"></i> <span>Usuarios</span></a>
    <a class="nav-link" href="/rositaforever/views/admins/admin_movimientos.php"><i class="fa-solid fa-arrow-right-arrow-left"></i> <span>Movimientos</span></a>
  </nav>

  <!-- Perfil y cierre -->
  <span class="navbar-text"><i class="fa-solid fa-user-shield"></i> <span>Administrador</span></span>
  <a href="/rositaforever/logout.php" class="btn btn-outline-info btn-sm btn-logout"><i class="fa-solid fa-right-from-bracket"></i></a>
</div>

<!-- Contenido principal -->
<div class="content">
  <!-- Aquí va el contenido de tus páginas de admin -->
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const toggleBtn = document.getElementById("toggle-btn");
  const sidebar = document.getElementById("sidebar");
  const icon = toggleBtn.querySelector("i");

  toggleBtn.addEventListener("click", () => {
    sidebar.classList.toggle("collapsed");
    icon.classList.toggle("fa-angle-left");
    icon.classList.toggle("fa-angle-right");
  });
</script>
</body>
</html>