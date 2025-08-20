<?php
session_start();
require_once '../config/db.php';
require_once '../models/Usuario.php';

// Asegúrate de tener la conexión
if (!isset($conn)) {
    $conn = new mysqli("localhost", "root", "", "xampp"); // usa tu base de datos real
    if ($conn->connect_error) {
        die('Error de conexión: ' . $conn->connect_error);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_SESSION['email_verificacion'];
    $nueva = $_POST['nueva_contrasena'];
    $confirmar = $_POST['confirmar_contrasena'];

    if ($nueva === $confirmar) {
        $usuarioModel = new Usuario($conn); // ✅ ahora sí está bien
        if ($usuarioModel->cambiarContraseña($email, $nueva)) {
            echo "Contraseña actualizada correctamente.";
            session_destroy(); // limpia la sesión
        } else {
            echo "Error al actualizar la contraseña.";
        }
    } else {
        echo "Las contraseñas no coinciden.";
    }
}
