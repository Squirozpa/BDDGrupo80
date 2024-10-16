<!DOCTYPE html>
<html>
<head>
    <title>Mostrar Notas</title>
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

<h2>Lista de Notas</h2>

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
                <th>Plan</th>
                <th>Cohorte</th>
                <th>Sede</th>
                <th>RUN</th>
                <th>DV</th>
                <th>Nombres</th>
                <th>Apellido Paterno</th>
                <th>Apellido Materno</th>
                <th>Número de Alumno</th>
                <th>Periodo Asignatura</th>
                <th>Código Asignatura</th>
                <th>Asignatura</th>
                <th>Convocatoria</th>
                <th>Calificación</th>
                <th>Nota</th>
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
                $query = "SELECT * FROM notas WHERE numero_alumno = '$numero_alumno'";
            } else {
                $query = "SELECT * FROM notas";
            }

            $result = pg_query($db, $query);

            if (!$result) {
                echo "Error fetching data: " . pg_last_error($db) . "\n";
                exit;
            }

            while ($row = pg_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['codigo_plan']) . "</td>";
                echo "<td>" . htmlspecialchars($row['plan']) . "</td>";
                echo "<td>" . htmlspecialchars($row['cohorte']) . "</td>";
                echo "<td>" . htmlspecialchars($row['sede']) . "</td>";
                echo "<td>" . htmlspecialchars($row['run']) . "</td>";
                echo "<td>" . htmlspecialchars($row['dv']) . "</td>";
                echo "<td>" . htmlspecialchars($row['nombres']) . "</td>";
                echo "<td>" . htmlspecialchars($row['apellido_paterno']) . "</td>";
                echo "<td>" . htmlspecialchars($row['apellido_materno']) . "</td>";
                echo "<td>" . htmlspecialchars($row['numero_alumno']) . "</td>";
                echo "<td>" . htmlspecialchars($row['periodo_asignatura']) . "</td>";
                echo "<td>" . htmlspecialchars($row['codigo_asignatura']) . "</td>";
                echo "<td>" . htmlspecialchars($row['asignatura']) . "</td>";
                echo "<td>" . htmlspecialchars($row['convocatoria']) . "</td>";
                echo "<td>" . htmlspecialchars($row['calificacion']) . "</td>";
                echo "<td>" . htmlspecialchars($row['nota']) . "</td>";
                echo "</tr>";
            }

            pg_close($db);
            ?>
        </tbody>
    </table>
</div>

</body>
</html>