<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Cambiar Contraseña</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      background-image: url('../../views/img/logos/login.jpg');
      font-family: 'Poppins', sans-serif;
      color: white;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .card {
      background: rgba(0, 0, 0, 0.85);
      padding: 30px;
      border-radius: 20px;
      box-shadow: 0 0 25px rgba(0, 255, 255, 0.2);
      width: 100%;
      max-width: 420px;
    }

    .card-title {
      font-size: 1.6rem;
      font-weight: bold;
      color: #00eaff;
      text-align: center;
      margin-bottom: 20px;
    }

    .form-label {
      font-weight: 500;
      color: #ccefff;
    }

    .form-control {
      border-radius: 12px;
      border: 2px solid #00cfff;
      background-color: #f5fcff;
      color: #000;
    }

    .form-control:focus {
      border-color: #00eaff;
      box-shadow: 0 0 8px rgba(0, 255, 255, 0.5);
    }

    .btn-cambiar {
      background: linear-gradient(to right, #00bfff, #00ffff);
      color: white;
      font-weight: bold;
      padding: 12px 18px;
      border: none;
      border-radius: 25px;
      width: 100%;
      box-shadow: 0 0 10px rgba(0, 255, 255, 0.4);
      transition: all 0.3s ease;
    }

    .btn-cambiar:hover {
      background: linear-gradient(to right, #00aaff, #00eaff);
      transform: scale(1.03);
      box-shadow: 0 0 16px rgba(0, 255, 255, 0.6);
    }

    .back-link {
      display: block;
      text-align: center;
      margin-top: 15px;
      color: #00eaff;
      text-decoration: none;
      font-weight: 500;
    }

    .back-link:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <form action="../../controllers/UsuarioController.php?accion=cambiarContrasena" method="POST" class="card">
    <h2 class="card-title">Cambiar Contraseña</h2>

    <div class="mb-3">
      <label for="nueva_contrasena" class="form-label">Nueva contraseña</label>
      <input type="password" class="form-control" name="nueva_contrasena" id="nueva_contrasena" required placeholder="Ingresa nueva contraseña">
    </div>

    <div class="mb-3">
      <label for="confirmar_contrasena" class="form-label">Confirmar contraseña</label>
      <input type="password" class="form-control" name="confirmar_contrasena" id="confirmar_contrasena" required placeholder="Confirma la contraseña">
    </div>

    <button type="submit" class="btn-cambiar">
      <i class="bi bi-key-fill"></i> Cambiar contraseña
    </button>

    <a href="login.php" class="back-link">
      <i class="bi bi-box-arrow-left"></i> Volver al inicio de sesión
    </a>
  </form>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
