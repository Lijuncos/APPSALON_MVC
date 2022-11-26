<?php

namespace Controllers;

use Model\Cita;
use Model\CitaServicio;
use Model\Servicio;

class APIController {
    public static function index() {
        $servicios = Servicio::all(); // utilizando el metodo all() de ActiveRecord y la clase Servicio del modelo Servicio.php consultamos la base de datos y nos trae todos los servicios
        echo json_encode($servicios); // con esta funcion transformamos el arreglo asociativo que teniamos de todos los servicios en json. Hay que hacerlo asi porque Javascript no lee arreglos asociativos
    }

    public static function guardar() {
        
        // Almacena la Cita y devuelve el ID
        $cita = new Cita($_POST); // creamos una instancia de Cita

        $resultado = $cita->guardar(); // lo insertamos en la base de datos en la tabla de citas // el metodo guardar() utiliza el metodo crear() y este ultimo nos retorna ademas del resultado un id
        $id = $resultado['id']; // creamos la variable $id utilizando el id que nos retorna $resultado de ActiveRecord
        
        // Almacena los servicios con el ID de la cita

        $idServicios = explode(",", $_POST['servicios']); // los id de los servicios estaban en un string y separados por comas "1,2,3,4" con explode convertimos este string en un arreglo seleccionando cada elemento mediante su separador ","(quedando: ["1","2","3","4"]) 
        // este ['servicios'] viene del objeto que creamos con el submit(POST) en FormData del app.js
        
        foreach($idServicios as $idServicio) {
            $args = [
                'citaId' => $id, // 'citaId' es del constructor de CitaServicio y $id viene de $resultado
                'servicioId' => $idServicio
            ]; 
            $citaServicio = new CitaServicio($args); // creamos una nueva instancia de CitaServicio y le agregamos los argumentos($args)
            $citaServicio->guardar(); // utilizamos el metodo guardar() y como no hay un id, solo citaId y servicioId lo inserta en la base de datos 
        }

        echo json_encode(['resultado' => $resultado]); //retornamos el resultado // esta respuesta la utilizamos en await respuesta.json() en la funcion reservarCita() que viene del method: 'POST' que indicamos lineas antes en el app.js
    }

    public static function eliminar() {
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $id = $_POST['id']; // seleccionamos el id del arreglo de $_POST
            $cita = Cita::find($id); // aplicamos el metodo find(de ActiveRecord) en Cita(que es el modelo con el objeto de cita y la tabla de citas) y buscamos en la base de datos por el $id que teniamos en el POST
            $cita->eliminar(); // utilizamos el metodo de ActiveRecord para eliminar el registro de la BD
            header('Location:' . $_SERVER['HTTP_REFERER']); // esto es para redireccionar a la pagina de donde veniamos (con debuguear($_SERVER) vemos mucha info inclusive el 'HTTP_REFERER' que es la pagina de donde veniamos)
        }
    }
}