CREATE TRIGGER calcular_calificacion
BEFORE INSERT ON notas
FOR EACH ROW
BEGIN
    DECLARE calif VARCHAR(2);
    DECLARE resultado VARCHAR(20);
    
    -- Asignación de calificación y resultado basado en la nota
    IF NEW.nota >= 6.6 AND NEW.nota <= 7.0 THEN
        SET calif = 'SO';
    ELSEIF NEW.nota >= 6.0 AND NEW.nota < 6.6 THEN
        SET calif = 'MB';
    ELSEIF NEW.nota >= 5.0 AND NEW.nota < 6.0 THEN
        SET calif = 'B';
    ELSEIF NEW.nota >= 4.0 AND NEW.nota < 5.0 THEN
        SET calif = 'SU';
    ELSEIF NEW.nota >= 3.0 AND NEW.nota < 4.0 THEN
        SET calif = 'I';
    ELSEIF NEW.nota >= 2.0 AND NEW.nota < 3.0 THEN
        SET calif = 'M';
    ELSEIF NEW.nota >= 1.0 AND NEW.nota < 2.0 THEN
        SET calif = 'MM';
    ELSEIF NEW.nota IS NULL THEN
        -- Notas nulas con resultado según tipo de nota especial
        IF NEW.oportunidad = 'P' THEN
            SET calif = 'P';
        ELSEIF NEW.oportunidad = 'NP' THEN
            SET calif = 'NP';
        ELSEIF NEW.oportunidad = 'EX' THEN
            SET calif = 'EX';
        ELSEIF NEW.oportunidad = 'A' THEN
            SET calif = 'A';
        ELSEIF NEW.oportunidad = 'R' THEN
            SET calif = 'R';
        ELSE
            SET calif = NULL;
        END IF;
    END IF;

    -- Asignar calificación y resultado calculados al nuevo registro
    SET NEW.calificacion = calif;
    SET NEW.resultado = resultado;
END;
