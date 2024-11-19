<!DOCTYPE html>
<html>
<head>
    <title>Subir Archivo de Notas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h2 {
            color: #333;
        }
        form {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h2>Subir Archivo de Notas</h2>
    <form action="load_course_grades.php" method="post" enctype="multipart/form-data">
        <label for="fileToUpload">Selecciona el archivo CSV:</label>
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Subir Archivo" name="submit">
    </form>
</body>
</html>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$db = pg_connect("host=localhost port=5432 dbname=grupo80e3 user=grupo80e3 password=grupo80");

// Verificar si se ha subido un archivo
if (isset($_FILES['fileToUpload']) && $_FILES['fileToUpload']['error'] == UPLOAD_ERR_OK) {
    $file = $_FILES['fileToUpload']['tmp_name'];
} else {
    // Usar el archivo por defecto
    $file = "../course/grades.csv";
}

$file_handle = fopen($file, "r");

if ($file_handle === false) {
    die("Error al abrir el archivo CSV.");
}

$curso_sigla = "WH236032";
$semestre_vigente = "2024-02";

$acta_notas = [];
$errores = [];
$alumnos_csv = [];

// Saltar la cabecera del archivo CSV
fgetcsv($file_handle);

while (($data = fgetcsv($file_handle, 1000, ",")) !== false) {
    list($codigo_plan, $plan, $cohorte, $sede, $run, $dv, $nombres, $apellido_paterno, $apellido_materno, $numero_alumno, $periodo_asignatura, $codigo_asignatura, $asignatura, $convocatoria, $calificacion, $nota) = $data;

    // Guardar los números de alumno del CSV para verificar después
    $alumnos_csv[] = $numero_alumno;

    // Validar el número de alumno
    if (!is_numeric($numero_alumno)) {
        $errores[] = [
            'numero_alumno' => $numero_alumno,
            'error' => 'Número de alumno no es numérico'
        ];
        continue;
    }

    // Validar la nota
    if (!is_numeric($nota) || $nota < 1.0 || $nota > 7.0) {
        $errores[] = [
            'numero_alumno' => $numero_alumno,
            'error' => 'Nota fuera del rango permitido (1.0 - 7.0)'
        ];
        continue;
    }

    // Validar la calificación
    $calificaciones_validas = ['SO', 'MB', 'B', 'SU', 'I', 'M', 'MM', 'P', 'NP', 'EX', 'A', 'R'];
    if (!in_array($calificacion, $calificaciones_validas)) {
        $errores[] = [
            'numero_alumno' => $numero_alumno,
            'error' => 'Calificación no válida'
        ];
        continue;
    }

    // Si no hay errores, preparar los datos para la inserción
    $acta_notas[] = [
        'codigo_plan' => $codigo_plan,
        'plan' => $plan,
        'cohorte' => $cohorte,
        'sede' => $sede,
        'run' => $run,
        'dv' => $dv,
        'nombres' => $nombres,
        'apellido_paterno' => $apellido_paterno,
        'apellido_materno' => $apellido_materno,
        'numero_alumno' => $numero_alumno,
        'periodo_asignatura' => $periodo_asignatura,
        'codigo_asignatura' => $codigo_asignatura,
        'asignatura' => $asignatura,
        'convocatoria' => $convocatoria,
        'calificacion' => $calificacion,
        'nota' => $nota
    ];
}

fclose($file_handle);

// Si hay errores, reportarlos y no cargar los datos
if (!empty($errores)) {
    pg_close($db);
    ?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>Errores en la Carga de Notas</title>
        <style>
            body {
                font-family: Arial, sans-serif;
            }
            h2, h3 {
                color: #333;
            }
            ul {
                list-style-type: none;
                padding: 0;
            }
            li {
                margin-bottom: 10px;
                color: red;
            }
        </style>
    </head>
    <body>
        <h2>Errores en la Carga de Notas</h2>
        <ul>
            <?php foreach ($errores as $error): ?>
                <li>Número de Alumno: <?php echo htmlspecialchars($error['numero_alumno']); ?> - Errores: <?php echo htmlspecialchars(implode(', ', $error['errors'])); ?></li>
            <?php endforeach; ?>
        </ul>
    </body>
    </html>

    <?php
    exit;
}

// Si no hay errores, cargar los datos en la tabla notas
foreach ($acta_notas as $nota) {
    $update_query = "
        UPDATE notas
        SET nota = $1, calificacion = $2
        WHERE numero_alumno = $3 AND codigo_asignatura = $4 AND periodo_asignatura = $5 AND convocatoria = $6
    ";
    $update_result = pg_query_params($db, $update_query, array($nota['nota'], $nota['calificacion'], $nota['numero_alumno'], $nota['codigo_asignatura'], $nota['periodo_asignatura'], $nota['convocatoria']));

    if (!$update_result) {
        $errores[] = [
            'numero_alumno' => $nota['numero_alumno'],
            'error' => pg_last_error($db)
        ];
    }
}

// Obtener los estudiantes que estaban en el curso y periodo pero no aparecen en el archivo CSV
$query = "
SELECT *
FROM notas n
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
                <td><?php echo htmlspecialchars($nota['run']); ?></td>
                <td><?php echo htmlspecialchars($nota['numero_alumno']); ?></td>
                <td><?php echo htmlspecialchars($nota['nombres']); ?></td>
                <td><?php echo htmlspecialchars($nota['apellido_paterno']); ?></td>
                <td><?php echo htmlspecialchars($nota['apellido_materno']); ?></td>
                <td><?php echo htmlspecialchars($nota['convocatoria']); ?></td>
                <td><?php echo htmlspecialchars($nota['nota']); ?></td>
                <td><?php echo htmlspecialchars($nota['calificacion']); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php if (!empty($errores)): ?>
    <h3>Errores</h3>
    <ul>
        <?php foreach ($errores as $error): ?>
            <li>Número de Alumno: <?php echo htmlspecialchars($error['numero_alumno']); ?> - Error: <?php echo htmlspecialchars($error['error']); ?></li>
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
                    <td><?php echo htmlspecialchars($alumno['run']); ?></td>
                    <td><?php echo htmlspecialchars($alumno['numero_alumno']); ?></td>
                    <td><?php echo htmlspecialchars($alumno['nombres']); ?></td>
                    <td><?php echo htmlspecialchars($alumno['apellido_paterno']); ?></td>
                    <td><?php echo htmlspecialchars($alumno['apellido_materno']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

</body>
</html>