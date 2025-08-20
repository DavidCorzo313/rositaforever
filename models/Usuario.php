<?php
class Usuario {
    private $conn;

    public function __construct($conexion) {
        $this->conn = $conexion;
    }

    // ✅ Cambiar contraseña
    public function cambiarContraseña($email, $nuevaContrasena) {
        $hash = password_hash($nuevaContrasena, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("UPDATE usuarios SET usu_Contraseña = ? WHERE usu_Email = ?");
        $stmt->bind_param("ss", $hash, $email);
        return $stmt->execute();
    }

    // ✅ Guardar código con expiración
    public function guardarCodigoRecuperacion($email, $codigo) {
        $expiracion = date("Y-m-d H:i:s", strtotime("+30 minutes"));
        $this->conn->query("DELETE FROM codigos_recuperacion WHERE email = '$email'");

        $stmt = $this->conn->prepare("INSERT INTO codigos_recuperacion (email, codigo, expiracion) VALUES (?, ?, ?)");
        if (!$stmt) {
            echo "❌ Error al preparar la consulta: " . $this->conn->error;
            return false;
        }

        $stmt->bind_param("sss", $email, $codigo, $expiracion);
        if ($stmt->execute()) {
            echo "✅ Código guardado correctamente en la base de datos para $email";
            return true;
        } else {
            echo "❌ Error al ejecutar: " . $stmt->error;
            return false;
        }
    }

    // ✅ Verificar código válido
    public function verificarCodigo($email, $codigo) {
        $stmt = $this->conn->prepare("SELECT * FROM codigos_recuperacion WHERE email = ? AND codigo = ? AND expiracion > NOW() ORDER BY id DESC LIMIT 1");
        $stmt->bind_param("ss", $email, $codigo);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->num_rows > 0;
    }
    public function obtenerUsuarioPorEmail($email) {
    $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE usu_Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();
    return $resultado->fetch_assoc(); // devuelve array asociativo del usuario o null
}

}
?>

?>
