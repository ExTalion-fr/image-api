<?php
class Image {
    private $connexion;

    public $id;
    public $url;
    public $tags;
    public $date;
    public $userId;
    
    public function __construct($db) {
        $this->connexion = $db;
    }

    public function getImagesByUserId() {
        $sql = "SELECT * FROM image WHERE user_id = :user_id ORDER BY date DESC";
        $query = $this->connexion->prepare($sql);
        $query->bindParam(':user_id', $this->userId);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getImageById($id) {
        $sql = "SELECT * FROM image WHERE id = :id LIMIT 0,1";
        $query = $this->connexion->prepare($sql);
        $query->bindParam(':id', $id);
        $query->execute();
        $row = $query->fetch(PDO::FETCH_ASSOC);
        $this->id = $row['id'];
        $this->url = $row['url'];
        $this->tags = $row['tags'];
        $this->date = $row['date'];
        $this->userId = $row['user_id'];
    }

    public function add($image) {
        $sql = 'INSERT INTO image (`url`, `tags`, `user_id`) VALUES(?, ?, ?)';
        $query = $this->connexion->prepare($sql);
        $query->execute(array($image->url, $image->tags, $image->userId));
        if ($query) {
            $imageId = $this->lastInsertId();
            $this->getImageById($imageId);
        }
    }

    public function lastInsertId() {
        $sql = 'SELECT MAX(id) FROM image';
        $query = $this->connexion->prepare($sql);
        $query->execute();
        $row = $query->fetch(PDO::FETCH_ASSOC);
        return $row['MAX(id)'];
    }

    public function remove($id) {
        $sql = 'DELETE FROM image WHERE id = :id';
        $query = $this->connexion->prepare($sql);
        $query->bindParam(':id', $id);
        $query->execute();
    }

}