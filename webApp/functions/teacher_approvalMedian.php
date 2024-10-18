<?php

// Conexión a la base de datos
$db = pg_connect("host=localhost port=5432 dbname=grupo80 user=grupo80 password=grupo80");

// Recibir el código del curso como parámetro
$codigo_asignatura = $_GET['codigo_asignatura'] ?? null;

if ($codigo_asignatura) {
    // Consulta para obtener el porcentaje de aprobación agrupado por profesor
    $query = "
        SELECT d.run, d.nombre, d.apellido_p, d.apellido_m, 
               COUNT(n.nota) AS total_estudiantes, 
               SUM(CASE WHEN n.calificacion = 'Aprobado' THEN 1 ELSE 0 END) AS aprobados,
               ROUND((SUM(CASE WHEN n.calificacion = 'Aprobado' THEN 1 ELSE 0 END) * 100.0) / COUNT(n.nota), 2) AS porcentaje_aprobacion
        FROM notas n
        JOIN planeacion p ON p.id_asignatura = n.codigo_asignatura
        JOIN docentes d ON d.run = p.run
        WHERE n.codigo_asignatura = $1
        GROUP BY d.run, d.nombre, d.apellido_p, d.apellido_m
        ORDER BY porcentaje_aprobacion DESC
    ";

    // Ejecutar la consulta
    $result = pg_query_params($db, $query, array($codigo_asignatura));

    // Verificar si hay resultados
    if (pg_num_rows($result) > 0) {
        echo "<h2>Porcentaje de Aprobación Histórico por Profesor para la Asignatura: $codigo_asignatura</h2>";
        echo "<table border='1'>
                <tr>
                    <th>Profesor</th>
                    <th>Total Estudiantes</th>
                    <th>Estudiantes Aprobados</th>
                    <th>Porcentaje de Aprobación</th>
                </tr>";

        // Iterar sobre los resultados y mostrar los datos en una tabla
        while ($row = pg_fetch_assoc($result)) {
            $nombre_completo = "{$row['nombre']} {$row['apellido_p']} {$row['apellido_m']}";
            echo "<tr>
                    <td>{$nombre_completo}</td>
                    <td>{$row['total_estudiantes']}</td>
                    <td>{$row['aprobados']}</td>
                    <td>{$row['porcentaje_aprobacion']}%</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No se encontraron datos de aprobación para el curso con código $codigo_asignatura.</p>";
    }
} else {
    echo "<p>Por favor ingrese el código del curso.</p>";
}

?>
