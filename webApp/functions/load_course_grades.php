<?php

$db = pg_connect("host=localhost port=5432 dbname=grupo80 user=grupo80 password=grupo80");

$file = fopen("../course/grades.csv", "w");

if ($file === false) {
    die("Error al abrir el archivo CSV.");
}

$curso_sigla = "WH236032";
$semestre_vigente = "2024-02";

$query = "
    SELECT *
    FROM notas
    WHERE Codigo_Asignatura = $1 AND Periodo_Asignatura = $2";
$result = pg_query_params($db, $query, array($curso_sigla, $semestre_vigente));

if (!$result) {
    die("Error en la consulta: " . pg_last_error($db));
}

$acta_notas = [];
$errores = [];

fputcsv($file, ["codigo_plan", "plan", "cohorte", "sede", "run", "DV", "Nombres", "Apellido_Paterno", "Apellido_Materno", "Numero_de_alumno", "Periodo_Asignatura", "Codigo_Asignatura", "Asignatura", "Convocatoria", "Calificacion", "Nota"]);

while ($row = pg_fetch_assoc($result)) {
    fputcsv($file, [$row['codigo_plan'], $row['plan'], $row['cohorte'], $row['sede'], 
    $row['run'], $row['dv'], $row['nombres'], $row['apellido_paterno'], 
    $row['apellido_materno'], $row['numero_de_alumno'], $row['periodo_asignatura'], 
    $row['codigo_asignatura'], $row['asignatura'], $row['convocatoria'], 
    $row['calificacion'], $row['nota']]);
}
echo "Archivo generado exitosamente.";
fclose($file);
pg_close($db);

//Generar informe de notas
?>