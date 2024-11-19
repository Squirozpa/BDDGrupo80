<!DOCTYPE html>
<html>
<head>
  <title>Consultas</title>
</head>
<body>
  <form action="view_consultas.php" method="post">
    <label for="A">A (Columnas):</label>
    <input type="text" name="A" id="A" placeholder="Ej: columna1,columna2" required>
    <label for="T">T (Tabla):</label>
    <input type="text" name="T" id="T" placeholder="Ej: tabla1" required>
    <label for="C">C (Condición):</label>
    <input type="text" name="C" id="C" placeholder="Ej: columna1 > 10" required>
    <input type="submit" value="Consultar">
  </form>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lista de tablas válidas
    $allowedTables = ['administrativo', 'asignaturas', 'curso', 'docentes', 'estudiantes', 'imparte', 'inscripcion', 'notas', 'persona', 'planeacion', 'planes', 'prerequisitos', 'users'];

    // Entrada del usuario
    $A = $_POST["A"];
    $T = $_POST["T"];
    $C = $_POST["C"];

    // Validación de la tabla
    if (!in_array($T, $allowedTables)) {
        die("Error: La tabla ingresada no es válida.");
    }

    // Conexión a PostgreSQL
    $db = pg_connect("host=localhost port=5432 dbname=grupo80e3 user=grupo80e3 password=grupo80");
    if (!$db) {
        die("Error: No se pudo conectar a la base de datos.");
    }

    // Construir consulta
    $query = "SELECT $A FROM $T WHERE $C";

    // Ejecutar consulta
    $result = pg_query($db, $query);

    if (!$result) {
        // Captura de errores del DBMS
        echo "Error en la consulta: " . htmlspecialchars(pg_last_error($db));
    } else {
        // Mostrar resultados
        echo "<table border='1'>";
        while ($row = pg_fetch_assoc($result)) {
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td>" . htmlspecialchars($value) . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }

    // Cerrar conexión
    pg_close($db);
}
?>
