<?php

//cors
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

require_once './proyectos/proyecto.php';

$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) {
    case 'POST':
        $datos = json_decode(file_get_contents('php://input'), true);
        $fotos = $_FILES['fotos'];
        $proyecto = new Proyecto($datos['nombre'], $datos['descripcion'], $datos['idEspecialidad'], $fotos);
        $respuesta = $proyecto->registrar($datos['tokenUsuario']);
        $response = array('ok' => $respuesta);
        echo json_encode($response);
        break;
    default:
        echo json_encode(array('mensaje' => 'Método no contemplado'));
        break;
}