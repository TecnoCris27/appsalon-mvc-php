<?php

namespace Model;

class Usuario extends ActiveRecord{
    // Base de Datos
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'email', 'password', 'celular', 'admin', 'confirmado', 'token'];

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $passwordConfir;
    public $celular;
    public $admin;
    public $confirmado;
    public $token;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->passwordConfir = $args['passwordConfir'] ?? '';
        $this->celular = $args['celular'] ?? '';
        $this->admin = $args['admin'] ?? '0';
        $this->confirmado = $args['confirmado'] ?? '0';
        $this->token = $args['token'] ?? '';
    }

    // Mensajes de Validacion para la creacion de una cuenta
    public function validarNuevaCuenta(){
        
        if(!$this->nombre){
            self::$alertas['error'][] = 'El Nombre es obligatorio';
        }
        if(!$this->apellido){
            self::$alertas['error'][] = 'El Apellido es obligatorio';
        }
        if(!$this->celular){
            self::$alertas['error'][] = 'El Celular es obligatorio';
        }
        if(!$this->email){
            self::$alertas['error'][] = 'El E-Mail es obligatorio';
        }
        if(!$this->password){
            self::$alertas['error'][] = 'El Password es obligatorio';
        }
        if(!$this->passwordConfir){
            self::$alertas['error'][] = 'Debes Confirmar el Password';
        }else if($this->password !== $this->passwordConfir){
            self::$alertas['error'][] = 'El password no coincide';
        }
        if(strlen($this->password) < 6 ){
            self::$alertas['error'][] = 'El password debe contener al menos 6 caracteres';
        }

        return self::$alertas;
    }

    public function validarLogin(){
        if(!$this->email){
            self::$alertas['error'][] = 'El Email es Obligatorio';
        }
        if(!$this->password){
            self::$alertas['error'][] = 'El Password es Obligatorio';
        }
        return self::$alertas;
    }

    public function validarEmail(){
        if(!$this->email){
            self::$alertas['error'][] = 'El Email es Obligatorio';
        }
        return self::$alertas;
    }

    public function validarPassword(){
        if(!$this->password){
            self::$alertas['error'][] = 'El Password es obligatorio';
        }
        if(strlen($this->password) < 6){
            self::$alertas['error'][] = "El Password debe tener al menos 6 caracteres";
        }
        if($this->password === $this->passwordConfir){
            
        }else{
            self::$alertas['error'][] = "El Password no coincide";
        }

        return self::$alertas;
    }

    // Revisa si el usuario ya existe
    public function existeUsuario(){
        $query = "SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1";
        
        $resultado = self::$db->query($query);

        if($resultado->num_rows){
            self::$alertas['error'][] = 'El Usuario ya esta registrado';
        }

        return $resultado;
    }


    public function hashPassword(){
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function crearToken(){
        $this->token = uniqid();
    }

    public function comprobarPasswordAndVerificado($password){
        $resultado = password_verify($password, $this->password);

        if(!$resultado ){
            self::$alertas['error'][] = 'Password Incorrecto';
        }elseif(!$this->confirmado){
            self::$alertas['error'][] = 'Tu cuenta no ha sido confirmada';
        }else{
            return true;
        }
    }
}