<?php
require_once '../../models/Inventario.php';
require_once '../../controllers/movimientosController.php';

// ⚠️ Nada de $conn aquí: el controller se encarga de la BD.
$controller = new movimientosController();
$movimientos = $controller->obtenerMovimientos();

/* =======================
   Filtros (GET, no intrusivos)
   ======================= */
$productoFiltro = trim($_GET['producto'] ?? '');
$tipoFiltro     = $_GET['tipo'] ?? ''; // '', 'ingreso', 'salida'
$desdeFiltro    = $_GET['desde'] ?? '';
$hastaFiltro    = $_GET['hasta'] ?? '';

function normalizarFecha($f) {
  if (!$f || $f === '-' ) return null;
  $t = strtotime($f);
  return $t ? date('Y-m-d', $t) : null;
}

function pasaFiltros($row, $productoFiltro, $tipoFiltro, $desdeFiltro, $hastaFiltro) {
  if ($productoFiltro !== '' && stripos($row['producto'] ?? '', $productoFiltro) === false) {
    return false;
  }

  $esIngreso = !empty($row['cantidad_ingresada']) && floatval($row['cantidad_ingresada']) > 0;
  $esSalida  = !empty($row['cantidad_salida'])    && floatval($row['cantidad_salida']) > 0;

  if ($tipoFiltro === 'ingreso' && !$esIngreso) return false;
  if ($tipoFiltro === 'salida'  && !$esSalida)  return false;

  $fi = normalizarFecha($row['fecha_ingreso'] ?? null);
  $fs = normalizarFecha($row['fecha_salida']  ?? null);

  $fechaClave = null;
  if ($tipoFiltro === 'ingreso') {
    $fechaClave = $fi;
  } elseif ($tipoFiltro === 'salida') {
    $fechaClave = $fs;
  } else {
    $fechaClave = $fs ?: $fi;
  }

  if (($desdeFiltro || $hastaFiltro) && !$fechaClave) return false;

  if ($desdeFiltro && $fechaClave && $fechaClave < $desdeFiltro) return false;
  if ($hastaFiltro && $fechaClave && $fechaClave > $hastaFiltro) return false;

  return true;
}

$movimientosFiltrados = array_values(array_filter($movimientos, function($row) use ($productoFiltro, $tipoFiltro, $desdeFiltro, $hastaFiltro) {
  return pasaFiltros($row, $productoFiltro, $tipoFiltro, $desdeFiltro, $hastaFiltro);
}));

