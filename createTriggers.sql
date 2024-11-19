CREATE OR REPLACE FUNCTION calcular_calificacion() RETURNS TRIGGER AS $$
BEGIN
    IF NEW.nota IS NULL THEN
        NEW.calificacion := NULL;
    ELSIF NEW.nota >= 6.6 AND NEW.nota <= 7.0 THEN
        NEW.calificacion := 'SO'; -- Sobresaliente
    ELSIF NEW.nota >= 6.0 AND NEW.nota < 6.6 THEN
        NEW.calificacion := 'MB'; -- Muy Bueno
    ELSIF NEW.nota >= 5.0 AND NEW.nota < 6.0 THEN
        NEW.calificacion := 'B';  -- Bueno
    ELSIF NEW.nota >= 4.0 AND NEW.nota < 5.0 THEN
        NEW.calificacion := 'SU'; -- Suficiente
    ELSIF NEW.nota >= 3.0 AND NEW.nota < 4.0 THEN
        NEW.calificacion := 'I';  -- Insuficiente
    ELSIF NEW.nota >= 2.0 AND NEW.nota < 3.0 THEN
        NEW.calificacion := 'M';  -- Malo
    ELSIF NEW.nota >= 1.0 AND NEW.nota < 2.0 THEN
        NEW.calificacion := 'MM'; -- Muy Malo
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER set_calificacion
BEFORE INSERT ON notas
FOR EACH ROW
EXECUTE FUNCTION calcular_calificacion();
