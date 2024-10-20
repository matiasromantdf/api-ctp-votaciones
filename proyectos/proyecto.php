<?php

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
                $ruta = "../../fotos_proyectos/";
                $fotos = $this->fotos;
                $fotosProyecto = [];
                $i = 0;
                foreach($fotos as $foto){
                    $i++;
                    $nombreFoto = $idProyecto . "_" . $i . ".jpg";
                    $rutaFoto = $ruta . $nombreFoto;
                    move_uploaded_file($foto['tmp_name'], $rutaFoto);
                    $fotosProyecto[] = $nombreFoto;
                }
                //insertar fotos en la base de datos
                foreach($fotosProyecto as $foto){
                    $sql = "INSERT INTO fotos_proyectos (nombre, proyecto_id) VALUES (?, ?)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$foto, $idProyecto]);
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