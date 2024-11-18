CREATE TRIGGER calcular_calificacion
BEFORE INSERT ON notas
FOR EACH ROW
BEGIN
    DECLARE calif VARCHAR(2);
    DECLARE resultado VARCHAR(20);
    
    -- Asignación de calificación y resultado basado en la nota
    IF NEW.nota >= 6.6 AND NEW.nota <= 7.0 THEN
        SET calif = 'SO';
        SET resultado = 'Aprobatorio';
    ELSEIF NEW.nota >= 6.0 AND NEW.nota < 6.6 THEN
        SET calif = 'MB';
        SET resultado = 'Aprobatorio';
    ELSEIF NEW.nota >= 5.0 AND NEW.nota < 6.0 THEN
        SET calif = 'B';
        SET resultado = 'Aprobatorio';
    ELSEIF NEW.nota >= 4.0 AND NEW.nota < 5.0 THEN
        SET calif = 'SU';
        SET resultado = 'Aprobatorio';
    ELSEIF NEW.nota >= 3.0 AND NEW.nota < 4.0 THEN
        SET calif = 'I';
        SET resultado = 'Reprobatorio';
    ELSEIF NEW.nota >= 2.0 AND NEW.nota < 3.0 THEN
        SET calif = 'M';
        SET resultado = 'Reprobatorio';
    ELSEIF NEW.nota >= 1.0 AND NEW.nota < 2.0 THEN
        SET calif = 'MM';
        SET resultado = 'Reprobatorio';
    ELSEIF NEW.nota IS NULL THEN
        -- Notas nulas con resultado según tipo de nota especial
        IF NEW.oportunidad = 'P' THEN
            SET calif = 'P';
            SET resultado = 'Curso Incompleto';
        ELSEIF NEW.oportunidad = 'NP' THEN
            SET calif = 'NP';
            SET resultado = 'Reprobatorio';
        ELSEIF NEW.oportunidad = 'EX' THEN
            SET calif = 'EX';
            SET resultado = 'Aprobatorio';
        ELSEIF NEW.oportunidad = 'A' THEN
            SET calif = 'A';
            SET resultado = 'Aprobatorio';
        ELSEIF NEW.oportunidad = 'R' THEN
            SET calif = 'R';
            SET resultado = 'Reprobatorio';
        ELSE
            SET calif = NULL;
            SET resultado = 'Curso Vigente en el período académico';
        END IF;
    END IF;

    -- Asignar calificación y resultado calculados al nuevo registro
    SET NEW.calificacion = calif;
    SET NEW.resultado = resultado;
END;
