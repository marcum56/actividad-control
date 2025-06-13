<?php
$host = "dpg-d0l7hhd6ubrc73brka8g-a.oregon-postgres.render.com";
$user = "actividadesmyv20251_db";
$password = "uotJSNlw76KNVHN9rpPCyWvaZf5X4nxc";
$dbname = "actividadesmyv20251_db_c6sw";
$port = "5432";

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Error de conexión: " . pg_last_error());
}

