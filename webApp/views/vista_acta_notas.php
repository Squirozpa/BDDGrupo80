<!DOCTYPE html>
<html>
<head>
    <title>Acta de Notas</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .scrollable {
            height: 400px;
            overflow-y: scroll;
        }
    </style>
</head>
<body>

<h2>Subir Archivo CSV</h2>
<form action="vista_acta_notas.php" method="post" enctype="multipart/form-data">
    Selecciona el archivo CSV:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Subir Archivo" name="submit">
</form>

<h2>Acta de Notas</h2>
<?php
$db = pg_connect("host=localhost port=5432 dbname=grupo80e3 user=grupo80e3 password=grupo80");

if (!$db) {
    echo "Error: Unable to open database.\n";
    exit;
}

if (isset($_POST["submit"])) {
    $file = $_FILES["fileToUpload"]["tmp_name"];
    if (($handle = fopen($file, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $numero_alumno = $data[0];
            $run = $data[1];
            $sigla = $data[2];
            $seccion = $data[3];
            $periodo = $data[4];
            $nota = $data[5];

            $query = "INSERT INTO acta (numero_alumno, run, sigla, seccion, periodo, nota) VALUES ('$numero_alumno', '$run', '$sigla', '$seccion', '$periodo', '$nota')";
            $result = pg_query($db, $query);
            if (!$result) {
                echo "Error inserting data: " . pg_last_error($db) . "\n";
            }
        }
        fclose($handle);
    }
}

$query = "SELECT * FROM acta_notas";
$result = pg_query($db, $query);

if (!$result) {
    echo "Error fetching data from acta_notas: " . pg_last_error($db) . "\n";
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Acta de Notas</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .scrollable {
            height: 400px;
            overflow-y: scroll;
        }
    </style>
</head>
<body>

<h2>Subir Archivo CSV</h2>
<form action="vista_acta_notas.php" method="post" enctype="multipart/form-data">
    Selecciona el archivo CSV:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Subir Archivo" name="submit">
</form>

<h2>Acta de Notas</h2>

<div class="scrollable">
    <table>
        <thead>
            <tr>
                <th>Número de Alumno</th>
                <th>RUN</th>
                <th>Curso</th>
                <th>Sección</th>
                <th>Periodo</th>
                <th>Nombre del Estudiante</th>
                <th>Nota</th>
                <th>Calificación</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = pg_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['numero_alumno']) . "</td>";
                echo "<td>" . htmlspecialchars($row['run']) . "</td>";
                echo "<td>" . htmlspecialchars($row['curso']) . "</td>";
                echo "<td>" . htmlspecialchars($row['seccion']) . "</td>";
                echo "<td>" . htmlspecialchars($row['periodo']) . "</td>";
                echo "<td>" . htmlspecialchars($row['nombre_estudiante']) . "</td>";
                echo "<td>" . htmlspecialchars($row['nota']) . "</td>";
                echo "<td>" . htmlspecialchars($row['calificacion']) . "</td>";
                echo "</tr>";
            }
            pg_close($db);
            ?>
        </tbody>
    </table>
</div>

</body>
</html>