<?php

namespace Model;

// TOMANDO DE EJEMPLO EL ID: EN LA BASE DE DATO LA COLUMNA ES ID, TODO LO DEMAS SE TIENE QUE LLAMAR ID SI NO NO MATCHEA BIEN EN LA BASE DE DATOS Y MARCA NULL
// O SEA $columnasDB=['id] si no no busca esa columna
// public $id; $this->id $args['id'] TODO SE TIENE QUE LLAMAR IGUAL

class Servicio extends ActiveRecord {
    // Base de datos
    protected static $tabla = 'servicios'; // colocamos el nombre de la tabla que vamos a consultar
    protected static $columnasDB = ['id', 'nombre', 'precio']; // ponemos los nombres de las columnas de la base de datos // esto es para crear un objeto igual a lo que tenemos en la base de datos

    public $id; // esto serian los elementos del objeto que luego transformamos en arreglo
    public $nombre;
    public $precio;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null; // esto es para una vez que lo instanciemos, asignamos la forma que pusimos arriba a cada campo del arreglo asociativo($args)
        $this->nombre = $args['nombre'] ?? '';
        $this->precio = $args['precio'] ?? '';
    }

    public function validar() {
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El Nombre del Servicio es Obligatorio';
        }
        if(!$this->precio) {
            self::$alertas['error'][] = 'El Precio del Servicio es Obligatorio';
        }
        if(!is_numeric($this->precio)) {
            self::$alertas['error'][] = 'El Precio no es Válido';
        }

        return self::$alertas;
    }
}