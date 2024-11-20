CREATE OR REPLACE FUNCTION crear_acta()
RETURNS VOID AS $$
BEGIN

    CREATE OR REPLACE VIEW acta_notas AS
    SELECT 
        numero_alumno,
        run,
        sigla AS curso,
        seccion,
        periodo,
        nota,
        calificacion
    FROM acta
END;
$$ LANGUAGE plpgsql;
