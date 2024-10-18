<?php

include 'load_data.php';

print_r($clean_docentes);

function validateRun($run) {
    if (is_numeric($run) && strlen($run) <= 8) {
        return true;
    } else {
        echo "Invalid data for student: RUN: {$run}\n";
        return false;
    }
}

function validateNombre($nombre) {
    if (preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s'-]+$/", $nombre)) {
        return true;
    } else {
        echo "Invalid data for student: Nombre: {$nombre}\n";
        return false;
    }
}

function validateTelefono($telefono) {
    if (preg_match("/^\d{9}$/", $telefono)) {
        return true;
    } else {
        echo "Invalid data for student: Telefono: {$telefono}\n";
        return false;
    }
}

function validateMail($mail) {
    if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        echo "Invalid data for student: Correo: {$mail}\n";
        return false;
    }
}

function validateDedicacion($dedicacion) {
    if (is_numeric($dedicacion) && $dedicacion >= 0 && $dedicacion <= 40) {
        return true;
    } else {
        echo "Invalid dedicacion: {$dedicacion}\n";
        return false;
    }
}
    

function validateContrato($contrato) {
    $valid_contratos = ["FULL TIME", "PART TIME", "HONORARIO"];
    if (in_array($contrato, $valid_contratos)) {
        return true;
    } else {
        echo "Invalid contrato: {$contrato}\n";
        return false;
    }
}

function validateJornada($jornada1, $jornada2) {
    $valid_jornadas = ["DIURNO", "VESPERTINO"];
    if (in_array($jornada1, $valid_jornadas) || in_array($jornada2, $valid_jornadas)) {
        return true;
    } else {
        echo "Invalid jornada: {$jornada1}, {$jornada2}\n";
        return false;
    }
}


function validateSede($sede){
    $valid_sedes = ["HOGWARTS", "BEAUXBATON", "UAGADOU"];
    if (in_array($sede, $valid_sedes)) {
        return true;
    } else {
        echo "Invalid sede: {$sede}\n";
        return false;
    }
}

function validateCarrera($carrera){
    if (preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $carrera)) {
        return true;
    } else {
        echo "Invalid carrera: {$carrera}\n";
        return false;
    }
}

function validateGrado($grado){
    $valid_grados = ["BACHILLER", "LICENCIADO", "MAGISTER", "DOCTOR"];
    if (in_array($grado, $valid_grados)) {
        return true;
    } else {
        echo "Invalid grado: {$grado}\n";
        return false;
    }
}

function validateJerarquia($jerarquia){
    $valid_jerarquías = ["ASISTENTE", "INSTRUCTOR", "INSTRUCTORA", "ASOCIADO", "ASOCIADA", "TITULAR", "SIN JERARQUIZAR", "COMISIÓN SUPERIOR"];
    $valid_tipo = ["DOCENTE", "REGULAR"];
    
    preg_split(" ", $jerarquia);
    if (in_array($jerarquia[0], $valid_jerarquías) && in_array($jerarquia[1], $valid_tipo)) {
        return true;
    } else {
        echo "Invalid jerarquia: {$jerarquia}\n";
        return false;
    }
}

function validateCargo($cargo){
    if (preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\.]+$/", $cargo)) {
        return true;
    } else {
        echo "Invalid cargo: {$cargo}\n";
        return false;
    }
}

function validateEstamento($estamento){
    $valid_estamentos = ["ACADÉMICO", "ADMINISTRATIVO"];
    if (in_array($estamento, $valid_estamentos)) {
        return true;
    } else {
        echo "Invalid estamento: {$estamento}\n";
        return false;
    }
}

