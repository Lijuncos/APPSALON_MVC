<?php

$db = mysqli_connect($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_BD']);
// reemplazamos los valores que habiamos puesto para iniciar la BD utilizando la superglobal que viene con el composer de vlucas
// y con $_ENV hacemos referencias a los datos que pusimos en el archivo .env
// y hacemos referencia a esos archivos porque en app.php le pusimos la ruta a ese archivo

if (!$db) {
    echo "Error: No se pudo conectar a MySQL.";
    echo "errno de depuración: " . mysqli_connect_errno();
    echo "error de depuración: " . mysqli_connect_error();
    exit;
}
