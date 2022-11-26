<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

// esto es para identificar cuando estamos en el ultimo servicio y poder sacar el total de los servicios para que aparezcan al final de cada cita a modo de resumen
function esUltimo(string $actual, string $proximo): bool {

    if($actual !== $proximo) { // si $actual es dif a $proximo retorna true
        return true;
    }
    return false; // caso contrario retorna false
}

// Funcion que revisa que el usuario este autenticado

function isAuth() : void {
    if(!isset($_SESSION['login'])) { // si no esta definida esta variable 'login' en $_SESSION lo redireccionamos a la pagina principal
        header('Location: /');
    }// en LoginController al inicial sesion el usuario le asignamos $_SESSION['login'] = true;
}

function isAdmin() : void {
    if(!isset($_SESSION['admin'])) { // el admin en $_SESSION se lo agregamos en LoginController, si tenia un 1 en admin en la BD le agregabamos el atributo admin a $_SESSION
        header('Location: /'); // en caso de que no tenga admin en $_SESSION lo enviamos a la pagina principal para que inicie sesion
    }
}