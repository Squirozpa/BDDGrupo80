<?php
include 'load_data.php';
ini_set('memory_limit', '20248M');

print_r($clean_planeacion);

function fixFecha($fecha){
    if (preg_match('/^\d{4}-\d{2}$/', $fecha)) {
        $fecha = $fecha;
        $year = substr($fecha, 0, 4);
        $semester = (int)substr($fecha, 5, 2); 
        if ($semester >= 1 && $semester <= 2) {
            $fecha = $year . '-' . $semester;
        } else {
            echo "Invalid semester: {$semester}\n";
        }
    }
    return $fecha;
}

function validatePeriodo($periodo){
    if (preg_match("/^[0-9]{4}-[0-9]{1}$/", $periodo)) {
        return true;
    } else {
        return false;
    }
}


function validateSede($sede){
    $valid_sede = ["BEAUXBATON", "HOGWARTS"];
    if (in_array($sede, $valid_sede)) {
        return true;
    } else {
        return false;
    }
}

function validateFacultad($facultad){
    if (preg_match("/^[A-Za-záéíóúÁÉÍÓÚñÑ\s]+$/", $facultad)) {
        return true;
    } else {
        return false;
    }

}

function validateCodigoDepartamento($codigo_departamento){
    if (preg_match("/^[0-9]+$/", $codigo_departamento)) {
        return true;
    } else {
        return false;
    }
}

function validateDepartamento($departamento){
    if (preg_match("/^[A-Za-záéíóúÁÉÍÓÚñÑ\s]+$/", $departamento)) {
        return true;
    } else {
        return false;
    }
}

function validateIdAsignatura($id_asignatura){
    if (preg_match("/^[a-zA-Z0-9]+$/", $id_asignatura)) {
        return true;
    } else {
        return false;
    }
}

function validateAsignatura($asignatura){
    if (preg_match("/^[A-Za-záéíóúÁÉÍÓÚñÑ\s:.]+$/", $asignatura)) {
        return true;
    } else {
        return false;
    }
}

function validateSeccion($seccion){
    if (preg_match("/^[A-Za-z0-9]+$/", $seccion)) {
        return true;
    } else {
        return false;
    }
}


function validateDuración($duracion){
    $valid_duracion = ["S", "A", "I"];
    if (in_array($duracion, $valid_duracion)) {
        return true;
    } else {
        return false;
    }
}

function validateJornada($jornada){
    $valid_jornada = ["Diurno", "Vespertino"];
    if (in_array($jornada, $valid_jornada)) {
        return true;
    } else {
        return false;
    }
}

function validateCupo($cupo){
    if (preg_match("/^[0-9]+$/", $cupo)) {
        return true;
    } else {
        return false;
    }
}

function validateInscritos($inscritos, $cupo){
    if ($inscritos <= $cupo) {
        return true;
    } else {
        return false;
    }
}

function validateDia($dia){
    $valid_dia = ["lunes", "martes", "miércoles", "jueves", "viernes", "sábado", "domingo"];
    if (in_array($dia, $valid_dia)) {
        return true;
    } else {
        return false;
    }
}

function validateHora($hora){
    if (preg_match("/^[0-9]{2}:[0-9]{2}$/", $hora)) {
        return true;
    } else {
        return false;
    }
}

function validateFecha($fecha) {

    if (preg_match("/^\d{2}\/\d{2}\/\d{2}$/", $fecha)) {
        $date = DateTime::createFromFormat('d/m/y', $fecha);

        if ($date && $date->format('d/m/y') === $fecha) {
            return true;
        }
    }
    echo "Invalid date: {$fecha}\n";
    return false;

}

function validateLugar($lugar){
    if (preg_match("/^[A-Za-záéíóúÁÉÍÓÚñÑ\s()0-9]+$/", $lugar)) {
        return true;
    } else {
        return false;
    }
}

function validateEdificio($edificio){
    if (preg_match("/^[A-Za-záéíóúÁÉÍÓÚñÑ\s\-]+$/", $edificio)) {
        return true;
    } else {
        return false;
    }
}

