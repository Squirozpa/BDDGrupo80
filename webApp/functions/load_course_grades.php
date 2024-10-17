<?php

$db = pg_connect("host=localhost port=5432 dbname=grupo80 user=grupo80 password=grupo80");

$file = fopen("../course/grades.csv", "r");

if ($file === false) {
    die("Error al abrir el archivo CSV.");
}

$curso_sigla = "WH236032";
$semestre_vigente = "2024-02";

$acta_notas = [];
$errores = [];

fputcsv($file, ['numero_alumno', 'primer_nombre', 'primer_apellido', 'nota', 'calificacion', 'convocatoria']);

while ($row = pg_fetch_assoc($result)) {
    fputcsv($file, [$row['numero_alumno'], $row['primer_nombre'], $row['primer_apellido'], $row['nota'], $row['calificacion'], $row['convocatoria']]);
}
echo "Archivo generado exitosamente.";
fclose($file);
pg_close($db);

//Generar informe de notas
?>