<?php
// Conexión a la base de datos
$db = pg_connect("host=localhost port=5432 dbname=grupo80 user=grupo80 password=grupo80");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capturar datos del formulario
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Consulta para verificar las credenciales
    $query = "SELECT * FROM usuarios WHERE email = $1 AND password = $2";
    $result = pg_query_params($db, $query, array($email, $password));
    if (!$db) {
        die("Error en la conexión a la base de datos.");
    }
    if (!$result) {
        die("Error en la consulta: " . pg_last_error($db));
    }
    if (pg_num_rows($result) > 0) {
        // Credenciales correctas
        echo "Login successful!";
    } else {
        // Credenciales incorrectas
        echo "Invalid email or password.";
    }
}
?>