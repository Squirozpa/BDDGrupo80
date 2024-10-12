<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$db = pg_connect("host=localhost port=5432 dbname=grupo80 user=grupo80 password=grupo80");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validar que la contraseña tenga exactamente 8 caracteres alfanuméricos
    if (!preg_match('/^[a-zA-Z0-9]{8}$/', $password)) {
        echo "<script>alert('La contraseña deben ser 8 alfanumericos.'); window.location.href='add_user_interface.html';</script>";
        exit;
    }

    // Verificar si el usuario ya existe
    $check_query = "SELECT * FROM users WHERE email = $1";
    $check_result = pg_query_params($db, $check_query, array($email));

    if (pg_num_rows($check_result) > 0) {
        echo "<script>alert('User already exists.'); window.location.href='add_user_interface.html';</script>";
    } else {
        // Insertar el nuevo usuario en la base de datos
        $query = "INSERT INTO users (email, password) VALUES ($1, $2)";
        $result = pg_query_params($db, $query, array($email, $password));

        if ($result) {
            echo "<script>alert('User added successfully.'); window.location.href='add_user_interface.html';</script>";
        } else {
            $error_message = pg_last_error($db);
            echo "<script>alert('Error adding user: $error_message'); window.location.href='add_user_interface.html';</script>";
            exit;
        }
    }
}
?>