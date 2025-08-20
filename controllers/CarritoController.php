<?php
session_start();
require_once '../models/Producto.php';
$productoModel = new Producto();

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// ✅ Agregar producto al carrito
if (isset($_POST['action']) && $_POST['action'] === 'agregar') {
    $id = intval($_POST['id']);
    $cantidad = intval($_POST['cantidad']);
    $producto = $productoModel->obtenerPorId($id);

    if ($producto) {
        if (isset($_SESSION['carrito'][$id])) {
            $_SESSION['carrito'][$id]['cantidad'] += $cantidad;
        } else {
            $_SESSION['carrito'][$id] = [
                'id' => $producto['pro_ID_producto'],
                'nombre' => $producto['pro_Nombre'],
                'precio' => $producto['pro_Precio'],
                'imagen' => $producto['pro_Imagen'],
                'cantidad' => $cantidad
            ];
        }
    }
    header('Location: ../views/usuarios/carrito.php');
    exit;
}

// ✅ Actualizar cantidades
if (isset($_POST['action']) && $_POST['action'] === 'actualizar') {
    foreach ($_POST['cantidades'] as $id => $cantidad) {
        if (isset($_SESSION['carrito'][$id])) {
            $_SESSION['carrito'][$id]['cantidad'] = max(1, intval($cantidad));
        }
    }
    header('Location: ../views/usuarios/carrito.php');
    exit;
}

// ✅ Eliminar producto del carrito
if (isset($_GET['action']) && $_GET['action'] === 'eliminar') {
    $id = intval($_GET['id']);
    unset($_SESSION['carrito'][$id]);
    header('Location: ../views/usuarios/carrito.php');
    exit;
}
