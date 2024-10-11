<!-- login.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
</head>
<body>
    <h2>Iniciar Sesión</h2>
    <form action="validate.php" method="post">
        <label for="email">Correo Institucional:</label>
        <input type="email" id="email" name="email" required><br><br>
        
        <label for="password">Clave (8 caracteres):</label>
        <input type="password" id="password" name="password" minlength="8" maxlength="8" required><br><br>
        
        <input type="submit" value="Iniciar Sesión">
    </form>
</body>
</html>
