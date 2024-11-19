<?php

include 'load_data.php';
ini_set('memory_limit', '20248M');

function validateCodigoPlan($codigo_plan){
    if (preg_match("/^[a-zA-Z0-9]+$/", $codigo_plan)) {
        return true;
    } else {
        return false;
    }

}

function validatePlan($plan){
    if (preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s0-9()]+$/", $plan)) {
        return true;
    } else {
        return false;
    }
}

function validateCohorte($cohorte){
    if (preg_match("/^[0-9]{4}$/", $cohorte)) {
        return true;
    } else {
        return false;
    }
}

function validateSede($sede){
    if (preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $sede)) {
        return true;
    } else {
        return false;
    }
}

function validateRun($run){
    if (is_numeric($run) && strlen($run) <= 8) {
        return true;
    } else {
        return false;
    }
}

function validateDv($dv){
    if (strlen($dv) == 1 && (ctype_digit($dv) || $dv == 'K')) {
        return true;
    } else {
        return false;
    }
}

function validateNombre($nombre){
    if (preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s'-]+$/", $nombre)) {
        return true;
    } else {
        return false;
    }
}

function validateNumeroAlumno($numero_alumno){
    if (preg_match("/^[0-9]+$/", $numero_alumno)) {
        return true;
    } else {
        return false;
    }
}

function validateFecha($periodo){
    if (preg_match("/^[0-9]{4}-[0-2]{1}$/", $periodo)) {
        return true;
    } else {
        return false;
    }
}

function validateCodigoAsignatura($codigo_asignatura){
    if (preg_match("/^[a-zA-Z0-9]+$/", $codigo_asignatura)) {
        return true;
    } else {
        return false;
    }
}

function validateAsignatura($asignatura){
    if (preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s0-9()]+$/", trim($asignatura))) {
        return true;
    } else {
        return false;
    }
}

function validateCalificacion($calificacion){
    $valid_calificaciones = ["SO", "MB", "B", "R", "I", "SU", "M", "MM", "P", "NP", "EX", "A", "R", ""];
    if (in_array($calificacion, $valid_calificaciones)) {
        return true;
    } else {
        return false;
    }
}

function validateNota($nota){
    if (is_numeric($nota) && $nota >= 1 && $nota <= 7) {
        return true;
    } elseif( $nota == ""){
        return true;
    } else {
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

function sanitizeNotas($clean_notas){
    $sanitized_notas = array();
    foreach ($clean_notas as $nota) {
        $nota['cohorte'] = fixFecha($nota['cohorte']);
        $nota['periodo'] = fixFecha($nota['periodo']);
        $valid_codigo_plan = validateCodigoPlan($nota['codigo_plan']);
        $valid_plan = validatePlan($nota['plan']);
        $valid_cohorte = validateFecha($nota['cohorte']);
        $valid_sede = validateSede($nota['sede']);
        $valid_run = validateRun($nota['run']);
        $valid_dv = validateDv($nota['dv']);
        $valid_nombre = validateNombre($nota['nombre']);
        $valid_numero_alumno = validateNumeroAlumno($nota['numero_alumno']);
        $valid_periodo = validateFecha($nota['periodo']);
        $valid_codigo_asignatura = validateCodigoAsignatura($nota['codigo_asignatura']);
        $valid_asignatura = validateAsignatura($nota['asignatura']);
        $valid_calificacion = validateCalificacion($nota['calificacion']);
        $valid_nota = validateNota($nota['nota']);
        if ($valid_codigo_plan && $valid_plan && $valid_cohorte && $valid_sede && $valid_run && $valid_dv && $valid_nombre && $valid_numero_alumno && $valid_periodo && $valid_codigo_asignatura && $valid_asignatura && $valid_calificacion && $valid_nota) {
            $sanitized_notas[] = $nota;
        }
        else{

            $errors = array();
            if(!$valid_codigo_plan){
                $errors[] = "Codigo Plan: {$nota['codigo_plan']}";
            }
            if(!$valid_plan){
                $errors[] = "Plan: {$nota['plan']}";
            }
            if(!$valid_cohorte){
                $errors[] = "Cohorte: {$nota['cohorte']}";
                
            }
            if(!$valid_sede){
                $errors[] = "Sede: {$nota['sede']}";
            }
            if(!$valid_run){
                $errors[] = "RUN: {$nota['run']}";
            }
            if(!$valid_dv){
                $errors[] = "DV: {$nota['dv']}";
            }
            if(!$valid_nombre){
                $errors[] = "Nombre: {$nota['nombre']}";
            }
            if(!$valid_numero_alumno){
                $errors[] = "Numero Alumno: {$nota['numero_alumno']}";
            }
            if(!$valid_periodo){
                $errors[] = "Periodo: {$nota['periodo']}";
            }
            if(!$valid_codigo_asignatura){
                $errors[] = "Codigo Asignatura: {$nota['codigo_asignatura']}";
            }
            if(!$valid_asignatura){
                $errors[] = "Asignatura: {$nota['asignatura']}";
            }
            if(!$valid_calificacion){
                $errors[] = "Calificacion: {$nota['calificacion']}";
            }
            if(!$valid_nota){
                $errors[] = "Nota: {$nota['nota']}";
            }
            $nota['errors'] = $errors;
            $unsanitized_notas[] = $nota;

        }
    }
    return [$sanitized_notas, $unsanitized_notas];

}

[$sanitized_notas, $unsanitized_notas] = sanitizeNotas($clean_notas);
print_r(array_slice($unsanitized_notas, 0, 10));
print(count($unsanitized_notas)."\n");
print(count($sanitized_notas));
?>