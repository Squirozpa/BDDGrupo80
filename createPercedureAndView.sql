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
        a.nota
    FROM acta a;
END;
$$ LANGUAGE plpgsql;
PERFORM 'Instancias de acta:';
PERFORM a.numero_alumno, a.run, a.sigla, a.seccion, a.periodo, a.nota
FROM acta a;