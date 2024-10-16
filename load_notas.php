<?php

function cargar_docentes($archivo) {
    $db = pg_connect("host=localhost port=5432 dbname=grupo80 user=grupo80 password=grupo80");

    if (!$db) {
        echo "Error: Unable to open database.\n";
        exit;
    }

    if (($handle = fopen($archivo, "r")) !== FALSE) {
        // Omitir la primera línea (cabecera)
        fgetcsv($handle, 1000, ",");

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $RUN = pg_escape_string($db, $data[0]);
            $Nombre = pg_escape_string($db, $data[1]);
            $Apellido_P = pg_escape_string($db, $data[2]);
            $telefono = pg_escape_string($db, $data[3]);
            $email_personal = pg_escape_string($db, $data[4]);
            $email_institucional = pg_escape_string($db, $data[5]);
            $DEDICACION = pg_escape_string($db, $data[6]);
            $CONTRATO = pg_escape_string($db, $data[7]);
            $DIURNO = pg_escape_string($db, $data[8]) == 'TRUE' ? 'TRUE' : 'FALSE';
            $VESPERTINO = pg_escape_string($db, $data[9]) == 'TRUE' ? 'TRUE' : 'FALSE';
            $SEDE = pg_escape_string($db, $data[10]);
            $CARRERA = pg_escape_string($db, $data[11]);
            $GRADO_ACADEMICO = pg_escape_string($db, $data[12]);
            $JERARQUIA = pg_escape_string($db, $data[13]);
            $CARGO = pg_escape_string($db, $data[14]);
            $ESTAMENTO = pg_escape_string($db, $data[15]);

            $query = "INSERT INTO docentes (RUN, Nombre, Apellido_P, telefono, email_personal, email_institucional, DEDICACION, CONTRATO, DIURNO, VESPERTINO, SEDE, CARRERA, GRADO_ACADEMICO, JERARQUIA, CARGO, ESTAMENTO) 
                      VALUES ('$RUN', '$Nombre', '$Apellido_P', '$telefono', '$email_personal', '$email_institucional', '$DEDICACION', '$CONTRATO', '$DIURNO', '$VESPERTINO', '$SEDE', '$CARRERA', '$GRADO_ACADEMICO', '$JERARQUIA', '$CARGO', '$ESTAMENTO')";
            $result = pg_query($db, $query);

            if (!$result) {
                echo "Error inserting data into docentes: " . pg_last_error($db) . "\n";
                file_put_contents("errores.log", pg_last_error($db) . "\n", FILE_APPEND);
            }
        }
        fclose($handle);
    } else {
        echo "Error: Unable to open file $archivo.\n";
    }

    pg_close($db);
}


cargar_docentes("E2_docentes.csv");

?>