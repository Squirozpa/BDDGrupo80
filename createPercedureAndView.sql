-- Function to create the view
CREATE OR REPLACE FUNCTION crear_acta()
RETURNS VOID AS $$
BEGIN
    -- Drop the view if it exists
    IF EXISTS (SELECT 1 FROM pg_views WHERE viewname = 'vista_acta') THEN
        DROP VIEW IF EXISTS vista_acta;
    END IF;

    -- Create the view
    CREATE VIEW vista_acta AS SELECT numero_alumno, run, curso, seccion, periodo, nota FROM acta;
END;
$$ LANGUAGE plpgsql;
