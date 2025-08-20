<?php
require_once '../models/Producto.php';
session_start();

$producto = new Producto();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ✅ ELIMINAR producto
    if (isset($_POST['action']) && $_POST['action'] === 'eliminar') {
        $id = $_POST['id'];
        if ($producto->eliminar($id)) {
            header('Location: ../views/admins/admin_productos.php?mensaje=eliminado');
            exit;
        } else {
            echo "<script>alert('❌ Error al eliminar.'); window.history.back();</script>";
        }
        exit;
    }

    // ✅ EDITAR producto
    if (isset($_POST['action']) && $_POST['action'] === 'editar') {
        $id = intval($_POST['id']);
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $precio = floatval($_POST['precio']);
        $descuento = intval($_POST['descuento']);
        $precioFinal = $precio - ($precio * ($descuento / 100));
        $categoria = $_POST['categoria'];
        $stock = intval($_POST['stock']);
        $destacado = $_POST['destacado'];
        $imagenActual = $_POST['imagen_actual'];
        $imagen = $_FILES['imagen']['name'] ?: $imagenActual;

        if ($_FILES['imagen']['tmp_name']) {
            move_uploaded_file($_FILES['imagen']['tmp_name'], '../views/img/productos/' . $imagen);
        }

        $proveedor = !empty($_POST['nuevo_proveedor'])
            ? $producto->insertarProveedor($_POST['nuevo_proveedor'])
            : intval($_POST['proveedor']);

        $data = [
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'precio' => $precioFinal,
            'descuento' => $descuento,
            'categoria' => $categoria,
            'imagen' => $imagen,
            'destacado' => $destacado,
            'proveedor' => $proveedor
        ];

        if ($producto->actualizar($id, $data)) {
            header('Location: ../views/admins/admin_productos.php?mensaje=editado');
            exit;
        } else {
            echo "❌ Error al actualizar el producto.";
        }
        exit;
    }

    // ✅ AGREGAR producto
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = floatval($_POST['precio']);
    $descuento = intval($_POST['descuento']);
    $precioFinal = $precio - ($descuento * $precio / 100);
    $categoria = $_POST['categoria'];
    $stock = intval($_POST['stock']);
    $destacado = $_POST['destacado'];
    $imagen = $_FILES['imagen']['name'];
    $tmp = $_FILES['imagen']['tmp_name'];

    $rutaImagen = '../views/img/productos/' . $imagen;
    move_uploaded_file($tmp, $rutaImagen);

    $proveedor = !empty($_POST['nuevo_proveedor'])
        ? $producto->insertarProveedor($_POST['nuevo_proveedor'])
        : intval($_POST['proveedor']);

    $usuarioID = $_SESSION['usuario']['usu_ID'];

    $data = [
        'nombre' => $nombre,
        'descripcion' => $descripcion,
        'precio' => $precioFinal,
        'descuento' => $descuento,
        'categoria' => $categoria,
        'imagen' => $imagen,
        'destacado' => $destacado,
        'stock' => $stock,
        'usuario' => $usuarioID,
        'proveedor' => $proveedor
    ];

    if ($producto->insertarConInventario($data)) {
        header('Location: ../views/admins/admin_productos.php?mensaje=agregado');
        exit;
    } else {
        echo "❌ Error al guardar el producto.";
    }
}
