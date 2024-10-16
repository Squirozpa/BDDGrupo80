<?php

function cargar_docentes($archivo) {
    $db = pg_connect("host=localhost port=5432 dbname=grupo80 user=grupo80 password=grupo80");

    if (!$db) {
        echo "Error: Unable to open database.\n";
        exit;
    }

    if (($handle = fopen($archivo, "r")) !== FALSE) {
        // Omitir la primera línea (cabecera)
        fgetcsv($handle, 1000, ",");

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $RUN = pg_escape_string($db, $data[0]);
            $Nombre = pg_escape_string($db, $data[1]);
            $Apellido_P = pg_escape_string($db, $data[2]);
            $telefono = pg_escape_string($db, $data[3]);
            $email_personal = pg_escape_string($db, $data[4]);
            $email_institucional = pg_escape_string($db, $data[5]);
            $DEDICACION = pg_escape_string($db, $data[6]);
            $CONTRATO = pg_escape_string($db, $data[7]);
            $DIURNO = pg_escape_string($db, $data[8]) == 'TRUE' ? 'TRUE' : 'FALSE';
            $VESPERTINO = pg_escape_string($db, $data[9]) == 'TRUE' ? 'TRUE' : 'FALSE';
            $SEDE = pg_escape_string($db, $data[10]);
            $CARRERA = pg_escape_string($db, $data[11]);
            $GRADO_ACADEMICO = pg_escape_string($db, $data[12]);
            $JERARQUIA = pg_escape_string($db, $data[13]);
            $CARGO = pg_escape_string($db, $data[14]);
            $ESTAMENTO = pg_escape_string($db, $data[15]);

            $query = "INSERT INTO docentes (RUN, Nombre, Apellido_P, telefono, email_personal, email_institucional, DEDICACION, CONTRATO, DIURNO, VESPERTINO, SEDE, CARRERA, GRADO_ACADEMICO, JERARQUIA, CARGO, ESTAMENTO) 
                      VALUES ('$RUN', '$Nombre', '$Apellido_P', '$telefono', '$email_personal', '$email_institucional', '$DEDICACION', '$CONTRATO', '$DIURNO', '$VESPERTINO', '$SEDE', '$CARRERA', '$GRADO_ACADEMICO', '$JERARQUIA', '$CARGO', '$ESTAMENTO')";
            $result = pg_query($db, $query);

            if (!$result) {
                echo "Error inserting data into docentes: " . pg_last_error($db) . "\n";
                file_put_contents("errores.log", pg_last_error($db) . "\n", FILE_APPEND);
            }
        }
        fclose($handle);
    } else {
        echo "Error: Unable to open file $archivo.\n";
    }

    pg_close($db);
}

ini_set('memory_limit', '2048M');
$filename = 'E2_Malla.csv';  
if (($handle = fopen($filename, 'r')) !== FALSE) {
    
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $unclean_malla[] = $data;
    }

    fclose($handle);
} else {
    echo "Error al abrir el archivo CSV.";
}

$clean_malla = array();
$row_counter = 0;

foreach ($unclean_malla as $data) {
    $row_counter++;
    if ($row_counter >= 4 && $row_counter <= 8) {
        $clean_malla[] = array(
            1 => $data[0],
            2 => $data[1],
            3 => $data[2],
            4 => $data[3],
            5 => $data[4],
            6 => $data[5],
            7 => $data[6],
            8 => $data[7],
            9 => $data[8],
            10 => $data[9]
        );
    }
}
foreach ($clean_malla as $key => $row) {
    foreach ($row as $key2 => $value) {
        if ($value == "Electivo área") {
            $clean_malla[$key][$key2] = "OPR";
        }
    }

}

$filename = 'E2_prereq.csv';
if (($handle = fopen($filename, 'r')) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $unclean_prereq[] = $data;
    }
    fclose($handle);
} else {
    echo "Error al abrir el archivo CSV.";
}

foreach ($unclean_prereq as $data) {
    $clean_prereq[] = array(
        "plan" => $data[0],
        "sigla" => $data[1],
        "nombre" => $data[2],
        "nivel" => $data[3],
        "prereq1" => $data[4],
        "prereq2" => $data[5],
    );
}

foreach ($clean_prereq as $key => $row) {
    foreach ($row as $key2 => $value) {
        if ($value == "por fijar") {
            $clean_prereq[$key][$key2] = null;
        }
    }
}

$filename = 'E2_notas.csv';
if (($handle = fopen($filename, 'r')) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $unclean_notas[] = $data;
    }
    fclose($handle);
} else {
    echo "Error al abrir el archivo CSV.";
}

