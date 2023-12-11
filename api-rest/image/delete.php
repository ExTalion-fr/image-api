<?php
// Headers requis
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// On vérifie que la méthode utilisée est correcte
if($_SERVER['REQUEST_METHOD'] == 'GET'){
    // On inclut les fichiers de configuration et d'accès aux données
    include_once '../config/Database.php';
    include_once '../models/Image.php';

    // On instancie la base de données
    $database = new Database();
    $db = $database->getConnection();
    $image = new Image($db);

    $id = isset($_GET['id']) ? $_GET['id'] : null;

    if($id != null) {
        $image->remove($id);
        http_response_code(200);
        echo json_encode(array(
            "success" => 1,
            "message" => "L'image a été supprimée"
        ));
    } else {
        echo json_encode(array(
            "success" => 0,
            "message" => "Il n'y a pas de paramètre"
        ));
    }
}elseif($_SERVER['REQUEST_METHOD'] !== "OPTIONS"){
    // On gère l'erreur
    http_response_code(405);
    echo json_encode(array(
        "success" => 0,
        "message" => "La méthode n'est pas autorisée"
    ));
}
