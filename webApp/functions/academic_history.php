<?php

$db = pg_connect("host=localhost port=5432 dbname=grupo80 user=grupo80 password=grupo80");

// Recibir el número de estudiante
$numero_alumno = $_GET['numero_alumno'] ?? null;

if ($numero_alumno) {
    // Consulta para obtener el historial académico del estudiante
    $query = "
        SELECT periodo_asignatura, asignatura, nota, calificacion
        FROM notas
        WHERE numero_alumno = $1
        ORDER BY periodo_asignatura ASC
    ";

    $result = pg_query_params($db, $query, array($numero_alumno));

    // Verificar si hay resultados
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
        echo "<h2>Historial Académico</h2>";
        foreach ($historial as $periodo => $datos) {
            $pps = $datos['cantidad_notas'] > 0 ? round($datos['suma_notas'] / $datos['cantidad_notas'], 2) : 0;
            echo "<h3>Período: $periodo</h3>";
            echo "<ul>";
            foreach ($datos['cursos'] as $curso) {
                echo "<li>{$curso['asignatura']} - Nota: {$curso['nota']} ({$curso['calificacion']})</li>";
            }
            echo "</ul>";
            echo "<p>Aprobados: {$datos['aprobados']}, Reprobados: {$datos['reprobados']}, Vigentes: {$datos['vigentes']}</p>";
            echo "<p>PPS: $pps</p>";
        }

        // Resumen total (PPA)
        $ppa = $resumen_total['cantidad_notas'] > 0 ? round($resumen_total['suma_notas'] / $resumen_total['cantidad_notas'], 2) : 0;
        echo "<h2>Resumen Total</h2>";
        echo "<p>Aprobados: {$resumen_total['aprobados']}, Reprobados: {$resumen_total['reprobados']}, Vigentes: {$resumen_total['vigentes']}</p>";
        echo "<p>PPA: $ppa</p>";

        // Estado del estudiante
        $estado = $resumen_total['vigentes'] > 0 ? 'Vigente' : ($resumen_total['aprobados'] > $resumen_total['reprobados'] ? 'De Término' : 'No Vigente');
        echo "<p>Estado del estudiante: $estado</p>";
    } else {
        echo "<p>No se encontró historial académico para el número de estudiante ingresado.</p>";
    }
} else {
    echo "<p>Por favor ingrese el número de estudiante.</p>";
}

?>
