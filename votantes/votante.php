
<?php

require_once '../vendor/autoload.php';


class Votante{
    public $id;
    private $email;
    private $nombre;
    private $votos;


    public function __construct($email, $nombre){
        $this->email = $email;
        $this->nombre = $nombre;
        $this->votos = [];
    }

    public function getId(){
        $conexion = new Conexion();
        $pdo = $conexion->getConexion();
        $sql = "SELECT * FROM votantes WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->email]);
        $votante = $stmt->fetch();
        return $votante['id'];
    }

    


    public function estaHabilitado(){
        $conexion = new Conexion();
        $pdo = $conexion->getConexion();
        $sql = "SELECT * FROM votantes WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->email]);
        $votante = $stmt->fetch();
        if(!$votante){
            //insertar votante
            $sql = "INSERT INTO votantes (email, nombre) VALUES (?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$this->email, $this->nombre]);
            return true;

        }else{
            //verificar si ya votó
            $sql = "SELECT * FROM votos WHERE idVotante = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$votante['id']]);
            $votos = $stmt->fetchAll();
            if(count($votos) > 2){
                return false;
            }else{
                return true;
            }

        }
    }

    public static function obtenerPorToken($token){
                    // Tu client ID de Google
        $CLIENT_ID = '196641321859-4tmsbn858ol5tilj0cgsu8lnddnec0lj.apps.googleusercontent.com';

        $client = new Google_Client(['client_id' => $CLIENT_ID]);  // Especifica tu client ID
        $payload = $client->verifyIdToken($token);

        if ($payload) {
            // El token es válido
            $email = $payload['email'];  // Obtén el correo electrónico del payload
            $name = $payload['name'];    // Obtén el nombre del usuario si lo necesitas
            $votante = new Votante($email, $name);
            return $votante;

        } else {
            // El token no es válido
            return false;
        }


    }

   

}