foreach (array_slice($unclean_notas,1,-1) as $data) {
    $clean_notas[] = array(
        "codigo_plan" => $data[0],
        "plan" => $data[1],
        "cohorte" => $data[2],
        "sede" => $data[3],
        "run" => $data[4],
        "dv" => $data[5],
        "nombre" => $data[6]." ".$data[7]." ".$data[8],
        "numero_alumno" => $data[9],
        "periodo" => $data[10],
        "codigo_asignatura" => $data[11],
        "asignatura" => $data[12],
        "calificacion" => $data[13],
        "nota" => $data[14],     
    );
}
$filename = 'E2_planes.csv';
if (($handle = fopen($filename, 'r')) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $unclean_planes[] = $data;
    }
    fclose($handle);
} else {
    echo "Error al abrir el archivo CSV.";
}
foreach(array_slice($unclean_planes,1,-1) as $data)
    $inicio = date_create_from_format('Y-m-d', $data[8]);
    $clean_planes[] = array(
        "codigo_plan" => $data[0],
        "facultad" => $data[1],
        "carrera" => $data[2],
        "plan" => $data[3],
        "jornada" => $data[4],
        "sede" => $data[5],
        "grado" => $data[6],
        "modalidad" => $data[7],
        "inicio" => $data[8],
    );

$filename = 'E2_asignaturas.csv';

if (($handle = fopen($filename, 'r')) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $unclean_asignaturas[] = $data;
    }
    fclose($handle);
} else {
    echo "Error al abrir el archivo CSV.";
}

foreach($unclean_asignaturas as $data)
    $clean_asignaturas[] = array(
        "plan" => $data[0],
        "codigo" => $data[1],
        "asignatura" => $data[2],
        "nivel" => $data[3],
    );

$filename = 'E2_estudiantes.csv';

if (($handle = fopen($filename, 'r')) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $unclean_alumnos[] = $data;
    }
    fclose($handle);
} else {
    echo "Error al abrir el archivo CSV.";
}

foreach($unclean_alumnos as $data)
    $clean_alumnos[] = array(
        "codigo_plan" => $data[0],
        "carrera" => $data[1],
        "cohorte" => $data[2],
        "numero_alumno" => $data[3],
        "bloqueo" => $data[4],
        "causal_bloqueo" => $data[5],
        "run" => $data[6],
        "dv" => $data[7],
        "nombre" => $data[8]." ".$data[9]." ".$data[10]." ".$data[11],
        "logro" => $data[12],
        "fecha_logro" => $data[13],
        "ultima_carga" => $data[14],
    );

$filename = "E2_planeacion.csv";
if (($handle = fopen($filename, 'r')) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $unclean_planeacion[] = $data;
    }
    fclose($handle);
} else {
    echo "Error al abrir el archivo CSV.";
}

foreach($unclean_planeacion as $data)
    $clean_planeacion[] = array(
        "periodo" => $data[0],
        "sede" => $data[1],
        "facultad" => $data[2],
        "codigo_departamento" => $data[3],
        "departamento" => $data[4],
        "asignatura" => $data[5],
        "seccion" => $data[6],
        "duración" => $data[7],
        "jornada" => $data[8],
        "cupo" => $data[9],
        "inscritos" => $data[10],
        "dia" => $data[11],
        "hora_inicio" => $data[12],
        "hora_termino" => $data[13],
        "fecha_inicio" => $data[14],
        "fecha_termino" => $data[15],
        "lugar" => $data[16],
        "edificio" => $data[17],
        "profesor_principal" => $data[18],
        "run" => $data[19],
        "nombre_profesor" => $data[20]." ".$data[21]." ".$data[22],
        "jerarquizacion" => $data[23],
    );

$filename = "E2_docentes.csv";

if (($handle = fopen($filename, 'r')) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $unclean_docentes[] = $data;
    }
    fclose($handle);
} else {
    echo "Error al abrir el archivo CSV.";
}

foreach($unclean_docentes as $data)
    $clean_docentes[] = array(
        "run" => $data[0],
        "nombre" => $data[1]." ".$data[2],
        "telefono" => $data[3],
        "email_personal" => $data[4],
        "email_institucional" => $data[5],
        "dedicacion" => $data[6],
        "contrato" => $data[7],
        "diurno" => $data[8],
        "vespertino" => $data[9],
        "sede" => $data[10],
        "carrera" => $data[11],
        "grado" => $data[12],
        "jeraquia" => $data[13],
        "cargo" => $data[14],
        "estamento" => $data[15],
    );


    cargar_docentes("E2_docentes.csv");
?>