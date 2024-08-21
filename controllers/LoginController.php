<?php

namespace Controllers;

use MVC\Router;
use Classes\Email;
use Model\Usuario;

class LoginController{
    public static function login(Router $router){
        $alertas = [];
        $auth = new Usuario;
        
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuario($_POST);

            $alertas = $auth->validarLogin();
            if(empty($alertas)){
                // Comprobar que exista el usuario
                $usuario = Usuario::where('email', $auth->email);
                
                if($usuario){
                    // Verificar el Password
                    if($usuario->comprobarPasswordAndVerificado($auth->password)){
                        // Autenticar el usuario
                        session_start();

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        // Redireccionamiento
                        if($usuario->admin === "1"){
                            $_SESSION['admin'] = $usuario->admin ?? null;

                            header('Location: /admin');
                        }else{
                            header('Location: /cita');
                        }
                    }
                }else{
                    Usuario::setAlerta('error', 'Usuario no encontrado');
                }
            }
        }

        $alertas= Usuario::getAlertas();

        $router->render('auth/login',[
            'alertas' => $alertas,
            'auth' => $auth
        ]);
    }

    public static function logout(){
        session_start();
        
        $_SESSION = [];

        header('Location: /');
    }
    
    public static function olvide(Router $router){

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();

            if(empty($alertas)){
                $usuario = Usuario::where('email', $auth->email);
                
                if($usuario){
                    if($usuario->confirmado === "1"){
                        
                        // Generar Token
                        $usuario->crearToken();
                        $usuario->guardar();

                        // Enviar email
                        $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                        $email->enviarInstrucciones();

                        // Alerta de Exito
                        Usuario::setAlerta('exito', 'Revisa tu email');

                    }else{
                        Usuario::setAlerta('error', 'El Usuario no esta confirmado');
                    }
                }else{
                    Usuario::setAlerta('error', 'El Usuario No Existe');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/olvide-password',[
            'alertas' => $alertas
        ]);
    }
    
    public static function recuperar(Router $router){

        $alertas = [];
        $error = false;

        $token = s($_GET['token'] ?? "");

        // Buscar usuario por su token
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)){
            Usuario::setAlerta('error', 'Token no valido');
            $error = true;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            // Leer el nuevo password y guardarlo
            $password = new Usuario($_POST);
            $password->validarPassword();

            if(empty($alertas)){
                $usuario->password = null;

                $usuario->password = $password->password;
                $usuario->hashPassword();
                $usuario->token = null;

                $resultado = $usuario->guardar();
                if($resultado) {
                    // Crear mensaje de exito
                    Usuario::setAlerta('exito', 'Contraseña Actualizada Correctamente');
                                    
                    // Redireccionar al login tras 3 segundos
                    header('Refresh: 2; url=/');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/recuperar-password',[
            'alertas' => $alertas,
            'error' => $error
        ]);
    }
    
    public static function crear(Router $router){
        $usuario = new Usuario;

        // Alertas vacias
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            // Revisar que alerta este vacio
            if(empty($alertas)){

                // Verificar que el usuario no este registrado
                $resultado = $usuario->existeUsuario();

                if($resultado->num_rows){
                    $alertas = Usuario::getAlertas();
                }else{
                    
                    $usuario->hashPassword(); // Hashear el Password
                    $usuario->crearToken(); // Generar un token unico
                    
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token); // Enviar Email
                    $email->enviarConfirmacion();

                    $resultado = $usuario->guardar();
                    if($resultado){
                        header('Location: /mensaje');
                    }
                    
                }
                
            }

        }

        $router->render('auth/crear-cuenta',[
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function mensaje(Router $router){

        $router->render('auth/mensaje');
    }

    public static function confirmar(Router $router){

        $alertas = [];
        $token = s($_GET['token']);
        
        $usuario = Usuario::where('token', $token);
        
        if(empty($usuario || $usuario->token === '')){
            // Mostrar mensaje de error
            Usuario::setAlerta('error', 'Token no valido');
        }else{
            // Modificar a usuario confirmado
            $usuario->confirmado ="1";
            $usuario->token = null;
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Cuenta Comprobada Correctamente');
        }

        // Obtener alertas
        $alertas = Usuario::getAlertas();

        // Reenderizar la vista
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }
}