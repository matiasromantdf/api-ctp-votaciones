<?php

//cors
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

require_once 'proyecto.php';

$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) {
    case 'POST':
        $datos=[];
        $datos['nombre'] = $_POST['nombre'];
        $datos['descripcion'] = $_POST['descripcion'];
        $datos['idEspecialidad'] = $_POST['idEspecialidad'];
        $datos['tokenUsuario'] = $_POST['tokenUsuario'];
        $fotos = $_FILES['fotos'];
        $proyecto = new Proyecto($datos['nombre'], $datos['descripcion'], $datos['idEspecialidad'], $fotos);
        $respuesta = $proyecto->registrar($datos['tokenUsuario']);
        $response = array('ok' => $respuesta);
        echo json_encode($response);
        break;
    case 'GET':
        if(isset($_GET['idProyecto'])){
            $proyecto = Proyecto::obtenerPorId($_GET['idProyecto']);
            echo json_encode($proyecto);
        }
        elseif(isset($_GET['idEspecialidad'])){
            $proyectos = Proyecto::obtenerPorEspecialidad($_GET['idEspecialidad']);
            echo json_encode($proyectos);
        }
        else{
            $proyectos = Proyecto::obtenerTodos();
            echo json_encode($proyectos);
        }
        break;
    default:
        echo json_encode(array('mensaje' => 'Método no contemplado'));
        break;
}