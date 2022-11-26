<?php

namespace Controllers;

use MVC\Router;

class CitaController {
    public static function index(Router $router) {

        session_start(); // iniciamos la session para tener los datos del usuario(lo usamos para poner su nombre en el placeholder cuando este reservando una cita)

        isAuth(); // funcion que verifica si el usuario esta logueado antes de renderizar la vista de crear la cita

        $router->render('cita/index', [
            'nombre' => $_SESSION['nombre'], // pasamos el nombre del usuario hacia la vista
            'id' => $_SESSION['id'] // creamos esta variable del id para tenerla disponible y usarla en el index.php de cita y nos sirve la insercion a la BD
        ]);
    }
}