function validateProfesorPrincipal($profesor_principal){
    $valid_profesor_p = ["S", ""];
    if (in_array($profesor_principal, $valid_profesor_p)) {
        return true;
    } else {
        return false;
    }
}

function validateRun($run){
    if (preg_match("/^[0-9]+$/", $run)) {
        return true;
    } else {
        return false;
    }
}

function validateNombre($nombre){
    if (preg_match("/^[A-Za-záéíóúÁÉÍÓÚñÑ\s\-]+$/", $nombre)) {
        return true;
    } else {
        return false;
    }
}

function validateJerarquizacion($jerarquizacion){
    if (preg_match("/^[0-9a-zA-ZáéíóúÁÉÍÓÚ]+$/", $jerarquizacion)) {
        return true;
    } else {
        return false;
    }
}

function sanitizePlaneacion($clean_planeacion){
    $sanitized_planeacion = array();
    foreach ($clean_planeacion as $planeacion) {
        $planeacion['nombre_profesor'] = preg_replace("/^[A-Za-záéíóúÁÉÍÓÚñÑ\s\-]", "", $planeacion['nombre_profesor']);
        $planeacion['periodo'] = fixFecha($planeacion['periodo']);
        $planeacion['sede']= strtoupper($planeacion['sede']);
        $valid_periodo = validatePeriodo($planeacion['periodo']);
        $valid_sede = validateSede($planeacion['sede']);
        $valid_facultad = validateFacultad($planeacion['facultad']);
        $valid_codigo_departamento = validateCodigoDepartamento($planeacion['codigo_departamento']);
        $valid_departamento = validateDepartamento($planeacion['departamento']);
        $valid_id_asignatura = validateIdAsignatura($planeacion['id_asignatura']);
        $valid_asignatura = validateAsignatura($planeacion['asignatura']);
        $valid_seccion = validateSeccion($planeacion['seccion']);
        $valid_duracion = validateDuración($planeacion['duracion']);
        $valid_jornada = validateJornada($planeacion['jornada']);
        $valid_cupo = validateCupo($planeacion['cupo']);
        $valid_inscritos = validateInscritos($planeacion['inscritos'], $planeacion['cupo']);
        $valid_dia = validateDia($planeacion['dia']);
        $valid_hora_inicio = validateHora($planeacion['hora_inicio']);
        $valid_hora_termino = validateHora($planeacion['hora_termino']);
        $valid_fecha_inicio = validateFecha($planeacion['fecha_inicio']);
        $valid_fecha_termino = validateFecha($planeacion['fecha_termino']);
        $valid_lugar = validateLugar($planeacion['lugar']);
        $valid_edificio = validateEdificio($planeacion['edificio']);
        $valid_profesor_principal = validateProfesorPrincipal($planeacion['profesor_principal']);
        $valid_run = validateRun($planeacion['run']);
        $valid_nombre = validateNombre($planeacion['nombre_profesor']);
        $valid_jerarquizacion = validateJerarquizacion($planeacion['jerarquizacion']);

        if (!$valid_nombre) {
            if ($planeacion['nombre_profesor'] == "") {
                $planeacion['nombre_profesor'] = "POR DESIGNAR";
                $valid_nombre = true;
                $planeacion['run'] = $planeacion['codigo_departamento'];
                $valid_run = validateRun($planeacion['run']);
                $valid_codigo_departamento = validateCodigoDepartamento($planeacion['codigo_departamento']);
            } elseif (strpos($planeacion['nombre_profesor'], "POR DESIGNAR") !== false) {
                $planeacion['nombre_profesor'] = "POR DESIGNAR";
                $valid_nombre = true;
                $planeacion['run'] = $planeacion['codigo_departamento'];
                $valid_run = validateRun($planeacion['run']);
                $valid_codigo_departamento = validateCodigoDepartamento($planeacion['codigo_departamento']);
            }
        }
        if ($valid_periodo && $valid_sede && $valid_facultad && $valid_codigo_departamento && $valid_departamento && $valid_id_asignatura && $valid_asignatura && $valid_seccion && $valid_duracion && $valid_jornada && $valid_cupo && $valid_inscritos && $valid_dia && $valid_hora_inicio && $valid_hora_termino && $valid_fecha_inicio && $valid_fecha_termino && $valid_lugar && $valid_edificio && $valid_profesor_principal && $valid_run && $valid_nombre && $valid_jerarquizacion) {
            $sanitized_planeacion[] = $planeacion;
        }else{
            $errors = array();
            if (!$valid_periodo) {
                $errors[] = "Invalid periodo: {$planeacion['periodo']}";
            }
            if (!$valid_sede) {
                $errors[] = "Invalid sede: {$planeacion['sede']}";
            }
            if (!$valid_facultad) {
                $errors[] = "Invalid facultad: {$planeacion['facultad']}";
            }
            if (!$valid_codigo_departamento) {
                $errors[] = "Invalid codigo_departamento: {$planeacion['codigo_departamento']}";
            }
            if (!$valid_departamento) {
                $errors[] = "Invalid departamento: {$planeacion['departamento']}";
            }
            if (!$valid_id_asignatura) {
                $errors[] = "Invalid id_asignatura: {$planeacion['id_asignatura']}";
            }
            if (!$valid_asignatura) {
                $errors[] = "Invalid asignatura: {$planeacion['asignatura']}";
            }
            if (!$valid_seccion) {
                $errors[] = "Invalid seccion: {$planeacion['seccion']}";
            }
            if (!$valid_duracion) {
                $errors[] = "Invalid duracion: {$planeacion['duracion']}";
            }
            if (!$valid_jornada) {
                $errors[] = "Invalid jornada: {$planeacion['jornada']}";
            }
            if (!$valid_cupo) {
                $errors[] = "Invalid cupo: {$planeacion['cupo']}";
            }
            if (!$valid_inscritos) {
                $errors[] = "Invalid inscritos: {$planeacion['inscritos']}";
            }
            if (!$valid_dia) {
                $errors[] = "Invalid dia: {$planeacion['dia']}";
            }
            if (!$valid_hora_inicio) {
                $errors[] = "Invalid hora_inicio: {$planeacion['hora_inicio']}";
            }
            if (!$valid_hora_termino) {
                $errors[] = "Invalid hora_termino: {$planeacion['hora_termino']}";
            }
            if (!$valid_fecha_inicio) {
                $errors[] = "Invalid fecha_inicio: {$planeacion['fecha_inicio']}";
            }
            if (!$valid_fecha_termino) {
                $errors[] = "Invalid fecha_termino: {$planeacion['fecha_termino']}";
            }
            if (!$valid_lugar) {
                $errors[] = "Invalid lugar: {$planeacion['lugar']}";
            }
            if (!$valid_edificio) {
                $errors[] = "Invalid edificio: {$planeacion['edificio']}";
            }
            if (!$valid_profesor_principal) {
                $errors[] = "Invalid profesor_principal: {$planeacion['profesor_principal']}";
            }
            if (!$valid_run) {
                $errors[] = "Invalid run: {$planeacion['run']}";
            }
            if (!$valid_nombre) {
                $errors[] = "Invalid nombre: {$planeacion['nombre_profesor']}";
            }
            if (!$valid_jerarquizacion) {
                $errors[] = "Invalid jerarquizacion: {$planeacion['jerarquizacion']}";
            }
            $planeacion['errors'] = $errors;
            $unsanitized_planeacion[] = $planeacion;
        }
    } return[$sanitized_planeacion, $unsanitized_planeacion];
}

[$sanitized_planeacion, $unsanitized_planeacion] = sanitizePlaneacion($clean_planeacion);

print_r(array_slice($unsanitized_planeacion, 0, 10));
print(count($unsanitized_planeacion)."\n");
print(count($sanitized_planeacion));

?>