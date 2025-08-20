<?php
require_once '../config/database.php';
session_start();

$conexion = database::getConexion();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $action = $_POST['action'] ?? '';
    $usuarioActualID = $_SESSION['usuario']['usu_ID_usuario'] ?? 0;

    // ❌ No eliminarse a sí mismo
    if ($action === 'eliminar' && $id > 0) {
        if ($id == $usuarioActualID) {
            echo "<script>alert('No puedes eliminar tu propio usuario.'); window.location.href = '../views/admins/admin_usuarios.php';</script>";
            exit;
        }

        // Evitar eliminar a Admin rositaforever
        $stmt = $conexion->prepare("SELECT usu_Nombre, usu_Apellido FROM usuarios WHERE usu_ID_usuario = :id");
        $stmt->execute([':id' => $id]);
        $target = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($target && trim($target['usu_Nombre'] . ' ' . $target['usu_Apellido']) === 'Admin rositaforever') {
            echo "<script>alert('Este administrador no se puede eliminar.'); window.location.href = '../views/admins/admin_usuarios.php';</script>";
            exit;
        }

        $stmt = $conexion->prepare("DELETE FROM usuarios WHERE usu_ID_usuario = :id");
        $stmt->execute([':id' => $id]);
        header("Location: ../views/admins/admin_usuarios.php?mensaje=Usuario eliminado exitosamente");
        exit;
    }

    // ✅ Cambiar rol con verificación
    if ($action === 'cambiar_rol' && $id > 0) {
        $nuevoRol = intval($_POST['nuevo_rol'] ?? 2);
        $adminPass = $_POST['admin_pass'] ?? '';

        // Obtener contraseña del admin actual
        $stmt = $conexion->prepare("SELECT usu_Contraseña FROM usuarios WHERE usu_ID_usuario = :id");
        $stmt->execute([':id' => $usuarioActualID]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$admin || !password_verify($adminPass, $admin['usu_Contraseña'])) {
            echo "<script>alert('❌ Contraseña incorrecta. No se pudo cambiar el rol.'); window.location.href = '../views/admins/admin_usuarios.php';</script>";
            exit;
        }

        // No cambiar rol al usuario 'Admin rositaforever'
        $stmt = $conexion->prepare("SELECT usu_Nombre, usu_Apellido FROM usuarios WHERE usu_ID_usuario = :id");
        $stmt->execute([':id' => $id]);
        $target = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($target && trim($target['usu_Nombre'] . ' ' . $target['usu_Apellido']) === 'Admin rositaforever') {
            echo "<script>alert('❌ Este administrador no puede ser modificado.'); window.location.href = '../views/admins/admin_usuarios.php';</script>";
            exit;
        }

        $stmt = $conexion->prepare("UPDATE usuarios SET usu_ID_rol = :rol WHERE usu_ID_usuario = :id");
        $stmt->execute([':rol' => $nuevoRol, ':id' => $id]);

        header("Location: ../views/admins/admin_usuarios.php?mensaje=Rol cambiado exitosamente");
        exit;
    }
}
