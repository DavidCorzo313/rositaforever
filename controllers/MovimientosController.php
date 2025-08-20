<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../libs/dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class MovimientosController {

    public function obtenerMovimientos() {
        $conexion = database::getConexion();
        $sql = "
        SELECT 
            p.pro_Nombre AS producto,
            i.inv_stock AS inv_stock,
            CASE WHEN m.tipo_movimiento = 'ingreso' THEN m.cantidad END AS cantidad_ingresada,
            CASE WHEN m.tipo_movimiento = 'ingreso' THEN m.fecha_movimiento END AS fecha_ingreso,
            CASE WHEN m.tipo_movimiento = 'salida' THEN m.cantidad END AS cantidad_salida,
            CASE WHEN m.tipo_movimiento = 'salida' THEN m.fecha_movimiento END AS fecha_salida,
            m.sal_motivo
        FROM movimientos_inventario m
        JOIN producto p ON m.pro_ID_producto = p.pro_ID_producto
        LEFT JOIN inventario i ON i.inv_ID_producto = p.pro_ID_producto
        ORDER BY m.fecha_movimiento DESC
        ";

        $stmt = $conexion->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function generarPDF() {
        $conexion = database::getConexion();
        $mesActual = date('m');
        $anioActual = date('Y');
        $fechaHoy  = date('d/m/Y'); // Fecha actual para mostrar en el informe
        $fechaFile = date('Y-m-d'); // Fecha actual para el nombre del archivo

        $stmt = $conexion->prepare("
            SELECT 
                p.pro_Nombre AS producto,
                i.inv_stock AS inv_stock,
                CASE WHEN m.tipo_movimiento = 'ingreso' THEN m.cantidad END AS cantidad_ingresada,
                CASE WHEN m.tipo_movimiento = 'ingreso' THEN m.fecha_movimiento END AS fecha_ingreso,
                CASE WHEN m.tipo_movimiento = 'salida' THEN m.cantidad END AS cantidad_salida,
                CASE WHEN m.tipo_movimiento = 'salida' THEN m.fecha_movimiento END AS fecha_salida,
                m.sal_motivo
            FROM movimientos_inventario m
            JOIN producto p ON m.pro_ID_producto = p.pro_ID_producto
            LEFT JOIN inventario i ON i.inv_ID_producto = p.pro_ID_producto
            WHERE (MONTH(m.fecha_movimiento) = ? AND YEAR(m.fecha_movimiento) = ?)
            ORDER BY p.pro_Nombre
        ");
        $stmt->execute([$mesActual, $anioActual]);
        $movimientos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Cabecera del informe con fecha
        $html = "
        <h2 style='text-align:center; color:#B22222;'>📊 Informe de Movimientos</h2>
        <p style='text-align:center; font-size:12px;'>Generado el: <b>$fechaHoy</b></p>
        <p style='text-align:center; font-size:12px;'>Periodo: $mesActual / $anioActual</p>
        <br>
        <table border='1' cellpadding='5' cellspacing='0' style='width:100%; font-size:12px; border-collapse:collapse;'>
          <thead style='background:#B22222; color:white;'>
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
          <tbody>";

        foreach ($movimientos as $m) {
            $html .= "<tr>
              <td>{$m['producto']}</td>
              <td>{$m['inv_stock']}</td>
              <td>" . ($m['cantidad_ingresada'] ?? '-') . "</td>
              <td>" . ($m['fecha_ingreso'] ?? '-') . "</td>
              <td>" . ($m['cantidad_salida'] ?? '-') . "</td>
              <td>" . ($m['fecha_salida'] ?? '-') . "</td>
              <td>" . ($m['sal_motivo'] ?? '-') . "</td>
            </tr>";
        }

        $html .= "</tbody></table>";

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        // Nombre dinámico con fecha
        $dompdf->stream("informe_movimientos_$fechaFile.pdf", ["Attachment" => true]);
        exit;
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'generarPDF') {
    $controller = new MovimientosController();
    $controller->generarPDF();
}
