<?php

function cargar_notas($archivo) {
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
            $plan = pg_escape_string($db, $data[1]);
            $cohorte = pg_escape_string($db, $data[2]);
            $sede = pg_escape_string($db, $data[3]);
            $run = pg_escape_string($db, $data[4]);
            $dv = pg_escape_string($db, $data[5]);
            $nombres = pg_escape_string($db, $data[6]);
            $apellido_paterno = pg_escape_string($db, $data[7]);
            $apellido_materno = pg_escape_string($db, $data[8]);
            $numero_alumno = pg_escape_string($db, $data[9]);
            $periodo_asignatura = pg_escape_string($db, $data[10]);
            $codigo_asignatura = pg_escape_string($db, $data[11]);
            $asignatura = pg_escape_string($db, $data[12]);
            $convocatoria = pg_escape_string($db, $data[13]);
            $calificacion = pg_escape_string($db, $data[14]);
            $nota = is_numeric($data[15]) ? pg_escape_string($db, $data[15]) : 'NULL';

            $query = "INSERT INTO notas (codigo_plan, plan, cohorte, sede, run, dv, nombres, apellido_paterno, apellido_materno, numero_alumno, periodo_asignatura, codigo_asignatura, asignatura, convocatoria, calificacion, nota) 
                      VALUES ('$codigo_plan', '$plan', '$cohorte', '$sede', '$run', '$dv', '$nombres', '$apellido_paterno', '$apellido_materno', '$numero_alumno', '$periodo_asignatura', '$codigo_asignatura', '$asignatura', '$convocatoria', '$calificacion', $nota)";
            $result = pg_query($db, $query);

            if (!$result) {
                echo "Error inserting data into notas: " . pg_last_error($db) . "\n";
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
cargar_notas("E2_notas.csv");
?>