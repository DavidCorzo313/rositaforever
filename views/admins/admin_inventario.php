<?php
require_once '../../models/Inventario.php';
$inventario = new Inventario();

// Paginación
$limite = 10;
$pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
$inicio = ($pagina - 1) * $limite;

$busqueda = trim($_GET['buscar'] ?? '');
$totalDatos = $inventario->contarInventario($busqueda);
$datos = $inventario->obtenerInventarioPaginado($inicio, $limite, $busqueda);
$productos = $inventario->obtenerProductosActivos();
$totalPaginas = ceil($totalDatos / $limite);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario Rosita Forever</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/rositaforever/public/css/admin_styles.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/yourkitid.js" crossorigin="anonymous"></script>
    <style>
        body {
            background: linear-gradient(135deg, #0d0d0d, #1c1c1c);
            color: #fff;
        }
        h1 {
            font-weight: 700;
            color: #00ff99;
            text-shadow: 0 0 10px rgba(0,255,153,.7);
        }
        .card {
            border: none;
            border-radius: 1rem;
            overflow: hidden;
            transition: transform .2s ease, box-shadow .2s ease;
        }
        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 0 15px rgba(0,255,153,.2);
        }
        .table thead {
            background: #00ff99;
            color: #000;
        }
        .badge {
            font-size: .85rem;
        }
        .search-input {
            position: relative;
        }
        .search-input input {
            padding-left: 2.2rem;
        }
        .search-input i {
            position: absolute;
            top: 50%;
            left: .8rem;
            transform: translateY(-50%);
            color: #666;
        }
        .btn i { margin-right: 4px; }
    </style>
</head>
<body class="p-4">
<?php include_once '../partials/navbar_admin.php'; ?>

<div class="container py-5">
    <h1 class="mb-5 text-center">📦 Panel de Inventario</h1>

    <!-- Mensajes con SweetAlert (ya manejados abajo con JS) -->

    <!-- Formulario ingreso -->
    <div class="card mb-4 bg-dark text-white shadow-lg">
        <div class="card-header bg-success text-white fs-5 fw-bold">
            <i class="fas fa-arrow-down"></i> Ingreso de productos
        </div>
        <div class="card-body">
            <form method="POST" action="../../controllers/inventarioController.php" class="row g-3">
                <input type="hidden" name="tipo" value="ingreso">
                <div class="col-md-6">
                    <label class="form-label">Producto</label>
                    <select name="producto" class="form-select rounded-3" required>
                        <option value="">-- Selecciona un producto --</option>
                        <?php foreach ($productos as $prod): ?>
                            <option value="<?= $prod['pro_ID_producto'] ?>"><?= htmlspecialchars($prod['pro_Nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Cantidad</label>
                    <input type="number" name="cantidad" class="form-control rounded-3" required>
                </div>
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-success px-4">
                        <i class="fas fa-save"></i> Registrar ingreso
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Formulario salida -->
    <div class="card mb-4 bg-dark text-white shadow-lg">
        <div class="card-header bg-danger text-white fs-5 fw-bold">
            <i class="fas fa-arrow-up"></i> Salida de productos
        </div>
        <div class="card-body">
            <form method="POST" action="/rositaforever/controllers/InventarioController.php" class="row g-3">
                <input type="hidden" name="tipo" value="salida">
                <div class="col-md-6">
                    <label class="form-label">Producto</label>
                    <select name="producto" class="form-select rounded-3" required>
                        <option value="">-- Selecciona un producto --</option>
                        <?php foreach ($productos as $prod): ?>
                            <option value="<?= $prod['pro_ID_producto'] ?>"><?= htmlspecialchars($prod['pro_Nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Cantidad</label>
                    <input type="number" name="cantidad" class="form-control rounded-3" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Motivo</label>
                    <input type="text" name="motivo" class="form-control rounded-3" required>
                </div>
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-danger px-4">
                        <i class="fas fa-paper-plane"></i> Registrar salida
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla inventario -->
    <div class="card bg-dark text-white shadow-lg">
        <div class="card-header bg-primary text-white fs-5 fw-bold">
            <i class="fas fa-warehouse"></i> Inventario actual
        </div>
        <div class="card-body">
            <!-- Buscador -->
            <form class="mb-3 d-flex justify-content-end" method="GET">
                <div class="search-input w-25 me-2">
                    <i class="fas fa-search"></i>
                    <input type="text" name="buscar" class="form-control rounded-3" placeholder="Buscar producto..." value="<?= htmlspecialchars($busqueda) ?>">
                </div>
                <button class="btn btn-primary"><i class="fas fa-filter"></i> Buscar</button>
            </form>

            <!-- Tabla -->
            <table class="table table-dark table-hover table-striped align-middle text-center rounded-3 overflow-hidden">
                <thead>
                    <tr>
                        <th>ID Producto</th>
                        <th>Nombre</th>
                        <th>Stock</th>
                        <th>Disponible</th>
                        <th>Estado</th>
                        <th>Último ingreso</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($datos as $item): ?>
                        <tr>
                            <td><?= $item['pro_ID_producto'] ?></td>
                            <td class="fw-semibold"><?= htmlspecialchars($item['pro_Nombre']) ?></td>
                            <td>
                                <span class="badge bg-<?= $item['inv_stock'] > 10 ? 'success' : ($item['inv_stock'] > 0 ? 'warning' : 'danger') ?>">
                                    <?= $item['inv_stock'] ?>
                                </span>
                            </td>
                            <td><?= $item['inv_cantidad_disponible'] ?></td>
                            <td>
                                <span class="badge bg-<?= $item['inv_estado'] === 'Activo' ? 'info' : 'secondary' ?>">
                                    <?= $item['inv_estado'] ?>
                                </span>
                            </td>
                            <td><?= $item['inv_fecha_ultimo_ingreso'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($datos)): ?>
                        <tr>
                            <td colspan="6" class="text-muted">No se encontraron registros.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Paginación -->
            <?php if ($totalPaginas > 1): ?>
                <nav class="mt-3">
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                            <li class="page-item <?= $i == $pagina ? 'active' : '' ?>">
                                <a class="page-link" href="?pagina=<?= $i ?>&buscar=<?= urlencode($busqueda) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    <?php if (isset($_GET['mensaje'])): ?>
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '<?= $_GET['mensaje'] === 'ingreso_ok' ? 'Ingreso registrado correctamente.' : 'Salida registrada correctamente.' ?>',
            timer: 2500,
            showConfirmButton: false
        });
        history.replaceState(null, null, window.location.pathname);
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '<?= htmlspecialchars($_GET['error']) ?>',
            confirmButtonColor: '#d33'
        });
        history.replaceState(null, null, window.location.pathname);
    <?php endif; ?>
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
