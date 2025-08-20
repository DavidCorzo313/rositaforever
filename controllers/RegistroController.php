<?php
require_once '../config/database.php';

// ✅ Obtener la conexión correctamente
$conexion = database::getConexion();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $nit = $_POST['nit'];
    $direccion = $_POST['direccion'];
    $localidad = $_POST['localidad'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Verifica que las contraseñas coincidan
    if ($password !== $confirmPassword) {
        echo "<script>alert('❌ Las contraseñas no coinciden.'); window.history.back();</script>";
        exit;
    }

    // Hashear la contraseña
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $rolCliente = 2;

    try {
        $stmt = $conexion->prepare("INSERT INTO usuarios (
            usu_ID_rol, usu_Nombre, usu_Apellido, usu_Email,
            usu_Contraseña, usu_Telefono, usu_NIT, usu_Direccion, usu_Localidad
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            $rolCliente, $nombre, $apellido, $correo,
            $passwordHash, $telefono, $nit, $direccion, $localidad
        ]);

        echo "<script>alert('✅ Registro exitoso. Ahora puedes iniciar sesión.'); window.location.href = '../views/usuarios/login.php';</script>";
    } catch (PDOException $e) {
        echo "❌ Error al registrar: " . $e->getMessage();
    }
}
