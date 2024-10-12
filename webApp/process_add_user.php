<?php
$db = pg_connect("host=localhost port=5432 dbname=grupo80 user=grupo80 password=grupo80");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Insertar el nuevo usuario en la base de datos
    $query = "INSERT INTO users (email, password) VALUES ($1, $2)";
    $result = pg_query_params($db, $query, array($email, $password));

    if ($result) {
        echo "<script>alert('User added successfully.'); window.location.href='add_user_interface.php';</script>";
    } else {
        echo "<script>alert('Error adding user.'); window.location.href='add_user_interface.php';</script>";
    }
}
?>