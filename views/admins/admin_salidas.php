<?php
require_once '../../config/database.php';
$conexion = database::getConexion();

$salidas = $conexion->query("
    SELECT s.*, p.pro_Nombre
    FROM salida_inventario s
    JOIN producto p ON s.sal_ID_producto = p.pro_ID_producto
    ORDER BY sal_fecha DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Informe de Salidas Rosita Forever</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light p-4">
     <?php include_once '../partials/navbar_admin.php'; ?>
<div class="container">
  <h2 class="mb-4 text-center">📤 Informe de Productos Vendidos</h2>

  <table class="table table-bordered table-hover">
    <thead class="table-dark">
      <tr>
        <th>Producto</th>
        <th>Cantidad</th>
        <th>Motivo</th>
        <th>Monto</th>
        <th>Fecha</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($salidas as $s): ?>
        <tr>
          <td><?= htmlspecialchars($s['pro_Nombre']) ?></td>
          <td><?= $s['sal_cantidad'] ?></td>
          <td><?= $s['sal_motivo'] ?></td>
          <td>$<?= number_format($s['sal_monto'], 2) ?></td>
          <td><?= date("d/m/Y H:i", strtotime($s['sal_fecha'])) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
 <?php include_once '../partials/footer.php'; ?>
</body>
</html>
