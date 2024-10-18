<?php
include 'load_data.php';

function validateSigla($sigla) {
    if (preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s0-9]+$/", $sigla) === 1) {
        return true;
    } else {
        echo "Invalid data for course: Sigla: {$sigla}\n";
        return false;
    }
}

function validateNombre($nombre) {
    if (preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s'0-9:(),\-\.]+$/u", $nombre) === 1) {
        return true;
    } else {
        echo "Invalid data for course: Nombre: {$nombre}\n";
        return false;
    }
}

function validateNivel($nivel) {
    if (preg_match("/^[0-9]{1,2}$/", $nivel) === 1) {
        return true;
    } else {
        echo "Invalid data for course: Nivel: {$nivel}\n";
        return false;
    }
}

function validatePlan($plan) {
    if (preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s0-9]+$/", $plan) === 1) {
        return true;
    } else {
        echo "Invalid data for course: Plan: {$plan}\n";
        return false;
    }
}

function sanitizeAsignaturas($asignaturas) {
    $sanitized_asignaturas = array();
    foreach ($asignaturas as $asignatura) {
        $valid_plan = validatePlan($asignatura['plan']);
        $valid_codigo = validateSigla($asignatura['codigo']);
        $valid_nombre = validateNombre($asignatura['asignatura']);
        $valid_nivel = validateNivel($asignatura['nivel']);
        if (!$valid_plan) {
            echo "Attempting to fix invalid Plan: {$asignatura['plan']}\n";

        }
        if (!$valid_codigo) {
            echo "Attempting to fix invalid Codigo: {$asignatura['codigo']}\n";

        }
        if (!$valid_nombre) {
            echo "Attempting to fix invalid Nombre: {$asignatura['asignatura']}\n";

        }
        if (!$valid_nivel) {
            echo "Attempting to fix invalid Nivel: {$asignatura['nivel']}\n";

        }

        if ($valid_plan && $valid_codigo && $valid_nombre && $valid_nivel) {
            $sanitized_asignaturas[] = $asignatura;
        }
        else{
            echo "Invalid data for course: {$asignatura['plan']}, {$asignatura['codigo']}, {$asignatura['asignatura']}, {$asignatura['nivel']}\n";
            $unsanitized_asignaturas[] = $asignatura;
            $error = array();

            if(!$valid_plan){
                $error[] = "Plan: {$asignatura['plan']}";
            }
            if(!$valid_codigo){
                $error[] = "Codigo: {$asignatura['codigo']}";
            }
            if(!$valid_nombre){
                $error[] = "Nombre: {$asignatura['asignatura']}";
            }
            if(!$valid_nivel){
                $error[] = "Nivel: {$asignatura['nivel']}";
            }
            $unsanitized_asignaturas[count($unsanitized_asignaturas)-1]['error'] = $error;
        }

    }return [$sanitized_asignaturas, $unsanitized_asignaturas];


}
[$sanitized_asignaturas, $unsanitized_asignaturas] = sanitizeAsignaturas($clean_asignaturas);
print_r($unsanitized_asignaturas);

?>