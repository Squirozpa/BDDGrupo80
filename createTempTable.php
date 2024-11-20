<?php
$db = pg_connect("host=localhost port=5432 dbname=grupo80e3  user=grupo80e3 password=grupo80");

if (!$db) {
    echo "Error: Unable to open database.\n";
    exit;
}

$archivo = 'E3_notas.csv';

if (($handle = fopen($archivo, "r")) !== FALSE) {
    // Iniciar la transacción
    pg_query($db, "BEGIN");

    // Crear la tabla temporal
    $create_table_query = "CREATE TEMP TABLE acta (
        numero_alumno VARCHAR(50),
        run VARCHAR(50),
        sigla VARCHAR(10),
        seccion VARCHAR(10),
        periodo VARCHAR(10),
        nota VARCHAR(10)
    )";
    pg_query($db, $create_table_query);

    // Omitir la primera línea (cabecera)
    fgetcsv($handle, 1000, ";");

    $error = false;

    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
        $numero_alumno = pg_escape_string($db, $data[0]);
        $run = pg_escape_string($db, $data[1]);
        $sigla = pg_escape_string($db, $data[2]);
        $seccion = pg_escape_string($db, $data[3]);
        $periodo = pg_escape_string($db, $data[4]);
        $oportunidadDIC = pg_escape_string($db, $data[5]);
        $oportunidadMAR = pg_escape_string($db, $data[6]);

        if ($numero_alumno == "") {
            continue;
        } 

        if ($oportunidadDIC == "") {
            $oportunidadDIC = 0;
        } 
        if ($oportunidadMAR== "") {
            $oportunidadMAR = 0;
        } 

        if ($oportunidadDIC >= 4.0) {
            $nota_final = $oportunidadDIC;
        } else {
            if ($oportunidadMAR == 0) {
                $nota_final = $oportunidadDIC;
            } else {
                $nota_final = $oportunidadMAR;
            }
        }

        $nota_final = str_replace(",", ".", $nota_final);
        if ($nota_final == "NP" ) {
            $nota_final = 1.0;
        }
        if ($nota_final == "P") {
            $nota_final = 1.0;
        }

        // Insertar en la tabla temporal
        $insert_query = "INSERT INTO acta (numero_alumno, run, sigla, seccion, periodo, nota) VALUES ('$numero_alumno', '$run', '$sigla','$seccion', '$periodo', '$nota_final')";
        $result = pg_query($db, $insert_query);

        if (!$result) {
            echo "Error inserting data for numero_alumno: $numero_alumno - " . pg_last_error($db) . "\n";
            $error = true;
            break;
        }
    }

    fclose($handle);

    if ($error) {
        // Abortar la transacción
        pg_query($db, "ROLLBACK");
    } else {
        // Confirmar la transacción
        pg_query($db, "COMMIT");
        echo "Datos insertados correctamente en la tabla temporal 'acta'.\n";

        // Mostrar el contenido de la tabla temporal
        $query = pg_query($db, "SELECT crear_acta();");

        if (!$query) {
            echo "Error creating view: " . pg_last_error($db) . "\n";
        } else {
            // Mostrar el contenido de la vista
            $query_view = pg_query($db, "SELECT * FROM acta_notas");

            if (!$query_view) {
                echo "Error fetching data from acta_notas: " . pg_last_error($db) . "\n";
            } else {
                while ($row = pg_fetch_assoc($query_view)) {
                    echo "Numero Alumno: " . $row['numero_alumno'] . "\n";
                    echo "RUN: " . $row['run'] . "\n";
                    echo "Curso: " . $row['curso'] . "\n";
                    echo "Seccion: " . $row['seccion'] . "\n";
                    echo "Periodo: " . $row['periodo'] . "\n";
                    echo "Nota Final: " . $row['nota'] . "\n";
                    echo "-------------------------\n";
                }
            }
        }
    }
} else {
    echo "Error: Unable to open file $archivo.\n";
}

?>