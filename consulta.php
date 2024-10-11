<?php
// Conectar a la base de datos PostgreSQL
$db = pg_connect("host=localhost port=5432 dbname=grupo80 user=grupo80 password=grupo80");

// Verificar si la conexión fue exitosa
if (!$db) {
    die("Error en la conexión a la base de datos: " . pg_last_error());
}

// Realizar la consulta a la base de datos (cambiar 'book' por tu tabla si es necesario)
$query = "SELECT * FROM usuarios";
$result = pg_query($db, $query);

// Verificar si la consulta fue exitosa
if (!$result) {
    die("Error en la consulta SQL: " . pg_last_error());
}

// Obtener todas las filas del resultado en un arreglo asociativo
$rows = pg_fetch_all($result);

// Si se obtuvieron filas, convertir a JSON y mostrar el resultado
if ($rows) {
    echo json_encode($rows); // Muestra los resultados en formato JSON
} else {
    echo json_encode([]); // Muestra un arreglo vacío si no hay resultados
}

// Cerrar la conexión a la base de datos
pg_close($db);
?>
