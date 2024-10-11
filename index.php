<?php

// Ejemplo que muestra como conectarse a la base de datos y ejecutar queries
// Recuerda que tienes que establecer una contraseña como indica el README
// Reemplaza `grupoX` y `contraseña` por el identificador de tu grupo y la
// contraseña que estableciste respectivamente.

echo "hola mundo!";

$db = pg_connect("host=localhost port=5432 dbname=grupoX user=grupoX password=contraseña");
$result = pg_query($db, "SELECT * FROM book");
$rows = pg_fetch_all($result);
echo json_encode($rows);

?>

