<?php

require_once __DIR__ . '/../../conexion.php';
require_once __DIR__.'/../usuario.php';

class Coordinador extends Usuario{

    private $especialidad;
    private $idUsuario;


    public function __construct($username, $pass, $nombre, $apellido, $dni, $especialidad){
        parent::__construct($username, $pass, $nombre, $apellido, $dni);
        $this->especialidad = $especialidad;
        $this->idUsuario = $dni;
    }

    public function registrar(){
        if(parent::existe()){
           $res= $this->registrarCoordinador();
            return $res;
        }
        else{
            parent::registrar();
            $conexion = new Conexion();
            $pdo = $conexion->getConexion();
            $sql = "INSERT INTO coordinadores (idEspecialidad, usuario_id) VALUES (?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$this->especialidad, parent::getId()]);
            return true;
        }
       
    }

    public function registrarCoordinador(){

        //verificar si ya existe un registro con el mismo id y el mismo idEspecialidad

        $conexion = new Conexion();
        $pdo = $conexion->getConexion();


        $sql = "SELECT * FROM coordinadores WHERE usuario_id = ? AND idEspecialidad = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->idUsuario, $this->especialidad]);
        
        if($stmt->fetch()){
            return false;
        }
        else{
            $sql = "insert into coordinadores (idEspecialidad, usuario_id) values (?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$this->especialidad, $this->idUsuario]);
            return true;
        }

       
    }

    public static function obtenerTodos(){
        $conexion = new Conexion();
        $pdo = $conexion->getConexion();
        $sql = "SELECT coordinadores.*, especialidades.* FROM coordinadores INNER JOIN especialidades ON coordinadores.idEspecialidad = especialidades.idEspecialidad";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $coordinadores = $stmt->fetchAll();
        return $coordinadores;
    }

    public static function loginCoordinador($username, $pass){
        $usuario = parent::login($username, $pass);
        if($usuario){
            $conexion = new Conexion();
            $pdo = $conexion->getConexion();
            $sql = "SELECT * FROM coordinadores WHERE usuario_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$usuario['dni']]);
            $coordinador = $stmt->fetch();
            if($coordinador){
                $res = array('id' => $coordinador['id'], 'idEspecialidad' => $coordinador['idEspecialidad'],'tokenUsuario' => $usuario['tokenUsuario']);
                return $res;
            }else{
                return false;
            }
        }else{
            return false;
        }
        
    }

    public static function obtenerPorToken($token){
        $usuario = parent::obtenerPorToken($token);
        if($usuario){
            $conexion = new Conexion();
            $pdo = $conexion->getConexion();
            $sql = "SELECT * FROM coordinadores WHERE usuario_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$usuario['dni']]);
            $coordinador = $stmt->fetch();
            if($coordinador){
                $res = array('id' => $coordinador['id'], 'idEspecialidad' => $coordinador['idEspecialidad'],'tokenUsuario' => $usuario['tokenUsuario']);
                return $res;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

}