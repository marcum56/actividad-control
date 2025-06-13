<?php
$hoy = date('Y-m-d');

// Usamos pg_query en lugar de $conn->query
pg_query($conn, "UPDATE actividades 
                 SET fecha = '$hoy' 
                 WHERE completada = false 
                   AND fecha < '$hoy' 
                   AND persona_id = $persona_id");
?>


