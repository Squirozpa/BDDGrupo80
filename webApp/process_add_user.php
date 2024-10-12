<?php
$db = pg_connect("host=localhost port=5432 dbname=grupo80 user=grupo80 password=grupo80");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

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
            echo "<script>alert('Error adding user.'); window.location.href='add_user_interface.html';</script>";
        }
    }
}
?>