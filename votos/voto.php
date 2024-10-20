<?php

class Voto{
    private $id;
    private $idVotante;
    private $idProyecto;

    public function __construct($id, $idVotante, $idProyecto){
        $this->id = $id;
        $this->idVotante = $idVotante;
        $this->idProyecto = $idProyecto;
    }

    public static function obtenerVotos(){
        
    }
}