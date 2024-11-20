CREATE OR REPLACE FUNCTION crear_acta()
RETURNS VOID AS $$
BEGIN

    CREATE OR REPLACE VIEW acta_notas AS
    SELECT 
        a.numero_alumno,
        a.run,
        a.sigla AS curso,
        a.seccion,
        a.periodo,
        a.nota,
        a.calificacion
    FROM acta a

    PERFORM * FROM acta_notas;
END;
$$ LANGUAGE plpgsql;
