<?php
$host = "dpg-d15o9n15pdvs73drcde0-a.oregon-postgres.render.com";
$user = "actividadesmyv20251_db";
$password = "WYGwGW7eOkNXULdJ1sL3lTalZrR7K5tw";
$dbname = "actividadesmyv20251_db_mdqu";
$port = "5432";

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Error de conexión: " . pg_last_error());
}

