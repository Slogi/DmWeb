<?php

require_once("mysql_config.php");

class Connexion
{
    public $conn;

    public function getConnection(){

        $this->conn = null;

        try{
            $this->conn = new PDO("mysql:host=" . MYSQL_HOST . ";" . "port=" . MYSQL_PORT . ";dbname=" . MYSQL_DB, MYSQL_USER, MYSQL_PASSWORD);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8");
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}