<?php
require_once '../../models/Categoria.php';
$categoria = new Categoria();
$categorias = $categoria->obtenerCategoriasTodas();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Categorías Rosita Forever</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/rositaforever/public/css/admin-style.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/yourkitid.js" crossorigin="anonymous"></script>
    <style>
        /* Extra decorativo */
        body {
            background: linear-gradient(135deg, #0d0d0d, #1c1c1c);
            color: #fff;
        }
        h1 {
            font-weight: 700;
            color: #00d4ff;
            text-shadow: 0 0 10px rgba(0,212,255,.7);
        }
        .card {
            border: none;
            border-radius: 1rem;
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0 20px rgba(0,212,255,.3);
        }
        .table thead {
            background: #00d4ff;
            color: #000;
        }
        .badge {
            font-size: .85rem;
        }
        .btn i {
            margin-right: 4px;
        }
    </style>
</head>
<body class="p-4">
<?php include_once '../partials/navbar_admin.php'; ?>

<div class="container py-5">
    <h1 class="mb-5 text-center">🗂️ Panel de Categorías</h1>

    <!-- Agregar Categoría -->
    <div class="card shadow-lg mb-5 bg-dark text-white">
        <div class="card-header bg-success text-white fs-5 fw-bold">
            <i class="fas fa-plus-circle"></i> Agregar nueva categoría
        </div>
        <div class="card-body">
            <form action="/rositaforever/controllers/CategoriaController.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="agregar">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control rounded-3" placeholder="Ej. Vinos" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Disponibilidad</label>
                        <select name="disponibilidad" class="form-select rounded-3" required>
                            <option value="Disponible">Disponible</option>
                            <option value="No Disponible">No Disponible</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control rounded-3" placeholder="Describe la categoría..." required></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Imagen</label>
                        <input type="file" name="imagen" class="form-control rounded-3" required>
                    </div>
                </div>
                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-success px-4">
                        <i class="fas fa-save"></i> Guardar categoría
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Categorías -->
    <div class="card shadow-lg bg-dark text-white">
        <div class="card-header bg-primary text-white fs-5 fw-bold">
            <i class="fas fa-list"></i> Categorías registradas
        </div>
        <div class="card-body">
            <table class="table table-dark table-hover table-striped align-middle text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Disponibilidad</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categorias as $cat): ?>
                        <tr>
                            <td><?= $cat['cat_ID_categoria'] ?></td>
                            <td class="fw-semibold"><?= htmlspecialchars($cat['cat_Nombre']) ?></td>
                            <td>
                                <span class="badge bg-<?= $cat['cat_Disponibilidad'] === 'Disponible' ? 'success' : 'secondary' ?>">
                                    <?= $cat['cat_Disponibilidad'] ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-<?= $cat['cat_estado'] === 'Activo' ? 'info' : 'danger' ?>">
                                    <?= $cat['cat_estado'] ?>
                                </span>
                            </td>
                            <td>
                                <!-- Activar / Desactivar -->
                                <form action="/rositaforever/controllers/CategoriaController.php" method="POST" class="d-inline">
                                    <input type="hidden" name="action" value="estado">
                                    <input type="hidden" name="id" value="<?= $cat['cat_ID_categoria'] ?>">
                                    <input type="hidden" name="estado" value="<?= $cat['cat_estado'] === 'Activo' ? 'Inactivo' : 'Activo' ?>">
                                    <button type="submit" class="btn btn-warning btn-sm mb-1" data-bs-toggle="tooltip" title="<?= $cat['cat_estado'] === 'Activo' ? 'Desactivar' : 'Activar' ?>">
                                        <i class="fas <?= $cat['cat_estado'] === 'Activo' ? 'fa-ban' : 'fa-check' ?>"></i>
                                    </button>
                                </form>
                                <!-- Eliminar -->
                                <form action="/rositaforever/controllers/CategoriaController.php" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta categoría?');">
                                    <input type="hidden" name="action" value="eliminar">
                                    <input type="hidden" name="id" value="<?= $cat['cat_ID_categoria'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="Eliminar">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($categorias)): ?>
                        <tr>
                            <td colspan="5" class="text-muted">No hay categorías registradas.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include_once '../partials/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Inicializar tooltips
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    [...tooltipTriggerList].map(el => new bootstrap.Tooltip(el));
</script>
</body>
</html>
