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
FROM 
    estudiantes e
WHERE 
    e.ultima_carga = '2024-2';
";

$result = pg_query($db, $query);

if (!$result) {
    echo "Error fetching data: " . pg_last_error($db) . "\n";
    exit;
}

pg_close($db);
?>


</body>
</html>