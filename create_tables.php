<?php
require('load_prerequisitos.php');
require('load_planeaciones.php');
require('load_asignaturas.php');
require('load_docentes.php');
require('load_estudiantes.php');
require('load_notas.php');
require('load_planes.php');
$db = pg_connect("host=localhost port=5432 dbname=grupo80 user=grupo80 password=grupo80");

if ($db) {
    // Array to hold SQL queries
    $sqlQueries = [
        "CREATE TABLE estudiantes (
            id_estudiante SERIAL PRIMARY KEY,
            codigo_plan VARCHAR(20),
            carrera VARCHAR(100),
            cohorte VARCHAR(10),
            numero_alumno VARCHAR(20),
            bloqueo BOOLEAN,
            causal_bloqueo VARCHAR(255),
            run VARCHAR(20),
            dv CHAR(1),
            primer_nombre VARCHAR(100),
            segundo_nombre VARCHAR(100),
            primer_apellido VARCHAR(100),
            segundo_apellido VARCHAR(100),
            logro VARCHAR(200),
            fecha_logro VARCHAR(10),
            ultima_carga VARCHAR(10)
        );",
        
        "CREATE TABLE docentes (
            id_docente SERIAL PRIMARY KEY,
            RUN VARCHAR(20),
            Nombre VARCHAR(100),
            Apellido_P VARCHAR(100),
            telefono VARCHAR(20),
            email_personal VARCHAR(100),
            email_institucional VARCHAR(100),
            DEDICACION VARCHAR(20),
            CONTRATO VARCHAR(20),
            DIURNO BOOLEAN,
            VESPERTINO BOOLEAN,
            SEDE VARCHAR(50),
            CARRERA VARCHAR(100),
            GRADO_ACADEMICO VARCHAR(50),
            JERARQUIA VARCHAR(50),
            CARGO VARCHAR(50),
            ESTAMENTO VARCHAR(50)
        );",
        
        "CREATE TABLE planes (
            codigo_plan VARCHAR(20) PRIMARY KEY,
            facultad VARCHAR(255),
            carrera VARCHAR(255),
            plan VARCHAR(255),
            jornada VARCHAR(50),
            sede VARCHAR(100),
            grado VARCHAR(50),
            modalidad VARCHAR(50),
            inicio_vigencia VARCHAR(50),
        )",

        "CREATE TABLE planeacion (
            periodo VARCHAR(10),
            sede VARCHAR(100),
            facultad VARCHAR(255),
            codigo_depto VARCHAR(20),
            departamento VARCHAR(255),
            id_asignatura VARCHAR(20),
            asignatura VARCHAR(255),
            seccion INT,
            duracion CHAR(1),
            jornada VARCHAR(50),
            cupo INT,
            inscritos INT,
            dia VARCHAR(20),
            hora_inicio VARCHAR(50),
            hora_fin VARCHAR(50),
            fecha_inicio VARCHAR(50),
            fecha_fin VARCHAR(50),
            lugar VARCHAR(100),
            edificio VARCHAR(100),
            profesor_principal CHAR(1),
            run VARCHAR(20),
            nombre_docente VARCHAR(100),
            primer_apellido_docente VARCHAR(100),
            segundo_apellido_docente VARCHAR(100),
            jerarquizacion CHAR(1)
        )",

        "CREATE TABLE prerequisitos (
            plan VARCHAR(20),
            asignatura_id VARCHAR(20),
            asignatura VARCHAR(255),
            nivel INT,
            prerequisito VARCHAR(255)
        )",
        
        "CREATE TABLE notas (
            id_nota SERIAL PRIMARY KEY,
            codigo_plan VARCHAR(20),
            plan VARCHAR(255),
            cohorte VARCHAR(10),
            sede VARCHAR(50),
            run VARCHAR(20),
            dv CHAR(1),
            nombres VARCHAR(100),
            apellido_paterno VARCHAR(100),
            apellido_materno VARCHAR(100),
            numero_alumno VARCHAR(20),
            periodo_asignatura VARCHAR(10),
            codigo_asignatura VARCHAR(20),
            asignatura VARCHAR(255),
            convocatoria VARCHAR(10),
            calificacion VARCHAR(10),
            nota NUMERIC(3, 1)
        )",
        
        "CREATE TABLE asignaturas (
            plan VARCHAR(20),
            asignatura_id VARCHAR(20) PRIMARY KEY,
            asignatura VARCHAR(255),
            nivel INT
        )",

    ];

    // Execute each query
    foreach ($sqlQueries as $query) {
        $result = pg_query($db, $query);
        if (!$result) {
            echo "<script>alert('Error creating table: " . pg_last_error($db) . "'); window.location.href='../views/error.html';</script>";
            exit();
        }
    }

    echo "<script>alert('Tables created successfully.'); window.location.href='../views/success.html';</script>";
} else {
    echo "<script>alert('Error connecting to the database.'); window.location.href='../views/error.html';</script>";
}


cargar_prerequisitos("E2_prereq.csv");
cargar_planeacion("E2_planeacion.csv");
cargar_asignaturas("E2_asignaturas.csv");
cargar_docentes("E2_docentes.csv");
cargar_estudiantes("E2_estudiantes.csv");
cargar_notas("E2_notas.csv");
cargar_planes("E2_planes.csv");
?>