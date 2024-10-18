<?php

function cargar_planes($archivo) {
    $db = pg_connect("host=localhost port=5432 dbname=grupo80 user=grupo80 password=grupo80");

    if (!$db) {
        echo "Error: Unable to open database.\n";
        exit;
    }

    if (($handle = fopen($archivo, "r")) !== FALSE) {
        // Omitir la primera línea (cabecera)
        fgetcsv($handle, 1000, ",");

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $codigo_plan = pg_escape_string($db, $data[0]);
            $facultad = pg_escape_string($db, $data[1]);
            $carrera = pg_escape_string($db, $data[2]);
            $plan = pg_escape_string($db, $data[3]);
            $jornada = pg_escape_string($db, $data[4]);
            $sede = pg_escape_string($db, $data[5]);
            $grado = pg_escape_string($db, $data[6]);
            $modalidad = pg_escape_string($db, $data[7]);

            $inicio_vigencia = pg_escape_string($db, $data[7]);


            $query = "INSERT INTO planes (codigo_plan, facultad, carrera, plan, jornada, sede, grado, modalidad, inicio_vigencia) 
                      VALUES ('$codigo_plan', '$facultad', '$carrera', '$plan', '$jornada', '$sede', '$grado', '$modalidad', '$inicio_vigencia')";
            $result = pg_query($db, $query);

            if (!$result) {
                echo "Error inserting data into planes: " . pg_last_error($db) . "\n";
                file_put_contents("errores.log", pg_last_error($db) . "\n", FILE_APPEND);
            }
        }
        fclose($handle);
    } else {
        echo "Error: Unable to open file $archivo.\n";
    }

    pg_close($db);
}

?>