<?php
class User {
    private $connexion;

    public $id;
    public $username;
    public $password;

    public function __construct($db) {
        $this->connexion = $db;
    }

    public function getUserById($id) {
        $sql = "SELECT * FROM user WHERE id = :id LIMIT 0,1";
        $query = $this->connexion->prepare($sql);
        $query->bindParam(':id', $id);
        $query->execute();
        $row = $query->fetch(PDO::FETCH_ASSOC);
        $this->id = $row['id'];
        $this->username = $row['username'];
    }

    public function add($username, $password) {
        $sql = 'INSERT INTO user (`username`, `password`) VALUES(?, ?)';
        $query = $this->connexion->prepare($sql);
        $query->execute(array($username, $password));
        if ($query) {
            $userId = $this->lastInsertId();
            return $userId;
            // echo "Enregistrement ajouté avec succès. Nouvel ID : " . $newId;
        } else {
            // echo "Erreur lors de l'ajout de l'enregistrement : " . $this->connexion->errorInfo();
        }
    }

    public function lastInsertId() {
        $sql = 'SELECT MAX(id) FROM user';
        $query = $this->connexion->prepare($sql);
        $query->execute();
        $row = $query->fetch(PDO::FETCH_ASSOC);
        return $row['MAX(id)'];
    }

    public function removeUser($id) {
        $sql = 'DELETE FROM user WHERE id = :id';
        $query = $this->connexion->prepare($sql);
        $query->bindParam(':id', $id);
        $query->execute();
    }

}