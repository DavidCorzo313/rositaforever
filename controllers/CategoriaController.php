<?php
require_once '../models/Categoria.php';
$categoria = new Categoria();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'agregar') {
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $disponibilidad = $_POST['disponibilidad'];
        $imagen = $_FILES['imagen']['name'];
        $tmp = $_FILES['imagen']['tmp_name'];
        $destino = '../views/img/categorias/' . $imagen;

        if (move_uploaded_file($tmp, $destino)) {
            $data = [
                ':nombre' => $nombre,
                ':descripcion' => $descripcion,
                ':disponibilidad' => $disponibilidad,
                ':imagen' => $imagen
            ];
            if ($categoria->insertarCategoria($data)) {
                header("Location: ../views/admins/admin_categorias.php?mensaje=creado");
                exit;
            } else {
                echo "❌ Error al guardar en base de datos.";
            }
        } else {
            echo "❌ Error al subir la imagen.";
        }
    }

    if ($_POST['action'] === 'estado') {
        $id = $_POST['id'];
        $estado = $_POST['estado'];
        $categoria->cambiarEstadoCategoria($id, $estado);
        header("Location: ../views/admins/admin_categorias.php?mensaje=estado");
        exit;
    }

    if ($_POST['action'] === 'eliminar') {
        $id = $_POST['id'];
        $resultado = $categoria->eliminarCategoria($id);
        if ($resultado === true) {
            header("Location: ../views/admins/admin_categorias.php?mensaje=eliminado");
            exit;
        } elseif ($resultado === "NO_ELIMINAR") {
            echo "<script>alert('❌ No se puede eliminar la categoría porque tiene productos asociados.'); window.history.back();</script>";
        } else {
            echo "<script>alert('❌ Error al eliminar la categoría.'); window.history.back();</script>";
        }
    }
}
