<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Recuperar Contraseña</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="../../public/css/recuperar_contraseña.css">
  
  <style>
    body {
      background-image: url('../../views/img/logos/login.jpg'); /* ✅ MISMA IMAGEN QUE verificar_codigo.php */
      background-size: cover;
      background-position: center;
      font-family: 'Poppins', sans-serif;
      color: white;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .card {
      background: rgba(0, 0, 0, 0.75);
      border: none;
      border-radius: 20px;
      box-shadow: 0 0 20px rgba(0, 255, 255, 0.3);
      padding: 30px;
      max-width: 400px;
      width: 100%;
    }

    .card-title {
      font-size: 1.5rem;
      font-weight: 600;
      color: #00eaff;
    }

    .form-label {
      color: #cceeff;
      font-weight: 500;
    }

    .form-control {
      border-radius: 10px;
      border: 2px solid #00cfff;
      background-color: #f9fcff;
      color: #000;
    }

    .form-control:focus {
      box-shadow: 0 0 10px rgba(0, 255, 255, 0.5);
      border-color: #00eaff;
    }

    .btn-personalizado {
      background: linear-gradient(to right, #00bfff, #00ffff);
      border: none;
      color: #fff;
      font-weight: bold;
      padding: 12px 20px;
      border-radius: 25px;
      width: 100%;
      box-shadow: 0 0 10px rgba(0, 255, 255, 0.4);
      transition: all 0.3s ease;
    }

    .btn-personalizado:hover {
      background: linear-gradient(to right, #00aaff, #00eaff);
      box-shadow: 0 0 16px rgba(0, 255, 255, 0.6);
      transform: scale(1.03);
    }

    .back-link {
      color: #00eaff;
      text-decoration: none;
      display: block;
      margin-top: 15px;
      text-align: center;
      font-weight: 500;
    }

    .back-link:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <form action="../../controllers/UsuarioController.php?accion=enviarCodigoRecuperacion" method="POST" class="card">
    <h2 class="card-title text-center mb-4">Recuperar Contraseña</h2>

    <div class="mb-3">
      <label for="email" class="form-label">Correo electrónico:</label>
      <input type="email" name="email" id="email" class="form-control" required placeholder="ejemplo@correo.com">
    </div>

    <button type="submit" class="btn-personalizado">
      <i class="bi bi-envelope-check-fill"></i> Enviar código
    </button>

    <a href="login.php" class="back-link">
      <i class="bi bi-arrow-left-circle"></i> Volver al inicio de sesión
    </a>
  </form>

  <!-- Bootstrap Bundle JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
