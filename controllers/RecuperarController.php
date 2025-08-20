<?php
require_once __DIR__ . '/../config/correo.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
    $email = $_POST['email'];
    $codigo = rand(100000, 999999);
    $_SESSION['codigo_verificacion'] = $codigo;
    $_SESSION['email_verificacion'] = $email;

    if (enviarCorreo($email, $codigo)) {
        header("Location: /rositaforever/views/usuarios/verificar_codigo.php");
        exit;
    } else {
        echo "Error al enviar el correo.";
    }
}

