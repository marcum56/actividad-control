<?php
include "db.php";

// Castear parámetros
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$persona_id = isset($_GET['persona_id']) ? (int)$_GET['persona_id'] : 0;

// Soft-delete: marcar borrado = TRUE
$result = pg_query_params(
  $conn,
  "UPDATE actividades SET borrado = TRUE WHERE id = $1",
  array($id)
);

if (!$result) {
  die("Error al marcar actividad como borrada: " . pg_last_error($conn));
}

header("Location: actividades.php?persona_id=$persona_id");
exit;

