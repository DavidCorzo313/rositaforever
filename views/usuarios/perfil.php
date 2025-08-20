<?php
session_start();
require_once '../../config/database.php';

// Verifica que el usuario esté autenticado
if (!isset($_SESSION['usuario']) || !isset($_SESSION['usuario']['usu_ID_usuario'])) {
    header('Location: ../../views/usuarios/login.php'); // redirige a login
    exit;
}

$conexion = database::getConexion();

$usuarioID = $_SESSION['usuario']['usu_ID_usuario'];
$stmt = $conexion->prepare("SELECT * FROM usuarios WHERE usu_ID_usuario = :id");
$stmt->execute([':id' => $usuarioID]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    die('❌ Usuario no encontrado');
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Perfil de Usuario</title>
  <link rel="stylesheet" href="../../public/css/perfil.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <?php include_once '../partials/chat.php'; ?>
  <script src="../../public/js/chat.js"></script>
  <link rel="stylesheet" href="../../public/css/chat.css">
</head>

<body>

<?php include '../partials/navbar_clientes.php'; ?>

<div class="container py-5">
  <h2 class="text-center mb-4" style="color: #00d9ff; text-shadow: 0 0 8px rgba(0, 255, 255, 0.5);">Mi Perfil</h2>


  <div class="text-center mb-4">
    <?php
    $rutaFoto = '../../public/img/perfiles/user.png'; // Por defecto
    if (!empty($usuario['usu_Foto'])) {
        $rutaFoto = '../../' . ltrim($usuario['usu_Foto'], '/'); // Corrige ruta relativa
    }
    ?>
    <img src="<?= $rutaFoto ?>" alt="Foto de perfil" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
  </div>

  <form action="../../controllers/PerfilController.php" method="POST" enctype="multipart/form-data">
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Nombre</label>
        <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($usuario['usu_Nombre']) ?>" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Apellido</label>
        <input type="text" name="apellido" class="form-control" value="<?= htmlspecialchars($usuario['usu_Apellido']) ?>" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Correo electrónico</label>
        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($usuario['usu_Email']) ?>" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Teléfono</label>
        <input type="text" name="telefono" class="form-control" value="<?= htmlspecialchars($usuario['usu_Telefono']) ?>" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">NIT</label>
        <input type="text" name="nit" class="form-control" value="<?= htmlspecialchars($usuario['usu_NIT']) ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">Dirección</label>
        <input type="text" name="direccion" class="form-control" value="<?= htmlspecialchars($usuario['usu_Direccion']) ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">Localidad</label>
        <input type="text" name="localidad" class="form-control" value="<?= htmlspecialchars($usuario['usu_Localidad']) ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">Cambiar foto de perfil</label>
        <input type="file" name="foto" class="form-control" accept="image/*">
      </div>
    </div>
    <div class="mt-4 text-end">
      <button type="submit" class="btn btn-success">Guardar cambios</button>
    </div>
  </form>
</div>

<?php include '../partials/footer_clientes.php'; ?>

</body>
</html>