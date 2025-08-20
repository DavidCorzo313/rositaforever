<?php
require_once '../../models/Categoria.php';
require_once '../../models/Producto.php';

$categoriaModel = new Categoria();
$productoModel = new Producto();

$categorias = $categoriaModel->obtenerCategoriasDisponibles();
$proveedores = $productoModel->obtenerProveedores();

// Paginación y búsqueda
$buscar = trim($_GET['buscar'] ?? '');
$paginaActual = max(1, intval($_GET['pagina'] ?? 1));
$porPagina = 10;
$totalProductos = $productoModel->contarProductos($buscar);
$totalPaginas = ceil($totalProductos / $porPagina);
$inicio = ($paginaActual - 1) * $porPagina;

$productosRegistrados = $productoModel->obtenerProductosPaginados($inicio, $porPagina, $buscar);

// Si viene un ID para editar
$editarProducto = null;
if (isset($_GET['editar'])) {
    $editarProducto = $productoModel->obtenerPorId($_GET['editar']);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Administrar Productos - Rosita Forever</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="/rositaforever/public/css/admin-style.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #1a1a1a, #2c2c2c);
      min-height: 100vh;
    }
    .card-header {
      background: linear-gradient(90deg, #0062ff, #00c6ff);
    }
    .table thead {
      background: linear-gradient(90deg, #00c6ff, #0062ff);
      color: white;
    }
    .table tbody tr:hover {
      background-color: rgba(0, 123, 255, 0.15) !important;
    }
    .form-control, .form-select {
      border-radius: 0.75rem;
      box-shadow: inset 0 1px 3px rgba(0,0,0,0.2);
    }
    .btn {
      border-radius: 0.75rem;
      font-weight: 500;
    }
    .btn-success {
      background: linear-gradient(90deg, #28a745, #20c997);
      border: none;
    }
    .btn-warning {
      background: linear-gradient(90deg, #ffc107, #ff9800);
      border: none;
      color: #212529;
    }
    .btn-danger {
      background: linear-gradient(90deg, #e53935, #d81b60);
      border: none;
    }
    .pagination .page-link {
      border-radius: 0.5rem;
      margin: 0 3px;
    }
    img.thumb {
      border-radius: 0.5rem;
      box-shadow: 0 2px 6px rgba(0,0,0,0.5);
    }
  </style>
</head>
<body class="text-white">

<?php include_once '../partials/navbar_admin.php'; ?>

<div class="container py-5">
  <div class="row g-4">
    <!-- Agregar / Editar Producto -->
    <div class="col-lg-4">
      <div class="card shadow-lg border-0 rounded-4 overflow-hidden h-100">
        <div class="card-header text-white text-center py-3">
          <h2 class="mb-0"><?= $editarProducto ? '✏️ Editar Producto' : '➕ Agregar Producto' ?></h2>
        </div>
        <div class="card-body bg-dark text-white">
          <form action="../../controllers/ProductoController.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="<?= $editarProducto ? 'editar' : 'agregar' ?>">
            <?php if ($editarProducto): ?>
              <input type="hidden" name="id" value="<?= $editarProducto['pro_ID_producto'] ?>">
              <input type="hidden" name="imagen_actual" value="<?= $editarProducto['pro_Imagen'] ?>">
            <?php endif; ?>

            <div class="mb-3">
              <label class="form-label fw-semibold">Nombre</label>
              <input type="text" name="nombre" class="form-control" required value="<?= $editarProducto['pro_Nombre'] ?? '' ?>">
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Categoría</label>
              <select name="categoria" class="form-select" required>
                <option disabled selected>Selecciona una categoría</option>
                <?php foreach ($categorias as $cat): ?>
                  <option value="<?= $cat['cat_ID_categoria'] ?>" <?= ($editarProducto && $editarProducto['pro_ID_categoria'] == $cat['cat_ID_categoria']) ? 'selected' : '' ?>>
                    <?= $cat['cat_Nombre'] ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Precio ($)</label>
              <input type="number" name="precio" step="0.01" class="form-control" required value="<?= $editarProducto['pro_Precio'] ?? '' ?>">
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Descuento (%)</label>
              <input type="number" name="descuento" min="0" max="100" class="form-control" value="<?= $editarProducto['pro_Descuento'] ?? 0 ?>">
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Cantidad en inventario</label>
              <input type="number" name="stock" min="1" class="form-control" required value="<?= $editarProducto['pro_Stock'] ?? 1 ?>">
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">¿Destacado?</label>
              <select name="destacado" class="form-select">
                <option value="0" <?= isset($editarProducto['pro_Destacado']) && $editarProducto['pro_Destacado'] == 0 ? 'selected' : '' ?>>No</option>
                <option value="1" <?= isset($editarProducto['pro_Destacado']) && $editarProducto['pro_Destacado'] == 1 ? 'selected' : '' ?>>Sí</option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Descripción</label>
              <textarea name="descripcion" class="form-control" required><?= $editarProducto['pro_Descripcion'] ?? '' ?></textarea>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Proveedor existente</label>
              <select name="proveedor" class="form-select">
                <option value="">-- Selecciona proveedor --</option>
                <?php foreach ($proveedores as $prov): ?>
                  <option value="<?= $prov['prov_ID_proveedor'] ?>" <?= ($editarProducto && $editarProducto['pro_ID_proveedor'] == $prov['prov_ID_proveedor']) ? 'selected' : '' ?>>
                    <?= $prov['prov_Nombre'] ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <input type="text" name="nuevo_proveedor" class="form-control mt-2" placeholder="Nuevo proveedor (opcional)">
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Imagen del producto</label>
              <input type="file" name="imagen" accept="image/*" class="form-control" <?= $editarProducto ? '' : 'required' ?>>
              <?php if ($editarProducto): ?>
                <div class="mt-3">
                  <small class="text-muted">Imagen actual:</small><br>
                  <img src="../img/productos/<?= $editarProducto['pro_Imagen'] ?>" width="120" class="thumb mt-2" alt="Imagen actual">
                </div>
              <?php endif; ?>
            </div>

            <div class="mt-4 text-end">
              <button type="submit" class="btn btn-success shadow px-4">💾 <?= $editarProducto ? 'Actualizar' : 'Guardar' ?> Producto</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Lista de Productos -->
    <div class="col-lg-8">
      <div class="card shadow-lg border-0 rounded-4 overflow-hidden h-100">
        <div class="card-header text-white text-center py-3">
          <h2 class="mb-0">📋 Productos Registrados</h2>
        </div>
        <div class="card-body bg-dark text-white">
          <form method="GET" class="d-flex justify-content-end mb-4">
            <input type="text" name="buscar" class="form-control w-50 me-2" placeholder="Buscar producto" value="<?= htmlspecialchars($buscar) ?>">
            <button class="btn btn-outline-primary">🔍 Buscar</button>
            <?php if ($buscar): ?>
              <a href="admin_productos.php" class="btn btn-outline-secondary ms-2">❌ Limpiar</a>
            <?php endif; ?>
          </form>

          <div class="table-responsive">
            <table class="table table-dark table-hover align-middle text-center shadow rounded-3 overflow-hidden">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nombre</th>
                  <th>Categoría</th>
                  <th>Precio</th>
                  <th>Descuento</th>
                  <th>Proveedor</th>
                  <th>Imagen</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($productosRegistrados as $p): ?>
                  <tr>
                    <td><?= $p['pro_ID_producto'] ?></td>
                    <td><?= htmlspecialchars($p['pro_Nombre']) ?></td>
                    <td><?= $p['cat_Nombre'] ?></td>
                    <td><span class="badge bg-success">$<?= $p['pro_Precio'] ?></span></td>
                    <td><span class="badge bg-info text-dark"><?= $p['pro_Descuento'] ?>%</span></td>
                    <td><?= $p['prov_Nombre'] ?? 'Sin proveedor' ?></td>
                    <td><img src="../img/productos/<?= $p['pro_Imagen'] ?>" width="60" class="thumb" alt=""></td>
                    <td>
                      <a href="?editar=<?= $p['pro_ID_producto'] ?>" class="btn btn-warning btn-sm mb-1">✏️ Editar</a>
                      <form method="POST" action="../../controllers/ProductoController.php" class="d-inline" onsubmit="return confirm('¿Eliminar este producto?');">
                        <input type="hidden" name="action" value="eliminar">
                        <input type="hidden" name="id" value="<?= $p['pro_ID_producto'] ?>">
                        <button class="btn btn-danger btn-sm">🗑️ Eliminar</button>
                      </form>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

          <!-- Paginación -->
          <?php if ($totalPaginas > 1): ?>
            <nav class="mt-4">
              <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                  <li class="page-item <?= $i == $paginaActual ? 'active' : '' ?>">
                    <a class="page-link" href="?pagina=<?= $i ?>&buscar=<?= urlencode($buscar) ?>"><?= $i ?></a>
                  </li>
                <?php endfor; ?>
              </ul>
            </nav>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include_once '../partials/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
