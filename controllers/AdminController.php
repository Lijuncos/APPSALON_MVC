<?php

namespace Controllers;

use Model\AdminCita;
use MVC\Router;

class AdminController {
    public static function index(Router $router) {
        session_start();

        isAdmin(); // funcion que valida que es un admin, para protegerla y que no pueda ingresar cualquiera(esta en funciones.php)

        $fecha = $_GET['fecha'] ?? date('Y-m-d'); // le asignamos la "fecha" de la url(que asignamos en buscador.js) a la variable $fecha. Y si no hay una fecha previa con "date('Y-m-d')" le asignamos la fecha del servidor que va a ser la del dia actual 
        $fechaexplode = explode('-', $fecha); // los tenemos que separar con explode para poder utilizar checkdate
        
        if(!checkdate( $fechaexplode[1], $fechaexplode[2], $fechaexplode[0])) { // checkdate es para validar que sea una fecha valida, para que, por ej: no nos cambien el dia y pongan dia 70 en la url
            header('Location: /404'); // en caso de no sea una fecha valida lo redireccionamos hacia el error /404
        } 


        // Consultar la base de datos
        $consulta = "SELECT citas.id, citas.hora, CONCAT( usuarios.nombre, ' ', usuarios.apellido) as cliente, ";
        $consulta .= " usuarios.email, usuarios.telefono, servicios.nombre as servicio, servicios.precio  ";
        $consulta .= " FROM citas  ";
        $consulta .= " LEFT OUTER JOIN usuarios ";
        $consulta .= " ON citas.usuarioId=usuarios.id  ";
        $consulta .= " LEFT OUTER JOIN citasServicios ";
        $consulta .= " ON citasServicios.citaId=citas.id ";
        $consulta .= " LEFT OUTER JOIN servicios ";
        $consulta .= " ON servicios.id=citasServicios.servicioId ";
        $consulta .= " WHERE fecha =  '${fecha}' ";

        $citas = AdminCita::SQL($consulta);

        $router->render('admin/index', [
            'nombre' => $_SESSION['nombre'],
            'citas' => $citas,
            'fecha' => $fecha // la pasamos a la vista para luego ponerla en el value del input
        ]);
    }
}