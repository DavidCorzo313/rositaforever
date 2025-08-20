<?php
require_once '../../config/database.php';
session_start();
$conexion = database::getConexion();

if (!isset($_SESSION['usuario']['usu_ID_usuario'])) {
    die("❌ No se encontró sesión válida.");
}

$adminID = $_SESSION['usuario']['usu_ID_usuario'];
$busqueda = trim($_GET['buscar'] ?? '');
$pagina = max(1, intval($_GET['pagina'] ?? 1));
$usuariosPorPagina = 10;
$offset = ($pagina - 1) * $usuariosPorPagina;

$parametros = [];
$condicion = '';

if ($busqueda !== '') {
    $condicion = "WHERE usu_Nombre LIKE :buscar1 
               OR usu_Apellido LIKE :buscar2 
               OR usu_Email LIKE :buscar3";
    $parametros = [
        ':buscar1' => "%$busqueda%",
        ':buscar2' => "%$busqueda%",
        ':buscar3' => "%$busqueda%"
    ];
    $stmtTotal = $conexion->prepare("SELECT COUNT(*) FROM usuarios $condicion");
    $stmtTotal->execute($parametros);
    $totalUsuarios = $stmtTotal->fetchColumn();

    $stmt = $conexion->prepare("SELECT * FROM usuarios $condicion LIMIT $usuariosPorPagina OFFSET $offset");
    $stmt->execute($parametros);
} else {
    $totalUsuarios = $conexion->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
    $stmt = $conexion->prepare("SELECT * FROM usuarios LIMIT $usuariosPorPagina OFFSET $offset");
    $stmt->execute();
}

