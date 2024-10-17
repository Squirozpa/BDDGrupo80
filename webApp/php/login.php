<?php
$db = pg_connect("host=localhost port=5432 dbname=grupo80 user=grupo80 password=grupo80");
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Verificar si el usuario es "bananer@lamejor.com" con clave "bananer0"
    if ($email === 'bananer@lamejor.com' && $password === 'bananer0') {
        // Redirigir a la interfaz de añadir usuarios
        header("Location: ../views/add_user_interface.html");
        exit();
    }

    $user_query = "SELECT * FROM users WHERE email = $1";
    $user_result = pg_query_params($db, $user_query, array($email));
    
    if (pg_num_rows($user_result) > 0) {
        $user = pg_fetch_assoc($user_result);
        $hashed_password = $user['password'];
    
        if (password_verify($password, $hashed_password)) {
            echo "<script>alert('Inicio de sesión exitoso.'); window.location.href='../views/dashboard.html';</script>";
        } else {
            echo "<script>alert('Contraseña incorrecta.'); window.location.href='../views/login.html';</script>";
        }
    } else {
        echo "<script>alert('email incorrecto.'); window.location.href='../views/login.html';</script>";
    }
}
?>