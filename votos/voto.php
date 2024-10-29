<?php

class Voto{
    private $id;
    private $idVotante;
    private $idProyecto;

    public function __construct($idVotante, $idProyecto){
        $this->idVotante = $idVotante;
        $this->idProyecto = $idProyecto;
    }

    public static function obtenerVotos(){
        
    }
    public function registrar(){
        $conexion = new Conexion();
        $pdo = $conexion->getConexion();
        $sql = "INSERT INTO votos (idVotante, idProyecto) VALUES (?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->idVotante, $this->idProyecto]);
        
    }

}