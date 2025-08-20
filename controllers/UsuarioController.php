<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../config/correo.php';

$usuarioModel = new Usuario($conexion);

if (isset($_GET['accion'])) {
    switch ($_GET['accion']) {
        case 'enviarCodigoRecuperacion':
            $email = isset($_POST['email']) ? $_POST['email'] : null;

            if (!$email) {
                echo "❌ No se recibió ningún correo electrónico.";
                exit();
            }

            $codigo = rand(100000, 999999);
            $_SESSION['email_verificacion'] = $email;

            if ($usuarioModel->guardarCodigoRecuperacion($email, $codigo) && enviarCorreo($email, $codigo)) {
                header("Location: ../views/usuarios/verificar_codigo.php");
                exit();
            } else {
                echo "❌ Error al generar o enviar el código.";
            }
            break;

        case 'verificarCodigo':
            $email = isset($_SESSION['email_verificacion']) ? $_SESSION['email_verificacion'] : null;
            $codigo = isset($_POST['codigo']) ? $_POST['codigo'] : null;

            if (!$email || !$codigo) {
                echo "❌ No se recibieron los datos correctamente.";
                exit();
            }

            if ($usuarioModel->verificarCodigo($email, $codigo)) {
                header("Location: ../views/usuarios/nueva_contrasena.php");
                exit();
            } else {
                echo "⚠️ Código inválido o expirado.";
            }
            break;

        case 'cambiarContrasena':
    $email = isset($_SESSION['email_verificacion']) ? $_SESSION['email_verificacion'] : null;
    $nueva = isset($_POST['nueva_contrasena']) ? $_POST['nueva_contrasena'] : null;
    $confirmar = isset($_POST['confirmar_contrasena']) ? $_POST['confirmar_contrasena'] : null;

    if (!$email || !$nueva || !$confirmar) {
        header("Location: ../views/usuarios/nueva_contrasena.php?error=Faltan datos del formulario");
        exit();
    }

    if ($nueva === $confirmar) {
        if ($usuarioModel->cambiarContraseña($email, $nueva)) {
            session_destroy();
            header("Location: ../views/usuarios/login.php?success=Contraseña actualizada correctamente");
            exit();
        } else {
            header("Location: ../views/usuarios/nueva_contrasena.php?error=Error al actualizar la contraseña");
            exit();
        }
    } else {
        header("Location: ../views/usuarios/nueva_contrasena.php?error=Las contraseñas no coinciden");
        exit();
    }
    break;

    }
}
