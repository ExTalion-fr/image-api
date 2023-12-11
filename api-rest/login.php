<?php
include_once './config/Database.php';
include_once './models/User.php';
// Headers requis
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// On vérifie que la méthode utilisée est correcte
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // On inclut les fichiers de configuration et d'accès aux données
    
    $database = new Database();
    $db = $database->getConnection();
    $user = new User($db);

    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    $query = "SELECT * FROM user WHERE username = :username LIMIT 0,1";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $userId = $row['id'];
        $userUsername = $row['username'];
        $password2 = $row['password'];

        if (password_verify($password, $password2)) {
            echo json_encode(
                array(
                    "success" => 1,
                    "message" => "Connexion avec succès",
                    "id" => $userId,
                    "username" => $userUsername
                )
            );
            http_response_code(200);
        } else {
            http_response_code(402);
            echo json_encode(array(
                "success" => 0,
                "message" => "Connexion refusée, mauvais mot de passe"
            ));
        }
    } else {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $userId = $user->add($username, $password_hash);
        echo json_encode(
            array(
                "success" => 1,
                "message" => "Connexion avec succès",
                "id" => $userId,
                "username" => $username
            )
        );
        http_response_code(200);
    }
} else if ($_SERVER['REQUEST_METHOD'] !== "OPTIONS") {
    http_response_code(405);
    echo json_encode(array(
        "success" => 0,
        "message" => "La méthode n'est pas autorisée"
    ));
}