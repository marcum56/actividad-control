<?php
require 'vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

include 'db.php';

$req = intval($_GET['requerimiento']);
$query = pg_query_params($conn, "SELECT * FROM cotizaciones WHERE requerimiento = $1", [$req]);
$data = pg_fetch_assoc($query);
$productos = json_decode($data['productos'], true);

$html = '
<style>
body { font-family: sans-serif; font-size: 12px; }
table { width: 100%; border-collapse: collapse; margin-top: 10px; }
th, td { border: 1px solid #000; padding: 6px; text-align: center; }
h3 { text-align: center; }
</style>
<h3>Cotización Nº ' . $data['requerimiento'] . '</h3>
<p><strong>Fecha:</strong> ' . date('d-M-Y', strtotime($data['fecha'])) . '</p>
<table>
  <thead>
    <tr>
      <th>CÓDIGO</th>
      <th>DESCRIPCIÓN</th>
      <th>PRECIO</th>
      <th>CANTIDAD</th>
      <th>DESCUENTO (%)</th>
      <th>VALOR</th>
    </tr>
  </thead>
  <tbody>';
foreach ($productos as $p) {
    $html .= '<tr>
        <td>' . htmlspecialchars($p['codigo']) . '</td>
        <td>' . htmlspecialchars($p['descripcion']) . '</td>
        <td>$' . number_format($p['precio'], 2) . '</td>
        <td>' . $p['cantidad'] . '</td>
        <td>' . $p['descuento'] . '</td>
        <td>$' . number_format($p['valor'], 2) . '</td>
    </tr>';
}
$html .= '</tbody></table>
<br><br>
<table>
  <tr><th>SUBTOTAL</th><td>$' . number_format($data['subtotal'], 2) . '</td></tr>
  <tr><th>IVA (15%)</th><td>$' . number_format($data['iva'], 2) . '</td></tr>
  <tr><th>TOTAL</th><td><strong>$' . number_format($data['total'], 2) . '</strong></td></tr>
</table>
<br><h4>MONTO TRANSFERENCIA: $' . number_format($data['total'], 2) . '</h4>';

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("Cotizacion_{$req}.pdf", ["Attachment" => true]);
