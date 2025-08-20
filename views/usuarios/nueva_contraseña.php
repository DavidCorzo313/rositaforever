<?php
session_start();
if (!isset($_SESSION['recuperar_id'])) {
    header("Location: recuperar_contraseña.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Actualizar Contraseña</title>
    <link rel="stylesheet" href="../../public/css/bootstrap.css">
    <link rel="stylesheet" href="../../public/css/login.css">
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card p-4 shadow-lg" style="max-width: 400px;">
        <h3 class="text-center mb-3">Nueva Contraseña</h3>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>

        <form action="../../controllers/RecuperarController.php" method="POST">
            <input type="hidden" name="action" value="actualizar">

            <div class="mb-3">
                <label class="form-label">Nueva contraseña</label>
                <input type="password" name="nueva" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Confirmar contraseña</label>
                <input type="password" name="confirmar" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success w-100">Actualizar</button>
        </form>
    </div>
</body>
</html>
