<?php
$db = pg_connect("host=localhost port=5432 dbname=grupo80e3 user=grupo80e3 password=grupo80");

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
        oportunidad1 VARCHAR(10),
        oportunidad2 VARCHAR(10)
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

        // Validar la nota
        if (!preg_match('/^(?:[1-7](?:\.[0-9])?|P|NP|EX|A|R|nulo)$/', $nota_final)) {
            echo "Nota de $numero_alumno contiene un valor erróneo, corríjalo manualmente en el archivo de origen y vuelva a cargar.\n";
            $error = true;
            break;
        }

        // Insertar en la tabla temporal
        $insert_query = "INSERT INTO acta (numero_alumno, run, sigla, seccion, periodo, nota) VALUES ('$numero_alumno', $'run', '$sigla',$'seccion', '$periodo', '$nota_final')";
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
        $query_view = "SELECT * FROM acta";
        $result_view = pg_query($db, $query_view);

        if (!$result_view) {
            echo "Error fetching data from acta: " . pg_last_error($db) . "\n";
        } else {
            while ($row = pg_fetch_assoc($result_view)) {
                echo "Numero Alumno: " . $row['numero_alumno'] . "\n";
                echo "RUN: " . $row['run'] . "\n";
                echo "Sigla: " . $row['sigla'] . "\n";
                echo "Seccion: " . $row['seccion'] . "\n";
                echo "Periodo: " . $row['periodo'] . "\n";
                echo "Nota: " . $row['nota'] . "\n";
                echo "-------------------------\n";
            }
        }
    }
} else {
    echo "Error: Unable to open file $archivo.\n";
}

?>