$totalPaginas = ceil($totalUsuarios / $usuariosPorPagina);
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestión de Usuarios - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
      min-height: 100vh;
      font-family: 'Segoe UI', sans-serif;
    }
    h2 {
      font-weight: 600;
      color: #00e6e6;
    }
    .card {
      border-radius: 1rem;
      overflow: hidden;
    }
    .card-header {
      background: linear-gradient(135deg, #007bff, #00c6ff);
    }
    table {
      border-radius: 1rem;
      overflow: hidden;
    }
    thead {
      background: linear-gradient(135deg, #00c6ff, #007bff);
    }
    .table th {
      color: #fff !important;
      font-weight: 600;
    }
    .badge {
      font-size: 0.9rem;
      padding: 0.4em 0.7em;
      border-radius: 0.7rem;
    }
    .badge-admin {
      background: #ff4757;
    }
    .badge-cliente {
      background: #2ed573;
    }
    .btn-gradient {
      background: linear-gradient(135deg, #00c6ff, #007bff);
      border: none;
      color: #fff;
      transition: transform 0.2s ease;
    }
    .btn-gradient:hover {
      transform: scale(1.05);
    }
    .search-box {
      position: relative;
      width: 280px;
    }
    .search-box input {
      padding-left: 35px;
      border-radius: 2rem;
    }
    .search-box i {
      position: absolute;
      top: 50%;
      left: 12px;
      transform: translateY(-50%);
      color: #6c757d;
    }
    .pagination .page-link {
      border-radius: 50% !important;
      margin: 0 5px;
    }
  </style>
  <script>
    function confirmarEliminar() {
        return confirm('⚠️ ¿Estás seguro de eliminar este usuario? Esta acción no se puede deshacer.');
    }

    function solicitarContrasena(id, nuevoRol) {
        const contrasena = prompt('🔒 Ingresa tu contraseña para confirmar el cambio de rol:');
        if (!contrasena) return;

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '../../controllers/AdminUsuariosController.php';

        ['id', 'nuevo_rol', 'admin_pass', 'action'].forEach((name, i) => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = name;
            input.value = [id, nuevoRol, contrasena, 'cambiar_rol'][i];
            form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit();
    }
  </script>
</head>
<body class="text-white">
<?php include '../partials/navbar_admin.php'; ?>

<div class="container py-5">
  <h2 class="mb-4 text-center">👥 Gestión de Usuarios</h2>

  <?php if (isset($_GET['mensaje'])): ?>
    <div class="alert alert-success text-center shadow"><?= htmlspecialchars($_GET['mensaje']) ?></div>
  <?php endif; ?>

  <!-- Buscador -->
  <form class="d-flex mb-4 justify-content-end" method="GET">
    <div class="search-box">
      <i class="fa fa-search"></i>
      <input type="text" name="buscar" class="form-control" placeholder="Nombre, apellido o correo" value="<?= htmlspecialchars($busqueda) ?>">
    </div>
    <button type="submit" class="btn btn-gradient ms-2">Buscar</button>
    <?php if ($busqueda !== ''): ?>
      <a href="admin_usuarios.php" class="btn btn-outline-light ms-2">Limpiar</a>
    <?php endif; ?>
  </form>

  <!-- Tabla -->
  <div class="card shadow border-0">
    <div class="card-header text-white text-center fs-5 fw-semibold">📋 Usuarios registrados</div>
    <div class="card-body bg-dark">
      <div class="table-responsive">
        <table class="table table-bordered table-hover table-dark table-striped align-middle mb-0">
          <thead class="text-center">
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Correo</th>
              <th>Teléfono</th>
              <th>Rol</th>
              <th>Último ingreso</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody class="text-center">
            <?php foreach ($usuarios as $usu): ?>
              <tr>
                <td><?= $usu['usu_ID_usuario'] ?></td>
                <td><?= htmlspecialchars($usu['usu_Nombre'] . ' ' . $usu['usu_Apellido']) ?></td>
                <td><?= htmlspecialchars($usu['usu_Email']) ?></td>
                <td><?= htmlspecialchars($usu['usu_Telefono']) ?></td>
                <td>
                  <span class="badge <?= $usu['usu_ID_rol'] == 1 ? 'badge-admin' : 'badge-cliente' ?>">
                    <?= $usu['usu_ID_rol'] == 1 ? 'Administrador' : 'Cliente' ?>
                  </span>
                </td>
                <td><?= $usu['usu_ultimo_login'] ? date("d/m/Y H:i", strtotime($usu['usu_ultimo_login'])) : '<span class="text-muted">Nunca</span>' ?></td>
                <td class="d-flex gap-2 justify-content-center">
                  <?php
                    $esActual = $usu['usu_ID_usuario'] == $adminID;
                    $esAdminCrazy = trim($usu['usu_Nombre'] . ' ' . $usu['usu_Apellido']) === 'Admin Crazy';
                  ?>
                  <?php if (!$esActual && !$esAdminCrazy): ?>
                    <button onclick="solicitarContrasena(<?= $usu['usu_ID_usuario'] ?>, <?= $usu['usu_ID_rol'] == 1 ? 2 : 1 ?>)" class="btn btn-warning btn-sm shadow">
                      <?= $usu['usu_ID_rol'] == 1 ? 'Cambiar a Cliente' : 'Cambiar a Admin' ?>
                    </button>

                    <form method="POST" action="../../controllers/AdminUsuariosController.php" onsubmit="return confirmarEliminar();">
                      <input type="hidden" name="id" value="<?= $usu['usu_ID_usuario'] ?>">
                      <button name="action" value="eliminar" class="btn btn-danger btn-sm shadow">Eliminar</button>
                    </form>
                  <?php else: ?>
                    <span class="text-muted">No editable</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Paginación -->
  <nav class="mt-4">
    <ul class="pagination justify-content-center">
      <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
        <li class="page-item <?= $i === $pagina ? 'active' : '' ?>">
          <a class="page-link" href="?pagina=<?= $i ?><?= $busqueda !== '' ? '&buscar=' . urlencode($busqueda) : '' ?>"><?= $i ?></a>
        </li>
      <?php endfor; ?>
    </ul>
  </nav>
</div>

<?php include '../partials/footer.php'; ?>
</body>
</html>
