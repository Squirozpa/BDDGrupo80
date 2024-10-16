<!DOCTYPE html>
<html>
<head>
    <title>Mostrar Docentes</title>
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

<h2>Lista de Docentes</h2>

<div class="scrollable">
    <table>
        <thead>
            <tr>
                <th>RUN</th>
                <th>Nombre</th>
                <th>Apellido P</th>
                <th>Telefono</th>
                <th>Email Personal</th>
                <th>Email Institucional</th>
                <th>DEDICACION</th>
                <th>CONTRATO</th>
                <th>DIURNO</th>
                <th>VESPERTINO</th>
                <th>SEDE</th>
                <th>CARRERA</th>
                <th>GRADO ACADEMICO</th>
                <th>JERARQUIA</th>
                <th>CARGO</th>
                <th>ESTAMENTO</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $db = pg_connect("host=localhost port=5432 dbname=grupo80 user=grupo80 password=grupo80");

            if (!$db) {
                echo "Error: Unable to open database.\n";
                exit;
            }

            $query = "SELECT * FROM docentes";
            $result = pg_query($db, $query);

            if (!$result) {
                echo "Error fetching data: " . pg_last_error($db) . "\n";
                exit;
            }

            while ($row = pg_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['run']) . "</td>";
                echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
                echo "<td>" . htmlspecialchars($row['apellido_p']) . "</td>";
                echo "<td>" . htmlspecialchars($row['telefono']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email_personal']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email_institucional']) . "</td>";
                echo "<td>" . htmlspecialchars($row['dedicacion']) . "</td>";
                echo "<td>" . htmlspecialchars($row['contrato']) . "</td>";
                echo "<td>" . htmlspecialchars($row['diurno']) . "</td>";
                echo "<td>" . htmlspecialchars($row['vespertino']) . "</td>";
                echo "<td>" . htmlspecialchars($row['sede']) . "</td>";
                echo "<td>" . htmlspecialchars($row['carrera']) . "</td>";
                echo "<td>" . htmlspecialchars($row['grado_academico']) . "</td>";
                echo "<td>" . htmlspecialchars($row['jerarquia']) . "</td>";
                echo "<td>" . htmlspecialchars($row['cargo']) . "</td>";
                echo "<td>" . htmlspecialchars($row['estamento']) . "</td>";
                echo "</tr>";
            }

            pg_close($db);
            ?>
        </tbody>
    </table>
</div>

</body>
</html>