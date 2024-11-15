<?php
$db = pg_connect("host=localhost port=5432 dbname=grupo80e3 user=grupo80e3 password=grupo80");
$db_profes = pg_connect("host=localhost port=5432 dbname=e3profesores user=grupo80e3 password=grupo80");

if (!$db || !$db_profes) {
    echo "Error: Unable to open database.\n";
    exit;
}

// Obtener datos de la tabla profesores
$query_profes = "SELECT run, nombre, apellido1 FROM profesores";
$result_profes = pg_query($db_profes, $query_profes);

if (!$result_profes) {
    echo "Error fetching data from profesores: " . pg_last_error($db_profes) . "\n";
    exit;
}

while ($row = pg_fetch_assoc($result_profes)) {
    $run = pg_escape_string($db, $row['run']);
    $nombre = pg_escape_string($db, $row['nombre']);
    $apellido1 = pg_escape_string($db, $row['apellido1']);

    // Verificar si la persona ya existe en la tabla persona
    $query_check = "SELECT * FROM persona WHERE run = '$run'";
    $result_check = pg_query($db, $query_check);

    if (pg_num_rows($result_check) > 0) {
        // Si existe, actualizar los datos
        $query_update = "UPDATE persona SET nombre = '$nombre', apellido = '$apellido1' WHERE run = '$run'";
        $result_update = pg_query($db, $query_update);

        if ($result_update) {
            echo "Registro actualizado para RUN: $run\n";
        } else {
            echo "Error updating data for RUN: $run - " . pg_last_error($db) . "\n";
        }
    } else {
        // Si no existe, insertar los datos
        $query_insert = "INSERT INTO persona (run, nombre, apellido) VALUES ('$run', '$nombre', '$apellido1')";
        $result_insert = pg_query($db, $query_insert);

        if ($result_insert) {
            echo "Registro insertado para RUN: $run\n";
        } else {
            echo "Error inserting data for RUN: $run - " . pg_last_error($db) . "\n";
        }
    }
}

pg_close($db);
pg_close($db_profes);
?>