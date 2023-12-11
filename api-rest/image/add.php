<?php
// Headers requis
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// On vérifie que la méthode utilisée est correcte
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // On inclut les fichiers de configuration et d'accès aux données
    include_once '../config/Database.php';
    include_once '../models/Image.php';

    // On instancie la base de données
    $database = new Database();
    $db = $database->getConnection();
    $image = new Image($db);
    
    $donnees = new stdClass();
    $donnees->url = $_POST['url'];
    $donnees->tags = $_POST['tags'];
    $donnees->userId = $_POST['user_id'];

    try {
        $image->add($donnees);
        // On envoie le code réponse 200 OK
        http_response_code(200);
        echo json_encode(array(
            "success" => 1,
            "message" => "L'image a été ajoutée",
            "image" => $image
        ));
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(array(
            "success" => 0,
            "message" => $e->getMessage()
        ));
    }
} else if($_SERVER['REQUEST_METHOD'] !== "OPTIONS") {
    // On gère l'erreur
    http_response_code(405);
    echo json_encode(array(
        "success" => 0,
        "message" => "La méthode n'est pas autorisée"
    ));
}
