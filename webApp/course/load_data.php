<?php


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
        "convocatoria" => $data[13],
        "calificacion" => $data[14],
        "nota" => $data[15],     
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

foreach($unclean_planeacion as $data) {
    if ($data[23] === '0') {
        $data[23] = '';
    }

    $clean_planeacion[] = array(
        "periodo" => $data[0],
        "sede" => $data[1],
        "facultad" => $data[2],
        "codigo_departamento" => $data[3],
        "departamento" => $data[4],
        "id_asignatura" => $data[5],
        "asignatura" => $data[6],
        "seccion" => $data[7],
        "duracion" => $data[8],
        "jornada" => $data[9],
        "cupo" => $data[10],
        "inscritos" => $data[11],
        "dia" => $data[12],
        "hora_inicio" => $data[13],
        "hora_termino" => $data[14],
        "fecha_inicio" => $data[15],
        "fecha_termino" => $data[16],
        "lugar" => $data[17],
        "edificio" => $data[18],
        "profesor_principal" => $data[19],
        "run" => $data[20],
        "nombre_profesor" => $data[21]." ".$data[22]." ".$data[23],
        "jerarquizacion" => $data[24],
    );
}

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


?>