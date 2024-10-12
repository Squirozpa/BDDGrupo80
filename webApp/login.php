<?php
$db = pg_connect("host=localhost port=5432 dbname=grupo80 user=grupo80 password=grupo80");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Verificar si el usuario es "bananer@lamejor.com" con clave "bananer0"
    if ($email === 'bananer@lamejor.com' && $password === 'bananer0') {
        // Redirigir a la interfaz de añadir usuarios
        header("Location: add_user_interface.html");
        exit();
    }

    // Consulta para verificar las credenciales en la base de datos
    $query = "SELECT * FROM users WHERE email = $1 AND password = $2";
    $result = pg_query_params($db, $query, array($email, $password));

    if (pg_num_rows($result) > 0) {
        // Credenciales correctas, redirigir al menú principal
        header("Location: menu_principal.html");
        exit();
    } else {
        // Credenciales incorrectas, mostrar alerta
        echo "<script>alert('Invalid email or password.'); window.location.href='login.html';</script>";
    }
}
?>