$totalIngresado = 0;
$totalSalida    = 0;
foreach ($movimientosFiltrados as $m) {
  $totalIngresado += floatval($m['cantidad_ingresada'] ?? 0);
  $totalSalida    += floatval($m['cantidad_salida'] ?? 0);
}
$balance = $totalIngresado - $totalSalida;
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>📦 Movimientos de Inventario</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="/rositaforever/public/css/admin-style.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #171717, #232323);
      min-height: 100vh;
      color: #fff;
    }
    .card {
      background: #1f1f1f;
      border: none;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(0,0,0,0.35);
    }
    .card-header {
      background: linear-gradient(90deg, #dc3545, #ff4d6d);
      font-size: 1.2rem;
      letter-spacing: .5px;
      text-transform: uppercase;
    }
    .metric-card {
      background: linear-gradient(180deg, rgba(255,255,255,0.06), rgba(255,255,255,0.02));
      border: 1px solid rgba(255,255,255,0.08);
      border-radius: 16px;
      padding: 16px;
    }
    .metric-label {
      font-size: .85rem;
      color: #ffffff; /* ✅ blanco */
      letter-spacing: .5px;
    }
    .metric-value {
      font-size: 1.35rem;
      font-weight: 700;
      color: #ffffff; /* ✅ blanco */
    }
    .badge-soft {
      background: rgba(255,255,255,.08);
      border: 1px solid rgba(255,255,255,.1);
      padding: .4rem .6rem;
      border-radius: .6rem;
      color: #ffffff; /* ✅ blanco */
    }
    .btn-danger-custom {
      background: linear-gradient(90deg, #dc3545, #ff4d6d);
      border: none;
      color: #fff;
      font-weight: 600;
      padding: 10px 18px;
      border-radius: 12px;
      transition: all .25s ease;
    }
    .btn-danger-custom:hover {
      transform: translateY(-1px);
      box-shadow: 0 10px 24px rgba(220, 53, 69, .35);
    }
    /* ✅ Botones blancos */
    .btn-white-custom {
      background-color: #ffffff !important;
      color: #000000 !important;
      border: 1px solid #ffffff !important;
      transition: all 0.3s ease-in-out;
    }
    .btn-white-custom:hover {
      background-color: #f0f0f0 !important;
      color: #000000 !important;
      border: 1px solid #f0f0f0 !important;
    }
    .table thead th {
      background: #f8f9fa;
      color: #212529;
      border-bottom: none !important;
    }
    .table tbody tr:hover {
      background-color: rgba(255,255,255,0.05) !important;
      transition: background-color .2s ease;
    }
    .form-control, .form-select {
      border-radius: 12px;
      background: #151515;
      border: 1px solid rgba(255,255,255,.12);
      color: #e7e7e7;
    }
    .form-control::placeholder { color: #9aa0a6; }
    .filters-card {
      background: linear-gradient(180deg, rgba(255,255,255,0.03), rgba(255,255,255,0.01));
      border: 1px solid rgba(255,255,255,0.08);
      border-radius: 16px;
      padding: 16px;
    }
  </style>
</head>
<body class="text-white">

<?php include_once '../partials/navbar_admin.php'; ?>

<div class="container py-5">
  <div class="card shadow-lg mb-4">
    <div class="card-header text-center text-white fw-bold">
      📊 Movimientos de Inventario
    </div>

    <div class="card-body">
      <!-- Resumen -->
      <div class="row g-3 mb-4">
        <div class="col-md-3">
          <div class="metric-card h-100">
            <div class="metric-label">Registros filtrados</div>
            <div class="metric-value"><?= number_format(count($movimientosFiltrados)) ?></div>
            <span class="badge-soft">Total coincidencias</span>
          </div>
        </div>
        <div class="col-md-3">
          <div class="metric-card h-100">
            <div class="metric-label">Cantidad ingresada</div>
            <div class="metric-value">+<?= number_format($totalIngresado) ?></div>
            <span class="badge-soft">Entradas</span>
          </div>
        </div>
        <div class="col-md-3">
          <div class="metric-card h-100">
            <div class="metric-label">Cantidad salida</div>
            <div class="metric-value">-<?= number_format($totalSalida) ?></div>
            <span class="badge-soft">Salidas</span>
          </div>
        </div>
        <div class="col-md-3">
          <div class="metric-card h-100">
            <div class="metric-label">Balance</div>
            <div class="metric-value"><?= ($balance >= 0 ? '+' : '') . number_format($balance) ?></div>
            <span class="badge-soft"><?= $balance >= 0 ? 'Positivo' : 'Negativo' ?></span>
          </div>
        </div>
      </div>

      <!-- Filtros -->
      <form method="GET" class="filters-card mb-4">
        <div class="row g-3 align-items-end">
          <div class="col-md-3">
            <label class="form-label">Producto</label>
            <input type="text" name="producto" class="form-control" placeholder="Nombre de producto"
                   value="<?= htmlspecialchars($productoFiltro) ?>">
          </div>
          <div class="col-md-2">
            <label class="form-label">Tipo</label>
            <select name="tipo" class="form-select">
              <option value="" <?= $tipoFiltro==='' ? 'selected' : '' ?>>Todos</option>
              <option value="ingreso" <?= $tipoFiltro==='ingreso' ? 'selected' : '' ?>>Ingresos</option>
              <option value="salida"  <?= $tipoFiltro==='salida'  ? 'selected' : '' ?>>Salidas</option>
            </select>
          </div>
          <div class="col-md-2">
            <label class="form-label">Desde</label>
            <input type="date" name="desde" class="form-control" value="<?= htmlspecialchars($desdeFiltro) ?>">
          </div>
          <div class="col-md-2">
            <label class="form-label">Hasta</label>
            <input type="date" name="hasta" class="form-control" value="<?= htmlspecialchars($hastaFiltro) ?>">
          </div>
          <div class="col-md-3 d-flex gap-2">
            <button class="btn btn-white-custom w-100" type="submit">Aplicar filtros</button>
            <a href="admin_movimientos.php" class="btn btn-white-custom w-100">Limpiar</a>
          </div>
        </div>
      </form>

      <!-- Acciones -->
      <div class="d-flex justify-content-between flex-wrap gap-2 mb-3">
        <div class="small text-secondary">
          Mostrando <strong><?= count($movimientosFiltrados) ?></strong> resultados
          <?= ($productoFiltro||$tipoFiltro||$desdeFiltro||$hastaFiltro) ? ' (filtrados)' : '' ?>.
        </div>
        <div class="d-flex gap-2">
          <form method="post" action="../../controllers/movimientosController.php?action=generarPDF" class="d-inline">
            <button id="btnExportPDF" class="btn btn-danger">📄 Generar Informe Mensual (PDF)</button>
          </form>
        </div>
      </div>

      <!-- Tabla -->
      <div class="table-responsive">
        <table id="tablaMovimientos" class="table table-dark table-striped table-hover align-middle text-center">
          <thead>
            <tr>
              <th>Producto</th>
              <th>Stock</th>
              <th>Ingresado</th>
              <th>Fecha Ingreso</th>
              <th>Salió</th>
              <th>Fecha Salida</th>
              <th>Motivo</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($movimientosFiltrados)): ?>
              <?php foreach ($movimientosFiltrados as $m): ?>
                <tr>
                  <td><?= htmlspecialchars($m['producto']) ?></td>
                  <td><?= htmlspecialchars($m['inv_stock']) ?></td>
                  <td><?= htmlspecialchars($m['cantidad_ingresada'] ?? '-') ?></td>
                  <td><?= htmlspecialchars($m['fecha_ingreso'] ?? '-') ?></td>
                  <td><?= htmlspecialchars($m['cantidad_salida'] ?? '-') ?></td>
                  <td><?= htmlspecialchars($m['fecha_salida'] ?? '-') ?></td>
                  <td><?= htmlspecialchars($m['sal_motivo'] ?? '-') ?></td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="7" class="text-muted">No se encontraron movimientos con los filtros seleccionados.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

    </div>
  </div>
</div>

<?php include_once '../partials/footer.php'; ?>

<script>
  (function() {
    function descargarArchivo(nombre, contenido) {
      const blob = new Blob([contenido], { type: 'text/csv;charset=utf-8;' });
      const url = URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url;
      a.download = nombre;
      document.body.appendChild(a);
      a.click();
      document.body.removeChild(a);
      URL.revokeObjectURL(url);
    }

    function tablaACSV(tabla) {
      const filas = Array.from(tabla.querySelectorAll('tr'));
      return filas.map(tr => {
        const celdas = Array.from(tr.querySelectorAll('th,td'));
        return celdas.map(td => {
          let text = (td.innerText || '').replace(/\s+/g, ' ').trim();
          text = '"' + text.replace(/"/g, '""') + '"';
          return text;
        }).join(',');
      }).join('\n');
    }

    const btn = document.getElementById('btnExportCSV');
    if (btn) {
      btn.addEventListener('click', function() {
        const tabla = document.getElementById('tablaMovimientos');
        if (!tabla) return;
        const csv = tablaACSV(tabla);
        const fecha = new Date().toISOString().slice(0,10);
        descargarArchivo(`movimientos_${fecha}.csv`, csv);
      });
    }
  })();
</script>
</body>
</html>
