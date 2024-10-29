<?php

//cors
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

require_once '../vendor/autoload.php';
require_once '../votos/voto.php';
require_once '../conexion.php';
require_once 'votante.php';


$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) {
    case 'POST':
        $datos=json_decode(file_get_contents('php://input'),true);
        $token= $datos['tokenUsuario'];
        $idProyecto= $datos['idProyecto'];
        $votante = Votante::obtenerPorToken($token);
        if($votante){
            if($votante->estaHabilitado()){
                $voto = new Voto($votante->getId(), $idProyecto);
                $voto->registrar();
                echo json_encode(array('ok' => true));
            }
            else{
                echo json_encode(array('ok' => false));
            }
        }
        else{
            echo json_encode(array('ok' => false));
        }
        break;
}