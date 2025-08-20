<?php
session_start();
$paso = isset($_GET['paso']) ? $_GET['paso'] : 1;
$email = $_GET['email'] ?? $_POST['email'] ?? '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Contraseña</title>
    <style>
        body {
            background: #111;
            color: white;
            font-family: sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-box {
            background: #1e1e1e;
            padding: 40px;
            border-radius: 20px;
            text-align: center;
            width: 300px;
        }
        input, button {
            margin: 10px 0;
            padding: 10px;
            width: 100%;
            border-radius: 8px;
            border: none;
        }
        button {
            background: #00d0ff;
            color: black;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="form-box">
    <?php if ($paso == 1): ?>
        <h2>Recuperar Contraseña</h2>
        <form action="../../controllers/UsuarioController.php?accion=enviarCodigoRecuperacion" method="POST">
            <input type="email" name="email" placeholder="Ingresa tu correo" required>
            <button type="submit">Enviar código</button>
        </form>

    <?php elseif ($paso == 2): ?>
        <h2>Verificar Código</h2>
        <form action="../../controllers/UsuarioController.php?accion=verificarCodigo" method="POST">
            <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
            <input type="text" name="codigo" placeholder="Ingresa el código" required>
            <button type="submit">Verificar</button>
        </form>

    <?php elseif ($paso == 3): ?>
        <h2>Nueva Contraseña</h2>
        <form action="../../controllers/UsuarioController.php?accion=cambiarContrasenia" method="POST">
            <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
            <input type="password" name="nueva" placeholder="Nueva contraseña" required>
            <button type="submit">Cambiar contraseña</button>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
