<?php
require_once __DIR__ . '/../conexion.php';

class Usuario{
    protected $username;
    protected $pass;
    protected $nombre;
    protected $apellido;
    protected $dni;
    protected $tokenUsuario;


    public function __construct($username, $pass, $nombre, $apellido, $dni){
        $this->username = $username;
        $this->pass = $pass;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->dni = $dni;
    }

    public function registrar(){
        $conexion = new Conexion();
        $pdo = $conexion->getConexion();
        $sql = "SELECT * FROM usuarios WHERE username = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->username]);
        $usuario = $stmt->fetch();
        if(!$usuario){
            //insertar usuario
            $sql = "INSERT INTO usuarios (username, pass, nombre, apellido, dni) VALUES (?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$this->username, $this->pass, $this->nombre, $this->apellido, $this->dni]);
            return true;

        }else{
            return false;
        }
    }

    public function existe(){
        $conexion = new Conexion();
        $pdo = $conexion->getConexion();
        $sql = "SELECT * FROM usuarios WHERE username = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->username]);
        $usuario = $stmt->fetch();
        if($usuario){
            return true;
        }else{
            return false;
        }
    }

    public function getId(){
        $conexion = new Conexion();
        $pdo = $conexion->getConexion();
        $sql = "SELECT * FROM usuarios WHERE username = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->username]);
        $usuario = $stmt->fetch();
        return $usuario['dni'];
    }

    public static function login($username, $pass){
        $conexion = new Conexion();
        $pdo = $conexion->getConexion();
        $sql = "SELECT * FROM usuarios WHERE username = ? AND pass = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username, $pass]);
        $usuario = $stmt->fetch();
        if($usuario){
            $token = bin2hex(openssl_random_pseudo_bytes(16));
            $sql = "UPDATE usuarios SET tokenUsuario = ? WHERE username = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$token, $username]);
            $res = array(
                'tokenUsuario' => $token,
                'dni' => $usuario['dni']
            );
            return $res;
        }else{
            return false;
        }
        return $usuario;
               

       
    }

    public static function obtenerPorToken($token){
        $conexion = new Conexion();
        $pdo = $conexion->getConexion();
        $sql = "SELECT * FROM usuarios WHERE tokenUsuario = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$token]);
        $usuario = $stmt->fetch();
        return $usuario;
    }



}