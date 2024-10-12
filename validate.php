<?php
// Conexión a la base de datos
$db = pg_connect("host=localhost port=5432 dbname=grupo80 user=grupo80 password=grupo80");

if (!$db) {
    echo "Error: No se pudo conectar a la base de datos.\n";
    exit;
}

$email = $_POST['email'];
$password = $_POST['password'];

$query = "SELECT * FROM usuarios WHERE email = $email AND clave = $password";
$result = pg_query_params($db, $query, array($email, $password));

if (pg_num_rows($result) > 0) {
    echo "Acceso concedido. Bienvenido al sistema.";
    header("Location: menu.php");
} else {
    echo "ID o clave incorrectos. Intenta nuevamente.";
    echo '<a href="index.php">Volver a intentar</a>';
}

pg_close($db);
?>
