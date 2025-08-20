<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Verificar Código</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
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
      font-size: 1.5rem;
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

    .btn-verificar {
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

    .btn-verificar:hover {
      background: linear-gradient(to right, #00aaff, #00eaff);
      transform: scale(1.03);
      box-shadow: 0 0 16px rgba(0, 255, 255, 0.6);
    }

    .btn-volver {
      display: block;
      margin-top: 15px;
      text-align: center;
      background: transparent;
      color: #00eaff;
      border: none;
      text-decoration: underline;
      font-weight: 500;
      font-size: 0.95rem;
      transition: 0.3s ease;
    }

    .btn-volver:hover {
      color: #00ffff;
      transform: scale(1.05);
    }
  </style>
</head>
<body>

  <form action="../../controllers/UsuarioController.php?accion=verificarCodigo" method="POST" class="card">
    <h2 class="card-title">Verificar Código</h2>

    <div class="mb-3">
      <label for="codigo" class="form-label">Código recibido por correo</label>
      <input type="text" class="form-control" name="codigo" id="codigo" required placeholder="Ej. 123456">
    </div>

    <button type="submit" class="btn-verificar">
      <i class="bi bi-check-circle-fill"></i> Verificar Código
    </button>

    <!-- 🔙 Botón para volver a recuperar contraseña -->
    <a href="recuperar_contraseña.php" class="btn-volver">
      <i class="bi bi-arrow-left-circle"></i> Volver a recuperar contraseña
    </a>
  </form>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
