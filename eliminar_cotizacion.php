<?php
include "db.php";
session_start();

// Verificar si se ha enviado el parámetro 'requerimiento'
if (isset($_GET['requerimiento'])) {
    $requerimiento = intval($_GET['requerimiento']);
    $sql = "DELETE FROM cotizaciones WHERE requerimiento = $1";
    $result = pg_query_params($conn, $sql, [$requerimiento]);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Cotización eliminada correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al eliminar la cotización']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Requerimiento no especificado']);
}
?>
