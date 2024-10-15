<?php

$db = pg_connect("host=localhost port=5432 dbname=grupo80 user=grupo80 password=grupo80");

function getPeriods() {
    global $db;
    $result = pg_query($db, "SELECT DISTINCT periodo_asignatura FROM notas ORDER BY periodo_asignatura");
    $periods = [];
    while ($row = pg_fetch_assoc($result)) {
        $periods[] = $row['periodo_asignatura'];
    }
    return $periods;
}

function generateReport($period) {
    global $db;
    $query = "
        SELECT 
            codigo_asignatura, 
            asignatura, 
            COUNT(*) AS total_estudiantes,
            SUM(CASE WHEN nota >= 4.0 THEN 1 ELSE 0 END) AS total_aprobados,
            ROUND(SUM(CASE WHEN nota >= 4.0 THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 2) AS porcentaje_aprobacion
        FROM 
            notas
        WHERE 
            periodo_asignatura = $1
        GROUP BY 
            codigo_asignatura, asignatura
        ORDER BY 
            codigo_asignatura
    ";
    $result = pg_query_params($db, $query, array($period));

    echo "<h2>Reporte de Aprobación para el periodo: $period</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Código Asignatura</th><th>Asignatura</th><th>Total Estudiantes</th><th>Total Aprobados</th><th>Porcentaje de Aprobación</th></tr>";
    while ($row = pg_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>{$row['codigo_asignatura']}</td>";
        echo "<td>{$row['asignatura']}</td>";
        echo "<td>{$row['total_estudiantes']}</td>";
        echo "<td>{$row['total_aprobados']}</td>";
        echo "<td>{$row['porcentaje_aprobacion']}%</td>";
        echo "</tr>";
    }
    echo "</table>";
}


$periods = getPeriods();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['period'])) {
        $selectedPeriod = $_POST['period'];
        generateReport($selectedPeriod);
    } else {
        echo "Por favor, seleccione un periodo.";
    }
} else {
    echo '<form method="POST">';
    echo '<label for="period">Seleccione un periodo:</label>';
    echo '<select name="period" id="period">';
    foreach ($periods as $period) {
        echo "<option value=\"$period\">$period</option>";
    }
    echo '</select>';
    echo '<input type="submit" value="Generar Reporte">';
    echo '</form>';
}

?>