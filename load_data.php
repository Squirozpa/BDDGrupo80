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

print_r($clean_planes);
?>