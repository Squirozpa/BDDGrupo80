CREATE TABLE acta (
    numero_alumno VARCHAR(20),
    run VARCHAR(20),
    sigla VARCHAR(20),
    seccion VARCHAR(20),
    periodo VARCHAR(20),
    nota VARCHAR(20),
    calificacion VARCHAR(20)
);

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
