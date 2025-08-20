<?php
require_once '../models/Resena.php';
session_start();

// Validación de datos
if (
    !isset($_POST['producto_id'], $_POST['categoria_id'], $_POST['calificacion'], $_POST['comentario'], $_POST['usuario_id'])
) {
    die("❌ Datos incompletos.");
}

$productoId  = intval($_POST['producto_id']);
$categoriaId = intval($_POST['categoria_id']);
$usuarioId   = intval($_POST['usuario_id']);
$calificacion = intval($_POST['calificacion']);
$comentario   = trim($_POST['comentario']);

$resenaModel = new Resena();

// Validar que el usuario no haya calificado antes
if ($resenaModel->existeResena($productoId, $usuarioId)) {
    header("Location: ../views/usuarios/productos_categoria.php?categoria=$categoriaId&resena=existe");
    exit;
}

// Guardar la reseña
$resenaModel->guardar([
    'res_ID_producto'  => $productoId,
    'res_ID_usuario'   => $usuarioId,
    'res_Calificacion' => $calificacion,
    'res_Comentario'   => $comentario
]);

header("Location: ../views/usuarios/productos_categoria.php?categoria=$categoriaId");
exit;
