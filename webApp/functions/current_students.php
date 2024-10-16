<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Estudiantes Vigentes</title>
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
    </style>
</head>
<body>

<h2>Reporte de Estudiantes Vigentes (2024-2)</h2>

<?php
$db = pg_connect("host=localhost port=5432 dbname=grupo80 user=grupo80 password=grupo80");

if (!$db) {
    echo "Error: Unable to open database.\n";
    exit;
}

$query = "
SELECT 
    e.numero_alumno,
    e.cohorte,
    e.ultimo_logro,
    CASE 
        WHEN e.cohorte = '2020-1' AND e.ultimo_logro = '9° semestre' THEN 'Dentro de nivel'
        ELSE 'Fuera de nivel'
    END AS nivel
FROM 
    estudiantes e
JOIN 
    inscripcion i ON e.numero_alumno = i.numero_alumno
WHERE 
    i.periodo = '2024-2';
";

$result = pg_query($db, $query);

if (!$result) {
    echo "Error fetching data: " . pg_last_error($db) . "\n";
    exit;
}

$dentro_nivel = 0;
$fuera_nivel = 0;

while ($row = pg_fetch_assoc($result)) {
    if ($row['nivel'] == 'Dentro de nivel') {
        $dentro_nivel++;
    } else {
        $fuera_nivel++;
    }
}

pg_close($db);
?>

<table>
    <thead>
        <tr>
            <th>Estado</th>
            <th>Cantidad</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Dentro de nivel</td>
            <td><?php echo $dentro_nivel; ?></td>
        </tr>
        <tr>
            <td>Fuera de nivel</td>
            <td><?php echo $fuera_nivel; ?></td>
        </tr>
    </tbody>
</table>

</body>
</html>