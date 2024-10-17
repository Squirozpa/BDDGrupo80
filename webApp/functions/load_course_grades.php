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
$alumnos_csv = [];

// Saltar la cabecera del archivo CSV
fgetcsv($file);

while (($data = fgetcsv($file, 1000, ",")) !== false) {
    list($codigo_plan, $plan, $cohorte, $sede, $run, $dv, $nombres, $apellido_paterno, $apellido_materno, $numero_alumno, $periodo_asignatura, $codigo_asignatura, $asignatura, $convocatoria, $calificacion, $nota) = $data;

    // Guardar los números de alumno del CSV para verificar después
    $alumnos_csv[] = $numero_alumno;

    // Actualizar los datos de la nota y calificación en la base de datos
    $update_query = "
        UPDATE notas
        SET nota = $1, calificacion = $2
        WHERE numero_alumno = $3 AND codigo_asignatura = $4 AND periodo_asignatura = $5 AND convocatoria = $6
    ";
    $update_result = pg_query_params($db, $update_query, array($nota, $calificacion, $numero_alumno, $codigo_asignatura, $periodo_asignatura, $convocatoria));

    if ($update_result) {
        $acta_notas[] = [
            'numero_alumno' => $numero_alumno,
            'run' => $run,
            'nombres' => $nombres,
            'apellido_paterno' => $apellido_paterno,
            'apellido_materno' => $apellido_materno,
            'convocatoria' => $convocatoria,
            'nota' => $nota,
            'calificacion' => $calificacion
        ];
    } else {
        $errores[] = [
            'numero_alumno' => $numero_alumno,
            'error' => pg_last_error($db)
        ];
    }
}

fclose($file);

// Obtener los estudiantes que estaban en el curso y periodo pero no aparecen en el archivo CSV
$query = "
    SELECT e.numero_alumno, e.run, e.primer_nombre, e.apellido_paterno, e.apellido_materno
    FROM notas n
    JOIN estudiantes e ON n.numero_alumno = e.numero_alumno
    WHERE n.codigo_asignatura = $1 AND n.periodo_asignatura = $2
";
$result = pg_query_params($db, $query, array($curso_sigla, $semestre_vigente));

$alumnos_no_encontrados = [];

while ($row = pg_fetch_assoc($result)) {
    if (!in_array($row['numero_alumno'], $alumnos_csv)) {
        $alumnos_no_encontrados[] = $row;
    }
}

pg_close($db);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Acta de Notas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h2, h3 {
            color: #333;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
            text-align: left;
        }
        .summary {
            margin-top: 20px;
        }
        .summary p {
            margin: 5px 0;
        }
    </style>
</head>
<body>

<h2>Acta de Notas</h2>
<p>Curso: <?php echo $curso_sigla; ?></p>
<p>Semestre: <?php echo $semestre_vigente; ?></p>

<h3>Notas Actualizadas</h3>
<table>
    <thead>
        <tr>
            <th>RUN</th>
            <th>Número de Alumno</th>
            <th>Nombre</th>
            <th>Apellido Paterno</th>
            <th>Apellido Materno</th>
            <th>Convocatoria</th>
            <th>Nota</th>
            <th>Calificación</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($acta_notas as $nota): ?>
            <tr>
                <td><?php echo $nota['run']; ?></td>
                <td><?php echo $nota['numero_alumno']; ?></td>
                <td><?php echo $nota['nombres']; ?></td>
                <td><?php echo $nota['apellido_paterno']; ?></td>
                <td><?php echo $nota['apellido_materno']; ?></td>
                <td><?php echo $nota['convocatoria']; ?></td>
                <td><?php echo $nota['nota']; ?></td>
                <td><?php echo $nota['calificacion']; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php if (!empty($errores)): ?>
    <h3>Errores</h3>
    <ul>
        <?php foreach ($errores as $error): ?>
            <li>Número de Alumno: <?php echo $error['numero_alumno']; ?> - Error: <?php echo $error['error']; ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php if (!empty($alumnos_no_encontrados)): ?>
    <h3>Estudiantes No Encontrados en el CSV</h3>
    <table>
        <thead>
            <tr>
                <th>RUN</th>
                <th>Número de Alumno</th>
                <th>Nombre</th>
                <th>Apellido Paterno</th>
                <th>Apellido Materno</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($alumnos_no_encontrados as $alumno): ?>
                <tr>
                    <td><?php echo $alumno['run']; ?></td>
                    <td><?php echo $alumno['numero_alumno']; ?></td>
                    <td><?php echo $alumno['primer_nombre']; ?></td>
                    <td><?php echo $alumno['apellido_paterno']; ?></td>
                    <td><?php echo $alumno['apellido_materno']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

</body>
</html>