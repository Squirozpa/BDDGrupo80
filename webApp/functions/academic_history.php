<!DOCTYPE html>
<html>
<head>
    <title>Historial Académico</title>
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
            margin: 5px 0;
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

<?php
function generateReport($numero_alumno){
    $db = pg_connect("host=localhost port=5432 dbname=grupo80 user=grupo80 password=grupo80");

    if (!$db) {
        echo "<p>Error: Unable to open database.</p>";
        exit;
    }

    if ($numero_alumno) {
        // Consulta para obtener el historial académico del estudiante
        $query = "
            SELECT periodo_asignatura, asignatura, nota, calificacion
            FROM notas
            WHERE numero_alumno = $1
            ORDER BY periodo_asignatura ASC
        ";

        $result = pg_query_params($db, $query, array($numero_alumno));

        if (!$result) {
            echo "<p>Error fetching data: " . pg_last_error($db) . "</p>";
            exit;
        }

        if (pg_num_rows($result) > 0) {
            $historial = [];
            $resumen_total = [
                'aprobados' => 0,
                'reprobados' => 0,
                'vigentes' => 0,
                'suma_notas' => 0,
                'cantidad_notas' => 0
            ];

            while ($row = pg_fetch_assoc($result)) {
                $periodo = $row['periodo_asignatura'];
                $nota = $row['nota'];
                $calificacion = $row['calificacion'];
                $primer_nombre = $row['nombres'];
                $primer_apellido = $row['apellido_paterno'];

                if (!isset($historial[$periodo])) {
                    $historial[$periodo] = [
                        'cursos' => [],
                        'aprobados' => 0,
                        'reprobados' => 0,
                        'vigentes' => 0,
                        'suma_notas' => 0,
                        'cantidad_notas' => 0
                    ];
                }

                $historial[$periodo]['cursos'][] = $row;
                $historial[$periodo]['suma_notas'] += $nota;
                $historial[$periodo]['cantidad_notas'] += 1;

                // Clasificar y contar los cursos según la calificación
                if ($calificacion === 'Aprobado') {
                    $historial[$periodo]['aprobados']++;
                    $resumen_total['aprobados']++;
                } elseif ($calificacion === 'Reprobado') {
                    $historial[$periodo]['reprobados']++;
                    $resumen_total['reprobados']++;
                } else {
                    $historial[$periodo]['vigentes']++;
                    $resumen_total['vigentes']++;
                }

                $resumen_total['suma_notas'] += $nota;
                $resumen_total['cantidad_notas'] += 1;
            }

            // Mostrar el historial
            echo "<h2>Historial Académico de: {$primer_nombre} {$primer_apellido}</h2>";
            foreach ($historial as $periodo => $datos) {
                $pps = $datos['cantidad_notas'] > 0 ? round($datos['suma_notas'] / $datos['cantidad_notas'], 2) : 0;
                echo "<h3>Período: $periodo</h3>";
                echo "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse; width: 100%;'>";
                echo "<thead>";
                echo "<tr>";
                echo "<th>Asignatura</th>";
                echo "<th>Nota</th>";
                echo "<th>Calificación</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                foreach ($datos['cursos'] as $curso) {
                    echo "<tr>";
                    echo "<td>{$curso['asignatura']}</td>";
                    echo "<td>{$curso['nota']}</td>";
                    echo "<td>{$curso['calificacion']}</td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table>";
                echo "<p>PPS: $pps</p>";
}

            // Resumen total (PPA)
            $ppa = $resumen_total['cantidad_notas'] > 0 ? round($resumen_total['suma_notas'] / $resumen_total['cantidad_notas'], 2) : 0;
            echo "<div class='summary'>";
            echo "<p>PPA: $ppa</p>";

            echo "</div>";
        } else {
            echo "<p>No se encontró historial académico para el número de estudiante ingresado.</p>";
        }
    } else {
        echo "<p>Por favor ingrese el número de estudiante.</p>";
    }
    pg_close($db);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['student_number'])) {
        $studentNumber = $_POST['student_number'];
        generateReport($studentNumber);
    } else {
        echo "Por favor, proporcione un número de alumno.";
    }
} else {
    echo '<form method="POST">';
    echo '<label for="student_number">Número de Alumno:</label>';
    echo '<input type="text" name="student_number" id="student_number" required>';
    echo '<input type="submit" value="Generar Reporte">';
    echo '</form>';
}

?>

</body>
</html>