function sanitizeDocentes($clean_docentes) {
    $sanitized_docentes = [];
    foreach ($clean_docentes as $docente) {
        $run_valid = validateRun($docente['run']);
        $nombre_valid = validateNombre($docente['nombre']);
        $telefono_valid = validateTelefono($docente['telefono']);
        $email_personal_valid = validateMail($docente['email_personal']);
        $email_institucional_valid = validateMail($docente['email_institucional']);
        $dedicacion_valid = validateDedicacion($docente['dedicacion']);
        $contrato_valid = validateContrato($docente['contrato']);
        $jornada_valid = validateJornada($docente['diurno'], $docente['vespertino']);
        $sede_valid = validateSede($docente['sede']);
        $carrera_valid = validateCarrera($docente['carrera']);
        $grado_valid = validateGrado($docente['grado']);
        $jerarquia_valid = validateJerarquia($docente['jerarquia']);
        $cargo_valid = validateCargo($docente['cargo']);
        $estamento_valid = validateEstamento($docente['estamento']);
        
        if (!$run_valid) {
            echo "Attempting to fix invalid RUN: {$docente['run']}\n";
        }
        if (!$nombre_valid) {
            echo "Attempting to fix invalid Nombre: {$docente['nombre']}\n";
        }
        if (!$telefono_valid) {
            echo "Attempting to fix invalid Telefono: {$docente['telefono']}\n";
        }
        if (!$email_personal_valid) {
            echo "Attempting to fix invalid Correo Personal: {$docente['email_personal']}\n";
        }
        if (!$email_institucional_valid) {
            echo "Attempting to fix invalid Correo Institucional: {$docente['email_institucional']}\n";
        }
        if (!$dedicacion_valid) {
            echo "Attempting to fix invalid Dedicacion: {$docente['dedicacion']}\n";
        }
        if (!$contrato_valid) {
            echo "Attempting to fix invalid Contrato: {$docente['contrato']}\n";
        }
        if (!$jornada_valid) {
            echo "Attempting to fix invalid Jornada: {$docente['diurno']}, {$docente['vespertino']}\n";
        }
        if (!$sede_valid) {
            echo "Attempting to fix invalid Sede: {$docente['sede']}\n";
        }
        if (!$carrera_valid) {
            echo "Attempting to fix invalid Carrera: {$docente['carrera']}\n";
        }
        if (!$grado_valid) {
            echo "Attempting to fix invalid Grado: {$docente['grado']}\n";
        }
        if (!$jerarquia_valid) {
            echo "Attempting to fix invalid Jerarquia: {$docente['jerarquia']}\n";
        }
        if (!$cargo_valid) {
            echo "Attempting to fix invalid Cargo: {$docente['cargo']}\n";
        }
        if (!$estamento_valid) {
            echo "Attempting to fix invalid Estamento: {$docente['estamento']}\n";
        }

        if ($run_valid && $nombre_valid && $telefono_valid && $email_personal_valid && $email_institucional_valid && $dedicacion_valid && $contrato_valid && $jornada_valid && $sede_valid && $carrera_valid && $grado_valid && $jerarquia_valid && $cargo_valid && $estamento_valid) {
            $sanitized_docentes[] = $docente;
        }else{
        $error = array();
        if(!$run_valid){
            $error[] = "RUN: {$docente['run']}";
        }
        if(!$nombre_valid){
            $error[] = "Nombre: {$docente['nombre']}";
        }
        if(!$telefono_valid){
            $error[] = "Telefono: {$docente['telefono']}";
        }
        if(!$email_personal_valid){
            $error[] = "Correo Personal: {$docente['email_personal']}";
        }
        if(!$email_institucional_valid){
            $error[] = "Correo Institucional: {$docente['email_institucional']}";
        }
        if(!$dedicacion_valid){
            $error[] = "Dedicacion: {$docente['dedicacion']}";
        }
        if(!$contrato_valid){
            $error[] = "Contrato: {$docente['contrato']}";
        }
        if(!$jornada_valid){
            $error[] = "Jornada: {$docente['diurno']}, {$docente['vespertino']}";
        }
        if(!$sede_valid){
            $error[] = "Sede: {$docente['sede']}";
        }
        if(!$carrera_valid){
            $error[] = "Carrera: {$docente['carrera']}";
        }
        if(!$grado_valid){
            $error[] = "Grado: {$docente['grado']}";
        }
        if(!$jerarquia_valid){
            $error[] = "Jerarquia: {$docente['jerarquia']}";
        }
        if(!$cargo_valid){
            $error[] = "Cargo: {$docente['cargo']}";
        }
        if(!$estamento_valid){
            $error[] = "Estamento: {$docente['estamento']}";
        }
        $docente['error'] = $error;
        $unsanitized_docentes[] = $docente;
        }

    }
    return [$sanitized_docentes, $unsanitized_docentes];
}

[$sanitized_docentes, $unsanitized_docentes] = sanitizeDocentes($clean_docentes);
print_r($unsanitized_docentes);
?>