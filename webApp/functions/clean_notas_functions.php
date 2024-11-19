<?php
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
?>