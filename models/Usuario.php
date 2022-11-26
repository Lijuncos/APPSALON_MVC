<?php

namespace Model;

class Usuario extends ActiveRecord {
    // Base de datos
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'email', 'password', 'telefono', 'admin', 'confirmado', 'token'];

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->admin = $args['admin'] ?? '0';
        $this->confirmado = $args['confirmado'] ?? '0';
        $this->token = $args['token'] ?? '';
    }

    // Mensajes de validacion para la creacion de la cuenta
    public function validarNuevaCuenta() {
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El Nombre es obligatorio'; // ['error'] es para diferenciar alertas de error y exito (una clase) [] y esto es para que se cree un arreglo de error (y al ponerlo al final los elementos se van posicionando al final del arreglo)
        }
        if(!$this->apellido) {
            self::$alertas['error'][] = 'El Apellido es obligatorio';
        }
        if(!$this->telefono) {
            self::$alertas['error'][] = 'El Teléfono es obligatorio';
        }
        if(!$this->email) {
            self::$alertas['error'][] = 'El Email es obligatorio';
        }
        if(!$this->password) {
            self::$alertas['error'][] = 'El Password es obligatorio';
        }
        if(strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El Password debe conener al menos seis caracteres';
        }

        return self::$alertas;
    }

    public function validarLogin() {
        if(!$this->email) {
            self::$alertas['error'][] = 'El email es Obligatorio';
        }
        if(!$this->password) {
            self::$alertas['error'][] = 'El password es Obligatorio';
        }

        return self::$alertas;
    }

    public function validarEmail() {
        if(!$this->email) {
            self::$alertas['error'][] = 'El email es Obligatorio';
        }
        return self::$alertas;
    }

    public function validarPassword() {
        if(!$this->password) {
            self::$alertas['error'][] = 'El Password es obligatorio';
        }
        if(strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El Password debe tener al menos seis caracteres';
        }

        return self::$alertas;
    }
    // Revisa si el usuario ya existe
    public function existeUsuario() {
        $query = " SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1"; // self::$tabla = usuarios(esta arriba). $this->email usamos this para hacer referencia al email de este objeto(el objeto en memoria)

        $resultado = self::$db->query($query); // utilizamos la funcion de php(query()) para ejecutar el query

        if($resultado->num_rows) { // en base al num_rows(1 si hay resultado y 0 si no lo hay)
            self::$alertas['error'][] = 'El usuario ya esta registrado'; // validamos y en caso de que ya exista agregamos el msj de error
        }

        return $resultado;
    }

    public function hashPassword() {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT); // password_hash es una funcion de php para hashear passwords y PASSWORD_BCRYPT es el metodo de hash
    }

    public function crearToken() {
        $this->token = uniqid(); // uniqid es una funcion de php que crear una combinacion de 13 digitos, no muy compleja pero ideal para un token
    }

    public function comprobarPasswordAndVerificado($password){
        
        $resultado = password_verify($password, $this->password); // utilizamos la funcion password_verify para comparar el password que puso el usuario con el que tenemos hasheado en la BD
    
        if(!$resultado || !$this->confirmado) { // si no hay resultado(o sea no coincide con la de la BD o no esta confirmada la cuenta le agregamos el error)
            self::$alertas['error'][] = 'Password incorrecto o tu cuenta ha sido confirmada';
        } else {
            return true; // en caso de que esten bien los dos retornamos un true
        }
    }
}