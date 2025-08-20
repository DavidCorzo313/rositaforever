<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Regístrate | Rosita Forever 🍷</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap y estilos -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
  <link rel="stylesheet" href="../../public/css/registro.css">
</head>
<body>

  <!-- Fondo decorativo -->
  <div class="background-overlay"></div>

  <div class="login-container">
    <div class="login-image"></div>

    <div class="login-form">
      <h2 class="text-center mb-4 text-dark">Crea tu Cuenta</h2>

      <form action="../../controllers/RegistroController.php" method="POST">
        <div class="mb-3">
          <label class="form-label">Nick name</label>
          <input type="text" name="usuario" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Nombre</label>
          <input type="text" name="nombre" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Apellidos</label>
          <input type="text" name="apellido" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Correo electrónico</label>
          <input type="email" name="correo" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Teléfono</label>
          <input type="text" name="telefono" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">NIT o Cédula</label>
          <input type="text" name="nit" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Dirección</label>
          <input type="text" name="direccion" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Ciudad</label>
          <input type="text" name="localidad" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Contraseña</label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Confirmar Contraseña</label>
          <input type="password" name="confirmPassword" class="form-control" required>
        </div>
        <div class="mb-3">
          <button type="submit" class="btn btn-danger w-100">Registrarse</button>
        </div>

        <p class="text-center text-dark">
          ¿Ya tienes cuenta? <a href="login.php" class="text-primary">Iniciar sesión</a>
        </p>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
