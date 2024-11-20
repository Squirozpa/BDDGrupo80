CREATE OR REPLACE FUNCTION crear_acta()
RETURNS VOID AS $$
BEGIN

    $query_view = pg_query($db, "SELECT * FROM acta");

    if (!$query_view) {
        echo "Error fetching data from acta_notas: " . pg_last_error($db) . "\n";
    } else {
        while ($row = pg_fetch_assoc($query_view)) {
                $nota = pg_fetch_assoc($nota);
                $nota = $nota['nota'];
                echo "Numero Alumno: " . $row['numero_alumno'] . "<br>";
                echo "RUN: " . $row['run'] . "<br>";
                echo "Curso: " . $row['curso'] . "<br>";
                echo "Seccion: " . $row['seccion'] . "<br>";
                echo "Periodo: " . $row['periodo'] . "<br>";
                echo "Nota Final: " . $row['nota'] . "<br>";
                echo "-------------------------<br>";
            
        }
    }
END;
$$ LANGUAGE plpgsql;
