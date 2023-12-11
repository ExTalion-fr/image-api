<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    include_once '../config/Database.php';
    include_once '../models/Image.php';

    $database = new Database();
    $db = $database->getConnection();
    $image = new Image($db);

    $id = isset($_GET['id']) ? $_GET['id'] : die();

    if (!empty($id)) {
        $image->userId = $id;
        $images = $image->getImagesByUserId();
        
        http_response_code(200);
        echo json_encode(array(
            "success" => 1,
            "message" => "Voici les images de l'utilisateur",
            "images" => $images
        ));
    } else {
        echo json_encode(array(
            "success" => 0,
            "message" => "Il n'y a pas de paramètre"
        ));
    }
} else if ($_SERVER['REQUEST_METHOD'] !== "OPTIONS") {
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}
