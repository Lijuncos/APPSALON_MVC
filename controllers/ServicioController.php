<?php

namespace Controllers;

use Model\Servicio;
use MVC\Router;

class ServicioController {
    public static function index(Router $router) {
        session_start(); // iniciamos la sesion para poder hacer uso de $_SESSION y poder mostrar el nombre del usuario en la barra

        isAdmin(); // esto es para validar que sea admin el que ingresa a la pagina y en caso de que no sea lo redirecciona a la pagina principal

        $servicios = Servicio::all();

        $router->render('/servicios/index', [
            'nombre' => $_SESSION['nombre'],
            'servicios' => $servicios
        ]);
    }

    public static function crear(Router $router) {
        session_start();
        isAdmin();

        $servicio = new Servicio; // creamos una instancia de servicio
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $servicio->sincronizar($_POST); // asignamos los datos que mandamos en el POST al objeto en memoria(los sincronizamos) ademas tambien sirve para que se guarde lo que escriben los usuarios en el formulario, en caso de que se equivoquen se queda lo ultimo que asignaron
        
            $alertas = $servicio->validar();

            if(empty($alertas)) {
                $servicio->guardar();
                header('Location: /servicios');
            }
        }

        $router->render('/servicios/crear', [
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);
    }

    public static function actualizar(Router $router) {
        session_start();
        isAdmin();

        if(!is_numeric($_GET['id'])) return; // validamos por que sea un numero el id
        $servicio = Servicio::find($_GET['id']); // buscamos por el id el servicio para generar el autollenado en el formulario
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $servicio->sincronizar($_POST); // sincronizamos objeto en memoria con datos de POST

            $alertas = $servicio->validar(); // validamos que los datos esten bien

            if(empty($alertas)) { // si esta todo ok, o sea alertas esta vacio, guardamos
                $servicio->guardar();
                header('Location: /servicios');
            }
        }

        $router->render('/servicios/actualizar', [
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);
    }

    public static function eliminar() {
        session_start();
        isAdmin();
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $servicio = Servicio::find($id);
            $servicio->eliminar();
            header('Location: /servicios');
        }
    }
}