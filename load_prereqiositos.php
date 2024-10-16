<?php

function cargar_prerequisitos($archivo) {
    $db = pg_connect("host=localhost port=5432 dbname=grupo80 user=grupo80 password=grupo80");

    if (!$db) {
        echo "Error: Unable to open database.\n";
        exit;
    }

    if (($handle = fopen($archivo, "r")) !== FALSE) {
        // Omitir la primera línea (cabecera)
        fgetcsv($handle, 1000, ",");

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $plan = pg_escape_string($db, $data[0]);
            $asignatura_id = pg_escape_string($db, $data[1]);
            $asignatura = pg_escape_string($db, $data[2]);
            $nivel = pg_escape_string($db, $data[3]);
            $prerequisito = pg_escape_string($db, $data[4]);

            $query = "INSERT INTO prerequisitos (plan, asignatura_id, asignatura, nivel, prerequisito) 
                      VALUES ('$plan', '$asignatura_id', '$asignatura', $nivel, '$prerequisito')";
            $result = pg_query($db, $query);

            if (!$result) {
                echo "Error inserting data into prerequisitos: " . pg_last_error($db) . "\n";
                file_put_contents("errores.log", pg_last_error($db) . "\n", FILE_APPEND);
            }
        }
        fclose($handle);
    } else {
        echo "Error: Unable to open file $archivo.\n";
    }

    pg_close($db);
}

// Llamar a la función para cargar los datos del archivo CSV
cargar_prerequisitos("path/to/E2_prereq.csv");
?>