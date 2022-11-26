<?php 

namespace Controllers;

use Classes\Email;
use MVC\Router;
use Model\Usuario;

class LoginController {
    public static function login(Router $router) {
        $alertas = [];

        $auth = new Usuario; // esto es para que guarde el email cuando queremos iniciar sesion, en caso de que pongamos mal la contraseña
        // no entiendo porque si o si la variable tiene que ser $auth, si le denomino con cualquier otro nombre no funciona(es porque cuando mandamos iniciar sesion y el metodo es post la variable que le pusimos es $auth)

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);

            $alertas = $auth->validarLogin();

            if(empty($alertas)) {
                // Comprobar que exista el usuario
                $usuario = Usuario::where('email', $auth->email); // comparamos el mail que ingresa el usuario con los mails de nuestra BD

                if($usuario) {
                    // Verificar el password
                    if ($usuario->comprobarPasswordAndVerificado($auth->password)) { // en el metodo le ponemos el password de $auth que es el que ingreso el usuario
                        // Autenticar el usuario
                        session_start(); // iniciamos sesion y tenemos uso de la superglobald $_SESSION

                        $_SESSION['id'] = $usuario->id; // le agregamos a $SESSION['id'] el id de $usuario que lo sacamos arriba con el where de la base de datos
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido; // le asociamos tambien el nombre y apellido
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        // Redireccionando

                        if($usuario->admin === '1') { // si el usuario en admin tiene '1' significa que es un admin y le agregamos el atributo admin a $_SESION para despues redireccionarlos a /admin
                            $_SESSION['admin'] = $usuario->admin ?? null; // este null hay que ponerlo asi no marca ningun error (yo probe sin el null y no me salia ningun error pero el profe sabra)

                            header('Location: /admin');
                        } else { // en caso de que no sea admin lo redireccionamos a /cita
                            header('Location: /cita');
                        }
                        debuguear($_SESSION);
                    } 
                } else {
                    Usuario::setAlerta('error', 'Usuario no encontrado');
                }
            }
        }

        $alertas = Usuario::getAlertas(); // le agregamos al arreglo de la alertas, las que agregamos con setAlerta

        $router->render('auth/login', [
            'alertas' => $alertas,
            'auth' => $auth // esta vista es solo para que muestre el email que pusimos en caso de equivocarnos en el password(tambien en el login hay que ponerle value
        ]);
    }

    public static function logout() {
        session_start();

        $_SESSION = [];

        header('Location: /');
    }
    
    public static function olvide(Router $router) {

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST); // instanciamos un nuevo objeto de usuario
            $alertas = $auth->validarEmail(); // validamos que el usuario haya ingresado un email

            if(empty($alertas)) {
                $usuario = Usuario::where('email', $auth->email); // buscamos el email que introduce el usuario en nuestra BD(si lo encuentra nos trae el objeto con todos los datos del usuario, y en caso de que no lo encuentre nos marca null)

                if($usuario && $usuario->confirmado === "1") { // aca verificamos que exista $usuario(o sea que no nos retorne null) y que ese $usuario en el campo de confirmado tenga 1(o sea que si este confirmado)
                    
                    // Generar un token
                    $usuario->crearToken(); // le asignamos un token que va a servir para identificar al usuario y asi pueda cambiar su contraseña
                    $usuario->guardar(); // guardamos el nuevo usuario con el token agregado y al ya tener un id este objeto solo lo actualiza en la BD

                    // Enviar el email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token); // creamos un nuevo objeto de la clase Email y le pasamos el nombre,email y token de $usuario
                    $email->enviarInstrucciones();

                    // Alerta de exito
                    Usuario::setAlerta('exito', 'Revisa tu email');

                } else {
                    Usuario::setAlerta('error', 'El Usuario no existe o no esta confirmado');// si no esta confirmado le agregamos esta alerta al objeto Usuario
                    
                }
            }
        }

        $alertas = Usuario::getAlertas(); // pasamos las alertas a $alertas ya sea la de exito o la de error
        
        $router->render('auth/olvide-password', [
            'alertas' => $alertas
        ]);
    }

    public static function recuperar(Router $router) {

        $alertas = [];
        $error = false; // variable que inicia en false y en caso de no se un token valido(empty $usuarios) lo pasamos a true y asi no se muestre el formulario(esta en recuperar-password.php)

        $token = s($_GET['token']); // obtenemos el token de la url con GET

        // Buscar usuario por su token
        $usuario = Usuario::Where('token', $token); // localizamos el usuario en la BD en base al token que le asignamos en la url en el link que le enviamos al mail

        if(empty($usuario)) { // si el token no es valido sale null por ende esta vacio $usuario
            Usuario::setAlerta('error', 'Token No Válido'); // le agregamos una alerta en dicho caso
            $error = true;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Leer el nuevo password y guardarlo

            $newpassword = new Usuario($_POST); // instanciamos Usuario para que al darle a "guardar nuevo password"(ejecutar POST) el password que se introdujo pase a ser parte del password de este objeto(DUDA: identifica que ese input de password es el password del objeto porque tiene de name passowrd?)
            $alertas = $newpassword->validarPassword();

            if(empty($alertas)) {
                $usuario->password = null;// pasamos a null el password hasheado que tenia el usuario anteriormente

                $usuario->password = $newpassword->password; // hacemos que el password de $usuario(que es el usuario que buscamos de la BD con el where) sea igual al password del nuevo objeto de Usuario que asignamos a $newpassword (el cual tiene en su slot de password el que escribio el usuario)
                $usuario->hashPassword(); // hasheamos el password con el metodo que habiamos creado
                $usuario->token = null; // eliminamos el token que le habiamos asignado al usuario

                $resultado = $usuario->guardar(); // hacemos uso del metodo guardar() para que nos actualice el usuario y nos retorna un resultado
                if($resultado) { // en caso de haber resultado redireccionamos al usuario a la pagina principal para que pueda iniciar sesion
                    header('Location: /');
                }
            }
        }


        $alertas = Usuario::getAlertas(); // pasamos las alertas a $alertas
        $router->render('auth/recuperar-password', [
            'alertas' => $alertas,
            'error' => $error
        ]);
    }

    public static function crear(Router $router) {
        
        $usuario = new Usuario; // creamos el objeto afuera del metodo post, para que se cree de una y poder sincronizar los datos que enviamos del formulario 
        //y se nos rellenen solos en caso de que salte un error y el usuario tenga que (por ej escribir la contraseña de vuelta porque no cumplio con el requisito de caracteres) hay añadidos en "crear-cuenta.php" en los value

        // Alertas vacias
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $usuario->sincronizar($_POST); // metodo para sincronizar el objeto que estaba vacio con los datos nuevos que llegan del POST
            $alertas = $usuario->validarNuevaCuenta();

            // Revisar que $alertas este vacio
            if(empty($alertas)) {
                // Verificar que el usuario no este registrado
                $resultado = $usuario->existeUsuario(); // $usuario es el objeto en memoria y le aplicamos el metodo que ejecuta el query y compara su email con la base de datos (y lo agregamos a la variable $resultado)

                if($resultado->num_rows) { // en caso de haber num_rows(o sea 1) utilizamos el metodo getAlertas de ActiveRecord para agregarle al arreglo de alertas el mensaje que ese usuario ya existe
                    $alertas = Usuario::getAlertas();
                } else { // No esta registrado (creamos nuevo usuario)
                    // Hashear el Password
                    $usuario->hashPassword();

                    // Generar un token unico
                    $usuario->crearToken();

                    // Enviar el email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token); // utilizamos el nombre, email y token que tenemos en el objeto de $usuario y se lo pasamos al objeto de Email
                        // estos "$usuario->email, etc" tienen que estar en el mismo orden que en los del constructor de Email.php(si no despues me pasaba que cuando ponia $this->nombre en el $contenido del mail en nombre me salia el email(Hola correo@correo.com etc...))
                    $email->enviarConfirmacion();

                    // Crear el usuario
                    $resultado = $usuario->guardar();
                    if($resultado) {
                        header('Location: /mensaje');
                    }
                }
            }

        }
        
        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function mensaje(Router $router) {
        $router->render('auth/mensaje');
    }

    public static function confirmar(Router $router) {
        $alertas = [];

        $token = s($_GET['token']); // seleccionamos el token del usuario que asociamos a la url

        $usuario = Usuario::where('token', $token); // utilizamos el modelo usuario y le aplicamos el metodo where

        if(empty($usuario)) {
            // Mostrar mensaje de error
            Usuario::setAlerta('error', 'Token No Válido');
        } else {
            // Modificar a usuario confirmado
            $usuario->confirmado = '1'; // cambiamos el campo de confirmado de 0 a 1
            $usuario->token = null; // eliminamos el token para que no pueda ser reutilizado
            $usuario->guardar(); // utilizamos el metodo guardar que si identifica que ya tiene un id el usuario, lo actualiza(y asi generamos los cambios de confirmado y token en la BD)
            Usuario::setAlerta('exito', 'Cuenta Comprobada Correctamente'); // le agregamos la alerta de exito
        }

        // Obtener alertas
        $alertas = Usuario::getAlertas(); // utilizamos getAlertas para que esas alertas del token que se estan almacenando en memoria puedan pasar a la vista 

        // Renderizar la vista
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }
}