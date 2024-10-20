<?php

class Conexion{
    private $host = 'localhost';
    private $db = 'ctp_votaciones';
    private $user = 'root';
    private $password = '';
    private $charset = 'utf8mb4';
    private $pdo;


    public function __construct(){
        $dsn = "mysql:host=$this->host;dbname=$this->db;charset=$this->charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $this->pdo = new PDO($dsn, $this->user, $this->password, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function getConexion(){
        return $this->pdo;
    }
}

