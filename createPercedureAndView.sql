-- Crear el Stored Procedure
CREATE OR REPLACE FUNCTION crear_acta()
RETURNS VOID AS $$
BEGIN
    -- Crear la Vista
    CREATE OR REPLACE VIEW acta_notas AS
    SELECT 
        a.numero_alumno,
        a.run,
        a.sigla AS curso,
        a.seccion,
        a.periodo,
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
    LEFT JOIN estudiantes e ON a.numero_alumno = e.numero_alumno
    LEFT JOIN profesores p ON a.sigla = p.sigla;
END;
$$ LANGUAGE plpgsql;