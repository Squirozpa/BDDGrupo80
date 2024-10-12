<?php
$db = pg_connect("host=localhost port=5432 dbname=grupo80 user=grupo80 password=grupo80");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email = $1 AND password = $2";
    $result = pg_query_params($db, $query, array($email, $password));

    if (pg_num_rows($result) > 0) {
        header("Location: menu.html");
        exit();
    } else {
        echo "<script>alert('Invalid email or password.'); window.location.href='login.html';</script>";
    }
}
?>