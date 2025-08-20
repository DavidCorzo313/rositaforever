<?php
require_once '../config/DatabaseMySQLi.php';  // ✅ Usamos MySQLi
require_once '../models/Usuario.php';
session_start();

// ✅ Obtener conexión válida con MySQLi
$conexion = DatabaseMySQLi::getConexion();

if (!$conexion || !($conexion instanceof mysqli)) {
    die("❌ Error de conexión a la base de datos.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['usuario'] ?? '');
    $password = $_POST['contraseña'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('El correo debe contener @'); window.location.href='../views/usuarios/login.php';</script>";
        exit;
    }

    $usuarioModel = new Usuario($conexion);  // Asegúrate que esta clase soporte MySQLi si la estás usando aquí
    $usuario = $usuarioModel->obtenerUsuarioPorEmail($email);

    if ($usuario && password_verify($password, $usuario['usu_Contraseña'])) {
        $_SESSION['usuario'] = [
            'usu_ID_usuario' => (int)$usuario['usu_ID_usuario'],
            'usu_Nombre'     => $usuario['usu_Nombre'],
            'usu_Email'      => $usuario['usu_Email'],
            'rol_Nombre'     => $usuario['rol_Nombre'],
            'usu_ID_rol'     => $usuario['usu_ID_rol']
        ];

        // ✅ Guardar último login
        $idUsuario = (int)$usuario['usu_ID_usuario'];
        $sql = "UPDATE usuarios SET usu_ultimo_login = NOW() WHERE usu_ID_usuario = ?";
        $stmt = mysqli_prepare($conexion, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $idUsuario);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }

        // Redirigir según el rol
        if ($usuario['usu_ID_rol'] == 1) {
            header("Location: ../views/admins/admin_categorias.php");
        } else {
            header("Location: ../views/usuarios/Pagina_Inicial.php");
        }
        exit;
    } else {
        echo "<script>alert('Correo o contraseña incorrectos o no registrados.'); window.location.href='../views/usuarios/login.php';</script>";
        exit;
    }
}
