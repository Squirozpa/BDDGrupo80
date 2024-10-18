<?php
include 'load_data.php';
ini_set('memory_limit', '20248M');

$clean_planes = array_filter($clean_planes, function($plan) {
    foreach ($plan as $value) {
        if (!empty($value)) {
            return true;
        }
    }
    return false;
});
function validateCodigoPlan($codigo_plan) {
    return preg_match("/^[a-zA-Z0-9]+$/", $codigo_plan);
}

function validateFacultad($facultad) {
    return preg_match("/^[A-Za-záéíóúÁÉÍÓÚñÑ\s]+$/", $facultad);
}

function validateCarrera($carrera) {
    return preg_match("/^[A-Za-záéíóúÁÉÍÓÚñÑ\s,()\-]+$/", $carrera);
}

function validatePlan($plan) {
    return preg_match("/^[A-Za-záéíóúÁÉÍÓÚñÑ0-9\s\()\-]+$/", $plan);
}

function validateJornada($jornada) {
    return preg_match("/^(Diurno|Vespertino|Nocturno)$/", $jornada);
}

function validateSede($sede) {
    return preg_match("/^[A-Za-záéíóúÁÉÍÓÚñÑ\s]+$/", $sede);
}

function validateGrado($grado) {
    return preg_match("/^(Pregrado|Postgrado)$/", $grado);
}

function validateModalidad($modalidad) {
    return preg_match("/^(Presencial|OnLine)$/", $modalidad);
}

function validateInicio($inicio) {
    return preg_match("/^\d{2}\/\d{2}\/\d{2}$/", $inicio);
}


function sanitizePlanes($planes) {

    foreach ($planes as $plan) {
        $valid_codigo_plan = validateCodigoPlan($plan['codigo_plan']);
        $valid_facultad = validateFacultad($plan['facultad']);
        $valid_carrera = validateCarrera($plan['carrera']);
        $valid_plan = validatePlan($plan['plan']);
        $valid_jornada = validateJornada($plan['jornada']);
        $valid_sede = validateSede($plan['sede']);
        $valid_grado = validateGrado($plan['grado']);
        $valid_modalidad = validateModalidad($plan['modalidad']);
        $valid_inicio = validateInicio($plan['inicio']);

        if ($valid_codigo_plan && $valid_facultad && $valid_carrera && $valid_plan && $valid_jornada && $valid_sede && $valid_grado && $valid_modalidad && $valid_inicio) {
            $sanitized_planes[] = array(
                "codigo_plan" => $plan["codigo_plan"],
                "facultad" => $plan["facultad"],
                "carrera" => $plan["carrera"],
                "plan" => $plan["plan"],
                "jornada" => $plan["jornada"],
                "sede" => $plan["sede"],
                "grado" => $plan["grado"],
                "modalidad" => $plan["modalidad"],
                "inicio" => $plan["inicio"],
            );
        } else {
            $errors = array();
            if (!$valid_codigo_plan) $errors[] = "Invalid codigo_plan: {$plan['codigo_plan']}";
            if (!$valid_facultad) $errors[] = "Invalid facultad: {$plan['facultad']}";
            if (!$valid_carrera) $errors[] = "Invalid carrera: {$plan['carrera']}";
            if (!$valid_plan) $errors[] = "Invalid plan: {$plan['plan']}";
            if (!$valid_jornada) $errors[] = "Invalid jornada: {$plan['jornada']}";
            if (!$valid_sede) $errors[] = "Invalid sede: {$plan['sede']}";
            if (!$valid_grado) $errors[] = "Invalid grado: {$plan['grado']}";
            if (!$valid_modalidad) $errors[] = "Invalid modalidad: {$plan['modalidad']}";
            if (!$valid_inicio) $errors[] = "Invalid inicio: {$plan['inicio']}";
            $plan['errors'] = $errors;
            $unsanitized_planes[] = $plan;
        }
    }

    return [$sanitized_planes, $unsanitized_planes];
}

[$sanitized_planes, $unsanitized_planes] = sanitizePlanes($clean_planes);
print_r($unsanitized_planes);
print(count($unsanitized_planes)."\n");
print(count($sanitized_planes));
?>
