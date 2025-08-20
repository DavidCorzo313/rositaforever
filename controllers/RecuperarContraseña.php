<?php
include '../../config/db.php'; // Asegúrate de incluir tu conexión

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['correo'];

    // Verifica si existe el correo
    $stmt = $conn->prepare("SELECT id, nombre FROM usuarios WHERE correo = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        $token = bin2hex(random_bytes(32));
        $id = $usuario['id'];

        // Guardar token en BD
        $stmt = $conn->prepare("UPDATE usuarios SET token_recuperacion = ? WHERE id = ?");
        $stmt->bind_param("si", $token, $id);
        $stmt->execute();

        // Enlace de recuperación
        $link = "http://localhost/rositaforever/views/usuarios/cambiar_contraseña.php?token=$token";

        // Enviar correo
        $asunto = "Recuperación de contraseña - Rosita Forever";
        $mensaje = "Hola {$usuario['nombre']},\n\nHaz clic en este enlace para recuperar tu contraseña:\n$link\n\nSi no solicitaste esto, ignora este correo.";
        $cabeceras = "From: rositaforever@localhost";

        if (mail($correo, $asunto, $mensaje, $cabeceras)) {
            echo "Correo enviado correctamente.";
        } else {
            echo "Error al enviar el correo.";
        }
    } else {
        header("Location: recuperar_contraseña.php?error=Correo no encontrado");
    }
}
?>
