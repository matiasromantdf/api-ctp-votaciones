<?php

require_once '../conexion.php';

class Especialidad{
    private $idEspecialidad;
    private $nombre;

    public function __construct($idEspecialidad, $nombre){
        $this->idEspecialidad = $idEspecialidad;
        $this->nombre = $nombre;
    }

    public function registrar(){
        $conexion = new Conexion();
        $pdo = $conexion->getConexion();
        $sql = "SELECT * FROM especialidades WHERE nombre = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->nombre]);
        $especialidad = $stmt->fetch();
        if(!$especialidad){
            //insertar especialidad
            $sql = "INSERT INTO especialidades (nombre) VALUES (?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$this->nombre]);
            return true;

        }else{
            return false;
        }
    }

    public static function obtenerTodos(){
        $conexion = new Conexion();
        $pdo = $conexion->getConexion();
        $sql = "SELECT * FROM especialidades";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $especialidades = $stmt->fetchAll();
        return $especialidades;
    }

    public static function obtenerPorId($id){
        $conexion = new Conexion();
        $pdo = $conexion->getConexion();
        $sql = "SELECT * FROM especialidades WHERE idEspecialidad = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $especialidad = $stmt->fetch();
        return $especialidad;
    }


}