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
        a.nota,
    FROM acta a
    LEFT JOIN estudiantes e ON a.numero_alumno = e.numero_alumno;
END;
$$ LANGUAGE plpgsql;