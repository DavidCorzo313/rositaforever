<?php
require_once '../../config/database.php';
$conexion = database::getConexion();

// Consulta de entradas
$entradas = $conexion->query("
    SELECT e.*, p.pro_Nombre
    FROM ingreso_inventario e
    JOIN producto p ON e.ing_ID_producto = p.pro_ID_producto
    ORDER BY e.ing_fecha DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Informe de Entradas Rosita Forever</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light p-4">
  <?php include_once '../partials/navbar_admin.php'; ?>

  <div class="container">
    <h2 class="mb-4 text-center">📥 Informe de Productos Ingresados</h2>

    <table class="table table-bordered table-hover">
      <thead class="table-dark">
        <tr>
          <th>Producto</th>
          <th>Cantidad</th>
          <th>Fecha</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($entradas as $e): ?>
          <tr>
            <td><?= htmlspecialchars($e['pro_Nombre']) ?></td>
            <td><?= $e['ing_cantidad'] ?></td>
            <td><?= date("d/m/Y", strtotime($e['ing_fecha'])) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <?php include_once '../partials/footer.php'; ?>
</body>
</html>
