CREATE OR REPLACE FUNCTION
mostrar_acta(archivo TEXT)
RETURNS VOID AS $$
DECLARE
    numero_alumno VARCHAR(50);
    curso VARCHAR(10);
    periodo VARCHAR(10);
    nombre_est VARCHAR(10);
    nombre_prof VARCHAR(10);
    record RECORD;
BEGIN
    -- Iniciar la transacción
    BEGIN;

    -- Crear la tabla temporal
    CREATE TEMP TABLE acta (
        numero_alumno VARCHAR(50),
        sigla VARCHAR(10),
        oportunidad VARCHAR(10),
        nota VARCHAR(10)
    );

    -- Leer el archivo CSV
    FOR record IN EXECUTE 'COPY (SELECT * FROM ' || quote_ident(archivo) || ') TO STDOUT WITH CSV HEADER DELIMITER '';'''
    LOOP
        numero_alumno := record.numero_alumno;
        sigla := record.sigla;
        oportunidad := record.oportunidad;
        nota := record.nota;

        -- Validar la nota
        IF nota !~ '^(?:[1-7](?:\.[0-9])?|P|NP|EX|A|R|nulo)$' THEN
            RAISE EXCEPTION 'Nota de % contiene un valor erróneo, corríjalo manualmente en el archivo de origen y vuelva a cargar.', numero_alumno;
        END IF;

        -- Insertar en la tabla temporal
        EXECUTE 'INSERT INTO acta (numero_alumno, sigla, oportunidad, nota) VALUES ($1, $2, $3, $4)'
        USING numero_alumno, sigla, oportunidad, nota;
    END LOOP;

    -- Confirmar la transacción
    COMMIT;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE VIEW acta_notas AS
SELECT 
    a.numero_alumno,
    a.sigla AS curso,
    a.oportunidad AS periodo,
    e.nombre AS nombre_estudiante,
    p.nombre AS nombre_profesor,
    CASE
        WHEN a.nota BETWEEN '6.6' AND '7.0' THEN 'SO'
        WHEN a.nota BETWEEN '6.0' AND '6.5' THEN 'MB'
        WHEN a.nota BETWEEN '5.0' AND '5.9' THEN 'B'
        WHEN a.nota BETWEEN '4.0' AND '4.9' THEN 'SU'
        WHEN a.nota BETWEEN '3.0' AND '3.9' THEN 'I'
        WHEN a.nota BETWEEN '2.0' AND '2.9' THEN 'M'
        WHEN a.nota BETWEEN '1.0' AND '1.9' THEN 'MM'
        WHEN a.nota = 'P' THEN 'Nota Pendiente'
        WHEN a.nota = 'NP' THEN 'No se Presenta'
        WHEN a.nota = 'EX' THEN 'Eximido'
        WHEN a.nota = 'A' THEN 'Aprobado'
        WHEN a.nota = 'R' THEN 'Reprobado'
        ELSE 'Curso Vigente'
    END AS nota_final
FROM acta a
JOIN estudiantes e ON a.numero_alumno = e.numero_alumno
JOIN profesores p ON a.sigla = p.sigla;