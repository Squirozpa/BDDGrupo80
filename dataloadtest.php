<?php

$db = pg_connect("host=localhost port=5432 dbname=grupo80 user=grupo80 password=grupo80");

if (!$db) {
    die("Error en la conexión a la base de datos: " . pg_last_error());
}

// Crear tabla de usuarios y sus contraseñas
$query = "CREATE TABLE IF NOT EXISTS usuarios (
    id SERIAL PRIMARY KEY,
    email VARCHAR(100) NOT NULL,
    clave VARCHAR(8) NOT NULL
)";
$result = pg_query($db, $query);
$query = "INSERT INTO usuarios (email, clave) VALUES ('usuario1@ing.puc.cl', 'abcd1234'), 
                                                      ('usuario2@ing.puc.cl', 'efgh5678')";
$result = pg_query($db, $query);

if ($result) {
    echo "Datos insertados correctamente en la tabla 'usuarios'.";
} else {
    die("Error al insertar los datos: " . pg_last_error());
}

pg_close($db);

?>