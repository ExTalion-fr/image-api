<?php
class Database{
    // Connexion à la base de données
    private $host = "localhost";
    private $db_name = "image_api";
    private $port = "3306";
    private $username = "root";
    private $password = "";
    public $connexion;

    // Getter pour la connexion
    public function getConnection() {

        $this->connexion = null;

        try {
            $this->connexion = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";port=" . $this->port , $this->username, $this->password);
            $this->connexion->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Erreur de connexion : " . $exception->getMessage();
        }

        return $this->connexion;
    }
}
