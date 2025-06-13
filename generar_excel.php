<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include 'db.php';

$req = intval($_GET['requerimiento']);
$query = pg_query_params($conn, "SELECT * FROM cotizaciones WHERE requerimiento = $1", [$req]);
$data = pg_fetch_assoc($query);
$productos = json_decode($data['productos'], true);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Título
$sheet->setCellValue('A1', 'Cotización Nº ' . $data['requerimiento']);
$sheet->setCellValue('B2', 'Fecha: ' . date('d-M-Y', strtotime($data['fecha'])));

// Cabecera
$sheet->fromArray(['CÓDIGO', 'DESCRIPCIÓN', 'PRECIO', 'CANTIDAD', 'DESCUENTO (%)', 'VALOR'], null, 'A4');

// Productos
$row = 5;
foreach ($productos as $p) {
    $sheet->setCellValue("A{$row}", $p['codigo']);
    $sheet->setCellValue("B{$row}", $p['descripcion']);
    $sheet->setCellValue("C{$row}", $p['precio']);
    $sheet->setCellValue("D{$row}", $p['cantidad']);
    $sheet->setCellValue("E{$row}", $p['descuento']);
    $sheet->setCellValue("F{$row}", $p['valor']);
    $row++;
}

// Totales
$sheet->setCellValue("E{$row}", 'SUBTOTAL');
$sheet->setCellValue("F{$row}", $data['subtotal']);
$row++;
$sheet->setCellValue("E{$row}", 'IVA (15%)');
$sheet->setCellValue("F{$row}", $data['iva']);
$row++;
$sheet->setCellValue("E{$row}", 'TOTAL');
$sheet->setCellValue("F{$row}", $data['total']);
$row += 2;
$sheet->setCellValue("E{$row}", 'MONTO TRANSFERENCIA:');
$sheet->setCellValue("F{$row}", $data['total']);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename=Cotizacion_{$req}.xlsx");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
