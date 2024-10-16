<?php

function cargar_estudiantes($archivo) {
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
            $carrera = pg_escape_string($db, $data[1]);
            $cohorte = pg_escape_string($db, $data[2]);
            $numero_alumno = pg_escape_string($db, $data[3]);
            $bloqueo = pg_escape_string($db, $data[4]) == 'TRUE' ? 'TRUE' : 'FALSE';
            $causal_bloqueo = pg_escape_string($db, $data[5]);
            $run = pg_escape_string($db, $data[6]);
            $dv = pg_escape_string($db, $data[7]);
            $primer_nombre = pg_escape_string($db, $data[8]);
            $segundo_nombre = pg_escape_string($db, $data[9]);
            $primer_apellido = pg_escape_string($db, $data[10]);
            $segundo_apellido = pg_escape_string($db, $data[11]);
            $logro = pg_escape_string($db, $data[12]);
            $fecha_logro = pg_escape_string($db, $data[13]);
            $ultima_carga = pg_escape_string($db, $data[14]);

            $query = "INSERT INTO estudiantes (codigo_plan, carrera, cohorte, numero_alumno, bloqueo, causal_bloqueo, run, dv, nombres, primer_apellido, segundo_apellido, logro, fecha_logro, ultima_carga) 
                      VALUES ('$codigo_plan', '$carrera', '$cohorte', '$numero_alumno', '$bloqueo', '$causal_bloqueo', '$run', '$dv', '$nombres', '$primer_apellido', '$segundo_apellido', '$logro', '$fecha_logro', '$ultima_carga')";
            $result = pg_query($db, $query);

            if (!$result) {
                echo "Error inserting data into estudiantes: " . pg_last_error($db) . "\n";
                file_put_contents("errores.log", pg_last_error($db) . "\n", FILE_APPEND);
            }
        }
        fclose($handle);
    } else {
        echo "Error: Unable to open file $archivo.\n";
    }

    pg_close($db);
}

cargar_estudiantes("E2_estudiantes.csv");
?>