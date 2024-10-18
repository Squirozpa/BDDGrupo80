<?php
include 'load_data.php';

function convertFechaLogro($fecha_logro) {

    $date = DateTime::createFromFormat('Y-m-d', $fecha_logro);
    if (!$date) {
        return false; 
    }

    $year = (int)$date->format('Y');
    $month = (int)$date->format('m');


    if ($month >= 1 && $month <= 4) {
    
        $semester = 1;
        $year--;
    } elseif ($month >= 5 && $month <= 7) {
    
        $semester = 1;
    } else {
    
        $semester = 2;
    }


    return "{$year}-{$semester}";
}

function validateRun($run) {

    return is_numeric($run) && strlen($run) <= 8;
}

function validateDv($dv) {
    return (strlen($dv) == 1) && (ctype_digit($dv) || strtoupper($dv) == 'K');
}

function validateNombre($nombre) {

    if (preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s'-]+$/", $nombre)) {
        return true;
    } else {
        echo "Invalid data for student: Nombre: {$nombre}\n";
        return false;
    }
}

function validateLogro($logro) {
    if ($logro == 'INGRESO' || $logro == "LICENCIATURA") {
        return true;
    } elseif(is_numeric($logro) && $logro >= 1 && $logro <= 10) {
        return true;
    } else {
        echo "Invalid data for student: Logro: {$logro}\n";
        return false;
    }
}

function validateFecha($fecha) {
    if ($fecha == 'INGRESO') {
        return true;
    } elseif (preg_match('/^\d{4}-[1-2]$/', $fecha)) {
        return true;
    } else {
        echo "Invalid data for student: Fecha: {$fecha}\n";
        return false;
    }

}

function fixFecha($fecha){
    if (preg_match('/^\d{4}-\d{2}$/', $fecha)) {
        $fecha = $fecha;
        $year = substr($fecha, 0, 4);
        $semester = (int)substr($fecha, 5, 2); // Convert semester to integer to remove leading zero
        if ($semester >= 1 && $semester <= 2) {
            $fecha = $year . '-' . $semester; // Convert semester back to string
        } else {
            echo "Invalid semester: {$semester}\n";
        }
    }
    return $fecha;
}

function sanitizeAlumnos($clean_alumnos) {
    $sanitized_alumnos = [];

    foreach ($clean_alumnos as $data) {
        $run_valid = validateRun($data['run']);
        $dv_valid = validateDv($data['dv']);
        $nombre_valid = validateNombre($data['nombre']);
        $logro_valid = validateLogro($data['logro']);
        $fecha_logro_valid = validateFecha($data['fecha_logro']);
        $ultima_carga_valid = validateFecha($data['ultima_carga']);

    
        if (!$run_valid) {
            echo "Attempting to fix invalid RUN: {$data['run']}\n";
        
        }

        if (!$dv_valid) {
            echo "Attempting to fix invalid DV: {$data['dv']}\n";
        
        }

        if (!$nombre_valid) {
            echo "Attempting to fix invalid Nombre: {$data['nombre']}\n";
        
        }

        if (!$logro_valid) {
            echo "Attempting to fix invalid Logro: {$data['logro']}\n";
            if (strpos($data['logro'], 'LICENCIATURA') !== false) {
                $data['logro'] = 'LICENCIATURA';
                $logro_valid = validateLogro($data['logro']);
            }
            if (preg_match('/\d+/', $data['logro'], $matches)) {
                $data['logro'] = (int)$matches[0];
                $logro_valid = validateLogro($data['logro']);
            }
            if ($logro_valid) {
                echo "Fixed Logro: {$data['logro']}\n";
            }
        }

        if (!$fecha_logro_valid) {
            echo "Attempting to fix invalid Fecha Logro: {$data['fecha_logro']}\n";
            if (preg_match('/^\d{4}-\d{2}$/', $data['fecha_logro'])) {
                $fecha = fixFecha($data['fecha_logro']);
                $data['fecha_logro'] = $fecha;
                $fecha_logro_valid = validateFecha($data['fecha_logro']);
            }
            if ($fecha_logro_valid) {
                echo "Fixed Fecha Logro: {$data['fecha_logro']}\n";
            }
        }

        if (!$ultima_carga_valid) {
            echo "Attempting to fix invalid Ultima Carga: {$data['ultima_carga']}\n";
            if ($data['logro'] == 'INGRESO') {
                $data['ultima_carga'] = $data['fecha_logro'];
                $ultima_carga_valid = validateFecha($data['ultima_carga']);
            } elseif (preg_match('/^\d{4}-\d{2}$/', $data['ultima_carga'])) {
                $fecha = fixFecha($data['ultima_carga']);
                    $data['ultima_carga'] = $fecha;
                    $ultima_carga_valid = validateFecha($data['ultima_carga']);
            }
            if ($ultima_carga_valid) {
                echo "Fixed Ultima Carga: {$data['ultima_carga']}\n";
            }
        }

    
        if ($run_valid && $dv_valid && $nombre_valid && $logro_valid && $fecha_logro_valid && $ultima_carga_valid) {
            $sanitized_alumnos[] = array(
                "codigo_plan" => $data['codigo_plan'],
                "carrera" => $data['carrera'],
                "cohorte" => $data['cohorte'],
                "numero_alumno" => $data['numero_alumno'],
                "bloqueo" => $data['bloqueo'],
                "causal_bloqueo" => $data['causal_bloqueo'],
                "run" => $data['run'],
                "dv" => $data['dv'],
                "nombre" => $data['nombre'],
                "logro" => $data['logro'],
                "fecha_logro" => $data['fecha_logro'],
                "ultima_carga" => $data['ultima_carga'],
            );
        } else {
            $unsanitized_alumnos[] = $data;
            $errors = array(); // Initialize an array to store errors for this student

            if (!$run_valid) {
                $errors[] = "Invalid RUN: {$data['run']}";
            }
            if (!$dv_valid) {
                $errors[] = "Invalid DV: {$data['dv']}";
            }
            if (!$nombre_valid) {
                $errors[] = "Invalid Nombre: {$data['nombre']}";
            }
            if (!$logro_valid) {
                $errors[] = "Invalid Logro: {$data['logro']}";
            }
            if (!$fecha_logro_valid) {
                $errors[] = "Invalid Fecha Logro: {$data['fecha_logro']}";
            }
            if (!$ultima_carga_valid) {
                $errors[] = "Invalid Ultima Carga: {$data['ultima_carga']}";
            }

            $unsanitized_alumnos[count($unsanitized_alumnos) - 1]['errors'] = $errors;
            echo "Invalid data for student: RUN: {$data['run']}, Nombre: {$data['nombre']}\n";
        }
    }
    return [$sanitized_alumnos, $unsanitized_alumnos];
}

[$sanitized_alumnos, $unsanitized_alumnos] = (sanitizeAlumnos($clean_alumnos));
print_r($unsanitized_alumnos);
echo "Original length: " . count($clean_alumnos) ." ". "converted to: " . count($sanitized_alumnos) . "\n";
?>