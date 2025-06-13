<?php
session_start();
include "db.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Validar que el usuario esté logueado
if (!isset($_SESSION['persona_id'])) {
    header("Location: index.php");
    exit;
}

// Validar y limpiar parámetros
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$persona_id = $_SESSION['persona_id']; // Se toma de la sesión, no por GET

if ($id <= 0) {
    die("ID inválido.");
}

// Marcar como completada
$result = pg_query_params(
    $conn,
    "UPDATE actividades SET completada = TRUE WHERE id = $1",
    array($id)
);

if (!$result) {
    die("Error al marcar la actividad como completada: " . pg_last_error($conn));
}

// Redirigir a actividades
header("Location: actividades.php");
exit;
?>

