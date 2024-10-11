<?php
// Conectar a la base de datos PostgreSQL
$db = pg_connect("host=localhost port=5432 dbname=grupo80 user=grupo80 password=grupo80");

// Verificar si la conexión fue exitosa
if (!$db) {
    die("Error en la conexión a la base de datos: " . pg_last_error());
}

// Insertar datos en la tabla
$query = "INSERT INTO usuarios (email, clave) VALUES ('usuario1@ing.puc.cl', 'abcd1234'), 
                                                      ('usuario2@ing.puc.cl', 'efgh5678')";

$result = pg_query($db, $query);

// Verificar si la inserción fue exitosa
if ($result) {
    echo "Datos insertados correctamente en la tabla 'usuarios'.";
} else {
    die("Error al insertar los datos: " . pg_last_error());
}

// Cerrar la conexión a la base de datos
pg_close($db);
?>
