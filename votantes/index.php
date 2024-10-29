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
        validarVotante();
        break;
    default:
        echo json_encode(array('mensaje' => 'Método no contemplado'));
        break;
}

function validarVotante(){
    $CLIENT_ID = '196641321859-4tmsbn858ol5tilj0cgsu8lnddnec0lj.apps.googleusercontent.com';

    // Token JWT recibido del frontend (enviado mediante fetch)
    $data = json_decode(file_get_contents('php://input'), true);
    $token = $data['token'];

    // Crear un cliente OAuth2
    $client = new Google_Client(['client_id' => $CLIENT_ID]);  // Especifica tu client ID
    $payload = $client->verifyIdToken($token);

    if ($payload) {
        // El token es válido
        $email = $payload['email'];  // Obtén el correo electrónico del payload
        $name = $payload['name'];    // Obtén el nombre del usuario si lo necesitas
        $votante = new Votante($email, $name);
        $habilitado = $votante->estaHabilitado();

        $respuesta = array(
            'habilitado' => $habilitado,
            'email' => $email,
            'name' => $name
        );

        echo json_encode($respuesta);
    } else {
        // El token no es válido
        echo "Token inválido";
    }

}