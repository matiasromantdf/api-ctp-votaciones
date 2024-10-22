<?php

require_once 'coordinador.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) {
    case 'POST':
        $datos = json_decode(file_get_contents('php://input'), true);
        if(isset($datos['login'])){
            $respuesta = Coordinador::loginCoordinador($datos['username'], $datos['pass']);
           if($respuesta){
               echo json_encode(array('ok' => true, 'datos' => $respuesta));
              }else{
                    echo json_encode(array('ok' => false));
                }
        }
        else{
            $coordinador = new Coordinador($datos['username'], $datos['pass'], $datos['nombre'], $datos['apellido'], $datos['dni'], $datos['especialidad']);
            $respuesta = $coordinador->registrar();
            $response = array('ok' => $respuesta);
            echo json_encode($response);
        }
        break;
    case 'GET':
        if(isset($_GET['tokenUsuario'])){
            $coordinador = Coordinador::obtenerPorToken($_GET['tokenUsuario']);
            echo json_encode($coordinador);
        }
        // elseif(){
        //     //
        // }
        else{
            $coordinadores = Coordinador::obtenerTodos();
            echo json_encode($coordinadores);
        }
        break;

}

