<?php

include 'load_data.php';

function validatePlan($plan) {
    return preg_match("/^[a-zA-Z0-9]+$/", $plan);
}

function validateSigla($sigla) {
    return preg_match("/^[a-zA-Z0-9]+$/", $sigla);
}

function validateNombre($nombre) {
    return preg_match("/^[A-Za-záéíóúÁÉÍÓÚñÑ\s.]+$/", $nombre);
}

function validateNivel($nivel) {
    return preg_match("/^\d+$/", $nivel);
}

function validatePrereq($prereq) {
    return empty($prereq) || preg_match("/^[A-Za-z0-9]*$/", $prereq);
}

function sanitizePrereq($prereqs) {
    $sanitized_prereqs = [];
    $unsanitized_prereqs = [];

    foreach ($prereqs as $prereq) {
        $valid_plan = validatePlan($prereq['plan']);
        $valid_sigla = validateSigla($prereq['sigla']);
        $valid_nombre = validateNombre($prereq['nombre']);
        $valid_nivel = validateNivel($prereq['nivel']);
        $valid_prereq1 = validatePrereq($prereq['prereq1']);
        $valid_prereq2 = validatePrereq($prereq['prereq2']);

        if ($valid_plan && $valid_sigla && $valid_nombre && $valid_nivel && $valid_prereq1 && $valid_prereq2) {
            $sanitized_prereqs[] = array(
                "plan" => $prereq["plan"],
                "sigla" => $prereq["sigla"],
                "nombre" => $prereq["nombre"],
                "nivel" => $prereq["nivel"],
                "prereq1" => $prereq["prereq1"],
                "prereq2" => $prereq["prereq2"],
            );
        } else {
            $errors = [];
            if (!$valid_plan) $errors[] = "Invalid plan: {$prereq['plan']}";
            if (!$valid_sigla) $errors[] = "Invalid sigla: {$prereq['sigla']}";
            if (!$valid_nombre) $errors[] = "Invalid nombre: {$prereq['nombre']}";
            if (!$valid_nivel) $errors[] = "Invalid nivel: {$prereq['nivel']}";
            if (!$valid_prereq1) $errors[] = "Invalid prereq1: {$prereq['prereq1']}";
            if (!$valid_prereq2) $errors[] = "Invalid prereq2: {$prereq['prereq2']}";
            $prereq['errors'] = $errors;
            $unsanitized_prereqs[] = $prereq;
        }
    }

    return [$sanitized_prereqs, $unsanitized_prereqs];
}

[$sanitized_prereqs, $unsanitized_prereqs] = sanitizePrereq($clean_prereq);

print_r($unsanitized_prereqs);
?>