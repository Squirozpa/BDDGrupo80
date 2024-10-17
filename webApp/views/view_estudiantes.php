<!DOCTYPE html>
<html>
<head>
    <title>Mostrar Estudiantes</title>
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

<h2>Lista de Estudiantes</h2>

<form method="GET" action="">
    <label for="numero_alumno">Número de Alumno:</label>
    <input type="text" id="numero_alumno" name="numero_alumno">
    <input type="submit" value="Filtrar">
</form>

<div class="scrollable">
    <table>
        <thead>
            <tr>
                <th>Código Plan</th>
                <th>Carrera</th>
                <th>Cohorte</th>
                <th>Número de Alumno</th>
                <th>Bloqueo</th>
                <th>Causal Bloqueo</th>
                <th>RUN</th>
                <th>DV</th>
                <th>Nombres</th>
                <th>Primer Apellido</th>
                <th>Segundo Apellido</th>
                <th>Logro</th>
                <th>Fecha Logro</th>
                <th>Última Carga</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $db = pg_connect("host=localhost port=5432 dbname=grupo80 user=grupo80 password=grupo80");

            if (!$db) {
                echo "Error: Unable to open database.\n";
                exit;
            }

            $numero_alumno = isset($_GET['numero_alumno']) ? pg_escape_string($db, $_GET['numero_alumno']) : '';

            if ($numero_alumno) {
                $query = "SELECT * FROM estudiantes WHERE numero_alumno = '$numero_alumno'";
            } else {
                $query = "SELECT * FROM estudiantes";
            }

            $result = pg_query($db, $query);

            if (!$result) {
                echo "Error fetching data: " . pg_last_error($db) . "\n";
                exit;
            }

            while ($row = pg_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['codigo_plan']) . "</td>";
                echo "<td>" . htmlspecialchars($row['carrera']) . "</td>";
                echo "<td>" . htmlspecialchars($row['cohorte']) . "</td>";
                echo "<td>" . htmlspecialchars($row['numero_alumno']) . "</td>";
                echo "<td>" . htmlspecialchars($row['bloqueo']) . "</td>";
                echo "<td>" . htmlspecialchars($row['causal_bloqueo']) . "</td>";
                echo "<td>" . htmlspecialchars($row['run']) . "</td>";
                echo "<td>" . htmlspecialchars($row['dv']) . "</td>";
                echo "<td>" . htmlspecialchars($row['nombres']) . "</td>";
                echo "<td>" . htmlspecialchars($row['primer_apellido']) . "</td>";
                echo "<td>" . htmlspecialchars($row['segundo_apellido']) . "</td>";
                echo "<td>" . htmlspecialchars($row['logro']) . "</td>";
                echo "<td>" . htmlspecialchars($row['fecha_logro']) . "</td>";
                echo "<td>" . htmlspecialchars($row['ultima_carga']) . "</td>";
                echo "</tr>";
            }

            pg_close($db);
            ?>
        </tbody>
    </table>
</div>

</body>
</html>