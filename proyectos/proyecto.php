<?php

require_once '../usuarios/coordinadores/coordinador.php';

class Proyecto{
    private $idProyecto;
    private $nombre;
    private $descripcion;
    private $idEspecialidad;
    private $fotos;
    
    public function __construct($nombre, $descripcion, $idEspecialidad, $fotos){
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->idEspecialidad = $idEspecialidad;
        $this->fotos = $fotos;
    }

    public function registrar($tokenUsuario){
        $coordinador = Coordinador::obtenerPorToken($tokenUsuario);
        if($coordinador){
            if($coordinador['idEspecialidad'] == $this->idEspecialidad){
                $conexion = new Conexion();
                $pdo = $conexion->getConexion();
                $sql = "INSERT INTO proyectos (nombre, descripcion, idEspecialidad) VALUES (?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$this->nombre, $this->descripcion, $this->idEspecialidad]);
                $idProyecto = $pdo->lastInsertId();
                //cargar fotos en la carpeta fotos_proyectos
                if(!file_exists('../fotos_proyectos')){
                    mkdir('../fotos_proyectos/', 0777, true);
                }
                $ruta = '../fotos_proyectos/' . $idProyecto . '/';
                if(!file_exists($ruta)){
                    mkdir($ruta, 0777, true);
                }
                $fotos = $this->fotos;
                $i = 0;
                //insertar fotos en la base de datos
                for($i = 0; $i < count($fotos['name']); $i++){
                    //crear un nombre único para la foto
                    $nombreFoto = uniqid() . '.' . pathinfo($fotos['name'][$i], PATHINFO_EXTENSION);
                    $rutaFoto = $ruta . $nombreFoto;
                    move_uploaded_file($fotos['tmp_name'][$i], $rutaFoto);
                    $sql = "INSERT INTO imagenes (idProyecto,url) VALUES (?, ?)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$idProyecto, '/fotos_proyectos/'.$idProyecto.'/'. $nombreFoto]);

                    
                }

                
                return true;
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }

}