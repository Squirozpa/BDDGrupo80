<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Conectar a la base de datos PostgreSQL
$db = pg_connect("host=localhost port=5432 dbname=grupo80 user=grupo80 password=grupo80");

// Verificar si la conexión fue exitosa
if (!$db) {
    die("Error en la conexión a la base de datos: " . pg_last_error());
}

// Consulta para crear una tabla
$query = "CREATE TABLE IF NOT EXISTS usuarios (
    id SERIAL PRIMARY KEY,
    email VARCHAR(100) NOT NULL,
    clave VARCHAR(8) NOT NULL
)";

// Ejecutar la consulta
$result = pg_query($db, $query);

// Verificar si la tabla fue creada exitosamente
if ($result) {
    echo "Tabla 'usuarios' creada exitosamente.";
} else {
    die("Error al crear la tabla: " . pg_last_error());
}

// Cerrar la conexión a la base de datos
pg_close($db);
?>
