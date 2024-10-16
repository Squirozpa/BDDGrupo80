<?php
    https://cursos.canvas.uc.cl/files/11120025/download?download_frd=1
$db = pg_connect("host=localhost port=5432 dbname=grupo80 user=grupo80 password=grupo80");

if ($db) {
    // Array to hold SQL queries
    $sqlQueries = [
        "CREATE TABLE Persona (
            id_persona SERIAL PRIMARY KEY,
            RUN VARCHAR(20),
            DV CHAR(1),
            Nombre VARCHAR(100),
            Estamento VARCHAR(50),
            Telefono VARCHAR(20),
            Correo VARCHAR(100)
        )",
        
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
        
        "CREATE TABLE Administrativo (
            id_administrativo SERIAL PRIMARY KEY,
            id_persona INT REFERENCES Persona(id_persona),
            cargo VARCHAR(50),
            jornada VARCHAR(20)
        )",
        
        "CREATE TABLE Plan (
            id_plan SERIAL PRIMARY KEY,
            codigo_plan VARCHAR(20) UNIQUE,
            nombre VARCHAR(100),
            facultad VARCHAR(100),
            jornada VARCHAR(20),
            modalidad VARCHAR(20),
            sede VARCHAR(20),
            grado VARCHAR(50),
            fecha_inicio_vigencia DATE
        )",
        
        "CREATE TABLE Curso (
            id_curso SERIAL PRIMARY KEY,
            sigla VARCHAR(10),
            nombre VARCHAR(100),
            nivel INT,
            caracter VARCHAR(20),
            departamento VARCHAR(100)
        )",
        
        "CREATE TABLE Inscripcion (
            id_inscripcion SERIAL PRIMARY KEY,
            id_estudiante INT REFERENCES estudiantes(id_estudiante),
            id_curso INT REFERENCES Curso(id_curso),
            fecha_inscripcion DATE
        )",
        
        "CREATE TABLE Imparte (
            id_imparte SERIAL PRIMARY KEY,
            id_profesor INT REFERENCES docentes(id_docente),
            id_curso INT REFERENCES Curso(id_curso),
            semestre VARCHAR(10)
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
        
        "CREATE TABLE Oferta_academica (
            id_oferta SERIAL PRIMARY KEY,
            vacantes INT,
            sala VARCHAR(20),
            seccion VARCHAR(20),
            id_profesor INT REFERENCES docentes(id_docente),
            id_profesor_principal INT REFERENCES docentes(id_docente)
        )"
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

?>