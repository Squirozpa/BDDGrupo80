<?php
// Datos de ejemplo (en la práctica, estos datos vendrían de una base de datos)
$valid_email = "usuario@institucion.cl";
$valid_password = "12345678";

// Obtener los datos del formulario
$email = $_POST['email'];
$password = $_POST['password'];

// Validar email y clave
if ($email === $valid_email && $password === $valid_password) {
    echo "Acceso concedido. Bienvenido al sistema.";
    // Aquí redireccionarías o mostrarías el menú de opciones
    // header("Location: menu.php");
} else {
    echo "ID o clave incorrectos. Intenta nuevamente.";
    // Podrías agregar un enlace para volver al formulario de login
}
?>