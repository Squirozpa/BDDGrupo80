<?php

function cargar_planeacion($archivo) {
    $db = pg_connect("host=localhost port=5432 dbname=grupo80 user=grupo80 password=grupo80");

    if (!$db) {
        echo "Error: Unable to open database.\n";
        exit;
    }

    if (($handle = fopen($archivo, "r")) !== FALSE) {
        // Omitir la primera línea (cabecera)
        fgetcsv($handle, 1000, ",");

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $periodo = pg_escape_string($db, $data[0]);
            $sede = pg_escape_string($db, $data[1]);
            $facultad = pg_escape_string($db, $data[2]);
            $codigo_depto = pg_escape_string($db, $data[3]);
            $departamento = pg_escape_string($db, $data[4]);
            $id_asignatura = pg_escape_string($db, $data[5]);
            $asignatura = pg_escape_string($db, $data[6]);
            $seccion = pg_escape_string($db, $data[7]);
            $duracion = pg_escape_string($db, $data[8]);
            $jornada = pg_escape_string($db, $data[9]);
            $cupo = pg_escape_string($db, $data[10]);
            $inscritos = pg_escape_string($db, $data[11]);
            $dia = pg_escape_string($db, $data[12]);
            $hora_inicio = pg_escape_string($db, $data[13]);
            $hora_fin = pg_escape_string($db, $data[14]);
            $fecha_inicio = DateTime::createFromFormat('d/m/y', $data[15])->format('Y-m-d');
            $fecha_fin = DateTime::createFromFormat('d/m/y', $data[16])->format('Y-m-d');
            $lugar = pg_escape_string($db, $data[17]);
            $edificio = pg_escape_string($db, $data[18]);
            $profesor_principal = pg_escape_string($db, $data[19]);
            $run = pg_escape_string($db, $data[20]);
            $nombre_docente = pg_escape_string($db, $data[21]);
            $primer_apellido_docente = pg_escape_string($db, $data[22]);
            $segundo_apellido_docente = pg_escape_string($db, $data[23]);
            $jerarquizacion = pg_escape_string($db, $data[24]);

            $query = "INSERT INTO planeacion (periodo, sede, facultad, codigo_depto, departamento, id_asignatura, asignatura, seccion, duracion, jornada, cupo, inscritos, dia, hora_inicio, hora_fin, fecha_inicio, fecha_fin, lugar, edificio, profesor_principal, run, nombre_docente, primer_apellido_docente, segundo_apellido_docente, jerarquizacion) 
                      VALUES ('$periodo', '$sede', '$facultad', '$codigo_depto', '$departamento', '$id_asignatura', '$asignatura', $seccion, '$duracion', '$jornada', $cupo, $inscritos, '$dia', '$hora_inicio', '$hora_fin', '$fecha_inicio', '$fecha_fin', '$lugar', '$edificio', '$profesor_principal', '$run', '$nombre_docente', '$primer_apellido_docente', '$segundo_apellido_docente', '$jerarquizacion')";
            $result = pg_query($db, $query);

            if (!$result) {
                echo "Error inserting data into planeacion: " . pg_last_error($db) . "\n";
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
cargar_planeacion("path/to/E2_planeacion.csv");
?>