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
    $result = pg_query_params($db, "SELECT * FROM your_table WHERE period = $1", array($period));
    // Aquí va la lógica para generar el informe basado en el periodo seleccionado
}


$periods = getPeriods();


echo '<label for="period">Seleccione un periodo:</label>';
echo '<select name="period" id="period">';
foreach ($periods as $period) {
    echo "<option value=\"$period\">$period</option>";
}
echo '</select>';
echo '<input type="submit" value="Generar Reporte">';
echo '</form>';

?>