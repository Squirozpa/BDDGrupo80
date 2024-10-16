<?php
    
$db = pg_connect("host=localhost port=5432 dbname=grupo80 user=grupo80 password=grupo80");

if ($db) {
    // Array to hold SQL queries
    $sqlQueries = [
        // Persona Table
        "CREATE TABLE Persona (
            id_persona SERIAL PRIMARY KEY,
            RUN VARCHAR(20),
            DV CHAR(1),
            Nombre VARCHAR(100),
            Estamento VARCHAR(50),
            Telefono VARCHAR(20),
            Correo VARCHAR(100)
        )",
        
                // Estudiante Table
        "CREATE TABLE Estudiante (
            numero_estudiante SERIAL PRIMARY KEY,
            cohorte VARCHAR(10),
            bloqueo BOOLEAN,
            logro TEXT,
            carga TEXT,
            id_persona INT REFERENCES Persona(id_persona)
        )",
        
        // Trabajador Table
        "CREATE TABLE Trabajador (
            id_persona INT REFERENCES Persona(id_persona),
            Contrato TEXT,
            PRIMARY KEY (id_persona)
        )",
        
        // Departamento Table
        "CREATE TABLE Departamento (
            codigo SERIAL PRIMARY KEY,
            nombre VARCHAR(100)
        )",
        
        // Carrera Table
        "CREATE TABLE Carrera (
            nombre VARCHAR(100) PRIMARY KEY
        )",
        
        // Plan de estudios Table
        "CREATE TABLE Plan_de_estudios (
            plan VARCHAR(50),
            modalidad VARCHAR(50),
            sede VARCHAR(50),
            departamento INT REFERENCES Departamento(codigo),
            PRIMARY KEY (plan)
        )",
        
        // Curso Table
        "CREATE TABLE Curso (
            id_curso SERIAL PRIMARY KEY,
            sigla VARCHAR(10),
            nombre VARCHAR(100),
            nivel VARCHAR(10),
            ciclo VARCHAR(10),
            convocatoria VARCHAR(20),
            convocatoria2 VARCHAR(20)
        )",
        
        // Nota Table (for Historial Academico)
        "CREATE TABLE Nota (
            calificacion DECIMAL(3, 2),
            descripcion TEXT,
            resultado TEXT,
            convocatoria VARCHAR(20),
            id_persona INT REFERENCES Persona(id_persona)
        )",
        
        // Grado Academico Table
        "CREATE TABLE Grado_academico (
            id_persona INT REFERENCES Trabajador(id_persona),
            Cargo VARCHAR(50),
            Jornada VARCHAR(50),
            PRIMARY KEY (id_persona)
        )",
        
        // Oferta academica Table
        "CREATE TABLE Oferta_academica (
            vacantes INT,
            sala VARCHAR(20),
            seccion VARCHAR(20),
            profesor INT REFERENCES Trabajador(id_persona),
            profesor_principal INT REFERENCES Trabajador(id_persona)
        )",
        
        // Cursos mínimos Table
        "CREATE TABLE Cursos_minimos (
            id_min SERIAL PRIMARY KEY,
            sigla VARCHAR(10),
            tipo VARCHAR(20),
            duracion INT
        )",
        
        // Prerequisitos Table
        "CREATE TABLE Prerequisitos (
            sigla VARCHAR(10),
            ciclo VARCHAR(10),
            PRIMARY KEY (sigla, ciclo)
        )",
        
        // Equivalentes Table
        "CREATE TABLE Equivalentes (
            sigla VARCHAR(10),
            ciclo VARCHAR(10),
            PRIMARY KEY (sigla, ciclo)
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