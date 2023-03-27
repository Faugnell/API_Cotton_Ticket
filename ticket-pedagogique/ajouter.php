<?php
// Headers requis
// Accès depuis n'importe quel site ou appareil (*)
header("Access-Control-Allow-Origin: *");

// Format des données envoyées
header("Content-Type: application/json; charset=UTF-8");

// Méthode autorisée
header("Access-Control-Allow-Methods: GET");

// Durée de vie de la requête
header("Access-Control-Max-Age: 3600");

// Entêtes autorisées
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    include_once('../model/bdd.php');
    include_once('../model/ticketPedagogique.php');

    // On récupère notre BDD
    $db = Bdd::getInstance()->getConnection();

    // On instancie le ticket pedagogique
    $ticketPedagogique = new TicketPedagogique($db);

    // On récupère les données
    $donnees = json_decode(file_get_contents("php://input"));
    if (!empty($donnees->id_utilisateur)) {
        // Ici on a reçu les données
        // On hydrate notre objet
        $ticketPedagogique->_id_utilisateur = $donnees->id_utilisateur;
        $id = $ticketPedagogique->ajouter_ticket_pedagogique();
        print_r($id);
        if ($id) {
            // Ici la création a fonctionné
            // On envoie un code 201
            http_response_code(201);
            echo json_encode(["message" => "Le ticket à bient été créer", "id_utilisateur" => $id], JSON_UNESCAPED_UNICODE);
        } else {
            // Ici la création n'a pas fonctionnée
            // On envoie un code 503
            http_response_code(503);
            echo json_encode(["message" => "Le ticket est déjà existant"], JSON_UNESCAPED_UNICODE);
        }
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Les informations de création sont incomplète"), JSON_UNESCAPED_UNICODE);
    }
} else {
    // On gère l'erreur
    http_response_code(405);
    echo json_encode(array("message" => "La méthode n'est pas autorisée"), JSON_UNESCAPED_UNICODE);
}
