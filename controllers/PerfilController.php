<?php
session_start();
require_once '../config/database.php';

$conexion = database::getConexion(); // ✅ Aquí obtienes la conexión correctamente

if (!isset($_SESSION['usuario']) || !isset($_SESSION['usuario']['usu_ID_usuario'])) {
    header('Location: ../index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuarioID = $_SESSION['usuario']['usu_ID_usuario'];

    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $email = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);
    $nit = trim($_POST['nit']);
    $direccion = trim($_POST['direccion']);
    $localidad = trim($_POST['localidad']);

    // Manejar foto si se subió
    $fotoPath = null;
    if (!empty($_FILES['foto']['name'])) {
        $permitidos = ['image/jpeg', 'image/png', 'image/webp'];
        if (!in_array($_FILES['foto']['type'], $permitidos)) {
            die('Formato de imagen no permitido. Usa JPG, PNG o WEBP.');
        }

        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $fotoNombre = 'perfil_' . $usuarioID . '_' . time() . '.' . $ext;
        $rutaDestino = '../views/img/perfiles/' . $fotoNombre;
        if (!move_uploaded_file($_FILES['foto']['tmp_name'], $rutaDestino)) {
            die('Error al subir la foto.');
        }
        $fotoPath = 'img/perfiles/' . $fotoNombre;
    }

    $sql = "UPDATE usuarios SET
        usu_Nombre = :nombre,
        usu_Apellido = :apellido,
        usu_Email = :email,
        usu_Telefono = :telefono,
        usu_NIT = :nit,
        usu_Direccion = :direccion,
        usu_Localidad = :localidad";

    if ($fotoPath) {
        $sql .= ", usu_Foto = :foto";
    }

    $sql .= " WHERE usu_ID_usuario = :id";

    $stmt = $conexion->prepare($sql);

    $params = [
        ':nombre' => $nombre,
        ':apellido' => $apellido,
        ':email' => $email,
        ':telefono' => $telefono,
        ':nit' => $nit,
        ':direccion' => $direccion,
        ':localidad' => $localidad,
        ':id' => $usuarioID
    ];

    if ($fotoPath) {
        $params[':foto'] = $fotoPath;
    }

    $stmt->execute($params);

    $_SESSION['usuario']['usu_Nombre'] = $nombre;

    header('Location: ../views/usuarios/perfil.php?actualizado=1');
    exit;
}