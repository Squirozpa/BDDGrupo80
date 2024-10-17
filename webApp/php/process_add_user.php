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
        echo "<script>alert('La contraseña deben ser 8 alfanumericos.'); window.location.href='../views/add_user_interface.html';</script>";
        exit;
    }

    // Verificar si el usuario ya existe
    $new_user_query = "SELECT * FROM users WHERE email = $1";
    $new_user_result = pg_query_params($db, $new_user_query, array($email));

    $docente_valid_query = "SELECT email_institucional FROM docentes WHERE email_institucional = $1";
    $docente_valid_result = pg_query_params($db, $docente_valid_query, array($email));

    if (pg_num_rows($new_user_result) > 0) {
        echo "<script>alert('El usuario ya existe.'); window.location.href='../views/add_user_interface.html';</script>";
    } elseif(pg_num_rows($docente_valid_result) == 0){
        echo "<script>alert('El usuario no es parte de la institución.'); window.location.href='../views/add_user_interface.html';</script>";
    }
    else {
        // Insertar el nuevo usuario en la base de datos
        $query = "INSERT INTO users (email, password) VALUES ($1, $2)";
        $result = pg_query_params($db, $query, array($email, $password));

        if ($result) {
            echo "<script>alert('Usuario creado.'); window.location.href='../views/add_user_interface.html';</script>";
        } else {
            $error_message = pg_last_error($db);
            echo "<script>alert('Error al crear usuario: $error_message'); window.location.href='../views/add_user_interface.html';</script>";
            exit;
        }
    }
}
?>