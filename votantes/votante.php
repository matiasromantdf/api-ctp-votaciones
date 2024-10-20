
<?php

class Votante{
    private $id;
    private $email;
    private $nombre;
    private $votos;


    public function __construct($email, $nombre){
        $this->email = $email;
        $this->nombre = $nombre;
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

}