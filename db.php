<?php
$host = "dpg-d19nl895pdvs739vdtl0-a.oregon-postgres.render.com";
$user = "actividadesmyv20251_db";
$password = "hVPZVsh9FI8oGVZx74pTCCXTwt2PtYSW";
$dbname = "actividadesmyv20251_db_5bi0";
$port = "5432";

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");
//$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password sslmode=require");


if (!$conn) {
    die("Error de conexión: " . pg_last_error());
}

