<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicia Sesión</title>
    
    <link rel="stylesheet" href="../../public/css/bootstrap.css">
    <link rel="stylesheet" href="../../public/css/login.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

    <!-- Fondo decorativo -->
    <div class="background-overlay"></div>

    <div class="login-container">
        <!-- Imagen lateral -->
        <div class="login-image"></div>

        <!-- Formulario -->
        <div class="login-form">
            <h1 class="text-center mb-4">Inicia Sesión</h1>

            <form action="../../controllers/LoginController.php" method="POST" onsubmit="return validarFormulario()">
                <div class="mb-3">
                    <label for="usuario" class="form-label">Correo Electrónico</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-regular fa-user"></i></span>
                        <input type="email" class="form-control" id="usuario" name="usuario" placeholder="Ingresa tu correo electrónico" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="contraseña" class="form-label">Contraseña</label>
                    <div class="input-group">                                          
                        <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                        <input type="password" class="form-control" id="contraseña" name="contraseña" placeholder="Ingresa tu contraseña" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>

                <p class="text-center mt-3">¿No estás registrado? <a href="registro.php">Crear una Cuenta</a></p>
                <p class="text-center">¿Olvidaste la contraseña? <a href="recuperar_contraseña.php">Recuperar Contraseña</a></p>
            </form>
        </div>
    </div>

    <script>
        function validarFormulario() {
            const email = document.getElementById("usuario").value;
            if (!email.includes("@")) {
                alert("El correo electrónico debe contener '@'.");
                return false;
            }
            return true;
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
