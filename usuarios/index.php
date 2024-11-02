<?php

//cors
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

require_once 'usuario.php';

$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) {
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $username = $data['username'];
        $pass = $data['pass'];
        $res = Usuario::login($username, $pass);
        echo json_encode($res);
        break;
    case 'GET':
        echo 'GET';
       
        break;
}